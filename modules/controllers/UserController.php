<?php
/**
 * Created by PhpStorm.
 * User: Aimee
 * Date: 16/3/24
 * Time: 下午5:57
 */

namespace app\modules\controllers;
use app\models\User;
use app\models\Role;
use app\models\Menu;
use app\models\WeixinUser;
use Yii;
use app\modules\weixin\BaseController;
use yii\filters\AccessControl;
use yii\data\Pagination;
use app\models\UserGroup;
use app\models\AppInfo;
use app\models\PublicList;

class UserController extends BaseController{
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
                        'actions' => ['login','captcha'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['index','change-group','set-remark','get-remark','role','menu','add-menu','get-weixin-user','change-user-group','list'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }


    /**
     * @return string用户列表
     */
    public function actionIndex()
    {
        $role_info=Role::find()->all();

        $list = User::find()->where(['is_del'=>1]);
        $pages = new Pagination(['totalCount' => $list->count(), 'pageSize' => '8']);
        $data = $list->offset($pages->offset)->limit($pages->limit)->all();
        return $this->render('index', ['list' => $data, 'pages' => $pages,"role"=>$role_info]);
    }

    /**
     * @return string用户列表
     */
    public function actionList()
    {

        $token=(new PublicList())->get_token();
        $list = WeixinUser::find()->where(['token'=>$token]);
        $pages = new Pagination(['totalCount' => $list->count(), 'pageSize' => '8']);
        $data = $list->offset($pages->offset)->limit($pages->limit)->all();
        $group=new UserGroup();
        $group_list=$group->get_group_list(2);
        return $this->render('lists', ['list' => $data, 'pages' => $pages,'group_list'=>$group_list]);
    }
    /**
     * 修改用户分组
     */
    public function actionChangeGroup()
    {
        $request = Yii::$app->request;
        if($request->isPost)
        {
            $ids=$request->post('ids');
            $group_id=$request->post('group_id');
            $openid_list=WeixinUser::find()->select(["openid"])->where(['in','id',$ids])->all();
            $group=UserGroup::find()->select(['token'])->where(['weixin_group_id'=>$group_id])->asArray()->one();
            $str='';
            foreach($openid_list as $v)
            {
                $str.='"'.$v['openid'].'",';
            }
            $str=rtrim($str,',');
            $data=$this->change_user_group($group_id,$str,$group['token']);
            if($data['errcode']==0){
                WeixinUser::updateAll(['weixin_group_id'=>$group_id],['in','id',$ids]);
                $params['access_token']=$this->get_access_token($group['token']);
                $url='https://api.weixin.qq.com/cgi-bin/groups/get';
                $output=http($url,$params);
                $datas=json_decode($output,true);
                if(!empty($datas['groups'])){
                    foreach($datas['groups'] as $v){
                        $model=new UserGroup();
                        $flag=$model->find()->where(['weixin_group_id'=>$v['id']])->one();
                        $flag->user_count=$v['count'];
                        $flag->save();
                    }
                }
                $flag=1;
            }else{
                $flag=0;
            }
            echo $flag;

        }
    }
    /**
     * 获取用户备注
     */
    public function actionGetRemark(){
        $request=Yii::$app->request;
        if($request->isPost)
        {
            $user_id=$request->post('uid');
            $info=WeixinUser::find()->select(['remark'])->where('id=:id',[':id'=>$user_id])->one();
            echo $info['remark'];
        }
    }
    /**
     * 修改用户备注
     */
    public function actionSetRemark()
    {
        $request=Yii::$app->request;
        if($request->isPost)
        {
            $user_id=$request->post('uid');
            $remark=$request->post('remark');
            if(!empty($remark)){
                $info=WeixinUser::findOne($user_id);
                $info->remark=$remark;
                $info->save();
            }

        }
    }

    /**
    * 角色管理
    */
    public function actionRole()
    {
        $strWhere = [];
        if (!empty($_REQUEST["role_name"])) {
            $strWhere = ['like', 'role_name', $_REQUEST["role_name"]];
        }
        $list = Role::find()->where($strWhere);
        $pages = new Pagination(['totalCount' => $list->count(), 'pageSize' => '8']);
        $data = $list->offset($pages->offset)->limit($pages->limit)->all();
        return $this->render('role', ['list' => $data, 'pages' => $pages]);
    }



    /**
     * 获取用户信息
     */
    public function actionGetWeixinUser()
    {
        $token=(new PublicList())->get_token();
        $list=$this->get_user_list();
        if(!empty($list['errcode']) && $list['errcode']!=0){
            $data['status']=0;
            $data['info']=$list['errmsg'];
        }else{
            foreach($list['data']['openid'] as $va){
                if(!empty($va))
                {
                    $flag=WeixinUser::find()->where(['openid'=>$va])->one();
                    if(!$flag){
                        $info=$this->getUserInfo($va);
                        $this->write_user($info,$token);
                    }
                }
            }
            $data['status']='1';
            $data['info']="拉取微信用户信息成功！";
        }
        echo json_encode($data);
    }
    /**
     *修改微信用户分组
     */
    public function change_user_group($id,$openid,$token)
    {

        $access_token=$this->get_access_token($token);
        if(strpos($openid,',')){
            $url='https://api.weixin.qq.com/cgi-bin/groups/members/batchupdate?access_token='.$access_token;
            $str='{"openid_list":['.$openid.'],"to_groupid":'.$id.'}';
        }else{
            $url='https://api.weixin.qq.com/cgi-bin/groups/members/update?access_token='.$access_token;
            $str='{"openid":'.$openid.',"to_groupid":'.$id.'}';
        }
        $output=http($url,$str,'POST');
        $data=json_decode($output,true);
        return $data;
    }

}