<?php
/**
 * Created by PhpStorm.
 * User: Aimee
 * Date: 16/3/24
 * Time: 下午5:57
 */

namespace app\modules\controllers;
use app\models\Menu;
use Yii;
use app\modules\weixin\BaseController;
use yii\filters\AccessControl;
use app\models\AppInfo;

class AppInfoController extends BaseController{
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
                        'actions' => ['index','edit','delete','save'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * @return 应用管理首页
     */
    public function actionIndex()
    {
        $list = AppInfo::find()->all();
        return $this->render("index",['list' => $list]);
    }

    /**
     * 菜单管理
     */
    public function actionEdit()
    {

        if (!empty($_REQUEST["id"])) {
            $app_info = AppInfo::findOne($_REQUEST["id"]);
        } else {
            $app_info = new AppInfo();
        }
        return $this->render('edit', ['model' => $app_info]);
    }


    /**
     * @return \yii\web\Response
     */
    public function actionSave()
    {
        if (!empty($_REQUEST["id"])) {
            $appInfo = AppInfo::findOne($_REQUEST["id"]);
        } else {
            $appInfo = new AppInfo();
        }
        $params = $_REQUEST["AppInfo"];
        $appInfo->app_code = $params["app_code"];
        $appInfo->app_name = $params["app_name"];
        $appInfo->app_desc = $params["app_desc"];
        $appInfo->app_icon = $params["app_icon"];
        $appInfo->is_show = $params["is_show"];
        $appInfo->save();
        return $this->redirect("/admin/appinfo/index");
    }

    /**
     * @return \yii\web\Response
     */
    public function actionDelete()
    {
        $ids=$_REQUEST["ids"];
        AppInfo::deleteAll(['id'=>$ids]);
        return $this->redirect("/admin/appinfo/index");
    }
}