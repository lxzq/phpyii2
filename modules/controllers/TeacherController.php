<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/12/9
 * Time: 13:12
 */

namespace app\modules\controllers;
use app\models\OrgInfo;
use app\models\TeacherForm;
use app\models\TeacherInfo;
use app\models\WeixinUser;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\data\Pagination;
use Yii;
class TeacherController extends Controller
{

    public $enableCsrfValidation = false;//yii默认表单csrf验证，如果post不带改参数会报错！
    public $layout  = 'layout';

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
                        'actions' => ['login','captcha'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['list','save','del','add'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionAdd(){
        $orgs = OrgInfo::find()->all();
        $orgslist = [];
        $weixinList = [];
        foreach ($orgs as $entity){
           $orgslist[$entity["id"]] = $entity["name"];
        }
        $model = new TeacherForm();
        if(!empty($_REQUEST["id"])){
            $teacher = TeacherInfo::findOne($_REQUEST["id"]);
            $model->id = $teacher->id;
            $model->orgId = $teacher->org_id;
            $model->name = $teacher->name;
            $model->sex = $teacher->sex;
            $model->address = $teacher->address;
            $model->notes = $teacher->notes;
            $model->workYears = $teacher->work_years;
            $model->phone = $teacher->phone;
            $model->weixinUserId = $teacher->weixin_user_id;
        }
        $weixinUser = WeixinUser::findAll(['weixin_group_id'=>101]);
        foreach($weixinUser as $item){
            $weixinList[$item['id']] = $item['nickname'];
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            return $this->goBack();
        } else {
            return $this->render('add',[
                'model' => $model,
                'orgs'=> $orgslist,
                'weixinUser'=>$weixinList
            ]);
        }
    }

    public function actionSave(){
        $params = $_REQUEST["TeacherForm"];
        if(!empty($_REQUEST["id"])){
            $techer = TeacherInfo::findOne($_REQUEST["id"]);
        }else{
            $techer = new TeacherInfo();
        }
        $techer->org_id = $params["orgId"];
        $techer->name =  $params["name"];
        $techer->sex = $_REQUEST["sex"];
        $techer->work_years = $params["workYears"];
        $techer->address = $params["address"];
        $techer->notes = $_REQUEST["notes"];
        $techer->phone = $params["phone"];
        $techer->weixin_user_id = $params['weixinUserId'];
        $techer->save();
        return $this->redirect("/admin/teacher/list");
    }

    public function actionList(){
        $sql = [];
        $orgId = "";
        if(!empty($_REQUEST["name"])){
            $sql = ['like','name',$_REQUEST["name"]];
        }
        if(!empty($_REQUEST["org"])){
            $sql = ['=','org_id',$_REQUEST["org"]];
            $orgId = $_REQUEST["org"];
        }
        $list = TeacherInfo::find()->where($sql)->with('weixinUser')->orderBy("id desc ");
        $pages = new Pagination(['totalCount' => $list->count(),'pageSize' => '8']);
        $data = $list->offset($pages->offset)->limit($pages->limit)->all();
        if(!empty($_REQUEST["org"])) {
            $pages->params = ['org' => $_REQUEST["org"]];
        }else{
            $pages->params = [];
        }
        $orgs = OrgInfo::find()->all();
        return $this->render('list',[
            'list' => $data,
            'orgs'=>$orgs,
            'pages' => $pages,
            'orgId'=> $orgId
        ]);
    }

    public function actionDel(){
        $id = $_REQUEST["id"];
        if(!empty($id)){
            TeacherInfo::findOne($id)->delete();
        }
        return $this->redirect("/admin/teacher/list");
    }

}