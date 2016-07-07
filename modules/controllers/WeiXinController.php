<?php

namespace app\modules\controllers;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use app\models\PublicList;
use yii\web\Session;
use app\modules\weixin\BaseController;
use app\models\AutoReply;
use app\models\MaterialImage;
class WeiXinController extends BaseController
{

    public function  beforeAction($action)
    {
        //parent::beforeAction($action);
        if ($_REQUEST ['doNotInit'])
            return true;
        $postStr = file_get_contents_time ( 'php://input' );
        ! empty ( $postStr ) || die ( '这是微信请求的接口地址，直接在浏览器里无效' );

    }
    public function actionIndex()
    {

        $request=Yii::$app->request;
        if(!empty($request->get('echostr'))){
                $echoStr=$request->get('echostr');
                $signature = $request->get('signature');
                $timestamp = $request->get('timestamp');
                $nonce = $request->get('nonce');
                $token = 'happycity777';
                $tmpArr = array($token, $timestamp, $nonce);
                sort($tmpArr, SORT_STRING);
                $tmpStr = implode( $tmpArr );
                $tmpStr = sha1( $tmpStr );
                if( $tmpStr == $signature )
                {
                    echo $echoStr;
                    exit;
                }
        }
         //接收数据
        //$postStr = $GLOBALS ["HTTP_RAW_POST_DATA"];
        //判断是否存在数据
        if (! empty ( $postStr )) {
            
            // 解析xml
            libxml_disable_entity_loader ( true );
            // simplexml_load_string载入xml到字符串
            $postObj = simplexml_load_string ( $postStr, 'SimpleXMLElement', LIBXML_NOCDATA );
            $type = $postObj->MsgType;
            $from = $postObj->FromUserName;
            $to = $postObj->ToUserName;
            $event = $postObj->Event;
            $id = $postObj->MsgId;
            // 时间戳
            $time = $postObj->CreateTime;
            
            if ($type == 'event' && $event == 'subscribe') {
                $userInfo=$this->getUserInfo($from);
                $this->write_user($userInfo,$to);
                $this->get_welcome($to,$from);
            }elseif($type == 'event' && $event == 'unsubscribe'){
                //修改微信用户关注状态
            }elseif ($type == 'text') {
                // 接收用户发送过来的数据，存储$keyword里
                $keyword = trim ( $postObj->Content );
                // 判断用户传递过来文本消息是否为空
                if (! empty ( $keyword )) {
                    $this->write_message_text($to,$from,$type,$id,$time,$keyword);
                    $info=AutoReply::find()->where(['token'=>$to,'keyword'=>$keyword])->one();
                    if($info->type=="text"){
                        $this->reply_text($from,$to,$info->content);
                    }elseif($info->type=="images"){
                        $data=$this->get_image($info->image_id);
                        $this->reply_image($from,$to,$data->media_id);
                    }elseif($info->type=="news"){
                        $this->reply_news($from,$to,$this->get_news($info->group_id));
                    }
                   
                }
            }elseif ($type=='image') {
                
            }elseif($type=='voice'){

            }elseif($type=='video'){
                
            }
        }
        exit;
    }
    public function actionTest()
    {
        echo phpinfo();
        die;
    }

}
