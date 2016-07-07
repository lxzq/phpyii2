<?php
/**
 * Created by PhpStorm.
 * User: made
 * Date: 2016-5-10
 * Time: 15:24:33
 */

namespace app\modules\controllers;

use app\models\CourseManagerPrice;
use app\models\CourseManagerPriceForm;
use Yii;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\web\Controller;

class CourseManagerController extends Controller
{
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
                        'actions' => ['price-manager-list', 'price-manager-edit', 'save-manager-price', 'del-manager-price'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    //价格管理
    public function actionPriceManagerList()
    {
        $course_id = $_REQUEST["course_id"];
        $pricelist = CourseManagerPrice::find()->where(["course_id" => intval($course_id), "is_delete" => 1]);
        $pages = new Pagination(['totalCount' => $pricelist->count(), 'pageSize' => '8']);
        $data = $pricelist->offset($pages->offset)->limit($pages->limit)->all();
        return $this->render('pricemanagerlist', ['pricelist' => $data, 'course_id' => $course_id, 'pages' => $pages]);
    }

    //编辑或添加课次价格页面
    public function actionPriceManagerEdit()
    {
        $model = new CourseManagerPriceForm();
        $course_id = $_REQUEST["course_id"];
        if (!empty($_REQUEST["id"])) {
            $course = CourseManagerPrice::findOne($_REQUEST["id"]);
            $model->id = $course["id"];
            $model->course_id = $course["course_id"];
            $model->course_nums = $course["course_nums"];
            $model->week_nums = $course["week_nums"];
            $model->org_price = $course["org_price"];
            $model->discount_price = $course["discount_price"];
            $model->is_delete = $course["is_delete"];
        }
        if ($model->load(Yii::$app->request->post()) && $model->submitData()) {
            return $this->goBack();
        } else {
            return $this->render('pricemanageredit', ['model' => $model, 'course_id' => $course_id, 'course_id' => $course_id]);
        }
    }


    //编辑/新增课次价格的保存
    public function actionSaveManagerPrice()
    {
        $params = $_REQUEST["CourseManagerPriceForm"];
        if (!empty($_REQUEST["id"])) {
            $price = CourseManagerPrice::findOne($_REQUEST["id"]);
        } else {
            $price = new CourseManagerPrice();
        }
        $price->course_nums = $params["course_nums"];
        $price->week_nums = $params["week_nums"];
        $price->course_id = $_REQUEST["course_id"];
        $price->org_price = $params["org_price"];
        $price->discount_price = $params["discount_price"];
        $price->is_delete = 1;
        $price->save();
        return $this->redirect("/admin/course-manager/price-manager-list?course_id=" . $_REQUEST["course_id"]);
    }


    //删除课次价格记录
    public function actionDelManagerPrice()
    {
        $id = $_REQUEST["id"];
        $course_id = $_REQUEST["course_id"];
        if (!empty($id)) {
            /*$cp = CourseManagerPrice::findOne($id);
            $cp->is_delete = 0;
            $cp->save();*/
            CourseManagerPrice::findOne($id)->delete();
        }
        return $this->redirect("/admin/course-manager/price-manager-list?course_id=" . $course_id);
    }

}