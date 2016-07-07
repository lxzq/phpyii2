<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016-05-05
 * Time: 9:36
 */

namespace app\modules\controllers;


use app\models\DocumentForm;
use app\models\DocumentManager;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\data\Pagination;
use Yii;
use yii\web\UploadedFile;

class DocumentController extends Controller
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
                        'actions' => ['list','add','save','del','upload'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionAdd(){
        $model = new DocumentForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            return $this->goBack();
        } else {
            return $this->render('add',[
                'model' => $model
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
        $params = $_REQUEST["DocumentForm"];
        $path = $_REQUEST["path"];
        $data = new DocumentManager();
        $data->add_time = date('Y-m-d', time());
        $data->name = $_REQUEST["name"];
        $data->path = $path;
        $data->user_id = Yii::$app->user->identity->id;
        $data->notes = $params["notes"];
        $data->save();
        return $this->redirect("list");
    }

    public function actionList(){
        $sql = [];
        if(!empty($_REQUEST["name"])){
            $sql = ['like','name',$_REQUEST["name"]];
        }
        $list = DocumentManager::find()->where($sql)->with("user")->orderBy("id desc ");
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
            DocumentManager::findOne($id)->delete();
        }
        return $this->redirect("list");
    }
}