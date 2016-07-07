<?php
/**
 * Created by PhpStorm.
 * User: Aimee
 * Date: 16/3/24
 * Time: 下午5:57
 */

namespace app\modules\controllers;
use app\models\Menu;
use app\models\Role;
use Yii;
use app\modules\weixin\BaseController;
use yii\filters\AccessControl;
use app\models\AppInfo;

class RoleController extends BaseController{
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
        $list = Role::find()->all();
        return $this->render("index",['list' => $list]);
    }

    /**
     * 角色管理
     */
    public function actionEdit()
    {

        if (!empty($_REQUEST["id"])) {
            $role_info = Role::findOne($_REQUEST["id"]);
        } else {
            $role_info = new Role();
        }
        return $this->render('edit', ['model' => $role_info]);
    }


    /**
     * @return \yii\web\Response
     */
    public function actionSave()
    {
        if (!empty($_REQUEST["id"])) {
            $roleInfo = Role::findOne($_REQUEST["id"]);
        } else {
            $roleInfo = new Role();
        }
        $params = $_REQUEST["Role"];
        $roleInfo->role_name = $params["role_name"];
        $roleInfo->role_desc = $params["role_desc"];
        $roleInfo->save();
        return $this->redirect("/admin/role/index");
    }

    /**
     * @return \yii\web\Response
     */
    public function actionDelete()
    {
        $ids=$_REQUEST["ids"];
        Role::deleteAll(['id'=>$ids]);
        return $this->redirect("/admin/role/index");
    }
}