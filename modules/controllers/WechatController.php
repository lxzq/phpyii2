<?php

namespace app\modules\controllers;
use app\models\CourseView;
use app\models\User;
use app\models\WeixinUser;
use Yii;
use yii\db\Query;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use app\models\PublicList;
use yii\web\Session;
use app\modules\weixin\BaseController;
use app\models\AutoReply;
use app\models\MaterialImage;
class WechatController extends BaseController
{
    public $enableCsrfValidation = false;
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
                ob_clean();
                echo $echoStr;
                exit;
            }
        }
        $postStr = empty($GLOBALS ["HTTP_RAW_POST_DATA"])?'':$GLOBALS ["HTTP_RAW_POST_DATA"];
        ! empty ( $postStr ) || die ( '这是微信请求的接口地址，直接在浏览器里无效' );
        //判断是否存在数据
        if (! empty ( $postStr )) {
            
            // 解析xml
            libxml_disable_entity_loader ( true );
            // simplexml_load_string载入xml到字符串
            $postObj = simplexml_load_string ( $postStr, 'SimpleXMLElement', LIBXML_NOCDATA );
            $type = $postObj->MsgType;
            $from = $postObj->FromUserName;
            $to = $postObj->ToUserName;
            if(!empty($postObj->Event)){
            	$event = $postObj->Event;
            }else{
            	$event='text';
            }
            $id = $postObj->MsgId;
            // 时间戳
            $time = $postObj->CreateTime;
            if ($type == 'event' && $event == 'subscribe') {
                $user=WeixinUser::find()->where(['openid'=>$from,'token'=>$to])->one();
                if(empty($user)){
                    $user_info=$this->getUserInfo($from,$to);
                    $this->write_user($user_info,$to);
                }else{
                    $user->is_del=1;
                    $user->save();
                }
                $this->get_welcome($to,$from);
            }elseif($type == 'event' && $event == 'unsubscribe'){
                $this->deleteUser($from);
                //修改微信用户关注状态
            }elseif ($type == 'text') {
                // 接收用户发送过来的数据，存储$keyword里
                $keyword = trim ( $postObj->Content );
                // 判断用户传递过来文本消息是否为空
                if (! empty ( $keyword )) {
                    $this->write_message_text($to,$from,$type,$id,$time,$keyword);
                    $info=AutoReply::find()->where(['token'=>$to,'keyword'=>$keyword])->one();
                    if($info){
                    	if($info->type=="text"){
	                        $this->reply_text($from,$to,$info->content);
	                    }elseif($info->type=="images"){
	                       $media_id=$this->get_image_media_id($info->image_id);
	                        $this->reply_image($from,$to,$media_id);
	                    }elseif($info->type=="news"){
	                        $this->reply_news($from,$to,$this->get_news($info->group_id));
	                    }else{
	                    	$this->reply_text($from,$to,'你好');
	                    }
                    }else{
                        $this->get_unknow($to,$from);
                    }
                }
            }elseif ($type=='image') {
                
            }elseif($type=='voice'){

            }elseif($type=='video'){
                
            }elseif($type=='event' && $event=='CLICK'){
                $key = $postObj->EventKey;
                if($key=='teacher_sign'){
                    $teacher=WeixinUser::find()->select(['id'])->where(['openid'=>$from])->one();
                    if(!empty($teacher->id)){
                        $course=(new Query())->select(['course_name','c.name as class_name','r.name as room_name','live_start_date'])->from('course_view')->leftJoin('course_place_class as c','course_class=c.id')->leftJoin('course_room as r','course_room=r.id')->where(['course_teacher'=>$teacher->id])->one();
                        $info="上课教室：".$course['room_name']."\n\n上课班级：<a href='http://weixin.test.happycity777.com/activity/list'>".$course['class_name']."</a>\n\n课程：<a href='http://test.happycity777.com' style='color:#cccccc'>".$course['course_name']."</a>\n\n上课时间：".$course['live_start_date'];
                        $this->reply_text($from,$to,$info);
                    }
                }
            }
        }
        exit;
    }

    /**
     * 发送模板消息
     */
    public function actionSendTemplate()
    {
        $request=Yii::$app->request;
        $info=$request->post('str');
        $data=$this->send_template($info);

        if(!empty($data['errcode']) && $data['errcode']!=0){
            //发送错误

        }else{
            //发送失败

        }
    }

    /**
     * 测试
     */
    public function actionTest()
    {
        file_put_contents('./cache/go',json_encode($_REQUEST));
        $info['status']=10;
        $info['test']="你好吗？我的朋友";
        echo json_encode($info);
    }

}
