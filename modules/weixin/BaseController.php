<?php

namespace app\modules\weixin;

use app\models\MaterialNews;
use app\models\Picture;
use app\models\PushTask;
use app\models\User;
use app\models\UserGroup;
use app\models\WeixinUser;
use Yii;
use yii\db\Query;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\db\ActiveRecord;
use yii\base\Model;
use app\models\PublicList;
use app\models\WeixinMessage;
//use app\models\SendMessage;
use app\models\MaterialImage;
use yii\web\UrlManager;
use app\models\UploadForm;
use app\models\Role;
class BaseController extends Controller
{
	/**
	 * 获取access_token
	 */
	public function get_access_token($token='')
	{
		$cache_name = './cache/access_token';
		if(empty($token)){
			$token=(new PublicList())->get_token();
		}
		$data = json_decode(file_get_contents($cache_name),true);

		if(!empty($data[$token]) &&  $data[$token]['expire_time'] > time())
		{
			$access_token=$data[$token]['access_token'];
		}
		else
		{

			$params=$this->get_appid($token);
			$params['grant_type']="client_credential";
			$info = http('https://api.weixin.qq.com/cgi-bin/token',$params,"GET");
			$info = json_decode($info,true);
			if(isset($info['access_token']))
			{
				$data[$token]['access_token']= $info['access_token'];
				$access_token=$info['access_token'];
				$data[$token]['expire_time']= time() + 7000;
				$info=json_decode(file_get_contents($cache_name),true);
				if(!empty($info)){
					$data=array_merge($info,$data);
				}
				file_put_contents($cache_name, json_encode($data));
			}
			else{
				return false;
			}
		}
		return $access_token;
	}
	/**
	 * 获取微信appid和secret
	 */
	public function get_appid($token)
	{
		$info=(new Query)->select(['appid','secret'])->from('public_list')->where(['token'=>$token])->one();
		return $info;
	}
	/**
	 * 写入微信文本消息
	 */
	public function write_message_text($toUser,$fromUser,$msgType,$msgId,$time,$content)
	{
		$model=new WeixinMessage();
		$model->ToUserName=$toUser;
		$model->FromUserName=$fromUser;
		$model->CreateTime=$time;
		$model->MsgType=$msgType;
		$model->MsgId=$msgId;
		$model->Content=$content;
		$model->save();
	}
	/**
	 * 写入微信图片消息
	 */
	public function write_message_images($toUser,$fromUser,$msgType,$msgId,$time,$picurl,$media_id)
	{
		$model=new WeixinMessage();
		$model->ToUserName=$toUser;
		$model->FromUserName=$fromUser;
		$model->CreateTime=$time;
		$model->MsgType=$msgType;
		$model->MsgId=$msgId;
		$model->PicUrl=$picurl;
		$model->MediaId=$media_id;
		$model->save();
	}
	/**
	 * 写入微信语音消息
	 */
	public function write_message_voice($toUser,$fromUser,$msgType,$msgId,$time,$media_id,$format)
	{
		$model=new WeixinMessage();
		$model->ToUserName=$toUser;
		$model->FromUserName=$fromUser;
		$model->CreateTime=$time;
		$model->MsgType=$msgType;
		$model->MsgId=$msgId;
		$model->MediaId=$media_id;
		$model->format=$format;
		$model->save();
	}
	/**
	 * 写入微信视频消息
	 */
	public function write_message_video($toUser,$fromUser,$msgType,$msgId,$time,$media_id,$ThumbMediaId)
	{
		$model=new WeixinMessage();
		$model->ToUserName=$toUser;
		$model->FromUserName=$fromUser;
		$model->CreateTime=$time;
		$model->MsgType=$msgType;
		$model->MsgId=$msgId;
		$model->MediaId=$media_id;
		$model->ThumbMediaId=$ThumbMediaId;
		$model->save();
	}
	/**
	 * 写入发送消息
	 */
	public function write_send_message()
	{

	}
	/**
	 * 获取欢迎语
	 */
	public function get_welcome($token,$to)
	{
		$model=PublicList::find()->select(['public_config'])->where(['token'=>$token])->one();
		$config=json_decode($model->public_config,true);
		if(!empty($config['welcome']))
		{
			$config=$config['welcome'];
			if($config['type']==2)
			{
				$this->reply_news($to,$token,$this->get_news($config['group_id']));
			}else{
				$this->reply_text($to,$token,$config['description']);
			}
		}
	}
	/**
	 * 未识别回复
	 */
	public function get_unknow($token,$to)
	{
		$model=PublicList::find()->select(['public_config'])->where(['token'=>$token])->one();
		$config=json_decode($model->public_config,true);
		if(!empty($config['unkown']))
		{
			$config=$config['unkown'];
			if($config['type']==2)
			{
				$this->reply_news($to,$token,$this->get_news($config['group_id']));
			}else{
				$this->reply_text($to,$token,$config['description']);
			}
		}
	}
	/**
	 * 获取图文信息
	 */
	public function get_news($group_id)
	{
		$list=(new \yii\db\Query())->select(['title','introduction as description','material_news.url','path as picurl'])->from('material_news')->leftJoin("picture",'material_news.cover_id=picture.id')->groupBy('group_id')->where(['group_id'=>$group_id])->all();
		return $list;
	}
	/**
	 * 获取模板消息列表
	 */
	function get_template()
	{
		$url='https://api.weixin.qq.com/cgi-bin/template/get_all_private_template';
		$paramas['access_token']=$this->get_access_token();
		$data=http($url,$paramas);
		$data=json_decode($data,true);
		return $data;

	}
	/**
	 * 删除模板消息
	 */
	function delete_template($template_id)
	{

		$url='https://api.weixin.qq.com/cgi-bin/template/del_private_template?access_token='.$this->get_access_token();
		$paramas['template_id']=$template_id;
		$data=http($url,json_encode($paramas),'POST');
		$data=json_decode($data,true);
		return $data;

	}
	/**
	 * 发送模板消息
	 *
	 */
	function send_template($info)
	{

		$url='https://api.weixin.qq.com/cgi-bin/message/template/send?access_token='.$this->get_access_token('gh_c84e60466f30');
		/*$str='{"touser":"oshvBvlLTYBmZFHR4mGvGi_jS96k",
		"template_id":"Y60b18SexQPoUq4THeGOTWotstk7fx5W_shI8PXDniE",
           "url":"",
            "data":{
                   "result": {
                       "value":"奖金领取通知",
                       "color":"#173177"
                   },
                   "withdrawMoney":{
                       "value":"88888888元",
                       "color":"#173177"
                   },
                   "withdrawTime": {
                       "value":"2016年5月17日",
                       "color":"#173177"
                   },
                   "cardInfo": {
                       "value":"中国民生银行（卡号：6226****4558）",
                       "color":"#173177"
                   },
                   "arrivedTime": {
                       "value":"2016年5月18日",
                       "color":"#173177"
                   },
                   "remark":{
                       "value":"请注意查收！",
                       "color":"#FF0000"
                   }
           }
		}';*/
		$data=http($url,$info,'POST');
		return $data;
	}

	// 发送文本消息
	function send_text($to,$from,$content)
	{
		$data = array(
				'touser' => "$to",
				'msgtype' => 'text',
				'text' => array(
						'content' => "$content",
				),
		);
		$data = json_encode($data);
		return http('https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token='.$this->get_access_token((string)$from), $data,'POST');
	}
	// 发送图片消息
	function send_image($to, $picurl)
	{
		//if(($media_id = upload($picurl, 'image')) === FALSE)
		//	return FALSE;
		$data = array(
				'touser' => "$to",
				'msgtype' => 'image',
				'image' => array(
						'media_id' => "$media_id",
				),
		);
		$data = json_encode($data);
		return http('https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token='.$this->get_access_token(), $data,'POST');
	}
	// 发送语音消息
	function send_voice($to, $voiceurl)
	{
		//if(($media_id = upload($voiceurl, 'voice')) === FALSE)
		//	return FALSE;
		$data = array(
				'touser' => "$to",
				'msgtype' => 'voice',
				'voice' => array(
						'media_id' => "$media_id",
				),
		);
		$data = json_encode($data);
		return http('https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token='.$this->get_access_token(), $data, 'POST');
	}
	// 发送视频消息
	function send_video($to, $title, $description, $videourl)
	{
		//if(($media_id = upload($videourl, 'video')) === FALSE)
		//	return FALSE;
		$data = array(
				'touser' => "$to",
				'msgtype' => 'video',
				'video' => array(
						'media_id' => "$media_id",
						'title' => "$title",
						'description' => "$description",
				),
		);
		$data = json_encode($data);
		return http('https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token='.$this->get_access_token(), $data,'POST');
	}
	// 发送音乐消息
	function send_music($to, $title, $description, $musicurl, $hqmusicurl, $thumbImageUrl)
	{
		//if(($thumb_media_id = upload($thumbImageUrl, 'image')) === FALSE)
		//	return FALSE;
		$data = array(
				'touser' => "$to",
				'msgtype' => 'music',
				'music' => array(
						'title' => "$title",
						'description' => "$description",
						'musicurl' => "$musicurl",
						'hqmusicurl' => "$hqmusicurl",
						'thumb_media_id' => "$thumb_media_id",
				),
		);
		$data = json_encode($data);
		return http('https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token='.$this->get_access_token(), $data,'POST');
	}
	// 发送图文消息
	function send_news($to, $content)
	{
		$data = array(
				'touser' => "$to",
				'msgtype' => 'news',
				'news' => array(
						'articles' => "$content",
				),
		);
		$data = json_encode($data);
		return http('https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token='.$this->get_access_token(), $data, 'POST');
	}
	function getUserInfo($openId,$to='')
	{
		$params ['access_token'] =$this->get_access_token((string)$to);
		$params ['openid'] = (string)$openId;
		$params ['lang'] = 'zh_CN';
		//$data = http('https://api.weixin.qq.com/cgi-bin/user/info',$params,'GET');
		$url='https://api.weixin.qq.com/cgi-bin/user/info'. '?' . http_build_query($params);
		$data = file_get_contents ( $url );
		$data = json_decode($data,true);
		return $data;
	}
	function deleteUser($openid){
		$model=User::find()->where(['openid'=>$openid])->one();
		$model->is_del=2;
		$model->save();

	}
	/**
	 * 获取微信用户列表
	 */
	public function get_user_list()
	{
		$params['access_token']=$this->get_access_token();
		$params['next_openid']='';
		$url='https://api.weixin.qq.com/cgi-bin/user/get';
		$data=http($url,$params,'GET');
		$data=json_decode($data,true);
		return $data;
	}
	/**
	 * 获取微信素材列表
	 * $type 1、news，2、image，3、video，4、voice
	 */
	public function get_material_list($type="news",$offset=0,$count=20)
	{
		$url='https://api.weixin.qq.com/cgi-bin/material/batchget_material?access_token='.$this->get_access_token();
		$str='{
   			 	"type":"'.$type.'",
    			"offset":'.$offset.',
    			"count":'.$count.'
			  }';
		$data=http($url,$str,'POST');
		return json_decode($data,true);
	}
	/**
	 * 上传微信素材
	 * $type 1、news，2、image，3、video，4、voice
	 */
	public function send_material($type="news",$info)
	{
		if($type=='news'){
			$url='https://api.weixin.qq.com/cgi-bin/material/add_news?access_token='.$this->get_access_token();

			if(!isset($info['title'])){
				foreach($info as $va){
					$data ['title'] = $va ['title'];
					$data ['thumb_media_id'] = empty ( $va ['thumb_media_id'] ) ? $this->thumb_media_id ( $va ['cover_id'] ) : $va ['thumb_media_id'];
					$data ['author'] = $va['author'];
					$data ['digest'] = $va['introduction'];
					$data ['show_cover_pic'] = 1;
					$data ['content'] = str_replace ( '"', '\'', $va ['content'] );
					$data ['content_source_url'] = $va['link'];
					$articles [] = $data;
				}
			}else {
				$data ['title'] = $info ['title'];
				$data ['thumb_media_id'] = empty ( $info ['thumb_media_id'] ) ? $this->thumb_media_id ( $info ['cover_id'] ) : $info ['thumb_media_id'];
				$data ['author'] = $info['author'];
				$data ['digest'] = $info['introduction'];
				$data ['show_cover_pic'] = 1;
				$data ['content'] = str_replace ( '"', '\'', $info ['content'] );
				$data ['content_source_url'] = $info['link'];
				$articles [] = $data;
			}
		}else{


		}
		$param ['articles'] = $articles;
		file_put_contents('./cache/str',json_encode($param));
		//$data=http($url,$str,'POST');
		$data = post_data ( $url, $param );
		file_put_contents('./cache/result',json_encode($data));
		return $data;
	}
	public function get_image_media_id($cover_id,$type=1) {
		if($type==1){
			$info=(new Query)->select(['media_id','id'])->from('material_image')->where(['cover_id'=>$cover_id])->one();
		}else{
			$info=(new Query)->select(['media_id','id','cover_id'])->from('material_image')->where(['id'=>$cover_id])->one();
			$cover_id=$info['cover_id'];
		}
		if(empty($info['media_id'])){
			$model=Picture::findOne($cover_id);
			$path ='.'.$model->path;
			$param ['type'] = 'image';
			$param ['media'] = '@' . realpath($path);
			$url = 'https://api.weixin.qq.com/cgi-bin/material/add_material?access_token=' . $this->get_access_token ();
			$res = post_data ( $url, $param, true );
			$model->url=$res['url'];
			$model->save();
			if(empty($info['id'])){
				$material=new MaterialImage();
				$material->cover_id=$cover_id;
				$material->cover_url=$model->path;
				$material->media_id=$res['media_id'];
				$material->wechat_url=$res['url'];
				$material->add_time=time();
				$material->user_id=Yii::$app->user->getId();
				$material->token=(new PublicList)->get_token();
				$material->is_use=1;
				$material->save();
			}else{
				MaterialImage::updateAll(['media_id'=>$res['media_id'],'wechat_url'=>$res['url']],['cover_id'=>$cover_id]);
			}
			return $res['media_id'];
		}else{
			return $info ['media_id'];
		}


	}

	/**
	 * @param $group_id
	 * @return bool
	 * 获取mediaid
	 */
	public function getMediaIdByGroupId($group_id) {
		$list = MaterialNews::find()->where ( ['group_id'=>$group_id])->orderBy( 'id asc' )->asArray()->all();
		if (! empty ( $list [0] ['media_id'] ))
			return $list [0] ['media_id'];

		// 自动同步到微信端
		foreach ( $list as $vo ) {
			$data ['title'] = $vo ['title'];
			$data ['thumb_media_id'] = empty ( $vo ['thumb_media_id'] ) ? $this->thumb_media_id ( $vo ['cover_id'] ) : $vo ['thumb_media_id'];
			$data ['author'] = $vo ['author'];
			$data ['digest'] = $vo ['introduction'];
			$data ['show_cover_pic'] = 1;
			$data ['content'] = $vo ['content'];
			$data ['content_source_url'] = $vo['url'];

			$articles [] = $data;
		}

		$url = 'https://api.weixin.qq.com/cgi-bin/material/add_news?access_token=' . $this->get_access_token ();
		$param ['articles'] = $articles;

		$res = post_data ( $url, $param );
		if ($res ['errcode'] != 0) {
			return false;
		} else {
			MaterialNews::updateAll(['group_id'=>$group_id],$res['media_id']);
			return $res ['media_id'];
		}
	}


	function thumb_media_id($cover_id) {
		$model=Picture::findOne($cover_id);
		$path ='.'.$model->path;
		$param ['type'] = 'thumb';
		$param ['media'] = '@' . realpath($path);
		$url = 'https://api.weixin.qq.com/cgi-bin/material/add_material?access_token=' . $this->get_access_token ();
		$res = post_data ( $url, $param, true );
		MaterialNews::updateAll(['thumb_media_id'=>$res['media_id']],['cover_id'=>$cover_id]);
		return $res ['media_id'];
	}
	function image_media_id($cover_id) {
		$cover=(new Query)->select('*')->from('picture')->where(['id'=>$cover_id])->one();
		// 先把图片下载到本地
		if(!empty($cover['url'])){
			$pathinfo = pathinfo ($cover ['path'] );
			mkdirs ( $pathinfo ['dirname'] );
			$content = file_get_contents_time ( $cover ['url'] );
			$res = file_put_contents ( $cover ['path'], $content );
			if (! $res) {
				die( '远程图片下载失败' );
			}
		}
		$path = '.'.$cover ['path'];
		$param ['type'] = 'image';
		$param ['media'] = '@' . realpath ( $path );
		$url = 'https://api.weixin.qq.com/cgi-bin/material/add_material?access_token=' . $this->get_access_token ();
		$res = post_data ( $url, $param, true );
		if (isset ( $res ['errcode'] ) && $res ['errcode'] != 0) {
			die( error_msg ( $res, '图片上传' ) );
		}
		$model=Picture::findOne($cover_id);
		$model->url=$res['url'];
		$model->save();
		return $res ['media_id'];
	}
	/**
	 * 将微信服务器图片下载到本地
	 */
	public function download_imgage($media_id, $picUrl = '') {
		$savePath =  'uploads/' . date('Ymd');
		mkdirs ( $savePath );
		if (empty ( $picUrl )) {
			// 获取图片URL
			$url = 'https://api.weixin.qq.com/cgi-bin/material/get_material?access_token=' . $this->get_access_token ();
			$param ['media_id'] = $media_id;
			$picContent = post_data ( $url, $param, false, false );
			$picName = call_user_func_array('uniqid',array()). '.jpg';
			$picPath = $savePath . '/' . $picName;
			$res = file_put_contents ( $picPath, $picContent );
			if (isset ( $picContent ['errcode'] ) && $picContent ['errcode'] != 0) {
				die('下载图片失败');
			}
		} else {
			$content = file_get_contents_time ( $picUrl );
			// 获取图片扩展名
			/*$picExt = substr ( $picUrl, strrpos ( $picUrl, '=' ) + 1 );
			// $picExt=='jpeg'
			if (empty ( $picExt ) || $picExt == 'jpeg') {
				$picExt = 'jpg';
			}*/
			$picName = call_user_func_array('uniqid',array()) . '.jpg';
			$picPath = $savePath . '/' . $picName;
			$res = file_put_contents ($picPath, $content );
			if (! $res) {
				die('下载图片失败');
			}
		}
		$cover_id = 0;
		if ($res) {
			// 保存记录，添加到picture表里，获取coverid
			$_FILES ['UploadForm[file]'] = array (
					'name' => $picName,
					'type' => 'application/octet-stream',
					'tmp_name' => $picPath,
					'size' => $res,
					'error' => 0
			);
			$info=(new UploadForm())->uploadPicture($_FILES);
			$cover_id = $info ['id'];
		}
		return $cover_id;
	}
	// 获取图文素材url
	public function news_url($media_id) {
		$url = 'https://api.weixin.qq.com/cgi-bin/material/get_material?access_token=' . $this->get_access_token ();
		$param ['media_id'] = $media_id;
		$news = post_data ( $url, $param );
		if (isset ( $news ['errcode'] ) && $news ['errcode'] != 0) {
			die( error_msg ( $news ) );
		}
		foreach ( $news ['news_item'] as $vo ) {
			$newsUrl [$vo ['title']] = $vo ['url'];
		}
		return $newsUrl;
	}

	/**
	 * 上传图文内容图片
	 *
	 */
	public function upload_image_to_wechat($path)
	{
		$url="https://api.weixin.qq.com/cgi-bin/media/uploadimg?access_token=". $this->get_access_token ();
		$path = '.'.$path;
		$param ['media'] = '@' . realpath ( $path );
		$res = post_data ( $url, $param, true );
		if (isset ( $res ['errcode'] ) && $res ['errcode'] != 0) {
			die( error_msg ( $res, '图片上传' ) );
		}
		return $res['url'];
	}
	/**
	 * 上传图文内容视频
	 *
	 */
	public function upload_video_to_wechat($path,$title="视频",$introduction='图文消息内容视频')
	{
		$url="https://api.weixin.qq.com/cgi-bin/material/add_material?access_token=". $this->get_access_token ()."&type=video";
		$path = '.'.$path;
		$param ['media'] = '@' . realpath ( $path );
		$param['description']['title']=$title;
		$param['description']['introduction']=$introduction;
		$res = post_data ( $url, $param);
		if (isset ( $res ['errcode'] ) && $res ['errcode'] != 0) {
			die( error_msg ( $res, '视频上传' ) );
		}
		return $res['url'];
	}
	public function get_picture_url($cover_id,$flag=1)
	{
		$model=Picture::findOne($cover_id);
		if($flag==2){
			return $model->url;
		}else{
			return $model->path;
		}
	}
	/**
	 * 将获取的微信材写入数据库
	 */
	public function write_material($type,$info)
	{
		if($type=='news')
		{

			$user_id=Yii::$app->user->getId();
			$token=(new PublicList())->get_token();
			foreach($info['item'] as $v){
				if(!empty($v['media_id'])){
					$model=new MaterialNews();
					$flag=$model->find()->where(['media_id'=>$v['media_id'],'token'=>$token])->one();
					if(empty($flag))
					{
						foreach($v['content']['news_item'] as $va) {
							$model->title = $va['title'];
							$model->thumb_media_id = $va['thumb_media_id'];
							$model->media_id=$v['media_id'];
							$model->cover_id = $this->download_imgage ( $va ['thumb_media_id'] );
							$model->author = $va['author'];
							$model->introduction = $va['digest'];
							$model->content = $va['content'];
							$model->url = $va['url'];
							$model->user_id = $user_id;
							$model->token = $token;
							$model->add_time=time();
							$model->save();
							if ($model->id) {
								$ids [] = $model->id;
							}
						}
						if (! empty ( $ids )) {
							$group_id = $ids [0];
							$model->updateAll(['group_id'=>$group_id],['in','id',$ids]);
						}
					}
				}
			}

		}else
		{
			if($type=='image')
			{
				$user_id=Yii::$app->user->getId();
				$token=(new PublicList())->get_token();
				foreach($info['item'] as $v){
					if(!empty($v['media_id']) && !empty($v['url'])){
						$model=new MaterialImage();
						$flag=$model->find()->where(['media_id'=>$v['media_id'],'token'=>$token])->one();
						if(empty($flag)){
							$model->media_id=$v['media_id'];
							$model->wechat_url=$v['url'];
							$model->add_time=time();
							$model->cover_id= $this->download_imgage ( $v['media_id'], $v['url'] );
							$model->cover_url=$this->get_picture_url($model->cover_id);
							$model->user_id=$user_id;
							$model->token=$token;
							$model->is_use=1;
							$model->save();
						}
					}
				}
			}elseif($type=='voice')
			{

			}elseif($type=='video'){

			}
		}
	}
	/**
	 * @param $userInfo
	 * @param $token
	 */
	function write_user($userInfo,$token)
	{
		$model=new WeixinUser();
		$model->openid=$userInfo['openid'];
		$model->sex=$userInfo['sex'];
		$model->nickname=$userInfo['nickname'];
		$model->subscribe_time=$userInfo['subscribe_time'];
		$model->weixin_group_id=(string)$userInfo['groupid'];
		$model->province=$userInfo['province'];
		$model->city=$userInfo['city'];
		$model->group_id=$this->get_user_group_id($userInfo['groupid'],$token);
		$model->remark=$userInfo['remark'];
		$model->userface=$userInfo['headimgurl'];
		$model->token=$token;
		$model->is_del=1;
		$model->save();
	}
	function get_user_group_id($weixin_group_id,$token)
	{
		$model=new UserGroup();
		$group=$model->find()->select(['id'])->where(['weixin_group_id'=>$weixin_group_id])->one();
		if(!empty($group)){
			return $group->id;
		}else{
			$group=$model->find()->select(['id'])->where(['token'=>$token,'weixin_group_id'=>''])->one();
			if(!empty($group))
			{
				return $group->id;
			}else{
				$model->token=$token;
				$model->group_name="未分组";
				$model->sort=1;
				$model->add_time=time();
				$model->update_time=time();
				$model->is_del=0;
				$model->save();
				return $model->id;
			}
		}
	}
	// 回复文本消息
	public function reply_text($to,$from,$content)
	{
		$textTpl = "<xml>
					<ToUserName><![CDATA[%s]]></ToUserName>
					<FromUserName><![CDATA[%s]]></FromUserName>
					<CreateTime>%s</CreateTime>
					<MsgType><![CDATA[%s]]></MsgType>
					<Content><![CDATA[%s]]></Content>
					<FuncFlag>0</FuncFlag>
					</xml>";
		$resultStr = sprintf($textTpl, $to, $from, time(), 'text', $content);
		echo $resultStr;
	}
	// 回复图片消息
	function reply_image($to,$from, $mediaId)
	{

		$textTpl = "<xml>
					<ToUserName><![CDATA[%s]]></ToUserName>
					<FromUserName><![CDATA[%s]]></FromUserName>
					<CreateTime>%s</CreateTime>
					<MsgType><![CDATA[%s]]></MsgType>
					<Image>
						<MediaId><![CDATA[%s]]></MediaId>
					</Image>
					</xml>";
		$resultStr = sprintf($textTpl, $to, $from, time(), 'image', $mediaId);
		echo $resultStr;
	}
	// 回复语音消息
	function reply_voice($to, $url)
	{
		//if(($mediaId = upload($url, 'voice')) === FALSE)
		//	return FALSE;
		$textTpl = "<xml>
					<ToUserName><![CDATA[%s]]></ToUserName>
					<FromUserName><![CDATA[%s]]></FromUserName>
					<CreateTime>%s</CreateTime>
					<MsgType><![CDATA[%s]]></MsgType>
					<Voice>
						<MediaId><![CDATA[%s]]></MediaId>
					</Voice>
					</xml>";

		$resultStr = sprintf($textTpl, $to, $from, time(), 'voice', $mediaId);
		echo $resultStr;
	}
	// 回复视频消息
	function reply_video($to, $title, $description, $url)
	{
		//if(($mediaId = upload($url, 'video')) === FALSE)
		//	return FALSE;
		$textTpl = "<xml>
					<ToUserName><![CDATA[%s]]></ToUserName>
					<FromUserName><![CDATA[%s]]></FromUserName>
					<CreateTime>%s</CreateTime>
					<MsgType><![CDATA[%s]]></MsgType>
					<Video>
						<MediaId><![CDATA[%s]]></MediaId>
						<Title><![CDATA[%s]]></Title>
						<Description><![CDATA[%s]]></Description>
					</Video>
					</xml>";

		$resultStr = sprintf($textTpl, $to, $from, time(), 'video', $mediaId, $title, $description);
		echo $resultStr;
	}
	// 回复音乐消息
	function reply_music($to, $title, $description, $musicUrl, $hdMusicUrl, $thumbImageUrl)
	{
		//if(($mediaId = upload($thumbImageUrl, 'thumb')) === FALSE)
		//	return FALSE;
		$textTpl = "<xml>
					<ToUserName><![CDATA[%s]]></ToUserName>
					<FromUserName><![CDATA[%s]]></FromUserName>
					<CreateTime>%s</CreateTime>
					<MsgType><![CDATA[%s]]></MsgType>
					<Music>
						<Title><![CDATA[%s]]></Title>
						<Description><![CDATA[%s]]></Description>
						<MusicUrl><![CDATA[%s]]></MusicUrl>
						<HQMusicUrl><![CDATA[%s]]></HQMusicUrl>
						<ThumbMediaId><![CDATA[%s]]></ThumbMediaId>
					</Music>
					</xml>";

		$resultStr = sprintf($textTpl, $to, $from, time(), 'music', $title, $description, $musicUrl, $hdMusicUrl, $mediaId);
		echo $resultStr;
	}
	// 回复图文消息
	function reply_news($to,$from,$articles)
	{
		$count = count($articles);
		$items = '';
		$_textTpl = "<item>
							<Title><![CDATA[%s]]></Title>
							<Description><![CDATA[%s]]></Description>
							<PicUrl><![CDATA[%s]]></PicUrl>
							<Url><![CDATA[%s]]></Url>
						</item>";
		foreach ($articles as $k => $v)
		{
			$v['picurl']=Yii::$app->request->getHostInfo().$v['picurl'];
			$items .= sprintf($_textTpl, $v['title'], $v['description'], $v['picurl'], $v['url']);
		}
		$textTpl = "<xml>
					<ToUserName><![CDATA[%s]]></ToUserName>
					<FromUserName><![CDATA[%s]]></FromUserName>
					<CreateTime>%s</CreateTime>
					<MsgType><![CDATA[%s]]></MsgType>
					<ArticleCount>%s</ArticleCount>
					<Articles>
						%s
					</Articles>
					</xml> ";

		$resultStr = sprintf($textTpl, $to, $from, time(), 'news', $count, $items);
		echo $resultStr;
	}


	/**
	 * 获取当前用户的角色清单
	 * @return array
	 */
	function  get_role_ids()
	{
		$list=Role::find()->innerJoin("sys_user_role",'sys_user_role.role_id=sys_role.id')
				->where(['sys_user_role.user_id'=>Yii::$app->user->identity->id])->indexBy('id')
				->all();
		return implode(',', array_keys($list));
	}

	/**
	 * 获取当前用户的用户组
	 * @return array
	 */
	function get_dept_ids()
	{
		$connection  = Yii::$app->db;
		$sql = "select GROUP_CONCAT(id) as ids from sys_user_dept where user_id=:id";
		$command = $connection->createCommand($sql);
		$command->bindValue(':id', Yii::$app->user->identity->id);
		$result=$command->queryAll();
		return $result[0]['ids']==NULL?'':$result[0]['ids'];

	}


	/**
	 * 获取当前用户有权限的应用清单
	 * @return array
	 */
	function get_app_info()
	{
		$connection  = Yii::$app->db;
		$sql = "select app_code,app_name,app_icon from sys_application as a
                inner join sys_privilege as p  on a.id=p.PrivilegeAccessValue and p.PrivilegeAccess=1
                where (p.PrivilegeMaster =1 and p.PrivilegeMasterValue=:id)";

		$role_ids=$this->get_role_ids();
		if($role_ids!=NULL&&$role_ids!='')
		{
			$sql.=" or (p.PrivilegeMaster=2 and p.PrivilegeMasterValue IN ($role_ids)) ";
		}
		$dept_ids=$this->get_dept_ids();
		if($dept_ids!=NULL&&$dept_ids!='')
		{
			$sql.=" or (p.PrivilegeMaster=3 and p.PrivilegeMasterValue in ($dept_ids))";
		}

		$command = $connection->createCommand($sql);
		$command->bindValue(':id', Yii::$app->user->identity->id);
		$result=$command->queryAll();
		return $result;
	}

	/**
	 * 获取当前用户可访问的页面清单
	 * @return array
	 */
	function get_menu_info($app_code)
	{
		$connection  = Yii::$app->db;
		$sql = "select id,menu_name as text,menu_url as href,menu_parent_no from sys_menu as m
        INNER join sys_privilege as p on p.PrivilegeAccess=2 and p.PrivilegeAccessValue=m.id
        where m.app_code='$app_code' and ((p.PrivilegeMaster=1 and p.PrivilegeMasterValue=:id)";


		$role_ids=$this->get_role_ids();
		if($role_ids!=NULL&&$role_ids!='')
		{
			$sql.=" or (p.PrivilegeMaster=2 and p.PrivilegeMasterValue IN ($role_ids)) ";
		}
		$dept_ids=$this->get_dept_ids();
		if($dept_ids!=NULL&&$dept_ids!='')
		{
			$sql.=" or (p.PrivilegeMaster=3 and p.PrivilegeMasterValue in ($dept_ids))";
		}
		$sql.=") order by m.menu_order ";
		$command = $connection->createCommand($sql);
		$command->bindValue(':id', Yii::$app->user->identity->id);
		$result=$command->queryAll();

		return $result;
	}



}
