<?php
namespace wxmanage\controllers;

use Yii;
use yii\data\Pagination;
use yii\web\Controller;
use common\models\SsActivity;
use models\models\Activity;

/**
 * Site controller
 */
class ActivityController extends Controller
{

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

    //活动列表
    public function actionActivitylist()
    {
        $params = $_REQUEST;
        $activityInfo = SsActivity::find()->orderBy("activity_id desc ");
        if (!empty($params["sorttype"])) {
            if ($params["sorttype"] == 1) {
                $activityInfo = $activityInfo->orderBy('activity_sort ASC');
            } elseif ($params["sorttype"] == 2) {
                $activityInfo = $activityInfo->orderBy('activity_sort DESC');
            } elseif ($params["sorttype"] == 3) {
                $activityInfo = $activityInfo->orderBy('activity_starttime ASC');
            } elseif ($params["sorttype"] == 4) {
                $activityInfo = $activityInfo->orderBy('activity_starttime DESC');
            } elseif ($params["sorttype"] == 5) {
                $activityInfo = $activityInfo->orderBy('activity_endtime ASC');
            } elseif ($params["sorttype"] == 6) {
                $activityInfo = $activityInfo->orderBy('activity_endtime DESC');
            }
        }
        $pages = new Pagination(['totalCount' => $activityInfo->count(), 'pageSize' => '5']);
        $data = $activityInfo->offset($pages->offset)->limit($pages->limit)->all();
        return $this->render('activitylist', ['activityInfo' => $data, 'pages' => $pages], false, true);
    }

    //编辑或添加活动页面
    public function actionActivityedit()
    {
        $params = $_REQUEST;
        $model = new Activity();
        if (!empty($params["activity_id"])) {
            $activity = SsActivity::findOne($params["activity_id"]);
            $model->activity_name = $activity["activity_name"];
            $model->activity_centent = $activity["activity_centent"];
            $model->activity_createtime = $activity["activity_createtime"];
            $model->activity_image = $activity["activity_image"];
            $model->activity_starttime = $activity["activity_starttime"];
            $model->activity_endtime = $activity["activity_endtime"];
            $model->activity_id = $activity["activity_id"];
        }
        if ($model->load(Yii::$app->request->post()) && $model->submitData()) {
            return $this->goBack();
        } else {
            return $this->render('activityedit', [
                'model' => $model,
            ]);
        }
        return $this->render('activityedit');
    }

    //编辑或添加活动的保存
    public function actionSave()
    {
        $params = $_REQUEST["Activity"];
        if (!empty($_REQUEST["activity_id"])) {
            $activityInfo = SsActivity::findOne($_REQUEST["activity_id"]);
        } else {
            $activityInfo = new SsActivity();
            $activityInfo->activity_createtime = date('Y-m-d H:i:s',time());
        }
        $activityInfo->activity_name = $params["activity_name"];
        $activityInfo->activity_centent = $params["activity_centent"];
        $activityInfo->activity_starttime = $params["activity_starttime"];
        $activityInfo->activity_endtime = $params["activity_endtime"];
        if (!empty($_REQUEST["activity_image"])) {
            $activityInfo->activity_image = $_REQUEST["activity_image"];
        }
        $activityInfo->save();
        return $this->redirect("/activity/activitylist");
    }

    //删除记录
    public function actionDel()
    {
        $id = $_REQUEST["activity_id"];
        if (!empty($id)) {
            SsActivity::findOne($id)->delete();
        }
        return $this->redirect("/activity/activitylist");
    }

    public function actionSort()
    {
        $id = $_REQUEST["activity_id"];
        $sort = $_REQUEST["sort"];
        if (!empty($id)) {
            $activityInfo = SsActivity::findOne($_REQUEST["activity_id"]);
        } else {
            $activityInfo = new SsActivity();
        }
        $activityInfo->activity_sort = $sort;
        $activityInfo->save();
        return $this->redirect("/activity/activitylist");
    }
}
