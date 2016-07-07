<?php

namespace app\modules\controllers;
use app\models\PublicList;
use app\models\UserGroup;
use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\data\Pagination;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use app\modules\weixin\BaseController;
class UserGroupController extends BaseController
{
    public $defaultAction = 'list';
    public $enableCsrfValidation = false;//yii默认表单csrf验证，如果post不带改参数会报错！
    public $layout  = 'layout';
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
                        'actions' => ['list', 'add', 'change', 'delete','get-weixin-group'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }
    public function actionList()
    {
        $model=new UserGroup();
        $token=(new PublicList())->get_token();
        $info=$model->find()->where(['is_del'=>0,'token'=>$token]);
        $pages = new Pagination(['totalCount' => $info->count(), 'pageSize' => '8']);
        $data = $info->offset($pages->offset)->limit($pages->limit)->orderBy("sort")->all();
        return $this->render('list',['list'=>$data,'pages' => $pages]);
    }
    /**
     * 增加分组
     */
    public  function  actionAdd()
    {
        $model=new UserGroup();
        if(Yii::$app->request->post() && $model->validate())
        {
            $post=Yii::$app->request->post();
            $id=$post['id'];
            if(!empty($id))
            {
                $info=$model->findOne($id);
                $info->group_name=$post['UserGroup']['group_name'];
                $info->description=$post['UserGroup']['description'];
                $info->sort=$post['UserGroup']['sort'];
                $info->update_time=time();
                $data=$this->change_weixin_group($info->weixin_group_id,$post['UserGroup']['group_name'],$info->token);
                if($data['errcode']==0){
                    $info->save();
                }
                return $this->redirect(['list']);

            }else{
                $token=(new PublicList())->get_token();
                $model->group_name=$post['UserGroup']['group_name'];
                $model->description=$post['UserGroup']['description'];
                $model->sort=$post['UserGroup']['sort'];
                $model->add_time=time();
                $model->update_time=time();
                $model->token=$token;
                $data=$this->add_weixin_group($post['UserGroup']['group_name'],$token);
                if(!empty($data['group']['id']))
                {
                    $model->weixin_group_id=$data['group']['id'];
                    if($model->save())
                    {
                        return $this->redirect(['user-group/list']);
                    }else{
                        return $this->render("add",['model'=>$model]);
                    }
                }else{
                    return $this->render("add",['model'=>$model]);
                }

            }


        }else{
            return $this->render('add',['model'=>$model]);
        }

    }
    /**
     * 修改分组信息
     */
    public function actionChange()
    {
        $id=$_REQUEST['id'];
        $model=new UserGroup();
        $info=$model->findOne($id);
        return $this->render('add',['model'=>$info]);
    }
    /**
     * 删除分组信息
     */
    public function actionDelete()
    {

        if(Yii::$app->request->post())
        {
            $ids=Yii::$app->request->post("ids");
            if(!empty($ids))
            {
                $model=new UserGroup();
                $flag=$model->updateAll(['is_del'=>1],['in','id',$ids]);
                if($flag){
                    $data['info']="删除成功";
                    $data['status']="1";
                    $data['url']="/admin/user-group/list";
                    $info= json_encode($data, JSON_UNESCAPED_UNICODE);
                    echo $info;
                }

            }

        }elseif(Yii::$app->request->isGet){
            $id=Yii::$app->request->get('id');
            $model=UserGroup::findOne($id);
            $token=(new PublicList())->get_token();
            $data=$this->delete_weixin_group($model->weixin_group_id,$token);
            if($data['errcode']==0){
                $model->is_del=1;
                $model->save();
            }
            return $this->redirect("user-group/list");
        }else{
            $data['info']="请选择要操作的数据";
            $data['status']="0";
           $info= json_encode($data, JSON_UNESCAPED_UNICODE);
            echo $info;
        }

    }
    /**
     * 添加微信用户分组
     */
    public function add_weixin_group($name,$token)
    {
        $access_token=$this->get_access_token($token);
        $url='https://api.weixin.qq.com/cgi-bin/groups/create?access_token='.$access_token;
        $str='{"group":{"name":"'.$name.'"}}';
        $output=http($url,$str,'POST');
        $data=json_decode($output,true);
        return $data;
    }
    /**
     *修改微信用户分组
     */
    public function change_weixin_group($id,$name,$token)
    {

        $access_token=$this->get_access_token($token);
        $url='https://api.weixin.qq.com/cgi-bin/groups/update?access_token='.$access_token;
        $str='{"group":{"id":'.$id.',"name":"'.$name.'"}}';
        $output=http($url,$str,'POST');
        $data=json_decode($output,true);
        return $data;
    }
    /**
     *删除微信用户分组
     */
    public function delete_weixin_group($id,$token)
    {

        $access_token=$this->get_access_token($token);
        $url='https://api.weixin.qq.com/cgi-bin/groups/delete?access_token='.$access_token;
        $str='{"group":{"id":'.$id.'}}';
        $output=http($url,$str,'POST');
        $data=json_decode($output,true);
        return $data;
    }
    /**
     * 拉取微信用户分组
     */
    public function actionGetWeixinGroup()
    {
        $token=(new PublicList())->get_token();
        $params['access_token']=$this->get_access_token($token);
        $url='https://api.weixin.qq.com/cgi-bin/groups/get';
        $output=http($url,$params);
        $data=json_decode($output,true);
        if(!empty($data['errcode']) && $data['errcode']!=0){
            $info['status']=0;
            $info['info']=$data['errmsg'];
        }else{
            foreach($data['groups'] as $v){
                $model=new UserGroup();
                $flag=$model->find()->where(['weixin_group_id'=>$v['id']])->one();
                if(empty($flag)){
                    $model->weixin_group_id=$v['id'];
                    $model->token=$token;
                    $model->group_name=$v['name'];
                    $model->user_count=$v['count'];
                    $model->sort=$v['id'];
                    $model->add_time=time();
                    $model->update_time=time();
                    $model->is_del=0;
                    $model->save();
                }
            }
            $info['status']=1;
            $info['info']='拉取微信用户分组成功！';
        }
        echo json_encode($info);
    }

}
