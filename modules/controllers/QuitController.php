<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016-04-20
 * Time: 14:53
 */

namespace app\modules\controllers;


use app\models\ChildClass;
use app\models\ChildClassRecord;
use app\models\ChildInfo;
use app\models\CourseClass;
use app\models\CourseInfo;
use app\models\QuitForm;

use yii\base\Exception;
use yii\web\Controller;
use yii\filters\AccessControl;
use Yii;
class QuitController extends Controller
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
                        'actions' => ['quit','send-quit'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * 退课界面
     */
    public function actionQuit(){
        $recordId = $_REQUEST["recordId"];
        $recordData = ChildClassRecord::findOne($recordId);
        $model = new QuitForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $delDate = $_REQUEST['payTime'];
            if(!empty($_POST["courseId"])){
                $courseIds = $_POST["courseId"];
                $transaction = ChildClass::getDb()->beginTransaction();
                $arrlength = count($courseIds);
                try{
                    $payName = '';
                    for($i = 0 ; $i < $arrlength ; $i++ ){
                        $courseId = $courseIds[$i];
                        $where = [];
                        $where["record_id"] = $recordId;
                        $where["course_id"] = $courseId;
                        $chClass = ChildClass::find()->where($where)->all();
                        $chClass[0]->is_delete = 0;
                        if(!$chClass[0]->save()){
                            throw new \Exception();
                        }
                        $cc = CourseInfo::findOne($courseId);
                        $childId = $chClass[0]["child_id"];
                        $payName .= '《'. $cc["name"] . "》";
                    }
                    $child = ChildInfo::findOne($childId);
                    mt_srand((double)microtime() * 10000);//optional for php 4.2.0 and up.
                    $orderNo = strtoupper(md5(uniqid(rand(), true)));
                    $uid = Yii::$app->user->identity->id;

                    $recordData->is_quit = 3;
                    if(!$recordData->save()){
                        throw new \Exception();
                    }
                    $data = new ChildClassRecord();
                    $data->add_time = $delDate;
                    $data->update_time = $delDate;
                    $data->add_type = 0;
                    $data->is_delete = 0;
                    $data->pay_no = $orderNo;
                    $data->pay_type =  $model->payType;
                    $data->pay_name = '【' . $child->nick_name . '】退课'.$payName;
                    $data->user_id = $recordData->user_id;
                    $data->yii_user_id = $uid;
                    $data->check_status = 0;
                    $data->total_money = -$model->payMoney;
                    $data->notes = $model->notes ;
                    $data->shop_id = $recordData->shop_id;
                    $data->receipt_id = $model->receiptId;
                    $data->is_quit = 3;
                    if(!$data->save()){
                        throw new \Exception();
                    }
                    $delRecord = ChildClassRecord::find()->max('id');
                    $delClass = new ChildClass();
                    $delClass->add_date = $delDate;
                    $delClass->is_delete = 0;
                    $delClass->record_id = $delRecord;
                    $delClass->course_id = $courseIds[0];
                    $delClass->child_id = $childId;
                    $delClass->class_id = 0;
                    $delClass->price_id = 0;
                    $delClass->pay_type = 1;
                    $delClass->price = 0;
                    $delClass->couser_order_id = 0;
                    $delClass->course_num = 0;
                    if(!$delClass->save()){
                        throw new \Exception();
                    }
                    $transaction->commit();
                }catch (\Exception $e){
                    $transaction->rollBack();
                    return $this->goBack();
                }
            }
            return $this->redirect("/admin/child/record");

        }else{
            $model->recordId = $recordId;
            $model->payType = 1;
            $model->payMoney = $recordData->total_money;
            $model->receiptId = 0;
            $where = [];
            $where["child_class.is_delete"] = 1;
            $where["child_class.record_id"] = $recordId;
            $childClass = ChildClass::find()->joinWith("course")->where($where)->all();
            $classArray = [];
            foreach($childClass as $class){
                array_push($classArray,['id'=>$class["course"]["id"],'title'=>$class["course"]["name"]]);
            }
            return $this->render('add', [
                'model' => $model,
                'classes' => $classArray
            ]);
        }
    }

    /**
     * 申请退课
     */
    public function actionSendQuit(){
        $recordId = $_REQUEST["recordId"];
        $recordData = ChildClassRecord::findOne($recordId);
        $recordData->is_quit = 2;
        $recordData->save();
        return $this->redirect("/admin/child/record");
    }

}