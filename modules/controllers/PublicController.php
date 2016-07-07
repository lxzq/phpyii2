<?php

namespace app\modules\controllers;
use app\models\Picture;
use app\models\PushTemplate;
use app\models\SendMessage;
use app\models\ShopInfo;
use app\models\User;
use app\models\WeixinUser;
use Yii;
use yii\data\Pagination;
use yii\db\Query;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use app\models\PublicList;
use yii\filters\AccessControl;
use app\models\PublicManager;
use yii\web\Session;
use app\models\MenuGroup;
use app\models\UserGroup;
use app\models\WeixinMenu;
use app\models\AutoReply;
use app\modules\weixin\BaseController;

class PublicController extends BaseController
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
                        'actions' => ['login', 'captcha'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['index','step1','step2','step3','delete','config','menu-lists','keywords','add-menu-group','menu','add-menu','change-menu','del-menu-group','delete-menu','get-menu-group','add-keywords','delete-keywords','add-text','add-news','add-images','unkown','set-menu','get-menu','get-template','template','get-column','template-change','template-delete','template-detail','set-manager','select-user','template-add','message','select-users','send-message','preview'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }
    public function actionIndex()
    {
        //$model=new PublicList();
        $user_id=Yii::$app->user->getId();
        $public_ids=PublicManager::find()->select('public_id')->indexBy('public_id')->where('user_id=:user_id',[':user_id'=>$user_id])->all();
        $ids=array_keys($public_ids);
        $data=(new Query())->select(['public_id','count(1) as num'])->from('public_manager')->indexBy('public_id')->where(['in','public_id',$ids])->groupBy('public_id')->all();
        if(!empty($data))
        {

            $info=(new Query())->select(['id','public_name','token','user_id'])->from('public_list')->where(['in','id',$ids])->all();


            foreach($info as $d)
            {

                $d ['num'] = $data[$d ['id']]['num'];
                $d ['is_creator'] = $d ['user_id'] == $user_id ? 1 : 0;
                $list [$d ['is_creator']] [] = $d;

            }
        }else{
            $list=[];
        }
        return $this->render('index',['list'=>$list]);
    }
    /**
     * 设置公众号管理员
     */
    public function actionSetManager()
    {
        if(Yii::$app->request->isPost){
            $public_id=$_REQUEST['public_id'];
            $ids=$_REQUEST['ids'];
            PublicManager::deleteAll(['public_id'=>$public_id,'is_creator'=>0]);
            foreach($ids as $va){
                $model=new PublicManager();
                $model->user_id=$va;
                $model->public_id=$public_id;
                $model->is_creator=0;
                $info=$model->save();
            }
            echo $info;

        }else{
            $public_id=$_REQUEST['id'];
            $list=User::find()->select(['id','nickname','userface','phone'])->where(['!=','password','NULL'])->andWhere(['!=','id',Yii::$app->user->id])->all();
            $users=PublicManager::find()->select(['user_id'])->where(['public_id'=>$public_id,'is_creator'=>0])->indexBy('user_id')->asArray()->all();
            $shops=ShopInfo::find()->select(['id','name'])->all();
            return $this->render('set-manager',['public_id'=>$public_id,'list'=>$list,'shops'=>$shops,'users'=>$users]);
        }

    }
    /**
     * 查询用户
     */
    public function actionSelectUser()
    {
        $shopId=!empty($_REQUEST['shopId'])?$_REQUEST['shopId']:'';
        $nickname=!empty($_REQUEST['nickname'])?$_REQUEST['nickname']:'';
        $phone=!empty($_REQUEST['phone'])?$_REQUEST['phone']:'';
        $public_id=$_REQUEST['public_id'];
        $where=['!=','password','null'];
        $list=User::find()->select(['id','nickname','userface','phone'])->where($where)->andWhere(['!=','id',Yii::$app->user->id]);
        if(!empty($nickname)){
            $list->andWhere(['like','nickname',$nickname]);
        }
        if(!empty($phone)){
            $list->andWhere(['like','phone',$phone]);
        }
        if(!empty($shopId)){
            $list->andWhere(['shopId'=>$shopId]);
        }
        $info['list']=$list->asArray()->all();
        $users=PublicManager::find()->select(['user_id'])->where(['public_id'=>$public_id,'is_creator'=>0])->indexBy('user_id')->asArray()->all();
        $info['users']=array_keys($users);
        echo json_encode($info);

    }
    /**
     * 新增公众号步骤一
     */
    public function actionStep1()
    {

        $model=new PublicList();

        if(Yii::$app->request->isPost)
        {
            $request=Yii::$app->request;
            if(!empty($request->post('id')))
            {
                $model=PublicList::findOne($request->post('id'));
                $is_creator=0;
            }else{
                $is_creator=1;
            }
            $model->token=$request->post('public_id');
            $model->user_id=Yii::$app->user->getId();
            $session['token']=$request->post('token');
            $model->type=$request->post('type');
            $model->public_name=$request->post('public_name');
            $model->public_id=$request->post('public_id');
            $model->wechat=$request->post('wechat');
            if($model->save())
            {
                if(empty($request->post('id'))){
                    $manager=new PublicManager();
                    $manager->user_id=$model->user_id;
                    $manager->public_id=$model->id;
                    $manager->is_creator=$is_creator;
                    $manager->save();
                }
                return $this->redirect(['public/step2?public_id='.$model->id]);
            }

        }else{
            if(Yii::$app->request->get('public_id')){
                $id=Yii::$app->request->get('public_id');
                $info=$model->findOne($id);
                if (! empty ( $info ) && $info ['user_id'] != Yii::$app->user->getId()) {
                    Yii::warning("您没有权限操作！");
                }
                $model=$info;
            }else{
                $id='';
            }
            return $this->render("step1",['model'=>$model,'id'=>$id]);
        }

    }
    /**
     * 删除公众号
     */
    public function actionDelete()
    {
        $public=PublicList::findOne(Yii::$app->request->get('public_id'));
        $public->delete();
    }
    /**
     * 新增公众号步骤二
     */
    public function actionStep2()
    {
        $id=Yii::$app->request->get('public_id');
        return $this->render('step2',['id'=>$id]);
    }
    /**
     * @return 新增公众号步骤三
     */
    public function actionStep3()
    {


        $request=Yii::$app->request;
        $id=$request->get('public_id');
        $model=PublicList::findOne($id);
        if (! empty ( $model ) && $model->user_id != Yii::$app->user->getId()) {
            Yii::warning("您没有权限操作！");
        }
        if($request->isPost){
            $model->appid=$request->post('appid');
            $model->secret=$request->post('secret');
            $model->encodingaeskey=$request->post('encodingaeskey');
            $model->save();
            $group=new UserGroup();
            $flag=$group->find()->where(['token'=>$model->token,'weixin_group_id'=>''])->one();
            if(empty($flag)){
                $group->token=$model->token;
                $group->group_name="未分组";
                $group->sort=1;
                $group->add_time=time();
                $group->update_time=time();
                $group->is_del=0;
                $group->save();
            }
            return $this->redirect(['public/index']);
        }else{
            return $this->render("step3",['model'=>$model,'id'=>$id]);
        }

    }
    /**
     * 公众号关注回复设置
     */
    public function actionConfig()
    {
        $session=Yii::$app->session;
        $request=Yii::$app->request;
        if($request->isPost)
        {
            $Public=new PublicList();
            $token=$Public->get_token();
            $conf=$request->post('config');
            $model=PublicList::find()->where('token=:token',[':token'=>$token])->one();
            if($model->public_config)
            {
                $config=json_decode($model->public_config,true);
                if(!empty($config['welcome'])){
                    $config['welcome']=array_merge($config['welcome'],$conf);
                    $model->public_config=json_encode($config);
                }else{
                    $config['welcome']=$conf;
                    $model->public_config=json_encode($config);
                }
            }else{
                $config['welcome']=$conf;
                $model->public_config=json_encode($config);
            }
            if($model->save()){
                $data['status']=1;
                $data['info']="保存成功！";
                echo json_encode($data);
            }else{
                $data['status']=0;
                $data['info']="保存失败，请重试！";
                echo json_encode($data);
            }

        }else{
            if(!empty($request->get('public_id'))){
                $session['public_id']=$request->get('public_id');
            }
            $model=new PublicList();
            $token=$model->get_token();
            $info=$model->find()->select(['public_config'])->where('token=:token',[':token'=>$token])->one();
            if(!empty($info->public_config)){
                $config=json_decode($info->public_config,true);
                if(!empty($config['welcome']))
                {
                    $config=$config['welcome'];
                }else{
                    $config=$info;
                }

            }else{
                $config=$info;
            }
            return $this->render('config',['info'=>$config]);
        }
    }
    /**
     *  菜单组列表
     */
    public function actionMenuLists()
    {

        $token=(new PublicList())->get_token();
        $info=MenuGroup::find()->select(['id','name','group_id'])->where(['token'=>$token])->orderBy('add_time')->all();
        $group_list=(new UserGroup())->get_group_list(2);
        if($info)
        {
            foreach($info as &$va)
            {
                if(!empty($va['group_id']) && strpos($va['group_id'],',')){
                    $group_ids=explode(',',$va['group_id']);
                    $va['group_id']='';
                    foreach($group_ids as $v)
                    {
                        $va['group_id'].= $group_list[$v]['group_name'].'， ';
                    }
                    $va['group_id']=rtrim($va['group_id'],'， ');
                }elseif(!empty($va['group_id']) && !strpos($va['group_id'],',')){
                    $va['group_id']=$group_list[$va['group_id']]['group_name'];
                }

            }
        }
        return $this->render('menu-lists',['list'=>$info,'group_list'=>$group_list]);
    }

    /**
     *  添加菜单组
     */
    public function actionAddMenuGroup()
    {
        $request=Yii::$app->request;
        if($request->isPost)
        {
            $token=(new PublicList())->get_token();
            if($request->post('id'))
            {
                $model=MenuGroup::findOne($request->post('id'));
                $model->group_id=$request->post('group_id');
                $model->name=$request->post('name');
                $model->sort=$request->post('sort');
                $model->change_user_id=Yii::$app->user->getId();
                $model->change_time=time();
                if($model->save())
                {
                    $info['status']=1;
                    $info['info']="添加成功";
                }else{
                    $info['status']=0;
                    $info['info']="添加失败，请重试";
                }
                echo json_encode($info);
            }else{
                $model=new MenuGroup();
                $model->name=$request->post('name');
                $model->group_id=$request->post('group_id');
                $model->sort=$request->post('sort');
                $model->token=$token;
                $model->user_id=Yii::$app->user->getId();
                $model->change_user_id=Yii::$app->user->getId();
                $model->change_time=time();
                $model->add_time=time();
                if($model->save())
                {
                    $info['status']=1;
                    $info['info']="添加成功";
                }else{
                    $info['status']=0;
                    $info['info']="添加失败，请重试";
                }
                echo json_encode($info);
            }
        }

    }

    /**
     * 删除菜单分组
     */
    public function actionDelMenuGroup()
    {
        $model=MenuGroup::findOne(Yii::$app->request->get('menu_group_id'));
        if(!empty($model->weixin_menu_id)){
            $data=$this->delete_weixin_menu(1,$model->weixin_menu_id);
            if(!empty($data['errcode']) && $data['errcode']!=0)
            {
                $info['status']=0;
                $info['info']=$data['errmsg'];
            }else{
                if($model->delete())
                {
                    $info['status']=1;
                    $info['info']="删除成功";
                }else{
                    $info['status']=0;
                    $info['info']="删除失败，请重试";
                }
            }
        }elseif(!empty($model)){
            $data=$this->delete_weixin_menu(2);
            if(!empty($data['errcode']) && $data['errcode']!=0)
            {
                $info['status']=0;
                $info['info']=$data['errmsg'];
            }else{
                if($model->delete())
                {
                    $info['status']=1;
                    $info['info']="删除成功";
                }else{
                    $info['status']=0;
                    $info['info']="删除失败，请重试";
                }
            }
        }
        echo json_encode($info);
    }
    /**
     * 获取菜单组信息
     */
    public function actionGetMenuGroup()
    {
        $reques=Yii::$app->request;
        $id=$reques->post('id');
        $model=MenuGroup::findOne($id);
        $info['id']=$model->id;
        $info['name']=$model->name;
        $info['sort']=$model->sort;
        $info['group_id']=explode(',',$model->group_id);
        echo json_encode($info,true);

    }
    /**
     * 菜单
     */
    public function actionMenu()
    {
        $request=Yii::$app->request;
        $menu_group_id=$request->get('menu_group_id');
        $list=WeixinMenu::find()->where(['menu_group_id'=>$menu_group_id])->orderBy('pid asc, sort asc')->all();
        if($list)
        {
            // 取一级菜单
            foreach ( $list as $k => $vo ) {
                if ($vo ['pid'] == 0)
                {
                    $one_arr [$vo ['id']] = $vo;
                }
            }
            foreach ( $one_arr as $p ) {
                $data [] = $p;
                $two_arr = array ();
                foreach ( $list as $key => $l ) {
                    if ($l ['pid'] == $p ['id'])
                    {

                        $two_arr [] = $l;
                    }
                }
                $data = array_merge ( $data, $two_arr );
             }
            $list=$data;
        }
        return $this->render('menu',['list'=>$list,'menu_group_id'=>$menu_group_id]);
    }
    /**
     * 添加菜单
     */
    public function actionAddMenu()
    {
        $request=Yii::$app->request;
        if($request->isPost)
        {
            $menu_group_id=$request->post('menu_group_id');
            if($request->post('id'))
            {
                $model=WeixinMenu::findOne($request->post('id'));
                $model->title = $request->post('title');
                $model->pid = $request->post('pid');
                $model->type = $request->post('type');
                $model->sort = $request->post('sort');
                $model->keyword = $request->post("keyword");
                $model->url = $request->post("url");
                $model->change_user_id = Yii::$app->user->getId();
                $model->change_time = time();
                $model->save();
                return $this->redirect(['public/menu?menu_group_id='. $model->menu_group_id]);
            }else {
                $model = new WeixinMenu();
                $model->title = $request->post('title');
                $model->menu_group_id = $request->post('menu_group_id');
                $model->pid = $request->post('pid');
                $model->type = $request->post('type');
                $model->sort = $request->post('sort');
                $model->keyword = $request->post("keyword");
                $model->url = $request->post("url");
                $model->user_id = Yii::$app->user->getId();
                $model->add_time = time();
                $model->change_user_id = Yii::$app->user->getId();
                $model->change_time = time();
                $model->save();
                return $this->redirect(['public/menu?menu_group_id='. $menu_group_id]);
            }
        }else{
            if($request->get('menu_id'))
            {
                $model=new WeixinMenu();
                $info=$model->findOne($request->get('menu_id'));
                $parent_list=$model->find()->select(['id','title'])->where(['pid'=>0])->orderBy('pid asc, sort asc')->all();
                return $this->render('add-menu',['model'=>$info,'parent_list'=>$parent_list]);

            }else{
                $model=new WeixinMenu();
                $menu_group_id=$request->get('menu_group_id');
                $parent_list=$model->find()->select(['id','title'])->where(['pid'=>0,'menu_group_id'=>$menu_group_id])->orderBy('pid asc, sort asc')->all();
                return $this->render('add-menu',['model'=>$model,'menu_group_id'=>$menu_group_id,'parent_list'=>$parent_list]);
            }
            
        }
    }
    /**
     * 关键字回复
     */
    public function actionKeywords()
    {
        $request=Yii::$app->request;
        if($request->get('type')=='news')
        {
            $type=$request->get('type');
            $token=(new PublicList())->get_token();
            $model=(new Query())->select(['keyword','material_news.title','auto_reply.id'])->from('auto_reply')->leftjoin('material_news','auto_reply.group_id=material_news.group_id')->where(['type'=>$type,'auto_reply.token'=>$token])->orderBy('auto_reply.id')->all();
        }elseif($request->get('type')=='images')
        {
            $type=$request->get('type');
            $token=(new PublicList())->get_token();
            $model=(new Query())->select('keyword,path,auto_reply.id')->from('auto_reply')->leftjoin('picture','auto_reply.image_id=picture.id')->where(['type'=>$type,'auto_reply.token'=>$token])->orderBy('auto_reply.id')->all();
        }else
        {
            $type='text';
            $token=(new PublicList())->get_token();
            $model=AutoReply::find()->where(['type'=>$type,'token'=>$token])->orderBy('id')->all();
            
            
        }
        return $this->render('keywords',['list'=>$model,'type'=>$type]);
    }
    /**
     * 添加关键字文本回复
     */
    public function actionAddText()
    {
        $request=Yii::$app->request;
        if($request->get('keyword_id'))
        {
            $model=AutoReply::findOne($request->get('keyword_id'));
        }else{
            $model=new AutoReply();
        }
        return $this->render('add-text',['model'=>$model]);
    }
    /**
     * 添加关键字图文回复
     */
    public function actionAddNews()
    {
         $request=Yii::$app->request;
        if($request->get('keyword_id'))
        {
            $model=(new Query())->select(['keyword','title','auto_reply.group_id','introduction','path','auto_reply.id'])->from('auto_reply')->leftjoin('material_news','auto_reply.group_id=material_news.group_id')->leftjoin('picture','material_news.cover_id=picture.id')->where(['auto_reply.id'=>$request->get('keyword_id')])->orderBy('auto_reply.id')->one();
        }else{
            $model=new AutoReply();
        }
        return $this->render('add-news',['model'=>$model]);
    }
    /**
     * 添加关键字图片回复
     */
    public function actionAddImages()
    {
        $request=Yii::$app->request;
        if($request->get('keyword_id'))
        {
            $model=(new Query())->select('keyword,path,auto_reply.image_id,auto_reply.id')->from('auto_reply')->leftjoin('picture','auto_reply.image_id=picture.id')->where(['auto_reply.id'=>$request->get('keyword_id')])->orderBy('auto_reply.id')->one();
        }else{
            $model=new AutoReply();
        }
        return $this->render('add-images',['model'=>$model]);
    }
    /**
     * 添加关键字
     */
    public function actionAddKeywords()
    {
        $request=Yii::$app->request;
        if($request->isPost){
            $type=$request->post('type');
            if($type=='text')
            {
                if($request->post('id'))
                {
                    $model=AutoReply::findOne($request->post('id'));
                    $model->content=$request->post('content');
                    $model->user_id=Yii::$app->user->getId();
                    $model->keyword=$request->post('keyword');
                    $model->save();
                }else{
                    $model=new AutoReply();
                    $model->content=$request->post('content');
                    $model->keyword=$request->post('keyword');
                    $model->type=$type;
                    $model->token=(new PublicList())->get_token();
                    $model->user_id=Yii::$app->user->getId();
                    $model->save();
                }
                return $this->redirect(['public/keywords']);
            }elseif($type=='news'){
                if($request->post('id'))
                {
                    $model=AutoReply::findOne($request->post('id'));
                    $model->group_id=$request->post('group_id');
                    $model->user_id=Yii::$app->user->getId();
                    $model->keyword=$request->post('keyword');
                    $model->save();
                }else{
                    $model=new AutoReply();
                    $model->group_id=$request->post('group_id');
                    $model->keyword=$request->post('keyword');
                    $model->type=$type;
                    $model->token=(new PublicList())->get_token();
                    $model->user_id=Yii::$app->user->getId();
                    $model->save();
                }
                return $this->redirect(['public/keywords?type=news']);
            }else{
                if($request->post('id'))
                {
                    $model=AutoReply::findOne($request->post('id'));
                    $model->image_id=$request->post('image_id');
                    $model->user_id=Yii::$app->user->getId();
                    $model->keyword=$request->post('keyword');
                    $model->save();
                }else{
                    $model=new AutoReply();
                    $model->image_id=$request->post('image_id');
                    $model->keyword=$request->post('keyword');
                    $model->type=$type;
                    $model->token=(new PublicList())->get_token();
                    $model->user_id=Yii::$app->user->getId();
                    $model->save();
                }
                $this->get_image_media_id($model->image_id);
                return $this->redirect(['public/keywords?type=images']);
            }
        }
    }
    /**
     * 删除关键字回复
     */
    public function actionDeleteKeywords()
    {
        $type=Yii::$app->request->get('type');
        $model=AutoReply::findOne(Yii::$app->request->get('keyword_id'));
        $model->delete();
        return $this->redirect(['public/keywords?type='.$type]);
    }
    /**
     * 公众号关注回复设置
     */
    public function actionUnkown()
    {
        $session=Yii::$app->session;
        $request=Yii::$app->request;
        if($request->isPost)
        {
            $Public=new PublicList();
            $token=$Public->get_token();
            $conf=$request->post('config');
            $model=PublicList::find()->where('token=:token',[':token'=>$token])->one();
            if($model->public_config)
            {
                $config=json_decode($model->public_config,true);
                if(!empty($config['unkown'])){
                    $config['unkown']=array_merge($config['unkown'],$conf);
                    $model->public_config=json_encode($config);
                }else{
                    $config['unkown']=$conf;
                    $model->public_config=json_encode($config);
                }
            }else{
                $config['unkown']=$conf;
                $model->public_config=json_encode($config);
            }
            if($model->save()){
                $data['status']=1;
                $data['info']="保存成功！";
                echo json_encode($data);
            }else{
                $data['status']=0;
                $data['info']="保存失败，请重试！";
                echo json_encode($data);
            }

        }else{
            $session['public_id']=$request->get('public_id');
            $model=new PublicList();
            $token=$model->get_token();
            $info=$model->find()->select('public_config')->where('token=:token',[':token'=>$token])->one();
            if(!empty($info)){
                $config=json_decode($info->public_config,true);
                if(!empty($config['unkown'])){
                    $config=$config['unkown'];
                    if(!empty($config['pic_url'])){
                        $picture=Picture::find()->select(['path'])->where(['id'=>$config['pic_url']])->one();
                        $config['path']=$picture->path;
                    }
                }else{
                    $config=$info;
                }
            }else{
                $config=$info;
            }
            return $this->render('unkown',['info'=>$config]);
        }
    }

    /**
     * 添加自定义菜单
     */
    public function actionSetMenu(){

        $menu_group_id=Yii::$app->request->get('menu_group_id');
        $token=(new PublicList())->get_token();
        $access_token=$this->get_access_token($token);
        $url="https://api.weixin.qq.com/cgi-bin/menu/create?access_token=".$access_token;
        $list=(new Query)->select(['group_id','weixin_menu.*'])->from('weixin_menu')->leftJoin('menu_group','weixin_menu.menu_group_id=menu_group.id')->where(['token'=>$token,'menu_group_id'=>$menu_group_id])->orderBy('pid asc,sort asc')->all();
        if(!empty($list))
        {
            foreach ($list as $key => $data) {
                $refer[$data['id']] = &$list[$key];
            }
            foreach ($list as $key => $data) {
                $parentId = $data['pid'];
                if ($parentId==0) {
                    $info[] = &$list[$key];
                } else {
                    if (isset($refer[$parentId])) {
                        $parent = &$refer[$parentId];
                        $parent['child'][] = &$list[$key];
                    }
                }
            }
        }
        $str='{"button":[';
        foreach($info as $v){
            if(!empty($v['child'])){
                $str.='{"name":"'.$v['title'].'", "sub_button":[';
                foreach($v['child'] as $ke=>$va){
                    if($va['type']=='click'){
                        $str.='{
                           "type":"'.$va['type'].'",
                           "name":"'.$va['title'].'",
                           "key":"'.$va['keyword'].'"
                        },';
                    }else{
                        $str.='{
                           "type":"'.$va['type'].'",
                           "name":"'.$va['title'].'",
                           "url":"'.$va['url'].'"
                        },';
                    }

                }
                $str=trim($str,',');
                $str.=']},';
            }else{

                if($v['type']=='click'){
                    $str.='{
                              "type":"click",
                              "name":"'.$v['title'].'",
                              "key":"'.$v['id'].'"
                            },';
                }else{

                    $str.='{
                              "type":"view",
                              "name":"'.$v['title'].'",
                              "url":"'.$v['url'].'"
                    }           ,';
                }
            }
        }
        $str=trim($str,',');
        if(!empty($list[0]['group_id'])){
            $url="https://api.weixin.qq.com/cgi-bin/menu/addconditional?access_token=".$access_token;
            $str.='],"matchrule":{"group_id":"'.$list[0]['group_id'].'"}';
        }else{
            $str.="]}";
        }
        $output=http($url,$str,'POST');
        $data=json_decode($output,true);
        if(!empty($data['menuid'])){
            $model=MenuGroup::findOne($menu_group_id);
            $model->weixin_menu_id=$data['menuid'];
            $model->save();
            $tips['status']='1';
            $tips['info']='菜单发布成功!';
        }else{
            if($data['errcode']==0){
                $tips['status']='1';
                $tips['info']='菜单发布成功!';
            }else{
                $tips['status']=$data['errcode'];
                $tips['info']=$data['errmsg'];
            }
        }
        echo json_encode($tips);

    }
    /**
     * 拉取微信自定义菜单
     */
    public function actionGetMenu(){
        $token=(new PublicList())->get_token();
        $access_token=$this->get_access_token($token);
        $params['access_token']=$access_token;
        $url="https://api.weixin.qq.com/cgi-bin/menu/get";
        $menu_info=http($url,$params);
        $data=json_decode($menu_info,true);
        if(!empty($data['menu'])){

            $user_id=Yii::$app->user->getId();
            $model=new MenuGroup();
            $flag=$model->find()->where(['name'=>'默认自定义菜单','token'=>$token])->one();
            if(empty($flag))
            {
                $model->name='默认自定义菜单';
                $model->token=$token;
                $model->user_id=$user_id;
                $model->add_time=time();
                $model->change_user_id=$user_id;
                $model->change_time=time();
                $model->group_id='"'.$this->user_group_id($token).'"';
                $model->save();
                $menu_group_id=$model->id;
            }else{
                $menu_group_id=$flag->id;
            }
            foreach ($data['menu']['button'] as $k => $v) {
                $menu=new WeixinMenu();
                $menu->menu_group_id=$menu_group_id;
                $menu->sort=$k+1;
                $menu->pid=0;
                $menu->type=empty($v['type'])?'none':$v['type'];
                $menu->title=$v['name'];
                $menu->url=empty($v['url'])?'':$v['url'];
                $menu->user_id=$user_id;
                $menu->add_time=time();
                $menu->change_user_id=$user_id;
                $menu->change_time=time();
                $menu->save();
                if(!empty($v['sub_button'])){
                    $pid=$menu->id;
                    foreach ($v['sub_button'] as $key => $val) {
                        $menu=new WeixinMenu();
                        $menu->menu_group_id=$menu_group_id;
                        $menu->sort=$key+1;
                        $menu->pid=$pid;
                        $menu->type=$val['type'];
                        $menu->title=$val['name'];
                        $menu->url=empty($val['url'])?'':$val['url'];
                        $menu->user_id=$user_id;
                        $menu->add_time=time();
                        $menu->change_user_id=$user_id;
                        $menu->change_time=time();
                        $menu->save();
                    }
                }
            }
        }
        if(!empty($data['conditionalmenu'])){
            $user_id=Yii::$app->user->getId();
            $model=new MenuGroup();
            $model->name='个性化自定义菜单';
            $model->token=$token;
            $model->user_id=$user_id;
            $model->add_time=time();
            $model->change_user_id=$user_id;
            $model->change_time=time();
            $model->weixin_menu_id=$data['menu']['menuid'];
            $model->group_id=$data['menu']['matchrule']['group_id'];
            $model->save();
            $menu_group_id=$model->id;
            foreach ($data['menu']['button'] as $k => $v) {
                $menu=new WeixinMenu();
                $menu->menu_group_id=$menu_group_id;
                $menu->sort=$k+1;
                $menu->pid=0;
                $menu->type=empty($v['type'])?'none':$v['type'];
                $menu->title=$v['name'];
                $menu->url=empty($v['url'])?'':$v['url'];
                $menu->user_id=$user_id;
                $menu->add_time=time();
                $menu->change_user_id=$user_id;
                $menu->change_time=time();
                $menu->save();
                if(!empty($v['sub_button'])){
                    $pid=$menu->id;
                    foreach ($v['sub_button'] as $key => $val) {
                        $menu=new WeixinMenu();
                        $menu->menu_group_id=$menu_group_id;
                        $menu->sort=$key+1;
                        $menu->pid=$pid;
                        $menu->type=$val['type'];
                        $menu->title=$val['name'];
                        $menu->url=empty($val['url'])?'':$val['url'];
                        $menu->user_id=$user_id;
                        $menu->add_time=time();
                        $menu->change_user_id=$user_id;
                        $menu->change_time=time();
                        $menu->save();
                    }
                }
            }
        }
        if(!empty($data['menu'])){
            $info['status']='1';
            $info['info']='菜单拉取成功!';
        }else{
           
            $info['status']=$data['errcode'];
            $info['info']=$data['errmsg'];
        }
        echo json_encode($info);

    }
    /**
     * 查找默认分组
     */
    public function user_group_id($token)
    {
        $model=UserGroup::find()->select(['weixin_group_id'])->where(['token'=>$token])->one();
        return $model->weixin_group_id;
    }
    /**
     * 删除个性化菜单
     */
    public function delete_weixin_menu($type,$menu_id='')
    {
        $token=(new PublicList())->get_token();
        $access_token=$this->get_access_token($token);
        if($type==1){
            $url='https://api.weixin.qq.com/cgi-bin/menu/delconditional?access_token='.$access_token;
            $str='{"menuid":"'.$menu_id.'"}';
            $output=http($url,$str,'POST');
        }else{
            $paramas['access_token']=$access_token;
            $url='https://api.weixin.qq.com/cgi-bin/menu/delete';
            $output=http($url,$paramas);
        }
        $data=json_decode($output,true);
        return $data;

    }

    /**
     * 拉取微信模板数据
     */
    public function actionGetTemplate()
    {
        $data=$this->get_template();
        if(!empty($data['errcode']) && $data['errcode']!=0){
            $info['status']=0;
            $info['info']=$data['errmsg'];
        }else{
            foreach($data['template_list'] as $va)
            {
                $model=new PushTemplate();
                $flag=$model->find()->where(['template_id'=>$va['template_id']])->one();
                if(!$flag){
                    $model->template_id=$va['template_id'];
                    $model->title=$va['title'];
                    $model->primary_industry=$va['primary_industry'];
                    $model->deputy_industry=$va['deputy_industry'];
                    $model->template_data=$va['content'];
                    preg_match_all('/{{(.*).DATA}}/Us',$va['content'],$data);
                    foreach($data[1] as $k=> $v){
                        $info[$v]='';
                        if($k==0){
                            $info[$v]['type']=1;
                        }
                    }
                    $model->content=json_encode($info);
                    $model->example=$va['example'];
                    $model->save();
                }
            }
            $info['status']='1';
            $info['info']="拉取微信模板消息成功！";
        }
        echo json_encode($info);

    }
    /**
     * 模板消息列表
     */
    public function actionTemplate()
    {
        $list=PushTemplate::find();
        $pages = new Pagination(['totalCount' => $list->count(), 'pageSize' => '8']);
        $lists = $list->offset($pages->offset)->limit($pages->limit)->all();
        return $this->render('template',['list'=>$lists,'pages'=>$pages]);
    }
    /**
     * 修改模板消息
     */
    public function actionTemplateChange()
    {
        $request=Yii::$app->request;
        if($request->isPost)
        {
            $id=$request->post('id');
            $template=PushTemplate::findOne($id);
            $template->url=$request->post('url');
            $template->flag=$request->post('flag');
            //$template->content=json_encode($request->post('content'));
            if($template->save())
            {
                return $this->redirect(['template']);
            }else{
                throw new \Exception();
            }

        }else
        {
            //$db=Yii::$app->db;
            //$tables = $db->createCommand('show tables')->queryAll();
            $id=$request->get('id');
            $info=PushTemplate::find()->where(['id'=>$id])->asArray()->one();
            /*$info['content']=json_decode($info['content'],true);
            foreach($info['content'] as $ke=>&$va){
                if(!empty($va['type']))
                {
                    if($va['type']==2){
                        $table=$db->getTableSchema($va['table']);
                        foreach($table->columns as $column)
                        {
                            $field['comment']=!empty($column->comment)?$column->comment:$column->name;
                            $field['name']=$column->name;
                            $va['field'][]=$field;
                        }
                    }

                }elseif($ke!='remark'){
                    if(!empty($va['table'])){
                        $table=$db->getTableSchema($va['table']);
                        foreach($table->columns as $column)
                        {
                            $field['comment']=!empty($column->comment)?$column->comment:$column->name;
                            $field['name']=$column->name;
                            $va['field'][]=$field;
                        }
                    }

                }

            }*/
            return $this->render('template-change',['info'=>$info]);
        }

    }
    /**
     * 新增模板消息
     */
    public function actionTemplateAdd()
    {
        $request=Yii::$app->request;
        if($request->isPost)
        {
            $id=$request->post('id');
            if(!empty($id)){
                $template=PushTemplate::findOne($id);
            }else{
                $template=new PushTemplate();
            }
            $template->url=$request->post('url');
            $template->title=$request->post('title');
            $template->flag=$request->post('flag');
            $template->content=json_encode($request->post('content'));
            if($template->save())
            {
                return $this->redirect(['template']);
            }else{
                throw new \Exception();
            }

        }else {
            $db = Yii::$app->db;
            $tables = $db->createCommand('show tables')->queryAll();
            $id = $request->get('id');
            if(!empty($id)){
                $info = PushTemplate::find()->where(['id' => $id])->asArray()->one();
                $info['content'] = json_decode($info['content'], true);
                foreach ($info['content'] as $ke => &$va) {
                    if (!empty($va['type'])) {
                        if ($va['type'] == 2) {
                            $table = $db->getTableSchema($va['table']);
                            foreach ($table->columns as $column) {
                                $field['comment'] = !empty($column->comment) ? $column->comment : $column->name;
                                $field['name'] = $column->name;
                                $va['field'][] = $field;
                            }
                        }

                    } else{
                        if (!empty($va['table'])) {
                            $table = $db->getTableSchema($va['table']);
                            foreach ($table->columns as $column) {
                                $field['comment'] = !empty($column->comment) ? $column->comment : $column->name;
                                $field['name'] = $column->name;
                                $va['field'][] = $field;
                            }
                        }

                    }

                }
            }else{
                $info=new PushTemplate();
            }
            return $this->render('template-add', ['info' => $info, 'tables' => $tables]);
        }
    }
    /**
     * 模板消息详情
     */
    public function actionTemplateDetail()
    {
        $request=Yii::$app->request;
        $id=$request->get('id');
        $info=PushTemplate::find()->where(['id'=>$id])->asArray()->one();
        $info['content']=json_decode($info['content'],true);
        return $this->render('template-detail',['info'=>$info]);
    }
    /**
     * 删除模板消息
     */
    public function actionTemplateDelete()
    {
        $request=Yii::$app->request;
        $id=$request->get('id');
        $model=PushTemplate::findOne($id);
        $data=$this->delete_template($model->template_id);
        if(!empty($data['errcode']) && $data['errcode']!=0){
            $info['status']=0;
            $info['info']=$data['errmsg'];
        }else{
            if($model->delete()){
                $info['status']=1;
                $info['info']="删除模板消息成功!";
            }
        }
        echo json_encode($info);

    }
    /**
     * 根据数据表获取数据表字段
     */
    public function actionGetColumn()
    {
        $db=Yii::$app->db;
        $table=$db->getTableSchema(Yii::$app->request->post('table'));
        foreach($table->columns as $column)
        {
            $info['comment']=!empty($column->comment)?$column->comment:$column->name;
            $info['name']=$column->name;
            $data[]=$info;
        }
        echo json_encode($data);
    }
    /**
     * 群发消息
     */
    public function actionMessage()
    {
        $group_list=(new UserGroup())->get_group_list(2);
        return $this->render('message',['group_list'=>$group_list]);
    }
    /**
     * 选择用户
     */
    public function actionSelectUsers()
    {
        $list=WeixinUser::find()->select(['id','nickname','userface','sex','weixin_group_id','openid'])->asArray();
        $group_id=isset($_REQUEST['group_id'])?$_REQUEST['group_id']:'';
        if(isset($_REQUEST['group_id']) && $_REQUEST['group_id']!='all'){
            $list->andWhere(['group_id'=>$_REQUEST['group_id']]);
        }
        $list=$list->all();
        $group_list=(new UserGroup())->get_group_list(2);
        return $this->render('select-user',['list'=>$list,'group_list'=>$group_list,'group_id'=>$group_id]);
    }
    /**
     * 发群发消息
     */
    public function actionSendMessage()
    {
        $request=\Yii::$app->request;
        if ($request->isPost) {
            $send_type = empty($request->post( 'send_type'))?0:$request->post( 'send_type');
            $group_id =empty($request->post( 'group_id'))?0:$request->post( 'group_id');
            $send_openids = $request->post( 'send_openids' );
            if ($send_type == 0) {
                $_POST ['msg_id'] = $this->send_by_group ( $group_id );
            } else {
                $_POST ['msg_id'] = $this->send_by_openid ( $send_openids );
            }
            $model=new SendMessage();
            $model->preview_openids=$request->post('preview_openids');
            $model->send_openids=$request->post('send_openids');
            $model->group_id=$request->post('group_id');
            $model->type=0;
            $model->media_id=!empty($_POST['media_id'])?(string)$_POST['media_id']:'';
            $model->send_type=$_POST['send_type'];
            $model->msg_id=(string)$_POST['msg_id'];
            $model->content=!empty($request->post('content'))?$request->post('content'):'';
            $model->msgtype=$_POST['msgtype'];
            $model->token=(new PublicList())->get_token();
            $model->appmsg_id=!empty($request->post('appmsg_id'))?$request->post('appmsg_id'):0;
            if($model->save()){
                $info['status']=1;
                $info['info']="发送成功！";
            }else{
                $info['status']=0;
                $info['info']=Yii::$app->errorHandler->exception;
            }
            echo json_encode($info);
        }
    }
    /**
     * 预览消息
     */
    public function actionPreview()
    {
        $info = $this->get_sucai_media_info ();
        if ($info ['msgtype'] == 'text') {
            $param ['text'] ['content'] = $info ['media_id'];
        } else if ($info ['msgtype'] == 'mpnews') {
            $param ['mpnews'] ['media_id'] = $info ['media_id'];
        } else if ($info ['msgtype'] == 'voice') {
            $param ['voice'] ['media_id'] = $info ['media_id'];
        } else if ($info ['msgtype'] == 'mpvideo') {
            $param ['mpvideo'] ['media_id'] = $info ['media_id'];
        }elseif($info['msgtype']=='image'){
            $param['image']['media_id']=$info['media_id'];
        }
        $param ['msgtype'] = $info ['msgtype'];

        $url = 'https://api.weixin.qq.com/cgi-bin/message/mass/preview?access_token=' . $this->get_access_token ();
        $flag=true;
        $openids=wp_explode($_REQUEST['preview_openids']);
        foreach ( $openids as $openid ) {
            $param ['touser'] = $openid;
            $res = post_data ( $url, $param );
            if(!empty($res['errcode']) && $res['errcode']!=0){
                $flag=false;
                $data['info']=error_msg($res);
            }
        }
        if($flag){
            $data['status']=1;
            $data['info']="发送成功！";
        }else{
            $data['status']=0;
        }
        echo json_encode($data);

    }
    public function get_sucai_media_info() {
        $request=Yii::$app->request;
        $type = $request->post( 'msg_type' );
        $content =$request->post( 'content' );
        $appmsg_id = $request->post ( 'appmsg_id' );
        $image =$request->post ( 'image' );
        $material_image=$request->post('image_material');
        if ($type == 'text') {
            if (empty ( $content ))
                Yii::warning('文本内容不能为空');
            $res ['media_id'] = $content;
            $res ['msgtype'] = 'text';
            $_POST ['content'] = $content;
        } else if ($type == 'appmsg') {
            if (empty ( $appmsg_id ))
                Yii::warning( '图文素材不能为空' );
            $res ['media_id'] = $this->getMediaIdByGroupId ( $appmsg_id );
            $_POST ['media_id'] = $res ['media_id'];
            $res ['msgtype'] = 'mpnews';
        }else if ($type == 'image') {
            if (empty ( $image ) && empty($material_image))
                Yii::warning( '图片素材不能为空' );
            if(!empty($image)){
                $res ['media_id'] = $this->get_image_media_id($image);
            }else{
                $res ['media_id'] = $this->get_image_media_id($material_image,2);
            }
            $_POST ['media_id'] = $res ['media_id'];
            $res ['msgtype'] = 'image';
        }/*else if ($type == 'voice') {
            $voice = $request->post ( 'voice_id' );
            if (empty ( $voice ))
                Yii::warning ( '语音素材不能为空' );
            $file = MaterialFile::findOne( $voice );
            if ($file ['media_id']) {
                $res ['media_id'] = $file ['media_id'];
            } else {
                $res ['media_id'] = $this->get_file_media_id ( $file ['file_id'], 'voice' );
            }
            $res ['msgtype'] = 'voice';
        } else if ($type == 'video') {
            $video = $request->post ( 'video_id' );
            if (empty ( $video ))
                Yii::warning( '视频素材不能为空' );
            $file = MaterialFile::findOne( $video );
            if ($file ['media_id']) {
                $mediaId = $file ['media_id'];
            } else {
                $mediaId =$this->get_file_media_id ( $file ['file_id'], 'video' );
            }
            $data ['media_id'] = $mediaId;
            $data ['title'] = $file ['title'];
            $data ['description'] = $file ['introduction'];
            $url1 = "https://file.api.weixin.qq.com/cgi-bin/media/uploadvideo?access_token=" .$this->get_access_token ();
            $result = post_data ( $url1, $data );
            $res ['media_id'] = $result ['media_id'];
            $res ['msgtype'] = 'mpvideo';
        }*/
        $_POST ['msgtype'] = $res ['msgtype'];
        return $res;
    }
    // 按用户组发送
    function send_by_group($group_id) {
        $url = 'https://api.weixin.qq.com/cgi-bin/message/mass/sendall?access_token=' . $this->get_access_token ();

        $paramStr = '';
        if ($group_id) {
            $paramStr .= '{"filter":{"is_to_all":false,"group_id":"' . $group_id . '"},';
        } else {
            $paramStr .= '{"filter":{"is_to_all":true},';
        }
        $info = $this->get_sucai_media_info ();

        if ($info ['msgtype'] == 'text') {
            $paramStr .= '"text":{"content":"' . $info ['media_id'] . '"},"msgtype":"text"}';
        } else if ($info ['msgtype'] == 'mpnews') {
            $paramStr .= '"mpnews":{"media_id":"' . $info ['media_id'] . '"},"msgtype":"mpnews"}';
        } else if ($info ['msgtype'] == 'voice') {
            $paramStr .= '"voice":{"media_id":"' . $info ['media_id'] . '"},"msgtype":"voice"}';
        } else if ($info ['msgtype'] == 'mpvideo') {
            $paramStr .= '"mpvideo":{"media_id":"' . $info ['media_id'] . '"},"msgtype":"mpvideo"}';
        }elseif($info['msgtype']=='image'){
            $paramStr .= '"image":{"media_id":"' . $info ['media_id'] . '"},"msgtype":"image"}';
        }
        $res = post_data ( $url, $paramStr );
        if ($res ['errcode'] != 0) {
            die(error_msg ( $res ));
        } else {
            return $res ['msg_id'];
        }
    }
    // 按用户发送 订阅号不可用，服务号认证后可用
    function send_by_openid($openids) {
        $openids = wp_explode ( $openids );
        if (empty ( $openids )) {
            Yii::warning( '要发送的OpenID值不能为空' );
        }
        if (count ( $openids ) < 2) {
            Yii::warning ( 'OpenID至少需要2个或者2个以上' );
        }
        $url = 'https://api.weixin.qq.com/cgi-bin/message/mass/send?access_token=' . $this->get_access_token ();
        $info = $this->get_sucai_media_info ();
        $param ['touser'] = $openids;
        if ($info ['msgtype'] == 'text') {
            $param ['text'] ['content'] = $info ['media_id'];
            $param ['msgtype'] = $info ['msgtype'];
        } else if ($info ['msgtype'] == 'mpnews') {
            $param ['mpnews'] ['media_id'] = $info ['media_id'];
            $param ['msgtype'] = $info ['msgtype'];
        } else if ($info ['msgtype'] == 'voice') {
            $param ['voice'] ['media_id'] = $info ['media_id'];
            $param ['msgtype'] = $info ['msgtype'];
        } else if ($info ['msgtype'] == 'mpvideo') {
            $param ['video'] ['media_id'] = $info ['media_id'];
            $param ['msgtype'] = $info ['video'];
        }elseif($info['msgtype']=='image'){
            $param['image']['media_id']=$info['media_id'];
            $param ['msgtype'] = $info ['msgtype'];
        }

        $param = JSON ( $param );
        $res = post_data ( $url, $param );
        if ($res ['errcode'] != 0) {
            Yii::warning( error_msg ( $res ) );
        } else {
            return $res ['msg_id'];
        }
    }
}
