<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/5/12
 * Time: 9:58
 */

namespace app\modules\controllers;


use app\models\OrganizationAgreementForm;
use app\models\OrgManagerFile;
use app\models\OrgInfo;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\data\Pagination;
use Yii;
use yii\web\UploadedFile;

class OrganizationAgreementController extends Controller
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
                        'actions' => ['list', 'add', 'save', 'del', 'upload'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionAdd(){
        $org = OrgInfo::find()->all();
        $orgList = [];
        foreach ($org as $entity){
            $orgList[$entity["id"]] = $entity["name"];
        }
        $model = new OrganizationAgreementForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            return $this->goBack();
        } else {
            return $this->render('add',[
                'model' => $model,
                'orgList' => $orgList
            ]);
        }
    }

    public function actionUpload(){
        $file = UploadedFile::getInstanceByName("upfile");
        $ext = time() . '.' . $file->extension;;//生成的引用路径
        $temp = Yii::$app->basePath . "/web/avatar/" . $ext;
        if(!$file->saveAs($temp)){
            echo '0';
        }
        $path = '/avatar/' . $ext;
        $name = $file->name;
        echo '{"name":"'.$name .'","path":"'.$path .'"}';
    }

    public function actionSave(){
        $params = $_REQUEST["OrganizationAgreementForm"];
        $path = $_REQUEST["path"];
        $data = new OrgManagerFile();
        $data->add_time = date('Y-m-d', time());
        $data->name = $_REQUEST["name"];
        $data->file_path = $path;
        $data->user_id = Yii::$app->user->identity->id;
        $data->notes = $params["notes"];
        $data->org_id = $params['orgId'];
        $data->save();
        return $this->redirect("list");
    }

    public function actionList(){
        $sql = [];
        if(!empty($_REQUEST["name"])){
            $sql = ['like','org_manager_file.name',$_REQUEST["name"]];
        }
        $shopId = Yii::$app->user->identity->shopId;
        $where =[];
        if(!empty($shopId)){
            $where['org_shop_info.shop_id'] = $shopId;
        }
        $list = OrgManagerFile::find()->joinWith('shop')->where($sql)->andWhere($where)->with("user")->with('org')->orderBy("org_manager_file.id desc ");
        $pages = new Pagination(['totalCount' => $list->count(),'pageSize' => '8']);
        $data = $list->offset($pages->offset)->limit($pages->limit)->all();
        return $this->render('list',[
            'list' => $data,
            'pages' => $pages
        ]);
    }

    public function actionDel(){
        $id = $_REQUEST["id"];
        if(!empty($id)){
            OrgManagerFile::findOne($id)->delete();
        }
        return $this->redirect("list");
    }
}