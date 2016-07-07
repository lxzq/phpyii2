<?php

namespace app\modules\controllers;
use app\models\WeixinNotice;
use app\models\PushTask;
use app\models\PushTemplate;
use app\models\PushTrigger;
use app\models\Role;
use app\models\ShopInfo;
use app\models\Source;
use app\models\User;
use yii;
use yii\filters\AccessControl;
use yii\data\Pagination;
use yii\db\Query;
use app\modules\push\PushBaseController;
class PushController extends PushBaseController
{
    public $enableCsrfValidation = false;//yii默认表单csrf验证，如果post不带改参数会报错！
    public $layout  = 'layout';
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index','scope','scope-add','scope-delete','task','task-add','task-delete','trigger','trigger-add','trigger-delete','select-data','task-select-user','get-template-content'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }
    public function actionIndex()
    {
        return $this->render('index');
    }
    /**
     * 推送触发器列表
     */
    public function actionTrigger()
    {

        $info=PushTrigger::find()->select(['id','trigger_name','source_id'])->with('sources');
        $pages=new Pagination(['totalCount'=>$info->count(),'pageSize'=>10]);
        $info=$info->offset($pages->offset)->limit($pages->limit)->all();

        return $this->render('trigger',['info'=>$info,'pages'=>$pages]);
    }
    /**
     * 添加推送触发器
     */
    public function actionTriggerAdd()
    {
        $request=Yii::$app->request;
        if($request->isPost){
            foreach($_REQUEST['group']['rule'] as $ke=> $vo){
            }
        }
        $model=new PushTrigger();
        $source_list=Source::find()->select(['SourceID','SourceName','SourceParentID'])->all();
        foreach($source_list as $va){
            if($va['SourceParentID']==0){
                $plist[]=$va;
            }else{
                $clist[]=$va;
            }
        }
        return $this->render('trigger-add',['model'=>$model,'plist'=>$plist,'clist'=>$clist]);
    }
    /**
     * 推送列表
     */
    public function actionTask()
    {
        $info=WeixinNotice::find()->select(['add_time','send_time','change_time','id','template_id'])->with('template');
        $pages=new Pagination(['totalCount'=>$info->count(),'pageSize'=>10]);
        $info=$info->offset($pages->offset)->limit($pages->limit)->all();
        return $this->render('task',['info'=>$info,'pages'=>$pages]);
    }
    /**
     * 添加推送
     */
    public function actionTaskAdd()
    {

        $request=Yii::$app->request;
        if($request->isPost){
            $transaction = Yii::$app->db->beginTransaction();
            if($request->post('id')){
                $model=WeixinNotice::findOne($request->post('id'));
            }else{
                $model=new WeixinNotice();
            }
            $model->template_id=$request->post('template_id');
            if($request->post('send_scope')['type']==2){
               $_REQUEST['send_scope']['user_ids']=array_keys($request->post('user'));
            }
            $model->send_scope=json_encode($_REQUEST['send_scope']);
            $model->content=json_encode($request->post('content'));
            $model->url=$request->post('url');
            $model->send_time=strtotime($request->post('send_time'));
            $model->add_time=time();
            $model->change_time=time();
            if($model->save()){
                $notice_id=$model->id;
                if($request->post('id')){
                   $status=PushTask::deleteAll(['master'=>2,'masterValue'=>$notice_id]);
                    if(!$status){
                        $transaction->rollBack();
                        Yii::warning(Yii::$app->errorHandler->exception);
                        exit;
                    }
                }
                if($request->post('send_scope')['type']==2){
                    $users=$request->post('user');
                }elseif($request->post('send_scope')['type']==1){
                    $users=(new Query())->select(['openid','user.id'])->from('yii_role_user as role')->rightJoin('user','role.userId=user.id')->where(['roleId'=>$request->post('send_scope')['role_id']])->all();
                }
                $flag=true;
                foreach($users as $va){
                    $str='{"touser":"'.$va['openid'].'",
		                   "template_id":"'.$request->post('weixin_template_id').'",
                           "url":"'.$request->post('url').'",
                            "data":{';
                    foreach($request->post('content') as $k=>$v){
                        $str.='"'.$k.'": {
                                   "value":"'.$v['content'].'",
                                   "color":"#173177"
                           },';
                    }
                    $str=rtrim($str,',');
                    $str.='}}';
                    $judge=$this->add_push_task($va['id'],$str,$notice_id,$request->post('send_time'),'0')
                    if(!$judge){
                        $flag=false;
                    }
                }
                if($flag){
                    $transaction->commit();
                    return $this->redirect(['task']);
                }else{
                    $transaction->rollBack();
                    Yii::warning(Yii::$app->errorHandler->exception);
                }
            }else{
                $transaction->rollBack();
            }

        }else{
            if(!empty($request->get('setting_id'))){
                $model=WeixinNotice::find()->where(['id'=>$request->get('setting_id')])->asArray()->one();
                $model['content']=json_decode($model['content'],true);
                $model['send_scope']=json_decode($model['send_scope'],true);
                if($model['send_scope']['type']==2){
                    $model['send_scope']['users']=User::find()->select(['id','nickname','userface','phone','openid'])->where(['id'=>$model['send_scope']['user_ids']])->asArray()->all();
                }
            }else{
                $model=new WeixinNotice();
            }
            $template_list=PushTemplate::find()->select(['id','title'])->where(['flag'=>'1'])->all();
            $role_list=Role::find()->select(['id','role_name'])->all();
            return $this->render('task-add',['model'=>$model,'template_list'=>$template_list,'role_list'=>$role_list]);
        }
    }
    /**
     * 删除推送
     */
    public function actionTaskDelete()
    {
        $id=$_REQUEST['id'];
        $transaction=Yii::$app->db->beginTransaction();
        $push=WeixinNotice::findOne($id);
        if($push->delete()){
            $flag=PushTask::deleteAll(['master'=>2,'masterValue'=>$id]);
            if($flag){
                $transaction->commit();
                $info['status']=1;
                $info['info']="删除成功！";
            }else{
                $transaction->rollBack();
                $info['status']=0;
                $info['info']="删除失败，请重试！";
            }
        }else{
            $transaction->rollBack();
            $info['status']=0;
            $info['info']="删除失败，请重试！";
        }
        echo json_encode($info);
    }
    /**
     * 选择推送范围
     */
    public function actionTaskSelectUser()
    {

        $list=User::find()->select(['id','nickname','userface','phone','openid'])->where(['!=','password','null'])->all();
        $shops=ShopInfo::find()->select(['id','name'])->all();
        return $this->render('task-select-user',['list'=>$list,'shops'=>$shops]);

    }
    /**
     * 模板内容
     */
    public function actionGetTemplateContent()
    {
        $id=$_REQUEST['id'];
        $info=PushTemplate::find()->select(['template_id','content','template_data'])->where(['id'=>$id])->asArray()->one();
        $info['content']=json_decode($info['content'],true);
        preg_match_all('/}}.*[\s](.*){{/Us',$info['template_data'],$data);
        array_pop($data[1]);
        if(count($info['content'])-count($data[1])==2){
            $i=0;
            foreach($info['content'] as $key=> &$va){
                if($i-1>=0 && $i<count($info['content'])-1){
                    $va=$data[1][$i-1];
                }
                $i++;
            }
        }else{
            $i=0;
            foreach($info['content'] as $key=> &$va){
                if($i<count($info['content'])-1){
                    $va=$data[1][$i-1];
                }
                $i++;
            }
        }
        echo json_encode($info);

    }
    /**
     * 选择推送条件
     */
    public function actionSelectData()
    {
       $request=Yii::$app->request;
        $field=$request->get('field');
        $type=$request->get('type');
        if($type=='in'){
            $type=='check';
        }else{
            $type='option';
        }
        if($field=='user'){
            $list=User::find()->select(['id','nickname as name'])->where(['!=','password','NULl'])->asArray()->all();
        }elseif($field=='role'){
            $list=Role::find()->select(['id','role_name as name'])->asArray()->all();
        }elseif($field=='dept'){
            $list=(new Query())->select(['id','dept_name as name'])->from('sys_department')->all();
        }elseif($field=='shop'){
            $list=ShopInfo::find()->select(['id','name'])->asArray()->all();
        }
        return $this->render('select-data',['list'=>$list,'type'=>$type]);
    }

}
