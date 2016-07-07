<?php

namespace app\modules\push;
use app\models\CourseRoom;
use app\models\PushTask;
use app\models\ShopInfo;
use app\models\User;
use Yii;
use yii\db\Query;
use yii\helpers\Url;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\db\ActiveRecord;
class PushBaseController extends Controller
{


	/**
	 * 添加课程推送
	 * @param $course_class
	 */
	function add_course_push($view)
	{
		$shop_info=ShopInfo::find()->select(['name'])->where(['id'=>$view->shop_id])->one();
		$room_info=CourseRoom::find()->select(['video_code'])->where(['id'=>$view->course_room])->one();
		$user_info=(new Query())->select(['user.id','nickname','openid'])->from('user')->rightJoin('user_child_info','user.id=user_child_info.user_id')->rightJoin('course_place_child','user_child_info.child_id=course_place_child.child_id')->where(['course_place_child.course_place_id'=>$view->course_class])->distinct()->andWhere(['!=','openid',''])->all();
		foreach($user_info as $vo){
			$content['first']['value']="亲爱的".$vo['nickname']."同学，你有一门课即将开始。\n";
			$content['first']['color']="#0ea7ff";
			$content['keyword1']['value']=$view->course_name;
			$content['keyword1']['color']="#333333";
			$content['keyword2']['value']=$view->course_date." ".$view->start_time.":".$view->start_m."-".$view->end_time.":".$view->end_m;
			$content['keyword2']['color']="#FF0000";
			$content['keyword3']['value']=$shop_info->name;
			$content['keyword3']['color']="#333333";
			$content['remark']['value']="\n 如需请假，请打电话给我们的老师！";
			$content['remark']['color']="#333333";
			$data['touser']=$vo['openid'];
			$data['template_id']="ynji80DuisBTN8gleSWYa496uZiUUsd1II6bSKVW7F8";
			$data['url']=Yii::$app->request->getHostInfo()."/course/live?code=".$room_info->video_code."&id=".$view->id;
			$data['data']=$content;
			$this->add_push_task($vo['id'],json_encode($data),$view->id,$view->live_start_date,'0');
		}
	}

	/**
	 * 添加推送任务
	 * @param $touser
	 * @param $send_content
	 * @param $masterValue
	 * @param $send_time
	 * @param string $send_type
	 * @return bool
	 *
	 */
	function add_push_task($touser,$send_content,$masterValue,$send_time,$send_type='0')
	{
		$task=new PushTask();
		$task->touser=$touser;
		$task->send_content=$send_content;
		$task->master=2;
		$task->masterValue=$masterValue;
		$task->send_time=$send_time;
		$task->send_type=$send_type;
		$task->status=0;
		$flag=$task->save();
		return $flag;
	}

}
