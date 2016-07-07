<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016-02-22
 * Time: 11:45
 */

namespace app\modules\controllers;

use app\models\ChildClass;
use app\models\ChildClassRecord;
use app\models\CourseClass;
use app\models\CourseDiscountOption;
use app\models\CourseInfo;
use app\models\CustomerVisit;
use app\models\CustomerVisitForm;
use app\models\ChildClassForm;
use app\models\ChildForm;
use app\models\ChildInfo;
use app\models\CourseOrder;
use app\models\CoursePlaceClass;
use app\models\CoursePrice;
use app\models\CourseRoom;
use app\models\CourseView;
use app\models\CourseViewForm;
use app\models\CustomerRecord;
use app\models\CustomerRecordForm;

use app\models\ShopInfo;
use app\models\UserChildInfo;
use app\models\UserInfo;
use app\models\WeixinMoneyRecord;
use app\models\WeixinMoneyRecordForm;
use app\modules\push\PushBaseController;
use Yii;
use yii\base\Exception;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\web\Controller;

class ChildController extends PushBaseController
{
    public $enableCsrfValidation = false;//yii默认表单csrf验证，如果post不带改参数会报错！
    public $layout = 'layout';


    /**
     * @用户授权规则
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'captcha'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['list', 'card', 'add', 'save', 'class', 'saveclass', 'editcard', 'savecard', 'child',
                            'report', 'money', 'course', 'savecourse', 'view', 'del', 'visitorlist', 'visitoredit',
                            'savevisitor',"delvisitor",'returnlist','returnedit','savereturn','delreturn','delclass','record','ajax-course','check-money-type'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionList()
    {
        $sql = [];
        $key = "";
        $where = [];
        $shopId = Yii::$app->user->identity->shopId;
        $params =[];
        if (!empty($_REQUEST["name"])) {
            $sql['child_info.nick_name'] = ['like', $_REQUEST["name"]];
        }
        if (!empty($_REQUEST["shop"])) {
            $sql['child_info.shop_id'] = $_REQUEST["shop"];
            $key = $_REQUEST["shop"];
            $params["shop"] = $key;
        }
        if (!empty($shopId)) {
            $sql['child_info.shop_id'] = $shopId;
            $where['id'] = $shopId;
        }

        $month = [];
        $monthParams='';
        if(!empty($_REQUEST["month"])){
            $monthParams = $_REQUEST["month"];
            $month = ['=','MONTH(birthday)',$monthParams];
            $params["month"] = $monthParams;
        }
        /*$startDate = [];
        $startTime = '';
        $endDate=[];
        $endTime = '';
        if(!empty($_REQUEST["startDate"])){
            $startDate = ['>=','birthday',$_REQUEST["startDate"]];
            $startTime = $_REQUEST["startDate"];
            $params["startDate"] = $startTime;
        }
        if(!empty($_REQUEST["endDate"])){
            $endDate = ['<=','birthday',$_REQUEST["endDate"]];
            $endTime = $_REQUEST["endDate"];
            $params["endDate"] = $endTime;
        }*/
        $sql["child_info.delete"] = 1;
        $list = ChildInfo::find()->where($sql)->andWhere($month)->orderBy("child_info.id desc ")/*->joinWith('user')*/;
        $pages = new Pagination(['totalCount' => $list->count(), 'pageSize' => '8']);
        $data = $list->offset($pages->offset)->limit($pages->limit)->all();

        /*if (!empty($_REQUEST["shop"])) {
            $pages->params = ['shop' => $key];
        } else {
            $pages->params = [];
        }*/
        $pages->params = $params;
        $shops = ShopInfo::find()->where($where)->all();

        $error = "";
        if(!empty($_REQUEST["error"])){
            $error = "1";
        }
        return $this->render('list', [
            'list' => $data,
            'pages' => $pages,
            'shops' => $shops,
            'key' => $key,
            'monthP'=>$monthParams,
            'error'=>$error

        ]);
    }

    public function actionChild()
    {
        $childId = $_REQUEST["id"];

        $connection = Yii::$app->db;
        $child_sql = "select * from child_info a where a.id = :childId";
        $childCommand = $connection->createCommand($child_sql);
        $childCommand->bindValue(':childId', $childId);
        $childInfo = $childCommand->queryAll();

        $class_sql = "select b.title , c.course_nums,a.add_date,c.discount_price,b.material_price,a.yy,a.price,a.pay_type,a.class_ap,a.class_gw,a.notes";
        $class_sql .= " from child_class a left join course_class b on a.class_id = b.id left join course_price c on a.price_id=c.id where a.child_id = :childId";
        $classCommand = $connection->createCommand($class_sql);
        $classCommand->bindValue(':childId', $childId);
        $classInfo = $classCommand->queryAll();

        $user_sql = "select a.nickname ,a.phone , a.location from `user`  a join user_child_info b on a.id = b.user_id where b.child_id = :childId";
        $userCommand = $connection->createCommand($user_sql);
        $userCommand->bindValue(':childId', $childId);
        $userInfo = $userCommand->queryAll();

        return $this->render('childinfo', [
            'childInfo' => $childInfo[0],
            'classInfo' => $classInfo,
            'userInfo' => $userInfo
        ]);
    }


    public function actionReport()
    {
        $year = date('Y', time());
        if (!empty($_REQUEST["year"])) {
            $year = $_REQUEST["year"];
        }
        $connection = Yii::$app->db;
        $sql = "select shop.id ,shop.`name`,";
        for($i = 1 ;$i<=12; $i++){
            $sql .= "(select count(c.id) from child_info  c where c.shop_id = shop.id and month(c.reg_date) = $i and year(c.reg_date) = :year) as num_".$i.",";
            $sql .= "(select IFNULL(sum(a.total_money),'0') from child_class_record a where a.shop_id = shop.id and month(a.add_time) = $i and year(a.add_time) = :year) as money_".$i;
            if($i<12)$sql.= ",";
        }
        $sql .= " from shop_info as shop where shop.dept_id > 0  group by shop.id,shop.`name` order by shop.id ";
        $childCommand = $connection->createCommand($sql);
        $childCommand->bindValue(':year', $year);
        $data = $childCommand->queryAll();
        return $this->render('report', [
            'data' => $data,
            'year' => $year
        ]);
    }

    public function actionAdd()
    {
        $model = new ChildForm();
        if (!empty($_REQUEST["id"])) {
            $model->id = $_REQUEST["id"];
            $child = ChildInfo::findOne($_REQUEST["id"]);

            $model->birthday = $child->birthday;
            $model->childName = $child->nick_name;
            $model->childSex = $child->sex;
            $model->shopId = $child->shop_id;
            $model->address = $child->address;
            $model->school = $child->school;
            $model->class = $child->class_cc;

        }else {
            $model->childSex = 0 ;
        }

        $where = [];
        $shopId = Yii::$app->user->identity->shopId;
        if (!empty($shopId)) {
            $where['id'] = $shopId;
        }
        $shops = ShopInfo::find()->where($where)->all();
        $shopsList = [];
        foreach ($shops as $shop) {
            $shopsList[$shop["id"]] = $shop["name"];
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            return $this->goBack();
        } else {
            return $this->render('add', [
                'model' => $model,
                'shops' => $shopsList
            ]);
        }
    }

    public function actionSave()
    {
        $params = $_REQUEST["ChildForm"];

        $shopInfo = ShopInfo::findOne($params["shopId"]);
        if(empty($shopInfo)){
            return $this->redirect("/admin/child/list");
        }

        if (!empty($_REQUEST["id"])) {
            $child = ChildInfo::findOne($_REQUEST["id"]);
            $child->sex = $params['childSex'];
            $child->nick_name = $params['childName'];
            $child->birthday = $_REQUEST['birthday'];
            $child->shop_id = $params["shopId"];
            $child->school = $params["school"];
            $child->address = $params["address"];
            $child->class_cc = $params["class"];
            $child->save();
        } else {

            $shopId =  $params["shopId"];
            $nickName = $params['childName'];
            $where["shop_id"] =  $shopId;
            $where["nick_name"] =  $nickName;
            $data = ChildInfo::findOne($where);
            if(!empty($data)) {
                return $this->redirect("/admin/child/list?error=1");
            }

            $phone = "";
            if(!empty( $params["phone"])){
                $phone = $params["phone"];
            }else if(!empty( $params["secondPhone"])){
                $phone = $params["secondPhone"];
            }
            $transaction = ChildInfo::getDb()->beginTransaction();
            try{
                $child = new ChildInfo();
                $child->nick_name = $params['childName'];
                $child->birthday = $_REQUEST['birthday'];
                $child->sex = $params['childSex'];
                $child->phone = $phone;
                $child->card_id = 0;
                $child->delete = 1;
                $child->shop_id = $params["shopId"];
                $child->card_code = 0;
                $child->reg_date = date('Y-m-d H:i:s', time());
                $child->school = $params["school"];
                $child->address = $params["address"];
                $child->class_cc = $params["class"];
                if(!$child->save()){
                    throw new \Exception();
                }
                $childId = ChildInfo::find()->max("id");

                //保存用户
                if(!empty($params["phone"])){
                    $uuser = UserInfo::find()->where(["phone"=>$params["phone"]])->one();
                    if(empty($uuser)){
                        $user = new UserInfo();
                        $user->phone = $params["phone"];
                        $user->nickname = $params["userName"];
                        $user->location = $params["work"];
                        $user->status = 10;
                        $user->sex = 1;

                        if(!$user->save()){
                            throw new \Exception();
                        }
                        $userId = UserInfo::find()->max("id");
                    }else {
                        $userId = $uuser["id"];
                    }
                    $userChild = new UserChildInfo();
                    $userChild->user_id = $userId;
                    $userChild->child_id = $childId;
                    $userChild->relation = 1;
                    $userChild->isNurse = 0;
                    if(!$userChild->save()){
                        throw new \Exception();
                    }
                }
                if(!empty($params["secondPhone"])){
                    $uuser = UserInfo::find()->where(["phone"=>$params["secondPhone"]])->one();
                    if(empty($uuser)){
                        $user = new UserInfo();
                        $user->phone = $params["secondPhone"];
                        $user->nickname = $params["secondName"];
                        $user->location = $params["secondWork"];
                        $user->status = 10;
                        $user->sex = 1;

                        if(!$user->save()){
                            throw new \Exception();
                        }
                        $userId = UserInfo::find()->max("id");
                    }else {
                        $userId = $uuser["id"];
                    }

                    $userChild = new UserChildInfo();
                    $userChild->user_id = $userId;
                    $userChild->child_id = $childId;
                    $userChild->relation = 2;
                    $userChild->isNurse = 0;
                    if( !$userChild->save()){
                        throw new \Exception();
                    }
                }
                $transaction->commit();
            }catch (\Exception $e){
                $transaction->rollBack();
            }
        }
        return $this->redirect("/admin/child/list");
    }


    /**申请一卡通
     */
    public function actionCard()
    {
        $childId = $_REQUEST["id"];
        //$phone = $_REQUEST["phone"];
        $childInfo = ChildInfo::findOne($childId);
        try {

            $shop = ShopInfo::findOne($childInfo["shop_id"]);
            $deptId = $shop["dept_id"];
            $connection = Yii::$app->ykt;
            $nick_name = $childInfo["nick_name"];
            $card_code = time();

            $preson_id_sql = "select max(Person_ID) + 1 as id from STCard_Enp.dbo.ST_Person";
            $command_id = $connection->createCommand($preson_id_sql);
            $result = $command_id->queryAll();
            $preson_id = $result[0]["id"];

            $create_preson = "insert into STCard_Enp.dbo.ST_Person (Person_ID,Dept_ID,Card_No,Person_No,Person_Name,Is_Marry,Is_Del) VALUES ('$preson_id',$deptId,'','$card_code','$nick_name','0','0') ";
            $connection->createCommand($create_preson)->execute();

            $update_id = "update STCard_Enp.dbo.ST_Identity set  Current_ID = $preson_id  where Table_Name = 'ST_Person' ";
            $connection->createCommand($update_id)->execute();

            $childInfo["card_id"] = $preson_id;
            $childInfo["card_code"] = $card_code;
            $childInfo->save();
        } catch (\Exception $e) {

            throw $e;
        }
        return $this->redirect("/admin/child/list");
    }

    /**
     * 选择课程
     */
    public function actionClass()
    {
        $childId = $_REQUEST["id"];
        $userId = $_REQUEST["userId"];
        $childInfo = ChildInfo::findOne($childId);
        $shopId = $childInfo["shop_id"];
        //课程列表
        $array = [];
        $where['org_shop_info.shop_id'] = $shopId;
        $where['course_info.status'] = 1;
        $courseList = CourseInfo::find()->joinWith('course')->where($where)->all();
        foreach($courseList as $course){
            array_push($array,['id'=>$course["id"],'name'=>$course["name"]]);
        }
        $jsonData = json_encode($array, JSON_UNESCAPED_UNICODE);
        //折扣优惠
        $disOption = CourseDiscountOption::find()->all();
        $model = new ChildClassForm();
        $model->addDate = date('Y-m-d H:i:s', time());
        $model->payType = 1;
        $model->moneyType = 1;//1表示全款 2 表示定金 3 表示余款
        $model->classList = [];
        return $this->render('class', [
            'courseList' => $jsonData,
            'childId' => $childId,
            'model' => $model,
            'userId' => $userId,
            'disOption'=>$disOption
        ]);
    }

    /**
     * 课程价格
     * @return string
     */
    public function actionAjaxCourse(){
        $courseId = $_REQUEST["courseId"];
        $shopId = Yii::$app->user->identity->shopId;
        if(empty($shopId)) $shopId = 1;
        $sql = 'select a.course_nums as course_num , a.org_price as price,1 as type,0 as dis from course_manager_price a where a.course_id = :course';//1原价 2 折扣价
        $sql .= ' union all ';
        $sql .= 'select a.course_nums as course_num , a.discount_price as price ,2 as type,0 as dis from course_manager_price a where a.course_id = :course';
        $sql .= ' union all ';
        $sql .= 'select c.course_num,c.price_one as price ,1 as type,1 as dis from course_dis_act b join course_dis_cr c on b.id = c.dis_act_id ';
        $sql .= ' where b.start_date <= SYSDATE() <= b.end_date and b.shop_id = :shopId and b.`status` = 1 and c.course_id = :course';
        $sql .= ' union all ';
        $sql .= 'select c.course_num,c.price_two as price ,2 as type,1 as dis from course_dis_act b join course_dis_cr c on b.id = c.dis_act_id ';
        $sql .= ' where b.start_date <= SYSDATE() <= b.end_date and b.shop_id = :shopId and b.`status` = 1 and c.course_id = :course';
        $connection  = Yii::$app->db;
        $classCommand = $connection->createCommand($sql);
        $classCommand->bindValue(':course', $courseId);
        $classCommand->bindValue(':shopId', $shopId);
        $comment = $classCommand->queryAll();
        $array = [];
        foreach($comment as $data){
            $type = '【原价】';
            if($data['type'] == 2){
                $type = '【折扣价】';
            }
            $dis = '';
            if($data['dis'] == 1){
                $dis = '【课程优惠活动】';
            }
            array_push($array,['id'=>$data['course_num'] .'|' .$data['price']  ,'name'=>$data['course_num'] . '【课时】/' . '￥'.$data['price'] .$type.$dis ]);
        }
        $jsonData = json_encode($array, JSON_UNESCAPED_UNICODE);
        return $jsonData;
    }

    /**
     * 保存课程
     */
    public function actionSaveclass()
    {
        $childId = $_REQUEST["id"];
        $userId = $_REQUEST["userId"];
        $params = $_REQUEST["ChildClassForm"];
        try {

            $courseList = $_POST["course"];
            $numList = $_POST["course_num"];
            if (!empty($courseList)) {
                $sysDate = date('Y-m-d H:i:s', time());
                $addDate = $_REQUEST['add_date'];
                $transaction = ChildInfo::getDb()->beginTransaction();
                try{
                    $childInfo = ChildInfo::findOne($childId);
                    mt_srand((double)microtime() * 10000);//optional for php 4.2.0 and up.
                    $orderNo = strtoupper(md5(uniqid(rand(), true)));
                    $notes = "【" . $childInfo->nick_name . "】申报课程";
                    $yiiId = Yii::$app->user->identity->id;
                    $classRecord = new ChildClassRecord();
                    $classRecord->user_id = $userId;
                    $classRecord->add_time = $addDate;
                    $classRecord->add_type = 0;
                    $classRecord->pay_name =$notes;
                    $classRecord->pay_no = $orderNo;
                    $classRecord->total_money =  $_REQUEST["price"];
                    $classRecord->notes = $notes;
                    $classRecord->pay_type = $params["payType"];
                    $classRecord->is_delete = 1 ;
                    $classRecord->yii_user_id = $yiiId ;//需要修改
                    $classRecord->check_status = 0;//0表示待审核
                    $classRecord->shop_id = $childInfo->shop_id;
                    $classRecord->receipt_id = $params['receiptId'];
                    $classRecord->money_type = $params['moneyType'];
                    $classRecord->update_time = $addDate;
                    if(!$classRecord->save()){
                        throw new \Exception();
                    }
                    $recordId = ChildClassRecord::find()->max("id");
                    $arrlength = count($courseList);
                    for ($i = 0; $i < $arrlength; $i++) {
                        $num = explode('|', $numList[$i]);
                        $course = $courseList[$i];
                        $childClass = new ChildClass();
                        $childClass->child_id = $childId;
                        $childClass->class_id = 0;
                        $childClass->price_id =0;
                        $childClass->add_date = $addDate;
                        $childClass->course_id =$course;
                        $childClass->pay_type = $params["payType"];
                        $childClass->price = $_REQUEST["price"];
                        $childClass->yy = '';
                        $childClass->class_ap = '';
                        $childClass->class_gw = '';
                        $childClass->notes = $params["notes"];
                        $childClass->couser_order_id = 0;
                        $childClass->record_id = $recordId;
                        $childClass->course_num = $num[0];
                        $childClass->is_delete = 1;
                        if(!$childClass->save()){
                            throw new \Exception();
                        }
                    }
                    $transaction->commit();
                } catch(\Exception $e){
                    $transaction->rollBack();
                }
            }
        } catch (\Exception $e) {
        }
        return $this->redirect("/admin/child/list");
    }

    /**
     * 提交分成
     */
    public function actionCheckMoneyType(){
        $recordId = $_REQUEST['recordId'];
        $re = ChildClassRecord::findOne($recordId);
        $re->check_status = 1;
        $re->update_time = date('Y-m-d H:i:s', time());
        $re->save();
        return $this->redirect('record');
    }

    /**
     * 删掉申报课程
     */
    public function actionDelclass(){
        $id = $_REQUEST["id"];
        $transaction = ChildClass::getDb()->beginTransaction();
        try{
            ChildClass::deleteAll(['=', 'record_id', $id]);
            ChildClassRecord::deleteAll(['=','id',$id]);
            $transaction->commit();
        }catch (\Exception $e){
            $transaction->rollBack();
        }
        return $this->redirect("/admin/child/record");
    }

    /**
     * 课程申报记录
     */
    public function actionRecord(){
        $shopId = Yii::$app->user->identity->shopId;
        $where = [];
        $searchWhere = [];
        $key = '';
        if(!empty($shopId)){
            $where['child_class_record.shop_id'] = $shopId;
            $searchWhere["id"] = $shopId;
            $key = $shopId;
        }
        if (!empty($_REQUEST["shop"])) {
            $where['child_class_record.shop_id'] = $_REQUEST["shop"];
            $key = $_REQUEST["shop"];
        }

        $like = [];
        $params = [];
        if(!empty($_REQUEST["name"])){
            $like = ['like','child_class_record.pay_name',$_REQUEST["name"]];
        }

        $startTime = [];
        $startTimeParams = '';
        if(!empty($_REQUEST["start_time"])){
            $startTime = ['>','child_class_record.add_time',$_REQUEST["start_time"]];
            $params["start_time"] = $_REQUEST["start_time"];
            $startTimeParams = $_REQUEST["start_time"];
        }

        $endTime = [];
        $endTimeParams = '';
        if(!empty($_REQUEST["end_time"])){
            $endTime = ['<','child_class_record.add_time',$_REQUEST["end_time"].' 23:59:59'];
            $params["end_time"] = $_REQUEST["end_time"];
            $endTimeParams = $_REQUEST["end_time"];
        }

        //课程查询条件
        $courseIdParam = '';
        $course = [];
        if(!empty($_REQUEST["courseId"])){
            $courseIdParam = $_REQUEST["courseId"];
            $params['courseId'] = $courseIdParam;
            $courseS = [];
            $childClass =  ChildClass::find()->where(['=','course_id',$_REQUEST["courseId"]])->all();
            foreach($childClass as $cou){
                array_push($courseS,$cou["record_id"]);
            }
            $course = ['in','child_class_record.id',$courseS];
        }
        $list = ChildClassRecord::find()->where($where)->andWhere($like)->andWhere($startTime)->andWhere($endTime)->andWhere($course)
            ->joinWith("shop")->with("yiiuser")->with("course")->with('record') ->orderBy("child_class_record.add_time desc ");
        $pages = new Pagination(['totalCount' => $list->count(), 'pageSize' => '8']);
        $data = $list->offset($pages->offset)->limit($pages->limit)->all();

        if (!empty($_REQUEST["shop"])) {
            $params["shop"] = $key;
        }
        $pages->params = $params;
        $shops = ShopInfo::find()->where($searchWhere)->all();
        $courseWhere = [];
        $courseWhere["course_info.status"] = 1;
        if(!empty($key))
            $courseWhere["org_shop_info.shop_id"] = $key;
        $courseList = CourseInfo::find()->joinWith('course')->where($courseWhere)->all();

        //合计
        $connection = Yii::$app->db;
        $sql = "SELECT sum(total_money) as total FROM child_class_record where shop_id = :shopId ";
        if(!empty($_REQUEST["start_time"])){
            $sql .= ' and add_time > :startTime';
        }
        if(!empty($_REQUEST["end_time"])){
            $sql .= ' and add_time < :endTime';
        }
        $command = $connection->createCommand($sql);
        $command->bindValue(':shopId', $key);
        if(!empty($_REQUEST["start_time"])){
            $command->bindValue(':startTime', $_REQUEST["start_time"]);
        }
        if(!empty($_REQUEST["end_time"])){
            $command->bindValue(':endTime', $_REQUEST["end_time"].' 23:59:59');
        }
        $total = $command->queryAll();

        return $this->render('record', [
            'list' => $data,
            'pages' => $pages,
            'shops' => $shops,
            'key' => $key,
            'startTime'=>$startTimeParams,
            'endTime'=>$endTimeParams,
            'courseId'=>$courseIdParam,
            'courseList'=>$courseList,
            'total'=>$total
        ]);

    }

    /**
     * @return string
     */
    public function actionEditcard()
    {
        $childId = $_REQUEST["id"];
        $userId = $_REQUEST["userId"];
        $mrecord = new WeixinMoneyRecordForm();
        return $this->render('card', [
            'model' => $mrecord,
            'userId' => $userId,
            'childId' => $childId
        ]);
    }

    /**
     * 保存充值记录
     */
    public function actionSavecard()
    {
        $childId = $_REQUEST["childId"];
        $userId = $_REQUEST["userId"];
        $card = $_REQUEST["WeixinMoneyRecordForm"];
        $data = new WeixinMoneyRecord();
        $data->user_id = $userId;
        $data->child_id = $childId;
        $data->money = $card["money"];
        $data->should_money = $card["shouldMoney"];
        $data->type = $card["type"];
        $data->add_time = date('Y-m-d H:i:s', time());
        mt_srand((double)microtime() * 10000);//optional for php 4.2.0 and up.
        $orderNo = strtoupper(md5(uniqid(rand(), true)));
        $data->order_no = $orderNo;
        $data->status = 1;
        $data->notes = $card["notes"];
        $data->save();
        return $this->redirect("/admin/child/list");
    }

    /**
     * 查看充值记录
     */
    public function actionMoney()
    {
        $childId = $_REQUEST["id"];
        $where = [];
        $where['child_id'] = $childId;
        $list = WeixinMoneyRecord::find()->where($where)->orderBy(" id desc ");
        $pages = new Pagination(['totalCount' => $list->count(), 'pageSize' => '5']);
        $data = $list->offset($pages->offset)->limit($pages->limit)->all();
        return $this->render('money', [
            'list' => $data,
            'pages' => $pages
        ]);
    }

    public function actionCourse()
    {
        $model = new CourseViewForm();
        if (!empty($_REQUEST["id"])) {
            $id = $_REQUEST["id"];
            $model->id = $id;
            $view = CourseView::findOne($id);
            $model->shopId = $view->shop_id;
            $model->courseName = $view->course_name;
            $model->courseRoom = $view->course_room;
            $model->courseClass = $view->course_class;
            $model->startTime = $view->start_time;
            $model->endTime = $view->end_time;
            $model->startM = $view->start_m;
            $model->endM = $view->end_m;
            $model->courseDate = $view->course_date;
            $model->courseTeacher = $view->course_teacher;
            $model->notes = $view->notes;
        }
        $where = [];
        $shopId = Yii::$app->user->identity->shopId;
        if (empty($shopId)) {
            $shopId = 1;
        }
        $where['id'] = $shopId;
        //选择店铺
        $shops = ShopInfo::find()->where($where)->all();
        $shopsList = [];
        foreach ($shops as $shop) {
            $shopsList[$shop["id"]] = $shop["name"];
        }
        //选择教室
        $roomList = [];
        $rooms = CourseRoom::find()->where(['shop_id' => $shopId])->all();
        foreach ($rooms as $room) {
            $roomList[$room["id"]] = $room["name"];
        }
        //选择上课班级
        $classList = [];
        $classs = CoursePlaceClass::find()->where(['shop_id' => $shopId])->all();
        foreach ($classs as $class) {
            $classList[$class["id"]] = $class["name"];
        }
        //选择上课老师
        $teachList = [];
        $connection = Yii::$app->db;
        $sql = "select a.id,a.`name` from teacher_info a join org_shop_info b on a.org_id = b.org_id where b.shop_id = :shopId";
        $command = $connection->createCommand($sql);
        $command->bindValue(':shopId', $shopId);
        $teachs = $command->queryAll();
        foreach ($teachs as $teach) {
            $teachList[$teach["id"]] = $teach["name"];
        }

        /*  $houseList = [];
          $mList = [];
          for($i = 1;$i<24;$i++){
              $houseList[$i] = $i;
          }

          for($h = 1;$h<60;$h++){
              $mList[$h] = $h;
          }*/

        return $this->render('course', [
            'model' => $model,
            'shops' => $shopsList,
            'rooms' => $roomList,
            'classs' => $classList,
            'teachers' => $teachList
        ]);
    }

    public function actionSavecourse()
    {
        $id = $_REQUEST["id"];
        $model = $_REQUEST["CourseViewForm"];
        if (!empty($id)) {
            $view = CourseView::findOne($id);
        } else {
            $view = new CourseView();
        }
        $view->shop_id = $model["shopId"];
        $view->course_name = $model["courseName"];
        $view->course_room = $model["courseRoom"];
        $view->course_class = $model["courseClass"];
        $view->course_date = $_REQUEST["courseDate"];
        $view->start_time = $_REQUEST["startTime"];
        $view->end_time = $_REQUEST["endTime"];
        $view->start_m = $_REQUEST["startM"];
        $view->end_m = $_REQUEST["endM"];
        $view->course_teacher = $model["courseTeacher"];
        $view->notes = $model["notes"];
        $liveStart =$_REQUEST["courseDate"] . ' ' . $_REQUEST["startTime"] . ':' .$_REQUEST["startM"] . ':00' ;
        $liveEnd  =$_REQUEST["courseDate"] . ' ' . $_REQUEST["endTime"] . ':' .  $_REQUEST["endM"] . ':59';
        $view->live_start_date = $liveStart;
        $view->live_end_date = $liveEnd;
        $view->createtime = date('Y-m-d H:i:s', time());
        if($view->save()){
            $this->add_course_push($view);
        }

        return $this->redirect("/admin/child/view");
    }

    public function actionView()
    {
        $shopId = Yii::$app->user->identity->shopId;
        $where = [];
        $sql = [];
        if (!empty($shopId)) {
            $where['id'] = $shopId;
            $sql["course_view.shop_id"] = $shopId;
        } else if (!empty($_REQUEST["shop"])) {
            $shopId = $_REQUEST["shop"];
            $where['id'] = $shopId;
            $sql["course_view.shop_id"] = $shopId;
        }
        $shops = ShopInfo::find()->where($where)->all();

        $list = CourseView::find()->where($sql)->orderBy("course_view.live_start_date desc ")
            ->joinWith('class')->joinWith('room')->joinWith('teacher')->joinWith('shop');
        $pages = new Pagination(['totalCount' => $list->count(), 'pageSize' => '8']);
        $data = $list->offset($pages->offset)->limit($pages->limit)->all();

        if (!empty($_REQUEST["shop"])) {
            $pages->params = ['shop' => $shopId];
        } else {
            $pages->params = [];
        }
        return $this->render('view', [
            'data' => $data,
            'pages' => $pages,
            'shops' => $shops,
            'key' => $shopId
        ]);
    }

    public function actionDel()
    {
        $id = $_REQUEST["id"];
        CourseView::findOne($id)->delete();
        return $this->redirect("/admin/child/view");
    }


    public function actionVisitorlist()
    {
        $shopId = Yii::$app->user->identity->shopId;
        $sql = [];
        $shops = ShopInfo::find()->all();
        $params = [];
        if (!empty($shopId)) {
            $sql["shop_id"] = $shopId;
            $shops = ShopInfo::findAll($shopId);
        }
        if (!empty($_REQUEST["shop_id"])) {
            $shopId = $_REQUEST["shop_id"];
            $sql["shop_id"] = $shopId;
        }
        if (!empty($_REQUEST["phone"])) {
            $sql["tel"] = $_REQUEST["phone"];
        }
        $likeSql = [];
        if (!empty($_REQUEST["name"])) {
            $likeSql = ['like','name',$_REQUEST["name"]];
        }
        if (!empty($_REQUEST["childClass"])) {
            $likeSql = ['like','child_class',$_REQUEST["childClass"]];
        }
        $content = '';
        if (!empty($_REQUEST["content"])) {
            $content = $_REQUEST["content"];
            $likeSql = ['like','content',$content];
            $params['content'] = $content;
        }
        $notes = '';
        if (!empty($_REQUEST["notes"])) {
            $notes = $_REQUEST["notes"];
            $likeSql = ['like','notes',$notes];
            $params['notes'] = $notes;
        }
        $visitorlist = CustomerRecord::find()->where($sql)->andWhere($likeSql) ->with("user")->orderBy("add_date desc");
        $pages = new Pagination(['totalCount' => $visitorlist->count(), 'pageSize' => '8']);
        $data = $visitorlist->offset($pages->offset)->limit($pages->limit)->all();
        $pages->params  = $params;
        return $this->render('visitorlist', ['visitorlist' => $data,
            'shops' => $shops,
            'shopId' => $shopId,
            'pages' => $pages,
            'content'=>$content,
            'notes'=>$notes]);
    }

    public function actionVisitoredit(){
        $model = new CustomerRecordForm();
        $shops = ShopInfo::find()->all();
        $shopId = Yii::$app->user->identity->shopId;
        if (!empty($shopId)) {
            $shops = ShopInfo::findAll($shopId);
        }
        $shopsList = [];
        foreach ($shops as $shop) {
            $shopsList[$shop["id"]] = $shop["name"];
        }
        if (!empty($_REQUEST["id"])) {
            $visitor = CustomerRecord::findOne($_REQUEST["id"]);
            $model->id = $_REQUEST["id"];
            $model->name = $visitor["name"];
            $model->tel = $visitor["tel"];
            $model->content = $visitor["content"];
            $model->shop_id = $visitor["shop_id"];
            //  $model->add_user = $visitor["add_user"];
            $model->notes = $visitor["notes"];
            $model->add_date = $visitor["add_date"];
            $model->sex =  $visitor["sex"];
            $model->birthDate = $visitor["birth_date"];
            $model->childClass = $visitor["child_class"];
        }
        if ($model->load(Yii::$app->request->post()) && $model->submitData()) {
            return $this->goBack();
        } else {
            return $this->render('visitoredit', ['model' => $model, 'shops' => $shopsList,  'shopId' => $shopId,'tel'=>'']);
        }
    }

    public function actionSavevisitor(){
        $params = $_REQUEST["CustomerRecordForm"];
        if (!empty($_REQUEST["id"])) {
            $visitor = CustomerRecord::findOne($_REQUEST["id"]);
        } else {
            $cr = CustomerRecord::find()->where(['=','tel',$params["tel"]])->all();
            if(!empty($cr)){
                $shops = ShopInfo::find()->all();
                $shopId = Yii::$app->user->identity->shopId;
                if (!empty($shopId)) {
                    $shops = ShopInfo::findAll($shopId);
                }
                $shopsList = [];
                foreach ($shops as $shop) {
                    $shopsList[$shop["id"]] = $shop["name"];
                }
                $model = new CustomerRecordForm();
                $model->name = $params["name"];
                $model->tel = '';
                $model->content = $params["content"];
                $model->shop_id = $params["shop_id"];
                $model->notes = $params["notes"];
                $model->add_date = $_REQUEST["add_date"];
                $model->sex =  $params["sex"];
                $model->birthDate = $_REQUEST["birth_date"];
                $model->childClass = $params["childClass"];
                return $this->render('visitoredit', ['model' => $model, 'shops' => $shopsList,  'shopId' => $shopId,'tel'=>'tel']);
            }
            $userId = Yii::$app->user->identity->id;
            $visitor = new CustomerRecord();
            $visitor->add_user = $userId;
        }
        $visitor->name = $params["name"];
        $visitor->tel = $params["tel"];
        $visitor->content = $params["content"];
        $visitor->shop_id = $params["shop_id"];

        $visitor->notes = $params["notes"];
        $visitor->child_class = $params["childClass"];
        $visitor->add_date = $_REQUEST["add_date"];
        $visitor->sex = $params["sex"];
        $visitor->birth_date = $_REQUEST["birth_date"];
        $visitor->save();
        return $this->redirect("/admin/child/visitorlist");
    }

    public function actionDelvisitor(){
        $id = $_REQUEST["id"];
        if (!empty($id)) {
            $cp = CustomerRecord::findOne($id);
            $cp->delete();
        }
        return $this->redirect("/admin/child/visitorlist");
    }

    public function actionReturnlist()
    {
        $returnlist = CustomerVisit::find()->where(["cusromer_record_id" => $_REQUEST["id"]])->joinWith("visitor")->orderBy("add_time desc");
        $pages = new Pagination(['totalCount' => $returnlist->count(), 'pageSize' => '8']);
        $data = $returnlist->offset($pages->offset)->limit($pages->limit)->all();

        return $this->render('returnlist', ['returnlist' => $data,'visitorid' => $_REQUEST["id"], 'pages' => $pages]);
    }

    public function actionReturnedit()
    {
        $model = new CustomerVisitForm();
        if (!empty($_REQUEST["cusromer_record_id"])) {
            $model->cusromer_record_id = $_REQUEST["cusromer_record_id"];
        }
        if (!empty($_REQUEST["id"])) {
            $return = CustomerVisit::findOne($_REQUEST["id"]);
            $model->id = $_REQUEST["id"];
            $model->cusromer_record_id = $return["cusromer_record_id"];
            $model->content = $return["content"];
            $model->user = $return["user"];
            $model->notes = $return["notes"];
            $model->add_time = $return["add_time"];
        }
        $visitor = CustomerRecord::findOne($model["cusromer_record_id"]);
        if ($model->load(Yii::$app->request->post()) && $model->submitData()) {
            return $this->goBack();
        } else {
            return $this->render('returnedit', ['model' => $model, 'visitor' => $visitor]);
        }
    }

    public function actionSavereturn()
    {
        $params = $_REQUEST["CustomerVisitForm"];
        if (!empty($_REQUEST["id"])) {
            $visitor = CustomerVisit::findOne($_REQUEST["id"]);
        } else {
            $visitor = new CustomerVisit();
        }
        $visitor->cusromer_record_id = $_REQUEST["cusromer_record_id"];
        $visitor->content = $params["content"];
        $visitor->user = $params["user"];
        $visitor->notes = $params["notes"];
        $visitor->add_time = $_REQUEST["add_time"];
        $visitor->save();
        return $this->redirect("/admin/child/returnlist?id=" . $_REQUEST["cusromer_record_id"]);
    }

    public function actionDelreturn()
    {
        $id = $_REQUEST["id"];
        if (!empty($id)) {
            $cp = CustomerVisit::findOne($id);
            $cp->delete();
        }
        return $this->redirect("/admin/child/returnlist?id=".$_REQUEST["visitorid"]);
    }
}