<?php

namespace app\modules\controllers;
use app\models\Follow;
use app\models\UserInfo;
use Yii;
use app\modules\weixin\BaseController;
use app\models\UserForm;
use yii\filters\AccessControl;

class IndexController extends BaseController{
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
                        'actions' => ['logout','edit','add','del','index','users','thumb','upload','cutpic','follow','nofollow'
                                       ,'password','set-password'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * @验证码独立操作
     */

    public function actions(){
        return [

            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }


    /**
     * @return string 后台默认页面
     */
    public function actionIndex()
    {
        $app_info= $this->get_app_info();
        $menu_info=$this->buildMenu($app_info);
        return $this->render('index',array('list'=>$menu_info,'app_info'=>$app_info));
    }


    function buildMenu($app_info){
        $apps_menus=array();

        foreach($app_info as $app_key=>$app_value){

            $app_menus=array();
            $app_menus['id']=$app_value['app_code'];

            $menus_info=$this->get_menu_info($app_value['app_code']);

            $app_modules = array();
            foreach($menus_info as $menus_info_key=>$menus_info_value)
            {
                if($menus_info_value['menu_parent_no']!=0)continue;
                $app_module=array();
                $app_module['text']=$menus_info_value['text'];
                $app_module_page=array();
                foreach($menus_info as $menu_key=>$menu_value)
                {
                    if($menu_value['menu_parent_no']!=$menus_info_value['id'])continue;
                    array_push($app_module_page,$menu_value);
                }
                $app_module['items']=$app_module_page;
                array_push($app_modules,$app_module);
            }
            $app_menus['menu']=$app_modules;
            array_push($apps_menus,$app_menus);
        }
        return $apps_menus;

    }

    /**
     * @return string 读取用户列表
     */
    public function actionUsers(){
        $uid=Yii::$app->user->getId();

        //我的粉丝
        $follow=Follow::find()->where(array("fid"=>$uid))->all();
        //$follow=Follow::findBySql("select uid from {{%follow}} where fid=".Yii::$app->user->getId())->all();
        //echo '<pre/>';print_r($follow);
        $ids=array();
        foreach($follow as $v){
            array_push($ids,$v->uid);
        }

        //获取我的粉丝信息
        $fensi=YiiUser::findAll($ids);
        //echo '<pre/>';print_r($fensi);

        //获取我关注的人【我的好友】
        $careids=Follow::find()->where(["uid"=>$uid])->all();
        $cids=array();
        foreach($careids as $v){
            array_push($cids,$v->fid);
        }
        $cares=YiiUser::findAll($cids);

        //获取我没有关注的用户【加关注的人】
         array_push($cids,$uid);//将我的id也加入到排除列表
        //$users=YiiUser::find()->where(['in','id',$ids])->all();//id在一个数组范围内
        $users=YiiUser::find()->where(['not in','id',$cids])->all();

        return $this->render('users',array('users'=>$users,'fensi'=>$fensi,'cares'=>$cares,'cids'=>$cids));
    }


    /**
     * @添加关注动作
     */
    public function actionFollow($id){
       //获取查询条件
        $fid=intval($id);
        $uid=Yii::$app->user->getId();

        //检查是否已经关注了
        $obj=new Follow();
        $num=$obj->find()->andWhere(['uid'=>$uid,'fid'=>$fid])->count();
        if($num==0){
            $obj->uid=$uid;
            $obj->fid=$fid;
            $obj->save();
            Yii::$app->session->setFlash('success','关注成功！');
        }else{
            Yii::$app->session->setFlash('error','关注失败！');
        }
        return $this->redirect(['index/users']);
    }

    /**
     * @取消关注
     */
    public function actionNofollow($id){
        $fid=intval($id);
        $uid=Yii::$app->user->getId();
        //检查是否已经关注了
        $user=Follow::find()->andWhere(['uid'=>$uid,'fid'=>$fid])->one();

        if(count($user)>0){
            //$user->delete() 失败，提示没有定义主键
            $user->deleteAll('uid=:uid and fid=:fid',[':uid'=>$uid,':fid'=>$fid]);
            Yii::$app->session->setFlash('success','取消关注成功！');
        }else{
            Yii::$app->session->setFlash('error','取消关注失败！');
        }
        return $this->redirect(['index/users']);
    }





    /**
     * @return string|\yii\web\Response 用户登录
     */

    public function actionLogin(){

        $model=new UserForm();
        if($model->load(Yii::$app->request->post())){
            $ip= $this->getIp();
/*            if($model->user=="admin"&&substr($ip,0,strrpos($ip,".",0))!="172.16.5"){
                return $this->render('login',['model'=>$model]);
            }*/
/*            if($model->user=="admin"&&$ip!="60.173.202.249"){
                return $this->render('login',['model'=>$model]);
            }*/
/*            $hash = Yii::$app->security->generatePasswordHash($model->pwd);
            Yii::$app->getSecurity()->validatePassword('admin', $hash);*/
            if($model->login()){
                //查询未读消息
/*                $count=Msg::find()->andwhere(['tid'=>Yii::$app->user->getId(),'status'=>0])->count();
                $session=Yii::$app->session;
                $session->set('msg',$count);*/
                return $this->redirect(['index/index']);
            }else{
                return $this->render('login',['model'=>$model]);
            }
        }

        return $this->render('login',['model'=>$model]);
    }

    function getIp(){
        $onlineip='';
        if(getenv('HTTP_CLIENT_IP')&&strcasecmp(getenv('HTTP_CLIENT_IP'),'unknown')){
            $onlineip=getenv('HTTP_CLIENT_IP');
        } elseif(getenv('HTTP_X_FORWARDED_FOR')&&strcasecmp(getenv('HTTP_X_FORWARDED_FOR'),'unknown')){
            $onlineip=getenv('HTTP_X_FORWARDED_FOR');
        } elseif(getenv('REMOTE_ADDR')&&strcasecmp(getenv('REMOTE_ADDR'),'unknown')){
            $onlineip=getenv('REMOTE_ADDR');
        } elseif(isset($_SERVER['REMOTE_ADDR'])&&$_SERVER['REMOTE_ADDR']&&strcasecmp($_SERVER['REMOTE_ADDR'],'unknown')){
            $onlineip=$_SERVER['REMOTE_ADDR'];
        }
        return $onlineip;
    }

    /**
     * @后台退出页面
     */
    public function actionLogout(){
        Yii::$app->user->logout();
        return $this->goHome();

    }


    /**
     * @用户头像上传
     */
    public function  actionThumb(){
       $user=YiiUser::findOne(Yii::$app->user->getId());
        return $this->render('thumb',array('user'=>$user));
    }

    /**
     * @
     */
    public  function  actionUpload(){

        $path = Yii::$app->basePath."/web/avatar/";
        $tmpath="/avatar/";
        if(!empty($_FILES)){

            //得到上传的临时文件流
            $tempFile = $_FILES['myfile']['tmp_name'];

            //允许的文件后缀
            $fileTypes = array('jpg','jpeg','gif','png');

            //得到文件原名
            $fileName = iconv("UTF-8","GB2312",$_FILES["myfile"]["name"]);
            $fileParts = pathinfo($_FILES['myfile']['name']);



            //最后保存服务器地址
            if(!is_dir($path)){
                mkdir($path);
            }

            if (move_uploaded_file($tempFile, $path.$fileName)){
                $info= $tmpath.$fileName;
                $status=1;
                $data=array('path'=>Yii::$app->basePath,'file'=> $path.$fileName);
            }else{
                $info=$fileName."上传失败！";
                $status=0;
                $data='';
            }
            echo $info;
        }

    }

    /**
     * @裁剪头像
     */

    public function actionCutpic(){
        if(Yii::$app->request->isAjax){
            $path="/avatar/";
            $targ_w = $targ_h = 150;
            $jpeg_quality = 100;
            $src =Yii::$app->request->post('f');
            $src=Yii::$app->basePath.'/web'.$src;//真实的图片路径

            $img_r = imagecreatefromjpeg($src);
            $ext=$path.time().".jpg";//生成的引用路径
            $dst_r = ImageCreateTrueColor( $targ_w, $targ_h );

            imagecopyresampled($dst_r,$img_r,0,0,Yii::$app->request->post('x'),Yii::$app->request->post('y'),
                $targ_w,$targ_h,Yii::$app->request->post('w'),Yii::$app->request->post('h'));

            $img=Yii::$app->basePath.'/web/'.$ext;//真实的图片路径

            if(imagejpeg($dst_r,$img,$jpeg_quality)){
                //更新用户头像
                $user=YiiUser::findOne(Yii::$app->user->getId());
                $user->thumb=$ext;
                $user->save();
                $arr['status']=1;
                $arr['data']=$ext;
                $arr['info']='裁剪成功！';
                echo json_encode($arr);

            }else{
                $arr['status']=0;
                echo json_encode($arr);
            }
            exit;
        }
    }

   public function actionPassword(){
        $error = '';
       if(isset($_REQUEST["error"])){
           $error = $_REQUEST["error"];
       }
      return $this->render('password',["error"=>$error]);
    }

    public function actionSetPassword(){
        $pass1 = $_REQUEST["pass1"];
        $pass2 = $_REQUEST["pass2"];
        if(!empty($pass1) && $pass1 === $pass2){
         $userId =  Yii::$app->user->getId();
         $user = UserInfo::findOne($userId) ;
         $password = md5($pass1);
         $user->password = $password;
         if($user->save()){
             $this->redirect("password?error=yes");
         }
      }else{
            $this->redirect("password?error=no");
        }
    }
}