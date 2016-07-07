<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\VideoForm */

use yii\helpers\Html;
use yii\widgets\LinkPager;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
?>
<!DOCTYPE HTML>
<html lang="zh-CN">
<head>
	<meta charset="UTF-8">
    <?= Html::cssFile('@web/font-awesome/css/font-awesome.min.css') ?>
    <?= Html::cssFile('@web/weixin/css/base.css') ?>
    <?= Html::cssFile('@web/weixin/css/module.css') ?>

    <?= Html::cssFile('@web/weixin/css/weixin.css') ?>
    <?= Html::cssFile('@web/css/emoji.css') ?>
    <?=Html::jsFile('@web/Js/jquery-1.10.2.js')?>
    <?= Html::jsFile('@web/uploadify/jquery.uploadify.min.js') ?>
    <?= Html::jsFile('@web/zclip/ZeroClipboard.min.js') ?>
    <?= Html::jsFile('@web/weixin/js/dialog.js') ?>
    <?= Html::jsFile('@web/weixin/js/admin_common.js') ?>
    <?= Html::jsFile('@web/weixin/js/admin_image.js') ?>
    <?= Html::jsFile('@web/masonry/masonry.pkgd.min.js') ?>
    <?= Html::jsFile('@web/Js/jquery.dragsort-0.5.2.min.js') ?>
	 <script type="text/javascript">
        var  IMG_PATH = "/weixin/images";
        var  ROOT = "";
    </script>
</head>
<body>
	<!-- 头部 -->
	<!-- 提示 -->
<div id="top-alert" class="top-alert-tips alert-error" style="display: none;">
  <a class="close" href="javascript:;"><b class="fa fa-times-circle"></b></a>
  <div class="alert-content"></div>
</div>
<div id="main-container" class="admin_container">
  <div class="main_body">
      <div class="span9 page_message">
          <section id="contents">
			<ul class="tab-nav nav">
				<li class="">
					<a href="<?= Url::toRoute(['public/config']);?>">
						欢迎语设置
						<span class="arrow fa fa-sort-up"></span>
					</a>
				</li>
				<li class="">
					<a href="<?=Url::toRoute(['public/menu-lists']);?>">
						自定义菜单
						<span class="arrow fa fa-sort-up"></span>
					</a>
				</li>
				<li class="">
					<a href="<?=Url::toRoute(['public/keywords'])?>">
						关键字回复
						<span class="arrow fa fa-sort-up"></span>
					</a>
				</li>
				<li class="">
					<a href="<?=Url::toRoute(['public/unkown'])?>">
						未识别回复
						<span class="arrow fa fa-sort-up"></span>
					</a>
				</li>
				<li class="">
					<a href="<?=Url::toRoute(['user/list'])?>">
						用户管理
						<span class="arrow fa fa-sort-up"></span>
					</a>
				</li>
				<li class="">
					<a href="<?=Url::toRoute(['user-group/list'])?>">
						分组管理
						<span class="arrow fa fa-sort-up"></span>
					</a>
				</li>
				<li class="">
					<a href="<?=Url::toRoute(['material/index'])?>">
						素材管理
						<span class="arrow fa fa-sort-up"></span>
					</a>
				</li>
				<li class="">
					<a href="<?=Url::toRoute(['public/template'])?>">
						模板消息管理
						<span class="arrow fa fa-sort-up"></span>
					</a>
				</li>
				<li class="current">
					<a href="<?=Url::toRoute(['public/message'])?>">
						群发消息
						<span class="arrow fa fa-sort-up"></span>
					</a>
				</li>
			 </ul>
			 <div class="tab-content"> 
				  <div class="message_list" style="padding:10px;  text-align: left;color: #333;padding: 10px;
			background-color: #eaeaea;">
					   <div class="msg_tab">
						   <a class="txt current" href="javascript:;">文本消息</a>
							<a class="appmsg" href="javascript:;">图文消息</a>
							<a class="image" href="javascript:;">图片消息</a>
							<!--<a class="voice" href="javascript:;">语音消息</a>
							<a class="video" href="javascript:;">视频消息</a>-->
						</div>
						<form id="form" action="/admin/public/send-message" method="post" class="form-horizontal form-center">
							<input type="hidden" name="msg_type" value="text"/>
							<label class="textarea" style="position:relative; overflow:hidden; zoom:1;">
								<a id="getText" class="txt_icon current" onClick="selectText();" style="position: absolute; bottom: 20px;left: 10px;cursor: pointer; color: #888;  border-radius: 5px; border: 1px solid #ccc;  padding: 5px 20px; background-color: #eee;">选择文本素材</a>
								<textarea name="content" placeholder="请输入要发送的文本"  id='message_text'></textarea>
								<div style="display:none" class="appmsg_area" id="appmsg_area">
									<input type="hidden" name="appmsg_id" value="0"/>
									<a class="select_appmsg" href="javascript:;" onClick="$.WeiPHP.openSelectAppMsg('/admin/material/material-data',selectAppMsgCallback)">选择图文</a>
									<div class="appmsg_wrap"></div>
									<a class="delete" href="javascript:;" style="left: 310px;">删除</a>
								</div>
								
								<div style="display:none;margin:0 10px 10px 0; background:#ddd; padding:6px;height:204px; width:930px;float:left" class="msg_image controls">
									<div class="uploadrow2" rel="image" title="点击修改图片" style="float:left; width:308px;">
										<input type="file" id="upload_picture_image">
										<input type="hidden" name="image" id="cover_id_image" value="0"/>
										<div class="upload-img-box" style="display:none;">
											<div class="upload-pre-item2"><img width="100" height="100" src=""/></div>
										</div>
									</div>
									<div class='image_material' id='image_material'>
										<input type="hidden" name="image_material" id="cover_id_image" value="0"/>
										<a class="select_image" href="javascript:;"  onClick="$.WeiPHP.openSelectAppMsg('/admin/material/picture-data',selectImageCallback,'选择图片素材')">从素材库选择图片</a>
										<div class="image_wrap"></div>
										<a class="delete" href="javascript:;" style="left: 15px;" >删除</a>
									 </div>
								</div>
								 
								 <div style="display:none" class="appmsg_area" id="voice_area">
									<input type="hidden" name="voice_id" value="0"/>
									<a class="select_appmsg" href="javascript:;" onClick="$.WeiPHP.openSelectAppMsg('/admin/material/voice-data',selectVoiceCallback,'选择语音素材')">选择语音素材</a>
									<div class="voice_wrap"></div>
									<a class="delete" href="javascript:;" style="left: 310px;display: inline;">删除</a>
								</div>
								  <div style="display:none" class="appmsg_area" id="video_area">
									<input type="hidden" name="video_id" value="0"/>
									<a class="select_appmsg select_video" href="javascript:;" onClick="$.WeiPHP.openSelectAppMsg('/admin/material/video-data',selectVideoCallback,'选择视频素材')">选择视频素材</a>
									<div class="video_wrap"></div>
									<a class="delete" href="javascript:;"  style="left: 310px;">删除</a>
								</div>
							 </label>
							 
							 <!--
							 <div class="action_type">
								<a class="action_item face" href="javascript:;" title="表情">&nbsp;</a>
								<a class="action_item link" href="javascript:;" title="连接">&nbsp;</a>
								<div class="action_item picture" href="javascript:;" title="图片">
									<div class="controls uploadrow2" title="点击修改图片" rel="pic">
									  <input type="file" id="upload_picture_pic">
									  <input type="hidden" name="pic" id="cover_id_pic" value="0"/>
									  <div class="upload-img-box">
									   
										  <div class="upload-pre-item2"><img width="100" height="100" src=""/></div>
										
									  </div>
								  </div>
								</div>
								<a class="action_item article" href="javascript:;" title="图文">&nbsp;</a>
							 </div>
							 -->
							 <div class="form-item cf toggle-send_type">
								<label class="item-label"> 发送方式 <span class="check-tips"> </span></label>
								<div class="controls">
								  <select name="send_type">
									<option selected="" toggle-data="group_id@show,send_openids@hide" class="toggle-data" value="0">按用户组发送 </option>
									<option toggle-data="group_id@hide,send_openids@show" class="toggle-data" value="1">指定OpenID发送 </option>
								  </select>
								</div>
							  </div>
							  <div class="form-item cf toggle-group_id">
									<label class="item-label"> 群发对象 <span class="check-tips"> （全部用户或者某分组用户） </span></label>
									<div class="controls">
									  <div id="dynamic_select_group_id"></div>
									  <select name="group_id">
										<option toggle-data="全部用户" class="toggle-data" value="0">全部用户</option>
										<?php if(!empty($group_list)): foreach($group_list as $vo):?>
										<option toggle-data="" class="toggle-data" value="<?= $vo['weixin_group_id']?>"><?= $vo['group_name']?></option>
										<?php endforeach;endif;?>

									  </select>
									</div>
								  </div>
								  <div class="form-item cf toggle-send_openids" style="display: none;">
									<a class="border-btn" href="javascript:;" onClick="selectUser('send_openids')">选择指定的用户</a>
									<br/>
									<div class="mt10">指定的用户：<span id="send_openids" class="colorless"></span></div>
									<br/>
									<div style="display:none" class="send_openids">
										<label class="item-label"> 要发送的OpenID <span class="check-tips"> （多个可用逗号或者换行分开，OpenID值可在微信用户的列表中找到） </span></label>
										<div class="controls">
										  <label class="textarea input-large">
											<textarea name="send_openids"></textarea>
										  </label>
										</div>
									</div>
								  </div>
								  <div class="form-item cf toggle-preview_openids">
									<a class="border-btn" href="javascript:;" onClick="selectUser('preview_openids')">选择预览人</a>
									<br/>
									<div class="mt10">预览人：<span id="preview_openids" class="colorless"></span></div>
									<br/>
									<div style="display:none">
										<label class="item-label"> 预览人OPENID <span class="check-tips"> （选填，多个可用逗号或者换行分开，OpenID值可在微信用户的列表中找到） </span></label>
										<div class="controls">
										  <label class="textarea input-large">
											<textarea name="preview_openids" style="height:50px; min-height:50px;"></textarea>
										  </label>
										</div>
									</div>
								  </div>
								  
							 <button class="btn submit-btn ajax-post" id="submit" type="button" target-form="form-horizontal">群发</button>
							  &nbsp;&nbsp;&nbsp;&nbsp;
							  <button onclick="preview()" class="border-btn submit-btn ajax-post">预览</button>
									
						</form>
					</div>
				  </div>
          </section>
      </div>
  </div>
</div>

	<!-- /主体 -->
	<div class="loading" style="display: none">
		<div class="box_overlay"></div>
		<div style="top:30%;left:40%;height:200px;width:100px;overflow-y:auto;overflow-x:hidden;z-index: 10;background-color: none;position: fixed"><ul class="mt_10"><center><br/><br/><br/><img src="/weixin/images/loading.gif"/></center></ul></div>
	</div>
	<!-- 底部 -->
	<script type="text/javascript">
		function preview(){
			  $('.loading').fadeIn();
			  var query = $('#form').serialize();
			  $.post("/admin/public/preview",query,function(data){
				  	$('.loading').fadeOut();
				  	var data=$.parseJSON(data);
					if (data.status==1) {
						  updateAlert(data.info ,'alert-success');
					}else{
						  updateAlert(data.info);
						  setTimeout(function(){
								$('#top-alert').find('button').click();
						  },3000);
					}
			  })
		}

		$(function(){
			showTab();

			$('.toggle-data').each(function(){
				var data = $(this).attr('toggle-data');
				if(data=='') return true;

				 if($(this).is(":selected") || $(this).is(":checked")){
					 change_event(this)
				 }
			});

			$('select').change(function(){
				$('.toggle-data').each(function(){
					var data = $(this).attr('toggle-data');
					if(data=='') return true;
					 if($(this).is(":selected") || $(this).is(":checked")){
						 change_event(this)
					 }
				});
			});
			$("#submit").click(function(){
				$('.loading').fadeIn();
				var query = $('#form').serialize();
				$.post("/admin/public/send-message",query,function(data){
					$('.loading').fadeOut();
					var data=$.parseJSON(data);
					if (data.status==1) {
						updateAlert(data.info ,'alert-success');
						setTimeout(function(){
							location.reload();
						},3000);
					}else{
						updateAlert(data.info);
						setTimeout(function(){
							$('#top-alert').find('button').click();
						},3000);
					}
				})
			})
		});
		$(function(){
			//初始化上传图片插件
			initUploadImg({width:308,height:200,callback:function(){
				$('.image_wrap').html('').hide();
				$('.select_image').show();
				$('.image_material .delete').hide();
				$('input[name="image_material"]').val(0);
			}});
			$('.uploadify-button').css('background-color','#ccc')
		})
		$('.msg_tab .txt').click(function(){
			//纯文本
			$(this).addClass('current').siblings().removeClass('current');
			$('input[name="msg_type"]').val('text');
			$('textarea[name="content"]').show().siblings().hide();
			$('#getText').show();
		})
		$('.msg_tab .appmsg').click(function(){
			//图文消息
			$(this).addClass('current').siblings().removeClass('current');
			$('input[name="msg_type"]').val('appmsg');
			$('#appmsg_area').show().siblings().hide();
			$('#getText').hide();
		})
		$('.msg_tab .image').click(function(){
			//图片消息
			$(this).addClass('current').siblings().removeClass('current');
			$('input[name="msg_type"]').val('image');
			$('.msg_image').show().siblings().hide();
			$('#getText').hide();
			$('#image_material').show();
		})
		$('.msg_tab .voice').click(function(){
			//图片消息
			$(this).addClass('current').siblings().removeClass('current');
			$('input[name="msg_type"]').val('voice');
			$('#voice_area').show().siblings().hide();
			$('#getText').hide();
			$('#image_material').hide();
		})
		$('.msg_tab .video').click(function(){
			//图片消息
			$(this).addClass('current').siblings().removeClass('current');
			$('input[name="msg_type"]').val('video');
			$('#video_area').show().siblings().hide();
			$('#getText').hide();
			$('#image_material').hide();
		})

		$('#appmsg_area .delete').click(function(){
			$('#appmsg_area .appmsg_wrap').html('').hide();
			$('#appmsg_area .select_appmsg').show();
			$('#appmsg_area .delete').hide();
			$('input[name="appmsg_id"]').val(0);
		})
		$('.image_material .delete').click(function(){
			$('.image_wrap').html('').hide();
			$('.select_image').show();
			$('.image_material .delete').hide();
			$('input[name="image_material"]').val(0);
		})
		$('#voice_area .delete').click(function(){
			$('#voice_area .voice_wrap').html('').hide();
			$('#voice_area .select_appmsg').show();
			$('#voice_area .delete').hide();
			$('input[name="voice"]').val(0);
		})
		$('#video_area .delete').click(function(){
			$('#video_area .video_wrap').html('').hide();
			$('#video_area .select_appmsg').show();
			$('#video_area .delete').hide();
			$('input[name="video"]').val(0);
		})
		function selectAppMsgCallback(_this){
			$('.appmsg_wrap').html($(_this).html()).show();
			$('#appmsg_area .select_appmsg').hide();
			$('#appmsg_area .delete').show();
			$('input[name="appmsg_id"]').val($(_this).data('group_id'));
			$.Dialog.close();
		}
		function selectImageCallback(_this){
			$('.image_wrap').html($(_this).html()).show();
			$('.select_image').hide();
			$('.image_material .delete').show();
			$('input[name="image_material"]').val($(_this).data('id'));
			$("input[name='image']").val(0);
			$('.upload-img-box').hide();
			$.Dialog.close();
		}
		function selectVoiceCallback(_this){
			$(_this).find('.icon_sound').attr('src',IMG_PATH+'/icon_sound.png');
			$('.voice_wrap').html($(_this).html()).show();
			$('#voice_area .select_appmsg').hide();
			$('#voice_area .delete').show();
			$('input[name="voice_id"]').val($(_this).data('id'));
			$.Dialog.close();
		}
		function selectVideoCallback(_this){
			$('.video_wrap').html($(_this).html()).show();
			$('#video_area .select_appmsg').hide();
			$('#video_area .delete').show();
			$('input[name="video_id"]').val($(_this).data('id'));
			$.Dialog.close();
		}
		function selectText(){
			$.WeiPHP.openSelectLists("/admin/material/text-data",1,'选择素材',function(data){
				if(data && data.length>0){
					for(var i=0;i<data.length;i++){
						var id=data[i]['id'];
						if(id){
							$.post("/admin/material/get-text",{'id':id},function(d){
								$("textarea[name='content']").text(d);
							})
						}
					}
				}
			})
		}

		function selectUser(name){
			$.WeiPHP.openSelectUsers("/admin/public/select-users",0,function(data){
				if(data && data.length>0){
					var idsArr  = new Array();
					var nameArr = new Array();
					for(var i=0;i<data.length;i++){
						idsArr.push(data[i].openid);
						nameArr.push(data[i].nickname);
					}
					$('textarea[name="'+name+'"]').val(idsArr.toString());
					$('#'+name).text(nameArr.toString());
				}
			})
		}
	</script>

</body>
</html>