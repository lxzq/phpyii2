<?php
/**
 * Created by PhpStorm.
 * User: WWW
 * Date: 2015-12-09
 * Time: 10:23
 */

namespace app\modules\controllers;

use app\models\Course;
use app\models\CourseClass;
use app\models\CourseClassForm;
use app\models\CourseInfo;
use app\models\CoursePrice;
use app\models\CoursePriceForm;
use app\models\CourseTeacher;
use app\models\OrgShopInfo;
use app\models\ShopInfo;
use app\models\TeacherInfo;
use Yii;
use yii\data\Pagination;
use app\models\Organization;
use app\models\OrgInfo;
use yii\filters\AccessControl;
use yii\web\Controller;

class OrganizationController extends Controller
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
                        'actions' => ['orglist', 'orgedit', 'save', 'del', 'setlunbo',
                            'courselist', 'courseedit', 'savecourse', 'delcourse','ajax-teacher',
                          ],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    //机构列表
    public function actionOrglist()
    {
        $sql = [];
        if (!empty($_REQUEST["name"])) {
            $sql = ['like', 'name', $_REQUEST["name"]];
        }
        $orglist = OrgInfo::find()->where($sql)->orderBy("id desc ");
        $pages = new Pagination(['totalCount' => $orglist->count(), 'pageSize' => '8']);
        $data = $orglist->offset($pages->offset)->limit($pages->limit)->all();
        $shopList = ShopInfo::find()->all();
        return $this->render('orglist', ['orglist' => $data, 'pages' => $pages, 'shops' => $shopList], false, true);
    }

    //编辑或添加机构页面
    public function actionOrgedit()
    {
        $model = new Organization();
        $shopList = ShopInfo::find()->all();
        $checkShops = [];
        if (!empty($_REQUEST["id"])) {
            $org = OrgInfo::findOne($_REQUEST["id"]);
            $model->id = $org["id"];
            $model->name = $org["name"];
            $model->notes = $org["notes"];
            $model->logo = $org["logo"];
            $orgShop = OrgShopInfo::find()->where(['=', 'org_id', $_REQUEST["id"]])->all();
            foreach ($orgShop as $value) {
                array_push($checkShops, $value["shop_id"]);
            }
            $model->shopIds = $checkShops;
        }
        $shops = [];
        foreach ($shopList as $value) {
            $shops[$value["id"]] = $value["name"];
        }
        if ($model->load(Yii::$app->request->post()) && $model->submitData()) {
            return $this->goBack();
        } else {
            return $this->render('orgedit', [
                'model' => $model,
                'shops' => $shops,
                'checkShops' => $checkShops
            ]);
        }
        return $this->render('orgedit');
    }

    //编辑或添加机构的保存
    public function actionSave()
    {
        $params = $_REQUEST["Organization"];
        $shops = $params["shopIds"];

        $orgId = '';
        if (!empty($_REQUEST["id"])) {
            $info = OrgInfo::findOne($_REQUEST["id"]);
            $orgId = $_REQUEST["id"];
            $orgShop = OrgShopInfo::find()->where(['=', 'org_id', $_REQUEST["id"]])->all();
            foreach ($orgShop as $value) {
                $value->delete();
            }
        } else {
            $info = new OrgInfo();
        }
        $info->name = $params["name"];
        $info->notes = $_REQUEST["notes"];

        if (!empty($_REQUEST["logo"])) {
            $info->logo = $_REQUEST["logo"];
        }
        $info->save();
        if (empty($_REQUEST["id"])) $orgId = OrgInfo::find()->max("id");

        if (!empty($shops)) {
            $arrlength = count($shops);
            for ($i = 0; $i < $arrlength; $i++) {
                $rogShop = new OrgShopInfo();
                $rogShop->org_id = $orgId;
                $rogShop->shop_id = $shops[$i];
                $rogShop->save();
            }
        }

        return $this->redirect("/admin/organization/orglist");
    }

    //删除记录
    public function actionDel()
    {
        $id = $_REQUEST["id"];
        if (!empty($id)) {
            OrgInfo::findOne($id)->delete();
            $orgShop = OrgShopInfo::find()->where(['=', 'org_id', $id])->all();
            foreach ($orgShop as $value) {
                $value->delete();
            }
        }
        return $this->redirect("/admin/organization/orglist");
    }
    //课程列表
    public function actionCourselist()
    {
        $where = [];
        $where['status'] = 1;
        $sql = [];
        $orgId = "";
        if (!empty($_REQUEST["name"])) {
            $sql = ['like', 'name', $_REQUEST["name"]];
        }
        if (!empty($_REQUEST["org"])) {
            $sql = ['=', 'org_id', $_REQUEST["org"]];
            $orgId = $_REQUEST["org"];
        }
        $list = CourseInfo::find()->where($where)->andWhere($sql)->with('org')->with('teacher')->with('price')->orderBy("id desc");
        $pages = new Pagination(['totalCount' => $list->count(), 'pageSize' => '8']);
        $data = $list->offset($pages->offset)->limit($pages->limit)->all();
        if (!empty($_REQUEST["org"])) {
            $pages->params = ['org' => $_REQUEST["org"]];
        } else {
            $pages->params = [];
        }
        $orgs = OrgInfo::find()->all();
        return $this->render('courselist', ['courselist' => $data, 'orgs' => $orgs, 'orgId' => $orgId, 'pages' => $pages]);
    }

    //编辑或添加课程页面
    public function actionCourseedit()
    {
        $model = new Course();
        $teacherList = null;
        $ct = null;
        if (!empty($_REQUEST["id"])) {
            $course = CourseInfo::findOne($_REQUEST["id"]);
            $model->id = $course["id"];
            $model->name = $course["name"];
            $model->notes = $course["notes"];
            $model->logo = $course["logo"];
            $model->org_id = $course["org_id"];
            $model->describe = $course["describe"];
            $model->class_time = $course['class_time'];
            $teacherList = TeacherInfo::find()->where(['=','org_id',$model->org_id])->all();
            $ct = CourseTeacher::find()->where(['=','course_id',$model->id])->all();
      }
        $orgList = OrgInfo::find()->all();
       if ($model->load(Yii::$app->request->post()) && $model->submitData()) {
            return $this->goBack();
       } else {
            return $this->render('courseedit', [
                'model' => $model,
                'orgs' => $orgList,
                'teacherList'=>$teacherList,
                'ct'=>$ct
        ]);
        }
    }

    /**
     * 根据机构查询老师
     */
    public function actionAjaxTeacher(){
        $orgId = $_REQUEST["orgId"];
        $teacherList = TeacherInfo::find()->where(['=','org_id',$orgId])->all();
        $array = [];
        foreach($teacherList as $course){
            array_push($array,['id'=>$course["id"],'name'=>$course["name"]]);
        }
        $jsonData = json_encode($array, JSON_UNESCAPED_UNICODE);
         return $jsonData;
    }

    //编辑或添加课程的保存
    public function actionSavecourse()
    {
        $params = $_REQUEST["Course"];
        $transaction = CourseInfo::getDb()->beginTransaction();
        try{
            if (!empty($_REQUEST["id"])) {
                $info = CourseInfo::findOne($_REQUEST["id"]);
            } else {
                $info = new CourseInfo();
            }
            $info->name = $params["name"];
            $info->notes = $_REQUEST["notes"];
            $info->org_id = $_REQUEST["org"];
            $info->class_time = $params['class_time'];
            if (!empty($_REQUEST["logo"])) {
                $info->logo = $_REQUEST["logo"];
            }
            $info->describe = $params["describe"];
            if(!$info->save()){
                throw new \Exception();
            }
        if(!empty($_REQUEST["teacher"])){
            $teacher = $_REQUEST["teacher"];
           if (!empty($_REQUEST["id"])) {
                $courseId = $_REQUEST["id"];
                CourseTeacher::deleteAll(['=','course_id',$courseId]);
            }else{
                $courseId = $info->find()->max('id');
            }
            $size = count($teacher);
            for($i = 0 ; $i< $size ;$i++){
                $teacherId = $teacher[$i];
                $courseTe = new CourseTeacher();
                $courseTe->course_id = $courseId;
                $courseTe->teacher_id = $teacherId;
                if(!$courseTe->save()){
                    throw new \Exception();
                }
            }
        }
        $transaction->commit();
        }catch (\Exception $e){
            $transaction->rollBack();
        }
        return $this->redirect("courselist");
    }

    //删除记录
    public function actionDelcourse()
    {
        $id = $_REQUEST["id"];
        if (!empty($id)) {
            $course = CourseInfo::findOne($id);
            $course->status = 0;
            $course->save();
        }
        return $this->redirect("/admin/organization/courselist");
    }

}
