<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 15/11/10
 * Time: 下午3:16
 */

namespace common\controllers;

use yii;
use yii\web\Controller;
use common\models\User;

class WechatController extends Controller
{

    // 获取appid
    function get_appid()
    {
        return 'wxc67ca9ce7c8c378c';
    }
    // 获取secret
    function get_secret()
    {
        return 'c876738b2638f3bfecaba2e02463e415';
    }

    public function beforeAction($action)
    {
        $this->OAuthWeixin();
        return parent::beforeAction($action);
    }

    // 通过openid获取微信用户基本信息,此功能只有认证的服务号才能用
      function getWeixinUserInfo() {

        $param2 ['access_token'] = $this->get_access_token ();
        $param2 ['openid'] = $this->get_openid();
        $param2 ['lang'] = 'zh_CN';
        $url = 'https://api.weixin.qq.com/sns/userinfo?'. http_build_query ( $param2 );
        $content = file_get_contents ( $url );
        $content = json_decode ( $content, true );
        return $content;
    }



    // 获取access_token，自动带缓存功能
     function get_access_token() {
         $session = Yii::$app->session;
         if (isset($session['access_token']))
             return $session['access_token'];
    }


// 获取当前用户的OpenId
    function get_openid()
    {
        $session = Yii::$app->session;
        if (isset($session['openid']))
            return $session['openid'];
        else
            return null;
    }


    function OAuthWeixin() {
        $open_id=$this->get_openid();
        if (isset($open_id))
            return;

        if(isset($_REQUEST['code'])&&!empty($_REQUEST['code']))
        {
            $url='https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$this->get_appid().'&secret='.$this->get_secret().'&code='.$_REQUEST['code'].'&grant_type=authorization_code';
            $tempArr = json_decode ( file_get_contents ( $url ), true );
            if (@array_key_exists ( 'access_token',$tempArr )) {

                $session = Yii::$app->session;
                $session['access_token']=$tempArr['access_token'];
                $session['openid']=$tempArr['openid'];

                $user= User::findByOpen_id($tempArr['openid']);
                if(!isset($user)||empty($user))
                {
                    $user_info_url="https://api.weixin.qq.com/sns/userinfo";
                    $user_post_data=array(
                        'access_token'=>$tempArr['access_token'],
                        'openid'=>$tempArr['openid'],
                        'lang'=>"zh_CN"
                    );
                    $curl = curl_init();
                    curl_setopt($curl, CURLOPT_URL, $user_info_url);
                    curl_setopt($curl, CURLOPT_HEADER, 0);
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $user_post_data);
                    $user_data = curl_exec($curl);//获取文件内容或获取网络请求的内容
                    curl_close($curl);
                    $userinfo = json_decode ($user_data, true );
                    $user=new User();
                    $user->nickname=$userinfo['nickname'];
                    $user->openid=$userinfo['openid'];
                    $user->userface=$userinfo['headimgurl'];
                    $user->sex=$userinfo['sex'];
                    $user->save();
                }
            }
        }
        else{
            $param ['appid'] = $this->get_appid();
            $param ['redirect_uri'] =  Yii::$app->request->getHostInfo().Yii::$app->request->url;
            $param ['response_type'] = 'code';
            $param ['scope'] = 'snsapi_userinfo';
            $param ['state'] = 1;
            $url = 'https://open.weixin.qq.com/connect/oauth2/authorize?'. http_build_query ($param).'#wechat_redirect';
            return $this->redirect ( $url );
        }

    }

}