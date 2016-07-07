<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016-05-31
 * Time: 11:07
 */

namespace app\modules\controllers;


use app\models\ChildClass;
use app\models\ChildClassRecord;
use app\models\ChildClassRecordModel;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii;

class ChildClassRecordController extends Controller
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
                        'actions' => ['record','edit'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }


    /**
     * 补录定金余款记录
     */
    public function actionRecord(){
        $recordId = $_REQUEST['record'];
        $model = new ChildClassRecordModel();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $class = ChildClassRecord::findOne($recordId);
            $cc = ChildClass::find()->where(['record_id'=>$recordId])->with('course')->one();
            $transaction = ChildClassRecord::getDb()->beginTransaction();
            try{
                $data = new ChildClassRecord();
                $data->check_status = 0;
                $data->add_time = date('Y-m-d H:i:s', time());
                $data->add_type = 0;
                $data->is_delete = 1;
                $data->pay_name = $class->pay_name ;
                $data->pay_no = $class->pay_no;
                $data->pay_type = $model->payType;
                $data->yii_user_id = Yii::$app->user->identity->id;
                $data->shop_id = $class->shop_id;
                $data->user_id = $class->user_id;
                $data->total_money = $model->money;
                $data->notes = '《' . $cc['course']['name'] .'》余款' ;
                $data->receipt_id = $model->receiptId;
                $data->money_type = 3;//1表示全款 2 表示定金 3 表示余款
                $data->update_time = date('Y-m-d H:i:s', time());
                if(!$data->save()){
                    throw new \Exception();
               }
                $newRecord = ChildClassRecord::find()->max('id');
                $childClassData = new ChildClass();
                $childClassData->is_delete = 0;
                $childClassData->child_id = $cc['child_id'];
                $childClassData->course_id = $cc['course_id'];
                $childClassData->class_id = 0;
                $childClassData->price_id = 0;
                $childClassData->add_date =date('Y-m-d', time());
                $childClassData->yy = '';
                $childClassData->price = 0 ;
                $childClassData->pay_type = $cc['pay_type'];
                $childClassData->class_ap = '';
                $childClassData->class_gw = '';
                $childClassData->notes = '';
                $childClassData->couser_order_id = 0;
                $childClassData->record_id = $newRecord;
                $childClassData->course_num = $cc['course_num'];
                if(!$childClassData->save()){
                    throw new \Exception();
                }
               $transaction->commit();
            }catch (\Exception $e){
                $transaction->rollBack();
                return $this->goBack();
            }
            return $this->redirect('/admin/child/record');
        } else {
            $model->payType = 1;
            $model->recordId = $recordId;
            return $this->render('record', ['model' => $model]);
        }

    }

    public function actionEdit(){
        $recordId = $_REQUEST['record'];
        $model = new ChildClassRecordModel();
        $childClassRecord = ChildClassRecord::findOne($recordId);
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $childClassRecord->pay_type = $model->payType;
            $childClassRecord->total_money = $model->money;
            $childClassRecord->receipt_id = $model->receiptId;
            $childClassRecord->add_time = $_REQUEST['addDate'];
            $childClassRecord->check_status = 0;
            if($childClassRecord->save()){
                return $this->redirect('/admin/child/record');
            }
            return $this->goBack();
        }else{
            $model->receiptId = $childClassRecord->receipt_id;
            $model->money = $childClassRecord->total_money;
            $model->payType = $childClassRecord->pay_type;
            $model->recordId = $childClassRecord->id;
            return $this->render('edit', ['model' => $model,'addDate'=>$childClassRecord->add_time]);
        }

    }


}