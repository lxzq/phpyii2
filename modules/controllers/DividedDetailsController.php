<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/5/26
 * Time: 10:26
 */
namespace app\modules\controllers;

use Yii;
use yii\db\Query;
use yii\web\Controller;
use yii\data\Pagination;
use yii\web\Request;
use app\models\ChildClassRecord;
use app\models\ShopInfo;
use app\models\OrgInfo;
use common\controllers\PhpexcelController;
class DividedDetailsController extends Controller{
    public $enableCsrfValidation = false;//yii默认表单csrf验证，如果post不带改参数会报错！
    public $layout = 'layout';

    //分成列表明细
    public function actionList(){
        $db = \Yii::$app->db;
        $is_shopId = Yii::$app->user->identity->shopId;
        if($is_shopId){
            $shopId = $is_shopId;
        }else{
            $shopId = 1;
        }
        $key = '';
        $now_month = date("Y-m",time());
        $key_month = date("Y-m",strtotime("-1months",strtotime($now_month)));
        $last_month = date("Y-m",strtotime("-1months",strtotime($now_month)));
        $n = 1;
        $params= [];
        $startTime = '';
        $endTime = '';
        if (!empty($_REQUEST["page"])) {
            $n = $_REQUEST["page"];
        }
        if(!empty($shopId)){
            $key = $shopId;
        }
        if (!empty($_REQUEST["shop"])) {
            $shopId = $_REQUEST["shop"];
            $key = $_REQUEST["shop"];
            $params["shop"] = $key;
        }
        if (!empty($_REQUEST["month"])) {
            $startTime = $_REQUEST["month"]."-01";
            $endTime = date("Y-m-d",strtotime("+1months",strtotime($startTime)));
            $key_month = $_REQUEST["month"];
            $params["month"] = $key_month;
        }
//        var_dump($startTime);var_dump($endTime);die;
        //所有门店
        $shops = ShopInfo::find()->all();
        //所有机构
        $orgs = OrgInfo::find()->all();

        $org_course = (new \yii\db\Query())
            ->select(['org_id'])
            ->from('org_shop_info')
            ->where(['shop_id'=>$shopId]);
            //->orderBy('id asc');
        $pages = new Pagination(['totalCount' => $org_course->count(), 'pageSize' => '500']);
        $data = $org_course->offset($pages->offset)->limit($pages->limit)->all();

        foreach($orgs as $v){
            $orgs[$v['id']] = $v['name'];
        }

        //==================================查询分成比例、履约保证金、课程id和名称=====================================//
        foreach($data as $k1=>$v1){
            $divide_margin_proportion =$db->createCommand("select divide_proportion,margin_proportion
                from org_info where id=$v1[org_id]")->queryOne();
            //机构分成所得比例
            $data[$k1]['divide_proportion'] = $divide_margin_proportion['divide_proportion'];
            //履约保证金比例
            $data[$k1]['margin_proportion'] = $divide_margin_proportion['margin_proportion'];
            $data[$k1]['course_info'] = $db
                ->createCommand("select id,name from course_info where org_id=$v1[org_id]")->queryAll();
        }
        //==================================查询分成比例、履约保证金、课程id和名称=====================================//
        //var_dump($data);

        //==================================组装时间搜索SQL=====================================//
        $time_sql = '';
        if(!empty($startTime) && !empty($endTime)){
            $time_sql = "ccr.update_time >= '".$startTime."' and ccr.update_time < '".$endTime."'";
        }else{
            $time_sql = "ccr.update_time between '".$key_month."-01 00:00:00' and '".$key_month."-31 23:59:59'";
        }
        //==================================组装时间搜索SQL=====================================//

        //==================================遍历各个课程的报名人数和收入=====================================//
        $org_all_money = 0;
        $org_divide_money = 0;
        $org_margin_money = 0;
        $margin_money = 0;
        foreach($data as $k2=>$v2){
            foreach($v2['course_info'] as $k3=>$v3) {
                $course_id = $v3['id'];
                $org_id = $v2['org_id'];
                $other_info = $db->createCommand("
                       select temp.*, sum(temp.total_price) as heji from  (
                       select sum(ccr.total_money) as total_price ,
                       count(distinct(ccr.pay_no)) as child_num
                       from child_class_record as ccr
                       left join child_class as cc on ccr.id = cc.record_id
                       left join org_shop_info as osi on osi.shop_id = ccr.shop_id
                       where osi.org_id = :org_id and cc.course_id = :course_id
                       and osi.shop_id = $shopId and ($time_sql) and ccr.check_status=1
                       group by cc.course_id
                       ) AS temp
                   ",[':org_id'=>$org_id,':course_id'=>$course_id])->queryOne();
//                var_dump($other_info);
                if(!empty($other_info)){
                    $data[$k2]['course_info'][$k3]['child_num'] =
                        $other_info['child_num'] ? $other_info['child_num'] : 0;
                    $data[$k2]['course_info'][$k3]['total_price'] =
                        $other_info['total_price'] ? $other_info['total_price'] : 0;
                }
                //机构总收款 = 机构中各个课程收款的总和
                $org_all_money = $org_all_money+round($other_info['heji'],2);
                //机构税后分成所得
                $org_divide_money += round($other_info['heji']*0.9429*$v2['divide_proportion'],2);
                //扣除履约保证金后实际所得
                $org_margin_money +=
                    round($other_info['heji']*0.9429*$v2['divide_proportion']*(1-$v2['margin_proportion']),2);
                //履约保证金金额
                $margin_money +=
                    round($other_info['heji']*0.9429*$v2['divide_proportion']*$v2['margin_proportion'],2);
            }
            //总收款
            $data[$k2]['org_all_money'] = round($org_all_money);
            //清空初始总收款
            $org_all_money = 0;
            //各个机构分成比
            $data[$k2]['divide_proportion'] = $v2['divide_proportion'];
        }
//        var_dump($data);
        //==================================遍历各个课程的报名人数和收入=====================================//



        //=======================================合计==========================================//
        $heji = (new Query())->select(['sum(ccr.total_money) as heji'])
            ->addSelect('count(distinct(ccr.pay_no)) as num')
            ->from('child_class as cc')
            ->rightJoin('child_class_record as ccr','ccr.id=cc.record_id')
            ->where($time_sql)
            ->andWhere('ccr.shop_id='.$shopId)
            ->andWhere('ccr.check_status=1')
            ->one();
        $pages->params = $params;
//        var_dump($heji);
        //门店所有机构税后分成总额
        $heji['org_divide_money'] = $org_divide_money;
        //门店所有机构税后分成扣除履约保证金总额
        $heji['org_margin_money'] = $org_margin_money;
        //履约保证金金额总额
        $heji['margin_money'] = $margin_money;
        //=======================================合计==========================================//

        return $this->render('list',[
            'list' => $data,
            'key' => $key,
            'month' => $key_month,
            'now_month' => $now_month,
            'last_month' => $last_month,
            'n' => $n,
            'pages' => $pages,
            'shops' => $shops,
            'orgs' => $orgs,
            'heji' => $heji,
            'is_shopId' => $is_shopId //财务为空 门店为门店id
        ]);
    }
    //结算分成
    public function actionSubmitLastMonth(){
        $db = \Yii::$app->db;
        $request = \Yii::$app->request;
        $lastMonth = date("Y-m",(strtotime("-1 month")));
        $startTime = date("Y-m-01",strtotime($lastMonth));
        $endTime = date("Y-m-31",strtotime($lastMonth));
        if (!empty($request->post())){
            $shop_id = $request->post("shopId");
            $flag = $db->createCommand("
                 update child_class_record set check_status = 2 where shop_id = :shop_id and check_status = 1
                 and update_time BETWEEN :startTime AND :endTime",
                 [':shop_id'=>$shop_id,':startTime'=>$startTime,':endTime'=>$endTime])
                 ->execute();
            if($flag){
                $data['code'] = 1;
                $data['desc'] = 'ok';
                echo json_encode($data);exit;
            }else{
                $data['code'] = -1;
                $data['desc'] = 'no';
                echo json_encode($data);exit;
            }
        }
    }

    //保证金明细
    public function actionMarginList(){
        $db = \Yii::$app->db;
        $is_shopId = Yii::$app->user->identity->shopId;
        if($is_shopId){
            $shopId = $is_shopId;
        }else{
            $shopId = 1;
        }

        $key = '';
        $now_month = date("Y-m",time());
        $key_month = date("Y-m",strtotime("-1months",strtotime($now_month)));
        $last_month = date("Y-m",strtotime("-1months",strtotime($now_month)));
        $n = 1;
        $params= [];
        $startTime = '';
        $endTime = '';
        if (!empty($_REQUEST["page"])) {
            $n = $_REQUEST["page"];
        }
        if(!empty($shopId)){
            $key = $shopId;
        }
        if (!empty($_REQUEST["shop"])) {
            $shopId = $_REQUEST["shop"];
            $key = $_REQUEST["shop"];
            $params["shop"] = $key;
        }
        if (!empty($_REQUEST["month"])) {
            $startTime = $_REQUEST["month"]."-01";
            $endTime = date("Y-m-d",strtotime("+1months",strtotime($startTime)));
            $key_month = $_REQUEST["month"];
            $params["month"] = $key_month;
        }
//        var_dump($startTime);var_dump($endTime);die;
        //所有门店
        $shops = ShopInfo::find()->all();
        //所有机构
        $orgs = OrgInfo::find()->all();

        $org_course = (new \yii\db\Query())
            ->select(['org_id'])
            ->from('org_shop_info')
            ->where(['shop_id'=>$shopId]);
        //->orderBy('id asc');
        $pages = new Pagination(['totalCount' => $org_course->count(), 'pageSize' => '500']);
        $data = $org_course->offset($pages->offset)->limit($pages->limit)->all();

        foreach($orgs as $v){
            $orgs[$v['id']] = $v['name'];
        }

        //==================================查询分成比例、履约保证金、课程id和名称=====================================//
        foreach($data as $k1=>$v1){
            $divide_margin_proportion =$db->createCommand("select divide_proportion,margin_proportion
                from org_info where id=$v1[org_id]")->queryOne();
            //机构分成所得比例
            $data[$k1]['divide_proportion'] = $divide_margin_proportion['divide_proportion'];
            //履约保证金比例
            $data[$k1]['margin_proportion'] = $divide_margin_proportion['margin_proportion'];
            $data[$k1]['course_info'] = $db
                ->createCommand("select id,name from course_info where org_id=$v1[org_id]")->queryAll();
        }
        //==================================查询分成比例、履约保证金、课程id和名称=====================================//
//        var_dump($data);

        //==================================组装时间搜索SQL=====================================//
        $time_sql = '';
        if(!empty($startTime) && !empty($endTime)){
            $time_sql = "ccr.update_time >= '".$startTime."' and ccr.update_time < '".$endTime."'";
        }else{
            $time_sql = "ccr.update_time between '".$key_month."-01 00:00:00' and '".$key_month."-31 23:59:59'";
        }
        //==================================组装时间搜索SQL=====================================//

        //==================================遍历各个课程的报名人数和收入=====================================//
        $org_all_money = 0;
        $org_divide_money = 0;
        $org_margin_money = 0;
        $margin_money = 0;
        foreach($data as $k2=>$v2){
            //var_dump($v2['course_info']);
            foreach($v2['course_info'] as $k3=>$v3) {
                $course_id = $v3['id'];
                $org_id = $v2['org_id'];
                $other_info = $db->createCommand("
                       select temp.*, sum(temp.total_price) as heji from  (
                       select sum(ccr.total_money) as total_price  ,
                       count(distinct(ccr.pay_no)) as child_num
                       from child_class_record as ccr
                       left join child_class as cc on ccr.id = cc.record_id
                       left join org_shop_info as osi on osi.shop_id = ccr.shop_id
                       where osi.org_id = :org_id and cc.course_id = :course_id
                       and osi.shop_id = $shopId and ($time_sql) and ccr.check_status=2
                       group by cc.course_id
                       ) AS temp
                   ",[':org_id'=>$org_id,':course_id'=>$course_id])->queryOne();
//                var_dump($other_info);
                if(!empty($other_info)){
                    $data[$k2]['course_info'][$k3]['child_num'] =
                        $other_info['child_num'] ? $other_info['child_num'] : 0;
                    $data[$k2]['course_info'][$k3]['total_price'] =
                        $other_info['total_price'] ? $other_info['total_price'] : 0;
                }
                //机构总收款 = 机构中各个课程收款的总和
                $org_all_money = $org_all_money+round($other_info['heji'],2);
                //机构税后分成所得
                $org_divide_money += round($other_info['heji']*0.9429*$v2['divide_proportion'],2);
                //扣除履约保证金后实际所得
                $org_margin_money +=
                    round($other_info['heji']*0.9429*$v2['divide_proportion']*(1-$v2['margin_proportion']),2);
                //履约保证金金额
                $margin_money +=
                    round($other_info['heji']*0.9429*$v2['divide_proportion']*$v2['margin_proportion'],2);
            }
//            var_dump($org_all_money);
            //总收款
            $data[$k2]['org_all_money'] = round($org_all_money);
            //清空初始总收款
            $org_all_money = 0;
            //各个机构分成比
            $data[$k2]['divide_proportion'] = $v2['divide_proportion'];
        }
//        var_dump($data);
        //==================================遍历各个课程的报名人数和收入=====================================//



        //=======================================合计==========================================//
        $heji = (new Query())->select(['sum(ccr.total_money) as heji'])
            ->addSelect("count(distinct(ccr.pay_no)) as num")
            ->from('child_class as cc')
            ->rightjoin('child_class_record as ccr','ccr.id=cc.record_id')
            ->where($time_sql)
            ->andWhere('ccr.shop_id='.$shopId)
            ->andWhere('ccr.check_status=2')
            ->one();
        $pages->params = $params;
        //var_dump($heji);
        //门店所有机构税后分成总额
        $heji['org_divide_money'] = $org_divide_money;
        //门店所有机构税后分成扣除履约保证金总额
        $heji['org_margin_money'] = $org_margin_money;
        //履约保证金金额总额
        $heji['margin_money'] = $margin_money;
        //=======================================合计==========================================//

        return $this->render('margin-list',[
            'list' => $data,
            'key' => $key,
            'month' => $key_month,
            'now_month' => $now_month,
            'last_month' => $last_month,
            'n' => $n,
            'pages' => $pages,
            'shops' => $shops,
            'orgs' => $orgs,
            'heji' => $heji,
            'is_shopId' => $is_shopId //财务为空 门店为门店id
        ]);
    }

    //结算履约保证金
    public function actionSubmitLastMonthMargin(){
        $db = \Yii::$app->db;
        $request = \Yii::$app->request;
        $lastMonth = date("Y-m",(strtotime("-1 month")));
        $startTime = date("Y-m-01",strtotime($lastMonth));
        $endTime = date("Y-m-31",strtotime($lastMonth));
        if (!empty($request->post())){
            $shop_id = $request->post("shopId");
            $flag = $db->createCommand("
                 update child_class_record set check_status = 3 where shop_id = :shop_id and check_status = 2
                 and update_time BETWEEN :startTime AND :endTime",
                [':shop_id'=>$shop_id,':startTime'=>$startTime,':endTime'=>$endTime])
                ->execute();
            if($flag){
                $data['code'] = 1;
                $data['desc'] = 'ok';
                echo json_encode($data);exit;
            }else{
                $data['code'] = -1;
                $data['desc'] = 'no';
                echo json_encode($data);exit;
            }
        }
    }

    //导出分成明细
    public function actionExport(){

        $db = \Yii::$app->db;
        $shopId = 1;
        $key = '';
        $now_month = date("Y-m",time());
        $key_month = date("Y-m",strtotime("-1months",strtotime($now_month)));
        $last_month = date("Y-m",strtotime("-1months",strtotime($now_month)));
        $n = 1;
        $params= [];
        $startTime = '';
        $endTime = '';
        if (!empty($_REQUEST["page"])) {
            $n = $_REQUEST["page"];
        }
        if(!empty($shopId)){
            $key = $shopId;
        }
        if (!empty($_REQUEST["shop"])) {
            $shopId = $_REQUEST["shop"];
            $key = $_REQUEST["shop"];
            $params["shop"] = $key;
        }
        if (!empty($_REQUEST["month"])) {
            $startTime = $_REQUEST["month"]."-01";
            $endTime = date("Y-m-d",strtotime("+1months",strtotime($startTime)));
            $key_month = $_REQUEST["month"];
            $params["month"] = $key_month;
        }
//        var_dump($startTime);var_dump($endTime);die;
        //所有门店
        $shops = ShopInfo::find()->all();
        //所有机构
        $orgs = OrgInfo::find()->all();

        $org_course = (new \yii\db\Query())
            ->select(['osi.org_id','oi.name as orgName','si.name as shopName'])
            ->from('org_shop_info as osi')
            ->leftJoin("org_info as oi","oi.id=osi.org_id")
            ->leftJoin("shop_info as si","si.id=osi.shop_id")
            ->where(['osi.shop_id'=>$shopId]);
        //->orderBy('id asc');
        $pages = new Pagination(['totalCount' => $org_course->count(), 'pageSize' => '500']);
        $data = $org_course->offset($pages->offset)->limit($pages->limit)->all();

        foreach ($shops as $v) {
            $shops[$v['id']] = $v['name'];
        }

        foreach($orgs as $v){
            $orgs[$v['id']] = $v['name'];
        }

        //==================================查询分成比例、履约保证金、课程id和名称=====================================//
        foreach($data as $k1=>$v1){
            $divide_margin_proportion =$db->createCommand("select divide_proportion,margin_proportion
                from org_info where id=$v1[org_id]")->queryOne();
            //机构分成所得比例
            $data[$k1]['divide_proportion'] = $divide_margin_proportion['divide_proportion'];
            //履约保证金比例
            $data[$k1]['margin_proportion'] = $divide_margin_proportion['margin_proportion'];
            $data[$k1]['course_info'] = $db
                ->createCommand("select id,name from course_info where org_id=$v1[org_id]")->queryAll();
        }
        //==================================查询分成比例、履约保证金、课程id和名称=====================================//
        //var_dump($data);

        //==================================组装时间搜索SQL=====================================//
        $time_sql = '';
        if(!empty($startTime) && !empty($endTime)){
            $time_sql = "ccr.update_time >= '".$startTime."' and ccr.update_time < '".$endTime."'";
        }else{
            $time_sql = "ccr.update_time between '".$key_month."-01 00:00:00' and '".$key_month."-31 23:59:59'";
        }
        //==================================组装时间搜索SQL=====================================//

        //==================================遍历各个课程的报名人数和收入=====================================//
        $org_all_money = 0;
        $org_divide_money = 0;
        $org_margin_money = 0;
        $margin_money = 0;
        foreach($data as $k2=>$v2){
            foreach($v2['course_info'] as $k3=>$v3) {
                $course_id = $v3['id'];
                $org_id = $v2['org_id'];
                $other_info = $db->createCommand("
                       select temp.*, sum(temp.total_price) as heji from  (
                       select sum(ccr.total_money) as total_price ,
                       count(distinct(ccr.pay_no)) as child_num
                       from child_class_record as ccr
                       left join child_class as cc on ccr.id = cc.record_id
                       left join org_shop_info as osi on osi.shop_id = ccr.shop_id
                       where osi.org_id = :org_id and cc.course_id = :course_id
                       and osi.shop_id = $shopId and ($time_sql) and ccr.check_status=1
                       group by cc.course_id
                       ) AS temp
                   ",[':org_id'=>$org_id,':course_id'=>$course_id])->queryOne();
//                var_dump($other_info);
                if(!empty($other_info)){
                    $data[$k2]['course_info'][$k3]['child_num'] =
                        $other_info['child_num'] ? $other_info['child_num'] : 0;
                    $data[$k2]['course_info'][$k3]['total_price'] =
                        $other_info['total_price'] ? $other_info['total_price'] : 0;
                }
                //机构总收款 = 机构中各个课程收款的总和
                $org_all_money = $org_all_money+round($other_info['heji'],2);
                //机构税后分成所得
                $org_divide_money += round($other_info['heji']*0.9429*$v2['divide_proportion'],2);
                //扣除履约保证金后实际所得
                $org_margin_money +=
                    round($other_info['heji']*0.9429*$v2['divide_proportion']*(1-$v2['margin_proportion']),2);
                //履约保证金金额
                $margin_money +=
                    round($other_info['heji']*0.9429*$v2['divide_proportion']*$v2['margin_proportion'],2);
            }
            //总收款
            $data[$k2]['org_all_money'] = round($org_all_money);
            //清空初始总收款
            $org_all_money = 0;
            //各个机构分成比
            $data[$k2]['divide_proportion'] = $v2['divide_proportion'];
        }
//        var_dump($data);
        //==================================遍历各个课程的报名人数和收入=====================================//



        //=======================================合计==========================================//
        $heji = (new Query())->select(['sum(ccr.total_money) as heji'])
            ->addSelect("count(distinct(ccr.pay_no)) as num")
            ->from('child_class as cc')
            ->rightjoin('child_class_record as ccr','ccr.id=cc.record_id')
            ->where($time_sql)
            ->andWhere('ccr.shop_id='.$shopId)
            ->andWhere('ccr.check_status=1')
            ->one();
        $pages->params = $params;
        //var_dump($heji);
        //门店所有机构税后分成总额
        $heji['org_divide_money'] = $org_divide_money;
        //门店所有机构税后分成扣除履约保证金总额
        $heji['org_margin_money'] = $org_margin_money;
        //履约保证金金额总额
        $heji['margin_money'] = $margin_money;
        //=======================================合计==========================================//


        //=======================================组装设置EXCEL=================================//

        function convertUTF8($str)
        {
            if(empty($str)) return '';
            return  iconv('gb2312', 'utf-8', $str);
            //return  mb_convert_encoding($str, "UTF-8", "GBK");
        }
        //header('Content-Type:text/ html;Charset=utf-8;');
        $objPHPExcel = new \PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);
        //设置excel的属性：
        //创建人
        $objPHPExcel->getProperties()->setCreator("七彩世界");
        //最后修改人
        $objPHPExcel->getProperties()->setLastModifiedBy("made");
        //标题
        $objPHPExcel->getProperties()->setTitle("分成明细表");
        //题目
        $objPHPExcel->getProperties()->setSubject("分成明细表");
        //描述
        $objPHPExcel->getProperties()->setDescription("分成明细表");
        //关键字
        $objPHPExcel->getProperties()->setKeywords("分成明细表");
        //种类
        $objPHPExcel->getProperties()->setCategory("Test result file");

        //设置当前的sheet
        $objPHPExcel->setActiveSheetIndex(0);
        //水平居中
        $objPHPExcel->getActiveSheet()->getStyle('A1:M1000')
            ->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        //垂直居中
        $objPHPExcel->getActiveSheet()->getStyle('A1:M1000')
            ->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
        //设置颜色
        $objPHPExcel->getActiveSheet()->getStyle('A1:M1')->getFill()
            ->setFillType(\PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('ADD8E6');
        //设置sheet的name
        $objPHPExcel->getActiveSheet()->setTitle('Simple');
        //设置单元格的值
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1',convertUTF8('七彩世界儿童游乐合作机构分成明细表'));
        //合并单元格
        $objPHPExcel->getActiveSheet()->mergeCells('A1:M1');

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A2',convertUTF8('序号'));
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B2',convertUTF8('合作机构'));
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(40);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C2',convertUTF8('课程'));
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(22);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D2',convertUTF8('数量人次'));
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E2',convertUTF8('收款金额(元)'));
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F2',convertUTF8('总收款(元)'));
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G2',convertUTF8('税后总收款(元)'));
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H2',convertUTF8('分成比例(我方在前)'));
        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I2',convertUTF8('机构分成金额(税后)'));
        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J2',convertUTF8('履约保证金比例'));
        $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(20);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K2',convertUTF8('扣除履约保证金后实际应付机构金额(元)'));
        $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(20);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L2',convertUTF8('课程履约金(元)'));
        $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(20);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M2',convertUTF8('备注'));

        $i = 3;
        foreach($data as $k=>$v){
            $row = count($v['course_info']);
            if($row > 1){
                $objPHPExcel->getActiveSheet()->mergeCells('A'.($i).':A'.($i+$row-1));
                $objPHPExcel->getActiveSheet()->mergeCells('B'.($i).':B'.($i+$row-1));
                $objPHPExcel->getActiveSheet()->mergeCells('F'.($i).':F'.($i+$row-1));
                $objPHPExcel->getActiveSheet()->mergeCells('G'.($i).':G'.($i+$row-1));
                $objPHPExcel->getActiveSheet()->mergeCells('H'.($i).':H'.($i+$row-1));
                $objPHPExcel->getActiveSheet()->mergeCells('I'.($i).':I'.($i+$row-1));
                $objPHPExcel->getActiveSheet()->mergeCells('J'.($i).':J'.($i+$row-1));
                $objPHPExcel->getActiveSheet()->mergeCells('K'.($i).':K'.($i+$row-1));
                $objPHPExcel->getActiveSheet()->mergeCells('L'.($i).':L'.($i+$row-1));
                $objPHPExcel->getActiveSheet()->mergeCells('M'.($i).':M'.($i+$row-1));

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.($i),$k+1);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.($i),$v['orgName']);

                foreach($v['course_info'] as $s=>$t){
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.($i+$s),$t['name']);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.($i+$s),$t['child_num']);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.($i+$s),$t['total_price']);
                }

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.($i),$v['org_all_money']);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.($i),round($v['org_all_money']*0.9429,2));
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue
                ('H'.($i),((1-$v['divide_proportion'])*10)."/".($v['divide_proportion']*10));
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue
                ('I'.($i),round($v['org_all_money']*0.9429*$v['divide_proportion'],2));
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.($i),100*$v['margin_proportion']."%");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue
                ('K'.($i),round($v['org_all_money']*0.9429*$v['divide_proportion']*(1-$v['margin_proportion']),2));
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue
                ('L'.($i),round($v['org_all_money']*0.9429*$v['divide_proportion']*$v['margin_proportion'],2));
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M'.($i),'');

                $i = $i + $row ;
            }else{
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.($i),$k+1);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.($i),$v['orgName']);
                //课程
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue
                ('C'.($i),!empty($v['course_info'][0]['name']) ? $v['course_info'][0]['name'] : '');
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue
                ('D'.($i),!empty($v['course_info'][0]['child_num']) ? $v['course_info'][0]['child_num'] : 0);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue
                ('E'.($i),!empty($v['course_info'][0]['total_money']) ? $v['course_info'][0]['total_money'] : 0);

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.($i),$v['org_all_money']);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.($i),round($v['org_all_money']*0.9429,2));
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue
                ('H'.($i),((1-$v['divide_proportion'])*10)."/".($v['divide_proportion']*10));
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue
                ('I'.($i),round($v['org_all_money']*0.9429*$v['divide_proportion'],2));
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.($i),100*$v['margin_proportion']."%");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue
                ('K'.($i),round($v['org_all_money']*0.9429*$v['divide_proportion']*(1-$v['margin_proportion']),2));
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue
                ('L'.($i),round($v['org_all_money']*0.9429*$v['divide_proportion']*$v['margin_proportion'],2));
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M'.($i),'');

                $i = $i + 1;
            }
        }

        ob_end_clean();
        ob_start();
        header('Content-Type : application/vnd.ms-excel');
        header('Content-Disposition:attachment;filename="'.'分成信息表-'.date("Y年m月j日 H时i分s秒").'.xls"');
        $objWriter= \PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel5');
        //var_dump($objWriter);die;
        $objWriter->save('php://output');
        exit;
        //=======================================组装设置EXCEL=================================//
    }

    //导出保证金明细
    public function actionExportMargin(){
        $db = \Yii::$app->db;
        $shopId = 1;
        $key = '';
        $now_month = date("Y-m",time());
        $key_month = date("Y-m",strtotime("-1months",strtotime($now_month)));
        $last_month = date("Y-m",strtotime("-1months",strtotime($now_month)));
        $n = 1;
        $params= [];
        $startTime = '';
        $endTime = '';
        if (!empty($_REQUEST["page"])) {
            $n = $_REQUEST["page"];
        }
        if(!empty($shopId)){
            $key = $shopId;
        }
        if (!empty($_REQUEST["shop"])) {
            $shopId = $_REQUEST["shop"];
            $key = $_REQUEST["shop"];
            $params["shop"] = $key;
        }
        if (!empty($_REQUEST["month"])) {
            $startTime = $_REQUEST["month"]."-01";
            $endTime = date("Y-m-d",strtotime("+1months",strtotime($startTime)));
            $key_month = $_REQUEST["month"];
            $params["month"] = $key_month;
        }
//        var_dump($startTime);var_dump($endTime);die;
        //所有门店
        $shops = ShopInfo::find()->all();
        //所有机构
        $orgs = OrgInfo::find()->all();

        $org_course = (new \yii\db\Query())
            ->select(['osi.org_id','oi.name as orgName','si.name as shopName'])
            ->from('org_shop_info as osi')
            ->leftJoin("org_info as oi","oi.id=osi.org_id")
            ->leftJoin("shop_info as si","si.id=osi.shop_id")
            ->where(['osi.shop_id'=>$shopId]);

        $pages = new Pagination(['totalCount' => $org_course->count(), 'pageSize' => '500']);
        $data = $org_course->offset($pages->offset)->limit($pages->limit)->all();

        foreach($orgs as $v){
            $orgs[$v['id']] = $v['name'];
        }

        //==================================查询分成比例、履约保证金、课程id和名称=====================================//
        foreach($data as $k1=>$v1){
            $divide_margin_proportion =$db->createCommand("select divide_proportion,margin_proportion
                from org_info where id=$v1[org_id]")->queryOne();
            //机构分成所得比例
            $data[$k1]['divide_proportion'] = $divide_margin_proportion['divide_proportion'];
            //履约保证金比例
            $data[$k1]['margin_proportion'] = $divide_margin_proportion['margin_proportion'];
            $data[$k1]['course_info'] = $db
                ->createCommand("select id,name from course_info where org_id=$v1[org_id]")->queryAll();
        }
        //==================================查询分成比例、履约保证金、课程id和名称=====================================//
//        var_dump($data);

        //==================================组装时间搜索SQL=====================================//
        $time_sql = '';
        if(!empty($startTime) && !empty($endTime)){
            $time_sql = "ccr.update_time >= '".$startTime."' and ccr.update_time < '".$endTime."'";
        }else{
            $time_sql = "ccr.update_time between '".$key_month."-01 00:00:00' and '".$key_month."-31 23:59:59'";
        }
        //==================================组装时间搜索SQL=====================================//

        //==================================遍历各个课程的报名人数和收入=====================================//
        $org_all_money = 0;
        $org_divide_money = 0;
        $org_margin_money = 0;
        $margin_money = 0;
        foreach($data as $k2=>$v2){
            //var_dump($v2['course_info']);
            foreach($v2['course_info'] as $k3=>$v3) {
                $course_id = $v3['id'];
                $org_id = $v2['org_id'];
                $other_info = $db->createCommand("
                       select temp.*, sum(temp.total_price) as heji from  (
                       select sum(ccr.total_money) as total_price  ,
                       count(distinct(ccr.pay_no)) as child_num
                       from child_class_record as ccr
                       left join child_class as cc on ccr.id = cc.record_id
                       left join org_shop_info as osi on osi.shop_id = ccr.shop_id
                       where osi.org_id = :org_id and cc.course_id = :course_id
                       and osi.shop_id = $shopId and ($time_sql) and ccr.check_status=2
                       group by cc.course_id
                       ) AS temp
                   ",[':org_id'=>$org_id,':course_id'=>$course_id])->queryOne();
//                var_dump($other_info);
                if(!empty($other_info)){
                    $data[$k2]['course_info'][$k3]['child_num'] =
                        $other_info['child_num'] ? $other_info['child_num'] : 0;
                    $data[$k2]['course_info'][$k3]['total_price'] =
                        $other_info['total_price'] ? $other_info['total_price'] : 0;
                }
                //机构总收款 = 机构中各个课程收款的总和
                $org_all_money = $org_all_money+round($other_info['heji'],2);
                //机构税后分成所得
                $org_divide_money += round($other_info['heji']*0.9429*$v2['divide_proportion'],2);
                //扣除履约保证金后实际所得
                $org_margin_money +=
                    round($other_info['heji']*0.9429*$v2['divide_proportion']*(1-$v2['margin_proportion']),2);
                //履约保证金金额
                $margin_money +=
                    round($other_info['heji']*0.9429*$v2['divide_proportion']*$v2['margin_proportion'],2);
            }
//            var_dump($org_all_money);
            //总收款
            $data[$k2]['org_all_money'] = round($org_all_money);
            //清空初始总收款
            $org_all_money = 0;
            //各个机构分成比
            $data[$k2]['divide_proportion'] = $v2['divide_proportion'];
        }
//        var_dump($data);
        //==================================遍历各个课程的报名人数和收入=====================================//



        //=======================================合计==========================================//
        $heji = (new Query())->select(['sum(ccr.total_money) as heji'])
            ->addSelect("count(distinct(ccr.pay_no)) as num")
            ->from('child_class as cc')
            ->rightjoin('child_class_record as ccr','ccr.id=cc.record_id')
            ->where($time_sql)
            ->andWhere('ccr.shop_id='.$shopId)
            ->andWhere('ccr.check_status=2')
            ->one();
        $pages->params = $params;
        //var_dump($heji);
        //门店所有机构税后分成总额
        $heji['org_divide_money'] = $org_divide_money;
        //门店所有机构税后分成扣除履约保证金总额
        $heji['org_margin_money'] = $org_margin_money;
        //履约保证金金额总额
        $heji['margin_money'] = $margin_money;
        //=======================================合计==========================================//
//var_dump($data);die;

        //=======================================组装设置EXCEL=================================//

        function convertUTF8($str)
        {
            if(empty($str)) return '';
            return  iconv('gb2312', 'utf-8', $str);
            //return  mb_convert_encoding($str, "UTF-8", "GBK");
        }
        //header('Content-Type:text/ html;Charset=utf-8;');
        $objPHPExcel = new \PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);
        //设置excel的属性：
        //创建人
        $objPHPExcel->getProperties()->setCreator("七彩世界");
        //最后修改人
        $objPHPExcel->getProperties()->setLastModifiedBy("made");
        //标题
        $objPHPExcel->getProperties()->setTitle("保证金明细表");
        //题目
        $objPHPExcel->getProperties()->setSubject("保证金明细表");
        //描述
        $objPHPExcel->getProperties()->setDescription("保证金明细表");
        //关键字
        $objPHPExcel->getProperties()->setKeywords("保证金明细表");
        //种类
        $objPHPExcel->getProperties()->setCategory("Test result file");

        //设置当前的sheet
        $objPHPExcel->setActiveSheetIndex(0);
        //水平居中
        $objPHPExcel->getActiveSheet()->getStyle('A1:M1000')
            ->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        //垂直居中
        $objPHPExcel->getActiveSheet()->getStyle('A1:M1000')
            ->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
        //设置颜色
        $objPHPExcel->getActiveSheet()->getStyle('A1:J1')->getFill()
            ->setFillType(\PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('ADD8E6');
        //设置sheet的name
        $objPHPExcel->getActiveSheet()->setTitle('Simple');
        //设置单元格的值
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1',convertUTF8('七彩世界儿童游乐合作机构保证金明细表'));
        //合并单元格
        $objPHPExcel->getActiveSheet()->mergeCells('A1:J1');

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A2',convertUTF8('序号'));
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B2',convertUTF8('合作机构'));
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(40);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C2',convertUTF8('课程'));
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(22);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D2',convertUTF8('数量人次'));
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E2',convertUTF8('收款金额(元)'));
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F2',convertUTF8('总收款(元)'));
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G2',convertUTF8('分成比例(我方在前)'));
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H2',convertUTF8('履约保证金比例'));
        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I2',convertUTF8('课程履约金(元)'));
        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J2',convertUTF8('备注'));

        $i = 3;
        foreach($data as $k=>$v){
            $row = count($v['course_info']);
            if($row > 1){
                $objPHPExcel->getActiveSheet()->mergeCells('A'.($i).':A'.($i+$row-1));
                $objPHPExcel->getActiveSheet()->mergeCells('B'.($i).':B'.($i+$row-1));
                $objPHPExcel->getActiveSheet()->mergeCells('F'.($i).':F'.($i+$row-1));
                $objPHPExcel->getActiveSheet()->mergeCells('G'.($i).':G'.($i+$row-1));
                $objPHPExcel->getActiveSheet()->mergeCells('H'.($i).':H'.($i+$row-1));
                $objPHPExcel->getActiveSheet()->mergeCells('I'.($i).':I'.($i+$row-1));
                $objPHPExcel->getActiveSheet()->mergeCells('J'.($i).':J'.($i+$row-1));

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.($i),$k+1);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.($i),$v['orgName']);

                foreach($v['course_info'] as $s=>$t){
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.($i+$s),$t['name']);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.($i+$s),$t['child_num']);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.($i+$s),$t['total_price']);
                }

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.($i),$v['org_all_money']);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.($i),
                    ((1-$v['divide_proportion'])*10)."/".($v['divide_proportion']*10));
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.($i),100*$v['margin_proportion']."%");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.($i),
                    round($v['org_all_money']*0.9429*$v['divide_proportion']*$v['margin_proportion'],2));
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.($i),'');

                $i = $i + $row ;
            }else{
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.($i),$k+1);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.($i),$v['orgName']);

                /*=============================课程============================*/
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue
                ('C'.($i),!empty($v['course_info'][0]['name']) ? $v['course_info'][0]['name'] : '');
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue
                ('D'.($i),!empty($v['course_info'][0]['child_num']) ? $v['course_info'][0]['child_num'] : 0);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue
                ('E'.($i),!empty($v['course_info'][0]['total_money']) ? $v['course_info'][0]['total_money'] : 0);
                /*=============================课程============================*/

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.($i),$v['org_all_money']);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.($i),
                    ((1-$v['divide_proportion'])*10)."/".($v['divide_proportion']*10));
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.($i),100*$v['margin_proportion']."%");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.($i),
                    round($v['org_all_money']*0.9429*$v['divide_proportion']*$v['margin_proportion'],2));
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.($i),'');

                $i = $i + 1;
            }
        }

        ob_end_clean();
        ob_start();
        header('Content-Type : application/vnd.ms-excel');
        header('Content-Disposition:attachment;filename="'.'保证金明细表-'.date("Y年m月j日").'.xls"');
        $objWriter= \PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel5');
        //var_dump($objWriter);die;
        $objWriter->save('php://output');
        exit;
        //=======================================组装设置EXCEL=================================//
    }
}