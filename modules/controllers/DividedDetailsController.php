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
    public $enableCsrfValidation = false;//yiiĬ�ϱ�csrf��֤�����post�����Ĳ����ᱨ��
    public $layout = 'layout';

    //�ֳ��б���ϸ
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
        //�����ŵ�
        $shops = ShopInfo::find()->all();
        //���л���
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

        //==================================��ѯ�ֳɱ�������Լ��֤�𡢿γ�id������=====================================//
        foreach($data as $k1=>$v1){
            $divide_margin_proportion =$db->createCommand("select divide_proportion,margin_proportion
                from org_info where id=$v1[org_id]")->queryOne();
            //�����ֳ����ñ���
            $data[$k1]['divide_proportion'] = $divide_margin_proportion['divide_proportion'];
            //��Լ��֤�����
            $data[$k1]['margin_proportion'] = $divide_margin_proportion['margin_proportion'];
            $data[$k1]['course_info'] = $db
                ->createCommand("select id,name from course_info where org_id=$v1[org_id]")->queryAll();
        }
        //==================================��ѯ�ֳɱ�������Լ��֤�𡢿γ�id������=====================================//
        //var_dump($data);

        //==================================��װʱ������SQL=====================================//
        $time_sql = '';
        if(!empty($startTime) && !empty($endTime)){
            $time_sql = "ccr.update_time >= '".$startTime."' and ccr.update_time < '".$endTime."'";
        }else{
            $time_sql = "ccr.update_time between '".$key_month."-01 00:00:00' and '".$key_month."-31 23:59:59'";
        }
        //==================================��װʱ������SQL=====================================//

        //==================================���������γ̵ı�������������=====================================//
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
                //�������տ� = �����и����γ��տ���ܺ�
                $org_all_money = $org_all_money+round($other_info['heji'],2);
                //����˰��ֳ�����
                $org_divide_money += round($other_info['heji']*0.9429*$v2['divide_proportion'],2);
                //�۳���Լ��֤���ʵ������
                $org_margin_money +=
                    round($other_info['heji']*0.9429*$v2['divide_proportion']*(1-$v2['margin_proportion']),2);
                //��Լ��֤����
                $margin_money +=
                    round($other_info['heji']*0.9429*$v2['divide_proportion']*$v2['margin_proportion'],2);
            }
            //���տ�
            $data[$k2]['org_all_money'] = round($org_all_money);
            //��ճ�ʼ���տ�
            $org_all_money = 0;
            //���������ֳɱ�
            $data[$k2]['divide_proportion'] = $v2['divide_proportion'];
        }
//        var_dump($data);
        //==================================���������γ̵ı�������������=====================================//



        //=======================================�ϼ�==========================================//
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
        //�ŵ����л���˰��ֳ��ܶ�
        $heji['org_divide_money'] = $org_divide_money;
        //�ŵ����л���˰��ֳɿ۳���Լ��֤���ܶ�
        $heji['org_margin_money'] = $org_margin_money;
        //��Լ��֤�����ܶ�
        $heji['margin_money'] = $margin_money;
        //=======================================�ϼ�==========================================//

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
            'is_shopId' => $is_shopId //����Ϊ�� �ŵ�Ϊ�ŵ�id
        ]);
    }
    //����ֳ�
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

    //��֤����ϸ
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
        //�����ŵ�
        $shops = ShopInfo::find()->all();
        //���л���
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

        //==================================��ѯ�ֳɱ�������Լ��֤�𡢿γ�id������=====================================//
        foreach($data as $k1=>$v1){
            $divide_margin_proportion =$db->createCommand("select divide_proportion,margin_proportion
                from org_info where id=$v1[org_id]")->queryOne();
            //�����ֳ����ñ���
            $data[$k1]['divide_proportion'] = $divide_margin_proportion['divide_proportion'];
            //��Լ��֤�����
            $data[$k1]['margin_proportion'] = $divide_margin_proportion['margin_proportion'];
            $data[$k1]['course_info'] = $db
                ->createCommand("select id,name from course_info where org_id=$v1[org_id]")->queryAll();
        }
        //==================================��ѯ�ֳɱ�������Լ��֤�𡢿γ�id������=====================================//
//        var_dump($data);

        //==================================��װʱ������SQL=====================================//
        $time_sql = '';
        if(!empty($startTime) && !empty($endTime)){
            $time_sql = "ccr.update_time >= '".$startTime."' and ccr.update_time < '".$endTime."'";
        }else{
            $time_sql = "ccr.update_time between '".$key_month."-01 00:00:00' and '".$key_month."-31 23:59:59'";
        }
        //==================================��װʱ������SQL=====================================//

        //==================================���������γ̵ı�������������=====================================//
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
                //�������տ� = �����и����γ��տ���ܺ�
                $org_all_money = $org_all_money+round($other_info['heji'],2);
                //����˰��ֳ�����
                $org_divide_money += round($other_info['heji']*0.9429*$v2['divide_proportion'],2);
                //�۳���Լ��֤���ʵ������
                $org_margin_money +=
                    round($other_info['heji']*0.9429*$v2['divide_proportion']*(1-$v2['margin_proportion']),2);
                //��Լ��֤����
                $margin_money +=
                    round($other_info['heji']*0.9429*$v2['divide_proportion']*$v2['margin_proportion'],2);
            }
//            var_dump($org_all_money);
            //���տ�
            $data[$k2]['org_all_money'] = round($org_all_money);
            //��ճ�ʼ���տ�
            $org_all_money = 0;
            //���������ֳɱ�
            $data[$k2]['divide_proportion'] = $v2['divide_proportion'];
        }
//        var_dump($data);
        //==================================���������γ̵ı�������������=====================================//



        //=======================================�ϼ�==========================================//
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
        //�ŵ����л���˰��ֳ��ܶ�
        $heji['org_divide_money'] = $org_divide_money;
        //�ŵ����л���˰��ֳɿ۳���Լ��֤���ܶ�
        $heji['org_margin_money'] = $org_margin_money;
        //��Լ��֤�����ܶ�
        $heji['margin_money'] = $margin_money;
        //=======================================�ϼ�==========================================//

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
            'is_shopId' => $is_shopId //����Ϊ�� �ŵ�Ϊ�ŵ�id
        ]);
    }

    //������Լ��֤��
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

    //�����ֳ���ϸ
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
        //�����ŵ�
        $shops = ShopInfo::find()->all();
        //���л���
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

        //==================================��ѯ�ֳɱ�������Լ��֤�𡢿γ�id������=====================================//
        foreach($data as $k1=>$v1){
            $divide_margin_proportion =$db->createCommand("select divide_proportion,margin_proportion
                from org_info where id=$v1[org_id]")->queryOne();
            //�����ֳ����ñ���
            $data[$k1]['divide_proportion'] = $divide_margin_proportion['divide_proportion'];
            //��Լ��֤�����
            $data[$k1]['margin_proportion'] = $divide_margin_proportion['margin_proportion'];
            $data[$k1]['course_info'] = $db
                ->createCommand("select id,name from course_info where org_id=$v1[org_id]")->queryAll();
        }
        //==================================��ѯ�ֳɱ�������Լ��֤�𡢿γ�id������=====================================//
        //var_dump($data);

        //==================================��װʱ������SQL=====================================//
        $time_sql = '';
        if(!empty($startTime) && !empty($endTime)){
            $time_sql = "ccr.update_time >= '".$startTime."' and ccr.update_time < '".$endTime."'";
        }else{
            $time_sql = "ccr.update_time between '".$key_month."-01 00:00:00' and '".$key_month."-31 23:59:59'";
        }
        //==================================��װʱ������SQL=====================================//

        //==================================���������γ̵ı�������������=====================================//
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
                //�������տ� = �����и����γ��տ���ܺ�
                $org_all_money = $org_all_money+round($other_info['heji'],2);
                //����˰��ֳ�����
                $org_divide_money += round($other_info['heji']*0.9429*$v2['divide_proportion'],2);
                //�۳���Լ��֤���ʵ������
                $org_margin_money +=
                    round($other_info['heji']*0.9429*$v2['divide_proportion']*(1-$v2['margin_proportion']),2);
                //��Լ��֤����
                $margin_money +=
                    round($other_info['heji']*0.9429*$v2['divide_proportion']*$v2['margin_proportion'],2);
            }
            //���տ�
            $data[$k2]['org_all_money'] = round($org_all_money);
            //��ճ�ʼ���տ�
            $org_all_money = 0;
            //���������ֳɱ�
            $data[$k2]['divide_proportion'] = $v2['divide_proportion'];
        }
//        var_dump($data);
        //==================================���������γ̵ı�������������=====================================//



        //=======================================�ϼ�==========================================//
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
        //�ŵ����л���˰��ֳ��ܶ�
        $heji['org_divide_money'] = $org_divide_money;
        //�ŵ����л���˰��ֳɿ۳���Լ��֤���ܶ�
        $heji['org_margin_money'] = $org_margin_money;
        //��Լ��֤�����ܶ�
        $heji['margin_money'] = $margin_money;
        //=======================================�ϼ�==========================================//


        //=======================================��װ����EXCEL=================================//

        function convertUTF8($str)
        {
            if(empty($str)) return '';
            return  iconv('gb2312', 'utf-8', $str);
            //return  mb_convert_encoding($str, "UTF-8", "GBK");
        }
        //header('Content-Type:text/ html;Charset=utf-8;');
        $objPHPExcel = new \PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);
        //����excel�����ԣ�
        //������
        $objPHPExcel->getProperties()->setCreator("�߲�����");
        //����޸���
        $objPHPExcel->getProperties()->setLastModifiedBy("made");
        //����
        $objPHPExcel->getProperties()->setTitle("�ֳ���ϸ��");
        //��Ŀ
        $objPHPExcel->getProperties()->setSubject("�ֳ���ϸ��");
        //����
        $objPHPExcel->getProperties()->setDescription("�ֳ���ϸ��");
        //�ؼ���
        $objPHPExcel->getProperties()->setKeywords("�ֳ���ϸ��");
        //����
        $objPHPExcel->getProperties()->setCategory("Test result file");

        //���õ�ǰ��sheet
        $objPHPExcel->setActiveSheetIndex(0);
        //ˮƽ����
        $objPHPExcel->getActiveSheet()->getStyle('A1:M1000')
            ->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        //��ֱ����
        $objPHPExcel->getActiveSheet()->getStyle('A1:M1000')
            ->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
        //������ɫ
        $objPHPExcel->getActiveSheet()->getStyle('A1:M1')->getFill()
            ->setFillType(\PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('ADD8E6');
        //����sheet��name
        $objPHPExcel->getActiveSheet()->setTitle('Simple');
        //���õ�Ԫ���ֵ
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1',convertUTF8('�߲������ͯ���ֺ��������ֳ���ϸ��'));
        //�ϲ���Ԫ��
        $objPHPExcel->getActiveSheet()->mergeCells('A1:M1');

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A2',convertUTF8('���'));
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B2',convertUTF8('��������'));
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(40);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C2',convertUTF8('�γ�'));
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(22);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D2',convertUTF8('�����˴�'));
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E2',convertUTF8('�տ���(Ԫ)'));
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F2',convertUTF8('���տ�(Ԫ)'));
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G2',convertUTF8('˰�����տ�(Ԫ)'));
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H2',convertUTF8('�ֳɱ���(�ҷ���ǰ)'));
        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I2',convertUTF8('�����ֳɽ��(˰��)'));
        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J2',convertUTF8('��Լ��֤�����'));
        $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(20);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K2',convertUTF8('�۳���Լ��֤���ʵ��Ӧ���������(Ԫ)'));
        $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(20);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L2',convertUTF8('�γ���Լ��(Ԫ)'));
        $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(20);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M2',convertUTF8('��ע'));

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
                //�γ�
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
        header('Content-Disposition:attachment;filename="'.'�ֳ���Ϣ��-'.date("Y��m��j�� Hʱi��s��").'.xls"');
        $objWriter= \PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel5');
        //var_dump($objWriter);die;
        $objWriter->save('php://output');
        exit;
        //=======================================��װ����EXCEL=================================//
    }

    //������֤����ϸ
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
        //�����ŵ�
        $shops = ShopInfo::find()->all();
        //���л���
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

        //==================================��ѯ�ֳɱ�������Լ��֤�𡢿γ�id������=====================================//
        foreach($data as $k1=>$v1){
            $divide_margin_proportion =$db->createCommand("select divide_proportion,margin_proportion
                from org_info where id=$v1[org_id]")->queryOne();
            //�����ֳ����ñ���
            $data[$k1]['divide_proportion'] = $divide_margin_proportion['divide_proportion'];
            //��Լ��֤�����
            $data[$k1]['margin_proportion'] = $divide_margin_proportion['margin_proportion'];
            $data[$k1]['course_info'] = $db
                ->createCommand("select id,name from course_info where org_id=$v1[org_id]")->queryAll();
        }
        //==================================��ѯ�ֳɱ�������Լ��֤�𡢿γ�id������=====================================//
//        var_dump($data);

        //==================================��װʱ������SQL=====================================//
        $time_sql = '';
        if(!empty($startTime) && !empty($endTime)){
            $time_sql = "ccr.update_time >= '".$startTime."' and ccr.update_time < '".$endTime."'";
        }else{
            $time_sql = "ccr.update_time between '".$key_month."-01 00:00:00' and '".$key_month."-31 23:59:59'";
        }
        //==================================��װʱ������SQL=====================================//

        //==================================���������γ̵ı�������������=====================================//
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
                //�������տ� = �����и����γ��տ���ܺ�
                $org_all_money = $org_all_money+round($other_info['heji'],2);
                //����˰��ֳ�����
                $org_divide_money += round($other_info['heji']*0.9429*$v2['divide_proportion'],2);
                //�۳���Լ��֤���ʵ������
                $org_margin_money +=
                    round($other_info['heji']*0.9429*$v2['divide_proportion']*(1-$v2['margin_proportion']),2);
                //��Լ��֤����
                $margin_money +=
                    round($other_info['heji']*0.9429*$v2['divide_proportion']*$v2['margin_proportion'],2);
            }
//            var_dump($org_all_money);
            //���տ�
            $data[$k2]['org_all_money'] = round($org_all_money);
            //��ճ�ʼ���տ�
            $org_all_money = 0;
            //���������ֳɱ�
            $data[$k2]['divide_proportion'] = $v2['divide_proportion'];
        }
//        var_dump($data);
        //==================================���������γ̵ı�������������=====================================//



        //=======================================�ϼ�==========================================//
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
        //�ŵ����л���˰��ֳ��ܶ�
        $heji['org_divide_money'] = $org_divide_money;
        //�ŵ����л���˰��ֳɿ۳���Լ��֤���ܶ�
        $heji['org_margin_money'] = $org_margin_money;
        //��Լ��֤�����ܶ�
        $heji['margin_money'] = $margin_money;
        //=======================================�ϼ�==========================================//
//var_dump($data);die;

        //=======================================��װ����EXCEL=================================//

        function convertUTF8($str)
        {
            if(empty($str)) return '';
            return  iconv('gb2312', 'utf-8', $str);
            //return  mb_convert_encoding($str, "UTF-8", "GBK");
        }
        //header('Content-Type:text/ html;Charset=utf-8;');
        $objPHPExcel = new \PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);
        //����excel�����ԣ�
        //������
        $objPHPExcel->getProperties()->setCreator("�߲�����");
        //����޸���
        $objPHPExcel->getProperties()->setLastModifiedBy("made");
        //����
        $objPHPExcel->getProperties()->setTitle("��֤����ϸ��");
        //��Ŀ
        $objPHPExcel->getProperties()->setSubject("��֤����ϸ��");
        //����
        $objPHPExcel->getProperties()->setDescription("��֤����ϸ��");
        //�ؼ���
        $objPHPExcel->getProperties()->setKeywords("��֤����ϸ��");
        //����
        $objPHPExcel->getProperties()->setCategory("Test result file");

        //���õ�ǰ��sheet
        $objPHPExcel->setActiveSheetIndex(0);
        //ˮƽ����
        $objPHPExcel->getActiveSheet()->getStyle('A1:M1000')
            ->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        //��ֱ����
        $objPHPExcel->getActiveSheet()->getStyle('A1:M1000')
            ->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
        //������ɫ
        $objPHPExcel->getActiveSheet()->getStyle('A1:J1')->getFill()
            ->setFillType(\PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('ADD8E6');
        //����sheet��name
        $objPHPExcel->getActiveSheet()->setTitle('Simple');
        //���õ�Ԫ���ֵ
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1',convertUTF8('�߲������ͯ���ֺ���������֤����ϸ��'));
        //�ϲ���Ԫ��
        $objPHPExcel->getActiveSheet()->mergeCells('A1:J1');

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A2',convertUTF8('���'));
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B2',convertUTF8('��������'));
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(40);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C2',convertUTF8('�γ�'));
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(22);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D2',convertUTF8('�����˴�'));
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E2',convertUTF8('�տ���(Ԫ)'));
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F2',convertUTF8('���տ�(Ԫ)'));
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G2',convertUTF8('�ֳɱ���(�ҷ���ǰ)'));
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H2',convertUTF8('��Լ��֤�����'));
        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I2',convertUTF8('�γ���Լ��(Ԫ)'));
        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J2',convertUTF8('��ע'));

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

                /*=============================�γ�============================*/
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue
                ('C'.($i),!empty($v['course_info'][0]['name']) ? $v['course_info'][0]['name'] : '');
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue
                ('D'.($i),!empty($v['course_info'][0]['child_num']) ? $v['course_info'][0]['child_num'] : 0);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue
                ('E'.($i),!empty($v['course_info'][0]['total_money']) ? $v['course_info'][0]['total_money'] : 0);
                /*=============================�γ�============================*/

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
        header('Content-Disposition:attachment;filename="'.'��֤����ϸ��-'.date("Y��m��j��").'.xls"');
        $objWriter= \PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel5');
        //var_dump($objWriter);die;
        $objWriter->save('php://output');
        exit;
        //=======================================��װ����EXCEL=================================//
    }
}