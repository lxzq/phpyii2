<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/12/10
 * Time: 9:20
 */

namespace app\modules\controllers;


use app\models\ProjectForm;
use app\models\ProjectInfo;
use app\models\ShopForm;
use app\models\ShopInfo;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\data\Pagination;
use Yii;

class ShopController extends Controller
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
                        'actions' => ['list','save','del','add','listpro','savepro','delpro','addpro'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionAdd(){
        $model = new ShopForm();
        if(!empty($_REQUEST["id"])){
            $shop = ShopInfo::findOne($_REQUEST["id"]);
            $model->id = $shop->id;
            $model->name = $shop->name;
            $model->logo = $shop->logo;
            $model->notes = $shop->notes;
            $model->address = $shop->address;
            $model->psword = $shop->psword;
        }
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            return $this->goBack();
        } else {
            return $this->render('add',[
                'model' => $model
            ]);
        }
    }

    public function actionSave(){
        $params = $_REQUEST["ShopForm"];
        if(!empty($_REQUEST["id"])){
            $shop = ShopInfo::findOne($_REQUEST["id"]);
        }else{
            $shop = new ShopInfo();
        }
        $shop->name = $params["name"];
        $shop->logo = $_REQUEST["logo"];
        $shop->notes = $params["notes"];
        $shop->address = $params["address"];
        $shop->psword = $params["psword"];
        $shop->save();
        return $this->redirect("/admin/shop/list");
    }

    public function actionDel(){
        $id = $_REQUEST["id"];
        if(!empty($id)){
            ShopInfo::findOne($id)->delete();
        }
        return $this->redirect("/admin/shop/list");
    }

    public function actionList(){
        $sql = [];
        if(!empty($_REQUEST["name"])){
            $sql = ['like','name',$_REQUEST["name"]];
        }
        $list = ShopInfo::find()->where($sql)->orderBy("id desc ");
        $pages = new Pagination(['totalCount' => $list->count(),'pageSize' => '8']);
        $data = $list->offset($pages->offset)->limit($pages->limit)->all();
        return $this->render('list',[
            'list' => $data,
            'pages' => $pages
        ]);
    }

    /*****************店铺项目********************/

    public function actionAddpro(){
        $model = new ProjectForm();
        if(!empty($_REQUEST["id"])){
            $pro = ProjectInfo::findOne($_REQUEST["id"]);
            $model->id = $pro->id;
            $model->name = $pro->name;
            $model->shopId = $pro->shop_id;
            $model->image = $pro->image;
            $model->describe = $pro->describe;
            $model->notes = $pro->notes;
        }
        $shopList = ShopInfo::find()->all();
        $shops = [];
        foreach($shopList as $shop){
            $shops[$shop["id"]] = $shop["name"];
        }
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            return $this->goBack();
        } else {
            return $this->render('addpro',[
                'model' => $model,
                'shops' => $shops
            ]);
        }
    }

    public function actionSavepro(){
        $params = $_REQUEST["ProjectForm"];
        if(!empty($_REQUEST["id"])){
            $pro = ProjectInfo::findOne($_REQUEST["id"]);
        }else{
            $pro = new ProjectInfo();
        }
        $pro->name = $params["name"];
        $pro->shop_id = $params["shopId"];
        $pro->image = $_REQUEST["image"];
        $pro->describe= $params["describe"];
        $pro->notes = $_REQUEST["notes"];
        $pro->save();
        return $this->redirect("/admin/shop/listpro");
    }

    public function actionDelpro(){
        $id = $_REQUEST["id"];
        if(!empty($id)){
            ProjectInfo::findOne($id)->delete();
        }
        return $this->redirect("/admin/shop/listpro");
    }

    public function actionListpro(){
        $sql = [];
        $shopId='';
        if(!empty($_REQUEST["name"])){
            $sql = ['like','name',$_REQUEST["name"]];
        }
        if(!empty($_REQUEST["shopId"])){
            $sql = ['=','shop_id',$_REQUEST["shopId"]];
            $shopId = $_REQUEST["shopId"];
        }

        $list = ProjectInfo::find()->where($sql)->orderBy("id desc ");
        $pages = new Pagination(['totalCount' => $list->count(),'pageSize' => '5']);
        $data = $list->offset($pages->offset)->limit($pages->limit)->all();
        if(!empty($_REQUEST["shopId"])) {
            $pages->params = ['shopId' => $shopId];
        }
        $shopList = ShopInfo::find()->all();
        return $this->render('listpro',[
            'list' => $data,
            'pages' => $pages,
            'shopId'=>$shopId,
            'shops'=>$shopList
        ]);
    }

}