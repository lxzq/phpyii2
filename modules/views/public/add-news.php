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
    <?= Html::cssFile('@web/css/bootstrap.min.css') ?>
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

</head>
<body>
	<!-- 头部 -->
	<!-- 提示 -->
<div id="top-alert" class="top-alert-tips alert-error" style="display: none;">
  <a class="close" href="javascript:;"><b class="fa fa-times-circle"></b></a>
  <div class="alert-content"></div>
</div>

	<!-- /头部 -->
	
	<!-- 主体 -->

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
				<li class="current">
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
						<a class="sidenav_parent" href="javascript:;"> 关键字回复</a>
						<ul class="sidenav_sub">
							<li class="" >
								<a href="/admin/public/keywords"> 文本回复 </a>
								<b class="active_arrow"></b>
							</li>
							<li class="active" >
								<a href="/admin/public/keywords?type=news"> 图文回复 </a>
								<b class="active_arrow"></b>
							</li>
							<li class="" >
								<a href="/admin/public/keywords?type=images"> 图片回复 </a>
								<b class="active_arrow"></b>
							</li>
						</ul>
					</li>
				</ul>
			</div>
  <div class="main_body" style="min-height:300px;">
      <div class="span9 page_message">
          <section id="contents">
				<div class="tab-content">
					<?php $form=ActiveForm::begin(['id'=>'form','action'=>Url::toRoute(['public/add-keywords']),'method'=>'post','options'=>['class'=>'form-horizontal form-center']]);?>
						<div class="form-item cf toggle-keyword">
							<label class="item-label">
								<span class="need_flag">*</span>关键词<span class="check-tips"> （多个关键词可以用空格分开，如“高富帅 白富美”）</span>
							</label>
							<div class="controls">
								<input class="text input-large" type="text" value="<?=$model['keyword']?>" name="keyword">
							</div>
						</div>
						<div class="form-item cf toggle-group_id">
							<label class="item-label">图文<span class="check-tips"> </span>
							</label>
							<div class="controls">
								<div id="news_group_id"></div>
								<div id="appmsg_area_group_id" class="appmsg_area">
									<input type="hidden" value="<?=$model['group_id']?>" name="group_id">
									<a class="select_appmsg" onclick="$.WeiPHP.openSelectAppMsg('/admin/material/material-data',selectAppMsgCallback)" href="javascript:;">选择图文</a>
									<?php if(!empty($model['group_id'])):?>
										<div class="appmsg_wrap" style="display: block;">
											<div class="appmsg_item">
												<h6><?=$model['title']?></h6>
												<p class="title"></p>
												<div class="main_img">
													<img src="<?=$model['path']?>">
												</div>
												<p class="desc"><?=$model['introduction']?></p>
											</div>
											<div class="hover_area"></div>
										</div>
										<a class="delete" href="javascript:;" style="display: inline;">删除</a>
									<?php else:?>
									<div class="appmsg_wrap" style="display: none;">
										<div class="appmsg_item">
											<p class="title"></p>
											<div class="main_img">
												<img src="">
												<h6></h6>
											</div>
											<p class="desc"></p>
										</div>
										<div class="hover_area"></div>
									</div>
									<a class="delete" href="javascript:;" style="display: none;">删除</a>
									<?php endif;?>
								</div>
							</div>
						</div>
						<div class="form-item col-md-3" style="padding-top:30px;">
							<?php if(!empty($model['id'])):?>
						  		<input type="hidden" name="id" value="<?=$model['id']?>"/>
						  	<?php endif;?>
							<input type="hidden" name="type" value="news"/>
							<button class="btn " type="submit" target-form="form-horizontal">确 定</button>
							<input type="button" class="btn btn-danger" onclick="backList()" value="返回">
						</div>
					<?php ActiveForm::end();?>
				</div>
          </section>
      </div>
  </div>
</div>

	<!-- /主体 -->

	<!-- 底部 -->
<script type="text/javascript">
	$('.msg_tab .appmsg').click(function(){
		//图文消息
		$(this).addClass('current').siblings().removeClass('current');
		$('input[name="msg_type"]').val('appmsg');
		$('#appmsg_area_group_id').show().siblings().hide();
	})
	$('.appmsg_area .delete').click(function(){
		$('.appmsg_wrap').html('').hide();
		$('.select_appmsg').show();
		$('.appmsg_area .delete').hide();
		$('input[name="group_id"]').val(0);
	})
	function selectAppMsgCallback(_this){
		$('.appmsg_wrap').html($(_this).html()).show();
		$('.select_appmsg').hide();
		$('.appmsg_area .delete').show();
		$('input[name="group_id"]').val($(_this).data('id'));
		$.Dialog.close();
	}
	$(function(){
		var val = $('input[name="group_id"]').val();
		if(val!=''){
			$('.appmsg_wrap').show();
			$('.select_appmsg').hide();
			$('.appmsg_area .delete').show();
		}else{
			$('.appmsg_wrap').hide();
			$('.select_appmsg').show();
			$('.appmsg_area .delete').hide();		
		}
	})
function backList(){
	window.history.go(-1);
}
</script>
</body>
</html>