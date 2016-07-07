<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/12/14
 * Time: 14:56
 */

namespace app\modules\controllers;


use app\models\ShopDrive;
use app\models\ShopDriveForm;
use app\models\ShopInfo;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\data\Pagination;
use Yii;
class ShopdriveController extends Controller
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
                        'actions' => ['list','save','del','add','drives'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionAdd(){
        $model = new ShopDriveForm();
        if(!empty($_REQUEST["id"])){
            $shop = ShopDrive::findOne($_REQUEST["id"]);
            $model->shopId = $shop->shop_id;
            $model->name = $shop->drive_name;
            $model->id = $shop->id;
            $model->code = $shop->drive_code;
        }
        $shopList = ShopInfo::find()->all();
        $shops = [];
        foreach($shopList as $shop){
            $shops[$shop["id"]] = $shop["name"];
        }
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            return $this->goBack();
        } else {
            return $this->render('add',[
                'model' => $model,
                'shops' => $shops
            ]);
        }
    }

    public function actionSave(){
        $params = $_REQUEST["ShopDriveForm"];
        if(!empty($_REQUEST["id"])){
            $shop = ShopDrive::findOne($_REQUEST["id"]);
        }else{
            $shop = new ShopDrive();
        }
        $shop->shop_id = $params["shopId"];
        $shop->drive_name =  $params["name"];
        $shop->drive_code = $params["code"];
        $shop->save();
        return $this->redirect("/admin/shopdrive/list");
    }

    public function actionDel(){
        $id = $_REQUEST["id"];
        if(!empty($id)){
            ShopDrive::findOne($id)->delete();
        }
        return $this->redirect("/admin/shopdrive/list");
    }

    public function actionList(){
        $sql = [];
        $shopId='';
        if(!empty($_REQUEST["name"])){
            $sql = ['like','drive_name',$_REQUEST["name"]];
        }
        if(!empty($_REQUEST["shopId"])){
            $sql = ['=','shop_id',$_REQUEST["shopId"]];
            $shopId = $_REQUEST["shopId"];
        }
        $list = ShopDrive::find()->where($sql)->orderBy("id desc ");
        $pages = new Pagination(['totalCount' => $list->count(),'pageSize' => '8']);
        $data = $list->offset($pages->offset)->limit($pages->limit)->all();
        if(!empty($_REQUEST["shopId"])) {
            $pages->params = ['shopId' => $shopId];
        }
        $shopList = ShopInfo::find()->all();
        return $this->render('list',[
            'list' => $data,
            'pages' => $pages,
            'shopId'=>$shopId,
            'shops'=>$shopList
        ]);
    }
    public function actionDrives()
    {
        $teacherlist = ShopDrive::find()->where(["shop_id" => $_REQUEST["shopId"]])->all();
        $arrarlist = [];
        foreach ($teacherlist as $entity) {
            array_push($arrarlist, ['key' => $entity["drive_code"], 'value' => $entity["drive_name"]]);
        }
        $json_string = json_encode($arrarlist, JSON_UNESCAPED_UNICODE);
        echo $json_string;
    }
}