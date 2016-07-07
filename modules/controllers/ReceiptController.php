<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/5/24
 * Time: 17:08
 */
namespace app\modules\controllers;

use app\models\ChildClass;
use app\models\ShopCashRecord;
use Yii;
use yii\db\Query;
use yii\web\Controller;
use yii\data\Pagination;
use yii\web\Request;
use app\models\ChildClassRecord;
use app\models\OtherMoneyRecord;
use app\models\ShopInfo;
use app\models\OrgInfo;
use app\models\CourseInfo;
class ReceiptController extends Controller{
    public $enableCsrfValidation = false;//yii默认表单csrf验证，如果post不带改参数会报错！
    public $layout = 'layout';

    public function actionCheckList()
    {
        $where = '';
        $key = '';
        $key_pay = '';
        $key_money = '';
        $key_org = '';
        $key_course = '';
        $key_check = '';
        $course_arr = '';
        $params = [];
        $n = 1;
        if (!empty($_REQUEST["page"])) {
            $n = $_REQUEST["page"];
        }

        if (!empty($_REQUEST["shop"])) {
            $where['ccr.shop_id'] = $_REQUEST["shop"];
            $key = $_REQUEST["shop"];
            $params["shop"] = $key;
        }

        $startTime = '';
        $startTimeParams = '';
        if (!empty($_REQUEST["start_time"])) {
            $startTime = $_REQUEST["start_time"];
            $params["start_time"] = $startTime;
            $startTimeParams = $startTime;
        }

        $endTime = '';
        $endTimeParams = '';
        if (!empty($_REQUEST["end_time"])) {
            $rTime = $_REQUEST["end_time"];
            $endTime = date("Y-m-d", strtotime("$rTime +1 day"));
            $params["end_time"] = $rTime;
            $endTimeParams = $rTime;
        }
        if (!empty($_REQUEST['pay_type'])) {
            if($_REQUEST['pay_type'] == 2){
                $where ['ccr.pay_type']= array(2,3);
            }else{
                $where['ccr.pay_type'] = $_REQUEST['pay_type'];
            }
            $key_pay = $_REQUEST['pay_type'];
            $params['pay_type'] = $key_pay;
        }
        if (!empty($_REQUEST['money_type'])) {
            $where['ccr.money_type'] = $_REQUEST['money_type'];
            $key_money = $_REQUEST['money_type'];
            $params['money_type'] = $key_money;
        }
        if (!empty($_REQUEST['org_select'])) {
            $where['ci.org_id'] = $_REQUEST['org_select'];
            $key_org = $_REQUEST['org_select'];
            $params['org_select'] = $key_org;
            $course_arr = CourseInfo::find()->select(['id', 'name'])
                ->where(['org_id' => $_REQUEST['org_select']])->asArray()->all();
        }
        if (!empty($_REQUEST['course_id'])) {
            $where['cc.course_id'] = $_REQUEST['course_id'];
            $key_course = $_REQUEST['course_id'];
            $params['course_id'] = $key_course;
        }
        if (!empty($_REQUEST['check_status'])) {
            $status = $_REQUEST['check_status'];
            if($status == 2){
                $where['ccr.check_status'] = 0;
            }elseif($status == 3){
                $where['ccr.check_status'] = 1;
            }elseif($status == 4){
                $where['ccr.check_status'] = [2,3];
            }elseif($status == 5){
                $where['ccr.check_status'] = -1;
            }elseif($status == 6){
                $where['ccr.check_status'] = 3;
            }
            $key_check = $status;
            $params['check_status'] = $key_check;
        }
//        var_dump($where);
        $list = (new Query())->select(['*', 'si.name as shopName', 'ci.name as courseName',
            'oi.name as orgName', 'ccr.is_delete as recordDelete', 'ccr.notes as recordNotes',
            'ccr.id as recordId','ccr.pay_type as payType'])
            ->from("child_class_record as ccr")
            ->leftJoin('child_class as cc', 'cc.record_id = ccr.id')
            ->leftJoin('course_info as ci', 'ci.id = cc.course_id')
            ->leftJoin('shop_info as si', 'ccr.shop_id = si.id')
            ->leftJoin('org_info as oi', 'oi.id = ci.org_id')
            //->where('cc.is_delete = 1 ')
            //->where('ccr.is_delete=1')//注释掉后  列表中显示包含退课
            ->andWhere($where);
        if ($startTime && $endTime) {
            $list->andWhere(['between', 'ccr.add_time', $startTime, $endTime]);
        }
        $pages = new Pagination(['totalCount' => $list->count(), 'pageSize' => '8']);
        $data = $list->offset($pages->offset)->limit($pages->limit)->orderBy('ccr.add_time desc')->all();
//        var_dump($data);
        foreach ($data as $k => $v) {
            if (!$v['id']) {
                $dingjin_id = ChildClassRecord::find()->select(['id', 'shop_id'])
                    ->where(['pay_no' => $v['pay_no'], 'money_type' => 2])
                    ->asArray()->one();
                $course_id = ChildClass::find()->select(['course_id'])->where(['record_id' => $dingjin_id['id']])
                    ->asArray()->one();
                $org_id = CourseInfo::find()->select(['org_id'])->where(['id' => $course_id['course_id']])
                    ->asArray()->one();
                $org_name = OrgInfo::find()->select(['name'])->where(['id' => $org_id])->asArray()->one();
                $data[$k]['id'] = $org_id['org_id'];
                $data[$k]['yii_user_id'] = $v['yii_user_id'];
                $data[$k]['course_id'] = $course_id['course_id'];
                $data[$k]['org_id'] = $org_id['org_id'];
                $data[$k]['orgName'] = $org_name['name'];
            }
        }
//        var_dump($data);
        $orgs = OrgInfo::find()->select(['id', 'name'])->asArray()->all();
        foreach ($orgs as $val) {
            $org_list[$val['id']] = $val['name'];
        }

        $pages->params = $params;
        $shops = ShopInfo::find()->all();
//        var_dump($data);die;
        $heji = (new Query())->select(['sum(ccr.total_money) as heji_total',
            'count(ccr.pay_name) as heji_num','count(distinct(ccr.pay_no)) as num'])
            ->from("child_class_record as ccr")
            ->leftJoin('child_class as cc','cc.record_id = ccr.id')
            ->leftJoin('course_info as ci','ci.id = cc.course_id')
            ->leftJoin('shop_info as si','ccr.shop_id = si.id')
            ->leftJoin('org_info as oi','oi.id = ci.org_id')
            ->where('ccr.is_delete=1')
            ->andWhere($where);
            if($startTime && $endTime){
                $heji->andWhere(['between','ccr.add_time',$startTime,$endTime]);
            };
        $heji_total = $heji->one();
//        var_dump($heji_total);
        return $this->render('list', [
            'list' => $data,
            'pages' => $pages,
            'shops' => $shops,
            'key' => $key,
            'key_pay'=>$key_pay,
            'key_money'=>$key_money,
            'key_org'=>$key_org,
            'key_course'=>$key_course,
            'key_check'=>$key_check,
            'startTime'=>$startTimeParams,
            'endTime'=>$endTimeParams,
            'org_list'=>$org_list,
            'n'=>$n,
            'orgs'=>$orgs,
            'course_arr'=>$course_arr,
            'heji'=>$heji_total
        ]);
    }

    //对账
    public function actionDuizhang(){
        $request=\Yii::$app->request;
        if (!empty($request->get())){
            $recordId = $request->get("recordId");
        }
        $record = ChildClassRecord::findOne(['id'=>$recordId]);
        $record->check_status = '1';
        $record->save();
        return $this->redirect("/admin/receipt/check-list");
    }


    //其他收入
    public function actionOtherList(){
        $where = [];
        $key = '';
        $key_pay = '';
        $params = [];
        $n = 1;
        if (!empty($_REQUEST["page"])) {
            $n = $_REQUEST["page"];
        }

        if (!empty($_REQUEST["shop"])) {
            $where['other_money_record.shop_id'] = $_REQUEST["shop"];
            $key = $_REQUEST["shop"];
            $params["shop"] = $key;
        }

        $startTime = [];
        $startTimeParams = '';
        if(!empty($_REQUEST["start_time"])){
            $startTime = ['>','other_money_record.add_date',$_REQUEST["start_time"].' 00:00:00'];
            $params["start_time"] = $_REQUEST["start_time"];
            $startTimeParams = $_REQUEST["start_time"];
        }

        $endTime = [];
        $endTimeParams = '';
        if(!empty($_REQUEST["end_time"])){
            $endTime = ['<','other_money_record.add_date',$_REQUEST["end_time"].' 23:59:59'];
            $params["end_time"] = $_REQUEST["end_time"];
            $endTimeParams = $_REQUEST["end_time"];
        }

        if(!empty($_REQUEST['pay_type'])){
            $where['other_money_record.pay_type'] = $_REQUEST['pay_type'];
            $key_pay = $_REQUEST['pay_type'];
            $params['pay_type'] = $key_pay;
        }
//        var_dump($startTime);var_dump($endTime);
        $list = OtherMoneyRecord::find()->where($where)->andWhere($startTime)->andWhere($endTime)
            ->joinWith("shop")->with("user")->orderBy("other_money_record.add_date desc ");

        $pages = new Pagination(['totalCount' => $list->count(), 'pageSize' => '8']);
        $data = $list->offset($pages->offset)->limit($pages->limit)->asArray()->all();
//        var_dump($data);
        $org = OrgInfo::find()->asArray()->all();
        foreach($org as $val){
            $org_list[$val['id']]=$val['name'];
        }

        $pages->params = $params;
        $shops = ShopInfo::find()->select('id,name')->asArray()->all();
        foreach($shops as $val){
            $shopss[$val['id']] = $val['name'];
        }

        $heji = (new Query())->select(['sum(pay_money) as heji','count(*) as num'])
                ->from("other_money_record");
        if(!empty($where)){
            $heji->where($where);
        }
        if(!empty($startTime) && !empty($endTime)){
            $heji->andwhere($startTime)->andwhere($endTime);
        }
        $heji = $heji->one();
        return $this->render('other-list', [
            'list' => $data,
            'pages' => $pages,
            'shops' => $shopss,
            'key' => $key,
            'key_pay'=>$key_pay,
            'startTime'=>$startTimeParams,
            'endTime'=>$endTimeParams,
            'org_list'=>$org_list,
            'n'=>$n,
            'heji'=>$heji
        ]);
    }
    //退回修改其他收入
    public function actionTuihuiOther(){
        $request=\Yii::$app->request;
        if (!empty($request->post())){
            $recordId = $request->post("recordId");
        }
        $record = OtherMoneyRecord::findOne(['id'=>$recordId]);
        $record->check_status = 1;
        $flag = $record->save();
        if($flag){
            echo 1;exit;
        }else{
            echo -1;exit;
        }
    }
    //根据机构获取课程
    public function actionGetCourse(){
        $request = \Yii::$app->request;
        if (!empty($request->post())){
            $org_id = $request->post("org_id");
        }
        $course_arr = CourseInfo::find()->select(['id','name'])->where(['org_id'=>$org_id])->asArray()->all();
        if(!empty($course_arr)){
            $data['code'] = 1;
            $data['desc'] = $course_arr;
            echo json_encode($data);
            exit;
        }else{
            $data['code'] = -1;
            $data['desc'] = '';
            echo json_encode($data);
            exit;
        }
    }

    //退回修改申报记录
    public function actionTuihui(){
        $request=\Yii::$app->request;
        if (!empty($request->post())){
            $recordId = $request->post("recordId");
        }
        $record = ChildClassRecord::findOne(['id'=>$recordId]);
        $record->check_status = -1;
        $flag = $record->save();
        if($flag){
            echo 1;exit;
        }else{
            echo -1;exit;
        }
    }

    //取消分成
    public function actionCancelFencheng(){
        $request=\Yii::$app->request;
        if (!empty($request->post())){
            $recordId = $request->post("recordId");
        }
        $record = ChildClassRecord::findOne(['id'=>$recordId]);
        $record->check_status = 0;
        $flag = $record->save();
        if($flag){
            $data['code'] = 1;
            $data['desc'] = $recordId;
            echo json_encode($data);exit;
        }else{
            $data['code'] = -1;
            $data['desc'] = '取消失败';
            echo json_encode($data);exit;
        }
    }

    //审核退课
    public function actionTuike(){
        $request=\Yii::$app->request;
        if (!empty($request->post())){
            $recordId = $request->post("recordId");
            $type = $request->post("type");//0不允许退课  1允许退课
        }
        $record = ChildClassRecord::findOne(['id'=>$recordId]);
        $record->is_quit = $type;
        $flag = $record->save();
        if($flag){
            $data['code'] = 1;
            $data['desc'] = $recordId;
            echo json_encode($data);exit;
        }else{
            $data['code'] = -1;
            $data['desc'] = '允许退课';
            echo json_encode($data);exit;
        }
    }

    //门店现金总额剩余
    public function actionCashList(){
        $shops = ShopInfo::find()->select(['id','name'])->asArray()->all();
        $total_cash_money = [];
        foreach($shops as $k=>$v){
            $total_cash_money[$k] = $v;
            $course_cash_money = (new Query())->select(['sum(total_money) as scr_money'])
                ->from("shop_cash_record")->where("type=1 and shop_id=:shop_id")->params([':shop_id'=>$v['id']])->one();
            $other_cash_money = (new Query())->select(['sum(total_money) as scr_money'])
                ->from("shop_cash_record")->where("type=2 and shop_id=:shop_id")->params([':shop_id'=>$v['id']])->one();
            $total_cash_money[$k]['course_cash_money'] = doubleval($course_cash_money['scr_money']);
            $total_cash_money[$k]['other_cash_money'] = doubleval($other_cash_money['scr_money']);
            $total_cash_money[$k]['total_cash_money'] = $course_cash_money['scr_money']+$other_cash_money['scr_money'];
        }
//        var_dump($total_cash_money);die;

        return $this->render('cash-list',[
            'list'=>$total_cash_money
        ]);
    }
    //财务收款操作明细
    public function actionDetailList(){
        if(!empty($_GET["shop_id"])){
            $shop_id = $_GET["shop_id"];
            $list = (new Query())->select(['scr.*','si.name'])->from("shop_cash_record as scr")
                ->leftJoin("shop_info as si","si.id=scr.shop_id")
                ->where("scr.shop_id=:shop_id and scr.is_finance=2")
                ->params([':shop_id'=>$shop_id])->orderBy("scr.id desc")->all();
        }
//        var_dump($list);
        return $this->render('detail-list',[
            'list'=>$list
        ]);
    }

    //财务收款
    public function actionAddReceipt(){
        $model = new ShopCashRecord();
        $model2 = new ShopCashRecord();
        $request = Yii::$app->request;
        $shop_id = $request->get("shop_id");
        if(!empty($shop_id) && empty($request->post())){
                return $this->render('add-receipt',[
                    'model'=>$model,
                    'shop_id'=>$shop_id
                ]);
        }else{
            if(!empty($request->post())){
                $shop_id = $request->post("shop_id");
                $cash = $_POST['ShopCashRecord']["total_money"];
                $add_time = $request->post("add_time");
                $user_id = Yii::$app->user->identity->id;
//                var_dump($cash);die;
                $course_cash = "-".$cash[1];
                $other_cash = "-".$cash[2];
//                var_dump($shop_id);
//                var_dump($add_time);
//                var_dump($user_id);
//                var_dump($course_cash);
//                var_dump($other_cash);die;
                if($course_cash && $other_cash){
                    $model->shop_id = $shop_id;
                    $model->type = 1;
                    $model->add_time = $add_time;
                    $model->total_money = $course_cash;
                    $model->user_id = $user_id;
                    $model->is_finance = 2;
                    $model->save();

                    $model2->shop_id = $shop_id;
                    $model2->type = 2;
                    $model2->add_time = $add_time;
                    $model2->total_money = $other_cash;
                    $model2->user_id = $user_id;
                    $model2->is_finance = 2;
                    $model2->save();
                }else{
                    $model->shop_id = $shop_id;
                    $model->add_time = $add_time;
                    $model->user_id = $user_id;
                    $model->is_finance = 2;
                    if(!empty($course_cash)){
                        $model->type = 1;
                        $model->total_money = $course_cash;
                    }elseif(!empty($other_cash)){
                        $model->type = 2;
                        $model->total_money = $other_cash;
                    }
                    $model->save();
                }
                return $this->redirect("/admin/receipt/cash-list");
            }
        }
    }
}