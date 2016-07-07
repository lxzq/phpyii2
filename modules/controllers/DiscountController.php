<?php
/**
 * Created by PhpStorm.
 * User: WWW
 * Date: 2016-01-20
 * Time: 14:50
 */
namespace app\modules\controllers;

use app\models\CourseDiscount;
use app\models\CourseDiscountForm;
use Yii;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\web\Controller;

class DiscountController extends Controller{

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
                        'actions' => ['discountlist','discountedit','savediscount','deldiscount'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    //优惠列表
    public function actionDiscountlist()
    {
        $list = CourseDiscount::find()->orderBy("id desc ");
        $pages = new Pagination(['totalCount' => $list->count(), 'pageSize' => '5']);
        $data = $list->offset($pages->offset)->limit($pages->limit)->all();
        return $this->render('discountlist', ['discountlist' => $data, 'pages' => $pages], false, true);
    }

    //优惠编辑
    public function actionDiscountedit()
    {
        $model = new CourseDiscountForm();
        if (!empty($_REQUEST["id"])) {
            $info = CourseDiscount::findOne($_REQUEST["id"]);
            $model->id = $info["id"];
            $model->discount_describe = $info["discount_describe"];
            $model->discount_image = $info["discount_image"];
            $model->discount_pattern = $info["discount_pattern"];
            $model->discount_condition = $info["discount_condition"];
            $model->discount_value = $info["discount_value"];
            $model->start_time = $info["start_time"];
            $model->end_time = $info["end_time"];
        }

        if ($model->load(Yii::$app->request->post()) && $model->submitData()) {
            return $this->goBack();
        } else {
            return $this->render('discountedit', ['model' => $model]);
        }
    }

    //编辑/新增优惠的保存
    public function actionSavediscount()
    {
        $params = $_REQUEST["CourseDiscountForm"];
        if (!empty($_REQUEST["id"])) {
            $discount = CourseDiscount::findOne($_REQUEST["id"]);
        } else {
            $discount = new CourseDiscount();
        }
        $discount->discount_describe = $params["discount_describe"];
        $discount->discount_pattern = $_REQUEST["discount_pattern"];
        $discount->discount_image = $_REQUEST["discount_image"];
        $discount->start_time = $_REQUEST["start_time"];
        $discount->end_time = $_REQUEST["end_time"];
        $discount->discount_condition = $params["discount_condition"];
        $discount->discount_value = $params["discount_value"];
        $discount->save();
        return $this->redirect("discountlist");
    }

    //删除优惠
    public function actionDeldiscount()
    {
        $id = $_REQUEST["id"];
        if (!empty($id)) {
            $cp = CourseDiscount::findOne($id);
            $cp->delete();
        }
        return $this->redirect("discountlist");
    }
}