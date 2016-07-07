<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\VideoForm */

use yii\helpers\Html;
use yii\widgets\LinkPager;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
?>
<!DOCTYPE HTML>
<html lang="zh-CN">
<head>
	<meta charset="UTF-8">
    <?= Html::cssFile('@web/css/bootstrap.min.css') ?>
    <?= Html::cssFile('@web/font-awesome/css/font-awesome.min.css') ?>
    <?= Html::cssFile('@web/weixin/css/base.css') ?>
    <?= Html::cssFile('@web/weixin/css/module.css') ?>
    <?= Html::cssFile('@web/weixin/css/weixin.css') ?>
    <?= Html::cssFile('@web/css/emoji.css') ?>
    <?=Html::jsFile('@web/Js/jquery-2.0.3.min.js')?>
    <?= Html::jsFile('@web/uploadify/jquery.uploadify.min.js') ?>
    <?= Html::jsFile('@web/zclip/ZeroClipboard.min.js') ?>
    <?= Html::jsFile('@web/weixin/js/dialog.js') ?>
    <?= Html::jsFile('@web/weixin/js/admin_common.js') ?>
    <?= Html::jsFile('@web/weixin/js/admin_image.js') ?>
    <?= Html::jsFile('@web/masonry/masonry.pkgd.min.js') ?>
    <?= Html::jsFile('@web/Js/jquery.dragsort-0.5.2.min.js') ?>

</head>
<body>
	<!-- 头部 -->
	<!-- 提示 -->
<div id="top-alert" class="top-alert-tips alert-error" style="display: none;">
  <a class="close" href="javascript:;"><b class="fa fa-times-circle"></b></a>
  <div class="alert-content"></div>
</div>
<div id="main-container" class="admin_container">
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
				<li class="current">
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
				 <li class="">
					 <a href="<?=Url::toRoute(['public/message'])?>">
						 群发消息
						 <span class="arrow fa fa-sort-up"></span>
					 </a>
				 </li>
			 </ul>
			 <div class="sidebar">
				<ul class="sidenav">
					<li>
						<a class="sidenav_parent" href="javascript:;"> 素材管理</a>
						<ul class="sidenav_sub">
							<li class="" >
								<a href="<?= Url::to(['material/index']);?>"> 图文素材</a>
								<b class="active_arrow"></b>
							</li>
							<li class="active" >
								<a href="<?= Url::to(['material/picture']);?>"> 图片素材 </a>
								<b class="active_arrow"></b>
							</li>
							<!--<li class="" >
								<a href="/admin/public/keywords?type=images"> 语音素材 </a>
								<b class="active_arrow"></b>
							</li>
							<li class="" >
								<a href="/admin/public/keywords?type=images"> 视频素材 </a>
								<b class="active_arrow"></b>
							</li>-->
							<li class="" >
								<a href="<?= Url::to(['material/text']);?>"> 文本素材 </a>
								<b class="active_arrow"></b>
							</li>
						</ul>
					</li>
				</ul>
		  </div>
  <div class="main_body">
      <div class="span9 page_message">
		<section id="contents">
		   <div class="data-table" style="margin-top:20px;">
		        <div class="table-striped">
	        		<div style="padding-bottom: 20px;padding-left: 15px;">
	                	<a class="btn syc_to" href="javascript:void(0);" url="<?=Url::to(['material/syc-to-image'])?>">一键上传素材到微信素材库</a>
						<a class="btn syc_from" href="javascript:void(0);" url="<?=Url::to(['material/syc-from-image'])?>">一键下载微信素材库到本地</a>
	                </div>
		        	<ul class="picture_list">
		            	<li>
		                	<div class="controls uploadrow2" title="点击修改图片" rel="p_cover" style="width:210px">
		                        <input type="file" id="upload_picture_p_cover">
		                        <input type="hidden" name="p_cover" id="cover_id_p_cover" data-callback="uploadImgCallback" value=""/>
		                        <div class="upload-img-box" rel="img" style="display:none">
		                          <div class="upload-pre-item2"><img width="100" height="100" src=""/></div>
		                            <em class="edit_img_icon">&nbsp;</em>
		                        </div>
		                  </div>
		                </li>
		                <?php if(!empty($list)): foreach($list as $vo):?>
		            	<li>
		                	<div class="picture_item">
		                        <div class="main_img">
		                            <a href="<?=$vo['cover_url']?>" target="_blank"><img src="<?=$vo['cover_url']?>"/></a>
		                        </div>
		                        <div class="picture_action">
		                            <a href="javascript:;" data-clipboard-text="{$vo['cover_url']}" id="picture_<?=$vo['id']?>">复制链接</a>
		                            <a href="javascript:;" onclick="del(<?=$vo['id']?>, this)">删除</a>	
		                        </div>
		                        <script type="application/javascript">
									$.WeiPHP.initCopyBtn('picture_<?=$vo['id']?>');
								</script>
		                    </div>
		                </li>
		                <?php endforeach;endif;?>
		            </ul>
		          
		        </div>
		      </div>
		</section>
  </div>
	  <div class="loading" style="display: none">
		  <div class="box_overlay"></div>
		  <div style="top:30%;left:40%;height:200px;width:100px;overflow-y:auto;overflow-x:hidden;z-index: 10;background-color: none;position: fixed"><ul class="mt_10"><center><br/><br/><br/><img src="/weixin/images/loading.gif"/></center></ul></div>
	  </div>
   <script type="text/javascript">
		$(function(){
			//t上传图片
			initUploadImg({width:210,height:192});
			$('.uploadify-button').css('background-color','#ddd');
			$('.syc_to').click(function(){
				$('.loading').fadeIn();
				var url=$(this).attr('url');
				$.get(url).success(function(data){
					$('.loading').fadeOut();
					var data=$.parseJSON(data);
					if(data.status==1){
						updateAlert(data.info ,'alert-success');
						setTimeout(function(){
							location.reload();
						},1500);
					}else{
						updateAlert(data.info);
					}
				})
			})
			$('.syc_from').click(function(){
				$('.loading').fadeIn();
				var url=$(this).attr('url');
				$.get(url).success(function(data){
					$('.loading').fadeOut();
					var data=$.parseJSON(data);
					if(data.status==1){
						updateAlert(data.info ,'alert-success');
						setTimeout(function(){
							location.reload();
						},1500);
					}else{
						updateAlert(data.info);
					}
				})
			})
		});

		function uploadImgCallback(name,id,src){
			$('.upload-img-box').hide();

			$.post("/admin/material/add-picture",{cover_id:id,src:src},function(data){
				var data=$.parseJSON(data);
				if(data.status==1){
					var imgHtml = $('<li>'+
									'<div class="picture_item">'+
										'<div class="main_img">'+
											'<a href="'+src+'" target="_blank"><img src="'+src+'"/></a>'+
										'</div>'+
										'<div class="picture_action">'+
											'<a href="javascript:;" data-clipboard-text="'+src+'" id="picture_'+id+'">复制链接</a>'+
											'<a href="#" class="ajax-post" data-url="">删除</a>'+
										'</div>'+
									'</div>'+
								'</li>');
					imgHtml.insertAfter($('.picture_list li').eq(0));
					$.WeiPHP.initCopyBtn('picture_'+id);
				}else{
					updateAlert(data.info);
				}
			});
		}
		function del(id, _this){
			if(!confirm('确认删除？')) return false;

			$(_this).parent().parent().parent().remove();
			$.post("/admin/material/del-picture",{id:id});
		}
		
  </script> 
</body>
</html>

