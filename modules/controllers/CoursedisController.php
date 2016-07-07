<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016-05-10
 * Time: 10:33
 */

namespace app\modules\controllers;


use app\models\CourseDisAct;
use app\models\CourseDisActForm;
use app\models\CourseDisCr;
use app\models\CourseInfo;
use yii\filters\AccessControl;
use yii\web\Controller;
use Yii;
use yii\data\Pagination;

/**
 * 优惠课程活动
 * Class CoursedisController
 * @package app\modules\controllers
 */
class CoursedisController extends Controller
{
    public $enableCsrfValidation = false;//yii默认表单csrf验证，如果post不带改参数会报错！
    public $layout = 'layout';

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }
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
                        'actions' => ['list','add','save','del','course','check-list','check-yes','check-no'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionList(){
        $where = [];
        $params = [];
        $shopId = Yii::$app->user->identity->shopId;
        if(!empty($shopId)){
            $where["shop_id"] = $shopId;
        }
        $startDate = '';
        $startDateWhere = [];
        if(isset($_REQUEST["startDate"])){
            $startDate = $_REQUEST["startDate"];
            $params["startDate"] = $startDate;
            $startDateWhere = [">=","start_date",$startDate];
        }

        $activityInfo = CourseDisAct::find()->where($where)->andWhere($startDateWhere)->with("shop")->with("course")->with('user')->orderBy("id desc ");
        $pages = new Pagination(['totalCount' => $activityInfo->count(), 'pageSize' => '8']);
        $data = $activityInfo->offset($pages->offset)->limit($pages->limit)->all();
        $pages->params = $params;
        return $this->render('list', ['data' => $data,
                                        'pages' => $pages,
                                         'startDate'=>$startDate]);
    }

    public function actionCheckList(){
        $activityInfo = CourseDisAct::find()->where(['=','status',0])->with("shop")->with("course")->with('user')->orderBy("id desc ");
        $pages = new Pagination(['totalCount' => $activityInfo->count(), 'pageSize' => '8']);
        $data = $activityInfo->offset($pages->offset)->limit($pages->limit)->all();
        return $this->render('check-list', ['data' => $data,
            'pages' => $pages,
            ]);
    }

    public function actionCourse(){
        $actId = $_REQUEST["id"];
        $check = $_REQUEST['check'];
        $activityInfo = CourseDisCr::find()->where(['=','dis_act_id',$actId])->with("course")->orderBy("id desc ");
        $pages = new Pagination(['totalCount' => $activityInfo->count(), 'pageSize' => '8']);
        $data = $activityInfo->offset($pages->offset)->limit($pages->limit)->all();
        $pages->params = [];
        return $this->render('course', ['data' => $data,
            'pages' => $pages,
            'check'=>$check
           ]);
    }

    public function actionAdd(){
        $shopId = Yii::$app->user->identity->shopId;
        if(empty($shopId)){
            $shopId = 1;
        }
       $model = new CourseDisActForm();
       $model->startDate = date('Y-m-d', time());
       $model->endDate = date('Y-m-d', time());
       $array = [];
       $where['org_shop_info.shop_id'] = $shopId;
       $where['course_info.status'] = 1;
       $courseList = CourseInfo::find()->joinWith('course')->where($where)->all();
       foreach($courseList as $course){
           array_push($array,['id'=>$course["id"],'name'=>$course["name"]]);
       }
       $jsonData = json_encode($array, JSON_UNESCAPED_UNICODE);
        return $this->render('add', ['data' => $jsonData,
            'model' => $model
           ]);
    }

    public function actionSave(){
        $shopId = Yii::$app->user->identity->shopId;
        if(empty($shopId)){
            $shopId = 1;
        }
         if(empty( $_REQUEST["course"])){
            return $this->redirect("list");
        }
        $course = $_REQUEST["course"];
        $num = $_REQUEST["num"];
        $priceOne = $_REQUEST["price1"];
        $priceTwo = $_REQUEST["price2"];
        $params = $_REQUEST['CourseDisActForm'];
        $transaction = CourseDisAct::getDb()->beginTransaction();
        try{
            $courseDisA = new CourseDisAct();
            $courseDisA->title = $params['title'];
            $courseDisA->shop_id = $shopId;
            $courseDisA->start_date = $_REQUEST["startDate"];
            $courseDisA->end_date = $_REQUEST["endDate"];
            $courseDisA->status = 0;
            $courseDisA->user_id =  Yii::$app->user->identity->id;
            if(!$courseDisA->save()){
                throw new \Exception();
            }
            $id = CourseDisAct::find()->max("id");
            $size = count($course);
            for($i = 0 ; $i< $size ; $i++){
                $data = new CourseDisCr();
                $data->course_id = $course[$i];
                $data->dis_act_id = $id;
                $data->course_num = $num[$i];
                $data->price_one = $priceOne[$i];
                $data->price_two = $priceTwo[$i];
                if(!$data->save()){
                    throw new \Exception();
                }
            }
            $transaction->commit();
        }catch (\Exception $e){
            $transaction->rollBack();
        }
        return $this->redirect("list");
    }

    public function actionDel(){
        $id = $_REQUEST["id"];
        $transaction = CourseDisAct::getDb()->beginTransaction();
        try{
            CourseDisAct::findOne($id)->delete();
            CourseDisCr::deleteAll(['=','dis_act_id',$id]);
            $transaction->commit();
        }catch (\Exception $e){
            $transaction->rollBack();
        }
        return $this->redirect("list");
    }

    public function actionCheckYes(){
        $id = $_REQUEST['id'];
        $data = CourseDisAct::findOne($id);
        if(!empty($data)){
            $data->status = 1;
            $data->save();
        }
        return $this->redirect('check-list');
    }

    public function actionCheckNo(){
        $id = $_REQUEST['id'];
        $data = CourseDisAct::findOne($id);
        if(!empty($data)){
            $data->status = 2;
            $data->save();
        }
        return $this->redirect('check-list');
    }



}