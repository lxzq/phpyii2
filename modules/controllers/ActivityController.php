<?php
namespace app\modules\controllers;


use app\models\ActivityComment;
use app\models\ActivityImages;
use app\models\ActivityShare;
use app\models\ChildInfo;
use app\models\CommentImages;
use app\models\SsActivityUser;
use app\models\SsActivityUserFriend;
use Yii;
use yii\data\Pagination;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\web\Controller;
use app\models\SsActivity;
use app\models\Activity;

/**
 * Site controller
 */
class ActivityController extends Controller
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
                        'actions' => ['activitylist', 'activityedit', 'save', 'del', 'sort',
                            'setlunbo','activityuser','activitycomment','del-comment',
                        'activity-user-friend'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    //活动列表
    public function actionActivitylist()
    {
        $where = [];
        if(!empty($_REQUEST["name"])){
            $where = ['like','activity_name',$_REQUEST["name"]];
        }
        $activityInfo = SsActivity::find()->where($where)->orderBy("activity_id desc ");
        $pages = new Pagination(['totalCount' => $activityInfo->count(), 'pageSize' => '8']);
        $data = $activityInfo->offset($pages->offset)->limit($pages->limit)->all();
        return $this->render('activitylist', ['activityInfo' => $data, 'pages' => $pages], false, true);
    }

    /**
     * 获取报名人员列表
     */
    public function actionActivityuser(){
        $activityId = $_REQUEST["activityId"];
        $activityInfo = SsActivityUser::find()->select(['child_id','add_time','id'])->where(['=','activity_id',$activityId])->orderBy("id desc ");
        $pages = new Pagination(['totalCount' => $activityInfo->count(), 'pageSize' => '8']);
        $data = $activityInfo->offset($pages->offset)->limit($pages->limit)->asArray()->all();
        foreach($data as &$v){
            $v['child']=ChildInfo::find()->select(['nick_name','face','phone','sex'])->where(['in','id',explode(',',$v['child_id'])])->asArray()->all();
        }
        $pages->params = ['activityId' => $activityId];
        return $this->render('activityuser', ['activityInfo' => $data, 'pages' => $pages], false, true);
    }

    /**
     * 查看报名人的邀请朋友
     */
    public function actionActivityUserFriend(){
        $activityUser = $_REQUEST['uId'];
        $activityInfo = SsActivityUserFriend::find()->where(['activity_user_id'=>$activityUser])->orderBy('id desc');
        $pages = new Pagination(['totalCount' => $activityInfo->count(), 'pageSize' => '100']);
        $data = $activityInfo->offset($pages->offset)->limit($pages->limit)->all();
        $pages->params = ['uId' => $activityUser];
        return $this->render('user-friend', ['activityInfo' => $data, 'pages' => $pages], false, true);
    }

    /**
     * 获取评论列表
     */
    public function actionActivitycomment(){
        $activityId = $_REQUEST["activityId"];
        $activityInfo = (new Query())->select(['nickname','face','phone','c.add_time','content','c.id','c.status'])->from('ss_activity_comment as c')->rightJoin('ss_activity_user as u','c.open_id=u.open_id')->where(['c.activity_id'=>$activityId])->orderBy("c.add_time desc ");
        $pages = new Pagination(['totalCount' => $activityInfo->count(), 'pageSize' => '10']);
        $data = $activityInfo->offset($pages->offset)->limit($pages->limit)->all();
        foreach($data as &$va){
            $va['images']=CommentImages::find()->select(['image'])->where(['comment_id'=>$va['id']])->asArray()->all();
            $va['add_time']=\Yii::$app->formatter->asDatetime($va['add_time']);
        }
        $pages->params = ['activityId' => $activityId];
        return $this->render('activitycomment', ['activityInfo' => $data, 'pages' => $pages], false, true);
    }
    /**
     * @return 删除评论内容
     */
    public function actionDelComment()
    {
        $id=$_REQUEST['id'];
        $transaction=Yii::$app->db->beginTransaction();
        $model=ActivityComment::findOne($id);
        $model->status=2;
        $flag=$model->save();
        if($flag)
        {
            $activity=SsActivity::findOne($model->activity_id);
            $activity->activity_comment-=1;
            $flag=$activity->save();
        }
        if($flag){
            $info['status']=1;
            $transaction->commit();
        }else{
            $info['status']=0;
            $info['info']='删除失败，请重试！';
            $transaction->rollBack();
        }
        echo json_encode($info);
    }
    //编辑或添加活动页面
    public function actionActivityedit()
    {
        $params = $_REQUEST;
        $model = new Activity();
        $share=new ActivityShare();
        if (!empty($params["activity_id"])) {
            $activity = SsActivity::findOne($params["activity_id"]);
            $images=ActivityImages::find()->where(['activity_id'=>$params["activity_id"]])->asArray()->all();
            $share=ActivityShare::find()->where(['activity_id'=>$params["activity_id"]])->one();
            $model->activity_name = $activity["activity_name"];
            $model->activity_centent = $activity["activity_centent"];
            $model->intro = $activity["intro"];
           // $model->activity_createtime = $activity["activity_createtime"];
            $model->activity_image = $activity["activity_image"];
            $model->activity_starttime = $activity["activity_starttime"];
            $model->activity_endtime = $activity["activity_endtime"];
            $model->activity_id = $activity["activity_id"];
            $model->activity_address = $activity["activity_address"];
            $model->activity_number = $activity["activity_number"];
            $model->activity_theme = $activity["activity_theme"];
            $model->activity_host = $activity["activity_host"];
            $model->activity_tel = $activity["activity_tel"];
        }
        if ($model->load(Yii::$app->request->post()) && $model->submitData()) {
            return $this->goBack();
        } else {
            return $this->render('activityedit', [
                'model' => $model,'images'=>empty($images)?'':$images,'share'=>$share
            ]);
        }
    }

    //编辑或添加活动的保存
    public function actionSave()
    {
        $transaction=\Yii::$app->db->beginTransaction();
        $flag=true;
        $params = $_REQUEST["Activity"];
        if (!empty($_REQUEST["activity_id"])) {
            $activityInfo = SsActivity::findOne($_REQUEST["activity_id"]);
            $share=ActivityShare::find()->where(['activity_id'=>$_REQUEST["activity_id"]])->one();
        } else {
            $activityInfo = new SsActivity();
            $activityInfo->activity_createtime = time();
        }
        $activityInfo->activity_name = $params["activity_name"];
        $activityInfo->intro = $params["intro"];
        $activityInfo->activity_centent = $_REQUEST["activity_centent"];
        $activityInfo->activity_starttime =strtotime($_REQUEST["activity_starttime"]) ;
        $activityInfo->activity_endtime = strtotime($_REQUEST["activity_endtime"]);
        if (!empty($_REQUEST["activity_image"])) {
            $activityInfo->activity_image = $_REQUEST["activity_image"];
        }
        $activityInfo->activity_address = $params["activity_address"];
        $activityInfo->activity_theme =  $params["activity_theme"];
        $activityInfo->activity_number = $params["activity_number"];
        $activityInfo->activity_host = $params["activity_host"];
        $activityInfo->activity_tel = $params["activity_tel"];
        if($activityInfo->save()){
            $activity_id=$activityInfo->activity_id;
            if(!empty($_REQUEST['share']))
            {
                if(empty($share)){
                    $share=new ActivityShare();
                }
                $share->activity_id=$activity_id;
                $share->title=$_REQUEST['share']['title'];
                $share->link=$_REQUEST['share']['link'];
                $share->image_url=$_REQUEST['share']['image'];
                $share->description=$_REQUEST['share']['description'];
                $flag=$share->save();
            }
            if (!empty($_REQUEST["activity_id"])) {
                $judge=ActivityImages::find()->where(['activity_id'=>$activity_id])->all();
                if(!empty($judge) && $flag){
                    $flag=ActivityImages::deleteAll(['activity_id'=>$activity_id]);
                }
            }
            if($flag && !empty($_REQUEST['images'])){
                foreach($_REQUEST['images'] as $va){
                    $images=new ActivityImages();
                    $images->activity_id=$activity_id;
                    $images->activity_image=$va;
                    $images->save();
                }
            }
        }else{
            $flag=false;
        }
        if($flag){
            $transaction->commit();
        }else{
            $transaction->rollback();
        }
        return $this->redirect("/admin/activity/activitylist");
    }

    //删除记录
    public function actionDel()
    {
        $id = $_REQUEST["activity_id"];
        if (!empty($id)) {
            SsActivity::findOne($id)->delete();
        }
        return $this->redirect("/admin/activity/activitylist");
    }

    //设置排序
    public function actionSort()
    {
        $id = $_REQUEST["activity_id"];
        $sort = $_REQUEST["sort"];
        if (!empty($id)) {
            $activityInfo = SsActivity::findOne($_REQUEST["activity_id"]);
            $activityInfo->activity_sort = $sort;
            $activityInfo->save();
        }

        return $this->redirect("/admin/activity/activitylist");
    }

    //设置是否显示为轮播 0否 1是
    public function actionSetlunbo()
    {
        $id = $_REQUEST["activity_id"];
        $lunbo = $_REQUEST["lunbo"];
        if (!empty($id)) {
            $activityInfo = SsActivity::findOne($_REQUEST["activity_id"]);
            $activityInfo->lunbo = $lunbo;
            $activityInfo->save();
        }
        return $this->redirect("/admin/activity/activitylist");
    }
}
