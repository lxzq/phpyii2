<?php
/**
 * Created by PhpStorm.
 * User: Aimee
 * Date: 16/3/24
 * Time: 下午5:57
 */

namespace app\modules\controllers;
use app\models\User;
use app\models\Role;
use app\models\Menu;
use Yii;
use app\modules\weixin\BaseController;
use yii\filters\AccessControl;
use yii\data\Pagination;
use app\models\UserGroup;
use app\models\AppInfo;

class MenuController extends BaseController{
    public $enableCsrfValidation = false;//yii默认表单csrf验证，如果post不带改参数会报错！
    public $layout  = 'layout';

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
                        'actions' => ['index','edit','save','get-menu','delete'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * @return 菜单管理首页
     */
    public function actionIndex()
    {
        $list = Menu::find()->all();
        return $this->render("index",['list' => $list]);
    }

    /**
     * 获取某个应用的菜单
     */
    public function actionGetMenu()
    {
        $app_code=$_REQUEST["app_code"];
        $menu_list = Menu::find()->where(["app_code" => $app_code,"menu_parent_no"=>0])->all();
        $return_list = [];
        foreach ($menu_list as $entity) {
            array_push($return_list, ['key' => $entity["id"], 'value' => $entity["menu_name"]]);
        }
      return json_encode($return_list, JSON_UNESCAPED_UNICODE);
    }
    /**
     * 菜单管理
     */
    public function actionEdit()
    {

        $menu_info=array();
        if (!empty($_REQUEST["id"])) {
            $model = Menu::findOne($_REQUEST["id"]);
            $menu_info=Menu::find()->where(['app_code'=>$model->app_code,'menu_parent_no'=>0])->all();
        } else {
            $model = new Menu();
        }
        $app_info=AppInfo::find()->all();
        return $this->render('edit', ['model' => $model,'app_info'=>$app_info,'menu_info'=>$menu_info]);
    }


    /**
     * @return \yii\web\Response
     */
    public function actionSave()
    {
        if (!empty($_REQUEST["id"])) {
            $menuInfo = Menu::findOne($_REQUEST["id"]);
        } else {
            $menuInfo = new Menu();
        }
        $params = $_REQUEST["Menu"];
        $menuInfo->app_code = $_REQUEST["app_code"];
        $menuInfo->menu_parent_no = $_REQUEST["menu_parent_no"];
        $menuInfo->menu_order = $params["menu_order"];
        $menuInfo->menu_name = $params["menu_name"];
        $menuInfo->menu_url = $params["menu_url"];
        $menuInfo->IsVisisble = $params["IsVisisble"];
        $menuInfo->IsLeaf = $params["IsLeaf"];
        $menuInfo->save();
        return $this->redirect("/admin/menu/index");
    }

    /**
     * @return \yii\web\Response
     */
    public function actionDelete()
    {

        $ids=$_REQUEST["ids"];

        Menu::deleteAll(['id'=>$ids]);
        return $this->redirect("/admin/menu/index");

    }
}