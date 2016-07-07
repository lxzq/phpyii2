<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016-04-07
 * Time: 14:15
 */

namespace app\modules\controllers;


use app\models\News;
use app\models\NewsForm;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\data\Pagination;
use Yii;

class NewsController extends Controller
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
                        'actions' => ['list','add','save','del'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * 新闻管理列表
     */
    public function actionList(){
        $sql = [];
        if (!empty($_REQUEST["title"])) {
            $sql = ['like','title', $_REQUEST["title"]];
        }
        $list = News::find()->where($sql)->with('user')->orderBy("id desc ");
        $pages = new Pagination(['totalCount' => $list->count(), 'pageSize' => '8']);
        $data = $list->offset($pages->offset)->limit($pages->limit)->all();
        return $this->render('list', [
            'list' => $data,
            'pages' => $pages
        ]);
    }

    /**
     * 添加新闻
     */
    public function actionAdd(){
        $model = new NewsForm();
        if(!empty($_REQUEST["id"])){
            $data = News::findOne($_REQUEST["id"]);
            $model->id = $data->id;
            $model->image = $data->image;
            $model->title = $data->title;
            $model->content = $data->content;
            $model->details = $data->details;
        }
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            return $this->goBack();
        } else {
            return $this->render('add', [
                'model' => $model
            ]);
        }
    }

    /**
     * 保存新闻
     */
    public function actionSave(){
        $params = $_REQUEST["NewsForm"];
        if (!empty($_REQUEST["id"])) {
            $data = News::findOne($_REQUEST["id"]);
        }else{
            $data = new News();
            $data->add_time = date('Y-m-d', time());
        }
        $userId = Yii::$app->user->identity->id;
        $data->title = $params["title"];
        $data->image = $_REQUEST["image"];
        $data->content = $params["content"];
        $data->details = $_REQUEST["details"];
        $data->add_user = $userId;
        $data->save();
        return $this->redirect("/admin/news/list");
    }

    /**
     * 删除新闻
     */
    public function actionDel(){
        $data = News::findOne($_REQUEST["id"]);
        $data->delete();
        return $this->redirect("/admin/news/list");
    }

}