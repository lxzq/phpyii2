<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/12/1
 * Time: 11:59
 */

namespace app\modules\controllers;

use app\models\ChildClass;
use app\models\OrgInfo;
use app\models\CourseOrder;
use app\models\ShopInfo;
use yii\web\Controller;
use yii\data\Pagination;
use yii\filters\AccessControl;

class OrderController extends Controller
{

    public $enableCsrfValidation = false;//yii默认表单csrf验证，如果post不带改参数会报错！
    public $layout = 'layout';

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
                        'actions' => ['list', 'del', 'signuplist'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionList()
    {

        $sql = [];

        if (!empty($_REQUEST["userName"])) {
            $sql = ['like', 'course_user.bbname', $_REQUEST["userName"]];
        }
        if (!empty($_REQUEST["phone"])) {
            $sql = ['like', 'phone', $_REQUEST["phone"]];
        }

        $orgs = OrgInfo::find()->all();
        $shops = ShopInfo::find()->all();

        $list = CourseOrder::find()->joinWith("class")->joinWith("course")->joinWith("price")->joinWith("user")->
        where(["course_info.course_type" => 1])->andWhere($sql)->orderBy("creatTime desc ");
        $pages = new Pagination(['totalCount' => $list->count(), 'pageSize' => '10']);
        $data = $list->offset($pages->offset)->limit($pages->limit)->all();
        return $this->render('list', [
            'list' => $data, 'orgs' => $orgs, 'shops' => $shops,
            'pages' => $pages
        ]);
    }
    public function actionSignuplist()
    {
        $class_id = $_REQUEST["class_id"];
        $sql = [];
        if (!empty($_REQUEST["userName"])) {
            $sql = ['like', 'course_user.bbname', $_REQUEST["userName"]];
        }
        if (!empty($_REQUEST["phone"])) {
            $sql = ['like', 'phone', $_REQUEST["phone"]];
        }

        $list = ChildClass::find()->joinWith("class")->joinWith("child")->where(["child_class.class_id"=>$class_id])->andWhere($sql);

        $orgs = OrgInfo::find()->all();
        $shops = ShopInfo::find()->all();

        /*$list = CourseOrder::find()->joinWith("class")->joinWith("course")->joinWith("price")->joinWith("user")->
        where(["course_info.course_type" => 1])->andWhere($sql)->orderBy("creatTime desc ");*/
        $pages = new Pagination(['totalCount' => $list->count(), 'pageSize' => '10']);
        $data = $list->offset($pages->offset)->limit($pages->limit)->all();
        return $this->render('signuplist', [
            'list' => $data, 'orgs' => $orgs, 'shops' => $shops,
            'pages' => $pages
        ]);
    }

}