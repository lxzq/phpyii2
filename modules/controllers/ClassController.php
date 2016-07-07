<?php
/**
 * Created by PhpStorm.
 * User: WWW
 * Date: 2015-12-09
 * Time: 10:23
 */

namespace app\modules\controllers;

use app\models\ChildClass;
use app\models\CourseClass;
use app\models\CourseInfo;
use app\models\CoursePlaceChild;
use app\models\CoursePlaceClass;
use app\models\CoursePlaceClassForm;
use app\models\OrgInfo;
use app\models\OrgShopInfo;
use app\models\ShopInfo;
use app\models\TeacherInfo;
use Yii;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\web\Controller;

class ClassController extends Controller
{
    public $enableCsrfValidation = false;//yii默认表单csrf验证，如果post不带改参数会报错！
    public $layout = 'layout';

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * accesscontrol
     */

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
                        'actions' => ['placeclasslist','add','save','addstudent','savestudent','del','ajax-teacher'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    //分班列表
    public function actionPlaceclasslist()
    {
        $shopId = Yii::$app->user->identity->shopId;
        $sql = [];
        $sql2 = [];
        if (!empty($_REQUEST["name"])) {
            $sql = ['like', 'name', $_REQUEST["name"]];
        }
        if (!empty($_REQUEST["shop"])) {
            $shopId = $_REQUEST["shop"];
        }
        if (!empty($shopId)) {
            $sql2 = ['=', 'course_place_class.shop_id', $shopId];
        }
        if (!empty($shopId)) {
            $shops = ShopInfo::findAll($shopId);
        }else{
            $shops = ShopInfo::find()->where(['>','dept_id',0])->all();
        }
        $list = CoursePlaceClass::find()->where($sql)->with("course")->with("shop")->andwhere($sql2)->with("num")->with('teacher')->orderBy(" id desc ");
        $pages = new Pagination(['totalCount' => $list->count(), 'pageSize' => '8']);
        $data = $list->offset($pages->offset)->limit($pages->limit)->all();
        if (!empty($_REQUEST["shop"])) {
            $pages->params = ['shop' => $shopId];
        }
        return $this->render('placeclasslist', ['placeclasslist' => $data, 'pages' => $pages, 'shopId' => $shopId, 'shops' => $shops], false, true);
    }

    //编辑或添加分班页面
    public function actionAdd()
    {
        $model = new CoursePlaceClassForm();
        $teacherList = null;
        if(isset($_REQUEST['id']) && !empty($_REQUEST['id'])){
            $info = CoursePlaceClass::findOne($_REQUEST["id"]);
            $model->course_id = $info->course_id;
            $model->id = $info->id;
            $model->name = $info->name;
            $model->notes =$info->notes;
            $model->teacher = $info->teacher_id;
            $teacherList = TeacherInfo::find()->joinWith('teacher')->where(['=','course_teacher.course_id',$model->course_id])->all();
        }
        $shopId = Yii::$app->user->identity->shopId;
        if(empty($shopId)){
            $shopId = 1;
        }
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if (!empty($_REQUEST["id"])) {
                $info = CoursePlaceClass::findOne($_REQUEST["id"]);
            } else {
                $info = new CoursePlaceClass();
            }
            if(isset($_REQUEST['teacher'])){
                $info->teacher_id = $_REQUEST['teacher'];
            }
            $info->name = $model->name;
            $info->notes = $model->notes;
            $info->shop_id = $shopId;
            $info->course_id = $_REQUEST["course_id"];
            if($info->save()){
                return $this->redirect("placeclasslist");
            }
        } else {
            $where['org_shop_info.shop_id'] = $shopId;
            $where['course_info.status'] = 1;
            $courseList = CourseInfo::find()->joinWith('course')->where($where)->all();

             return $this->render('placeclassedit', [
                'model' => $model,
                'course' => $courseList,
                'teacherList'=>$teacherList
            ]);
     }
    }

    public function actionAjaxTeacher(){
        $courseId = $_REQUEST['courseId'];
        $teacherList = TeacherInfo::find()->joinWith('teacher')->where(['=','course_teacher.course_id',$courseId])->all();
        $array = [];
        foreach($teacherList as $course){
            array_push($array,['id'=>$course["id"],'name'=>$course["name"]]);
        }
        $jsonData = json_encode($array, JSON_UNESCAPED_UNICODE);
        return $jsonData;
    }

    //编辑或添加分班的保存
    public function actionSave()
    {
        $params = $_REQUEST["CoursePlaceClassForm"];
        if (!empty($_REQUEST["id"])) {
            $info = CoursePlaceClass::findOne($_REQUEST["id"]);
        } else {
            $info = new CoursePlaceClass();
        }
        $info->name = $params["name"];
        $info->notes = $params["notes"];
        $info->teacher_id = $_REQUEST['teacher'];
        $shopId = Yii::$app->user->identity->shopId;
        if(empty($shopId)){
            $shopId = 1;
        }
        $info->shop_id = $shopId;
        $info->course_id = $_REQUEST["course_id"];
        $info->save();
        return $this->redirect("placeclasslist");
    }

    public function actionAddstudent(){
        $placeclass_id = $_REQUEST["placeclass_id"];
        $info = CoursePlaceClass::findOne($placeclass_id);
        $courseId = $info["course_id"];
        $shopId = $info['shop_id'];
        $sql = "select distinct b.id, b.nick_name ,a.course_num,
        (select count(c.child_id) from course_place_child c where c.course_place_id = :placeId and c.child_id = a.child_id) as is_add
        from child_class a";
        $sql .= " left join child_info b on a.child_id = b.id ";
        $sql .= " where a.course_id = :course and b.shop_id = :shopId ";
        $connection  = Yii::$app->db;
        $classCommand = $connection->createCommand($sql);
        $classCommand->bindValue(':course', $courseId);
        $classCommand->bindValue(':placeId', $placeclass_id);
        $classCommand->bindValue(':shopId', $shopId);
        $comment = $classCommand->queryAll();
        return $this->render('addstudent', [
            'placeclass'=>$info,
            'studentlist' => $comment,
        ]);
    }

    //编辑或添加分班学生的保存
    public function actionSavestudent()
    {
      if (!empty($_REQUEST["childids"])) {
            $transaction = CoursePlaceChild::getDb()->beginTransaction();
            try{
                $coursePlaceId = $_REQUEST["id"];
                $childArray = [];
                foreach($_REQUEST["childids"] as $childid){
                    $courseChild = CoursePlaceChild::findOne(['course_place_id'=>$coursePlaceId,'child_id'=>$childid]);
                    array_push($childArray,$childid);
                    if(empty($courseChild)){
                        $info = new CoursePlaceChild();
                        $info->course_place_id = $coursePlaceId;
                        $info->child_id = $childid;
                        $info->add_time = date('Y-m-d h:i:s',time());
                        $info->status = 0;
                        if(!$info->save()){
                            throw new \Exception();
                        }
                    }
                }
                $transaction->commit();
              $coursePlaceChild  = CoursePlaceChild::find()->where(['course_place_id'=>$coursePlaceId])->andWhere(['not in','child_id',$childArray])->all();
              foreach($coursePlaceChild as $item){
                  $item->delete();
              }
            }catch (\Exception $e){
                $transaction->rollBack();
            }
       }
        return $this->redirect("placeclasslist");
    }
    //删除记录
    public function actionDel()
    {
        $id = $_REQUEST["id"];
        if (!empty($id)) {
            CoursePlaceClass::findOne($id)->delete();
            CoursePlaceChild::deleteAll(["course_place_id"=>$id]);
        }
        return $this->redirect("placeclasslist");
    }

}