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
    <?= Html::jsFile('@web/js/jquery.dragsort-0.5.2.min.js') ?>

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
							<li class="" >
								<a href="/admin/public/keywords?type=news"> 图文回复 </a>
								<b class="active_arrow"></b>
							</li>
							<li class="active" >
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
						<div class="form-item cf toggle-content">
							<label class="item-label">
								上传图片
								<span class="check-tips"> </span>
							</label>
							<div class="controls">
								<div class="controls uploadrow2" title="点击修改图片" rel="image_id" style="float: left;">
									<input type="file" id="upload_picture_image_id" >
									<input type="hidden" name="image_id" id="cover_id_image_id" value="<?= $model['image_id']?>"/>
									<div class="upload-img-box">
									<?php if(!empty($model['image_id'])):?>
										 <div class="upload-pre-item2"><img width="100" height="100" src="<?=$model['path']?>"/></div>
                                			<em class="edit_img_icon">&nbsp;</em>
                                	<?php endif;?>
									</div>
								</div>
	                            <div class='image_material' id='image_material'>
	                              <a class="select_image" href="javascript:;"  onClick="$.WeiPHP.openSelectAppMsg('/admin/material/picture-data',selectImageCallback,'选择图片素材')">从素材库选择图片</a>
	                              <div class="image_wrap"></div>
								  	<a class="delete" href="javascript:;" style="left: 15px;">删除</a>
								</div>
                            </div>
						</div>
						<div class="form-item col-md-3" style="padding-top:30px;">
							<?php if(!empty($model['id'])):?>
						  		<input type="hidden" name="id" value="<?=$model['id']?>"/>
						  	<?php endif;?>
							<input type="hidden" name="type" value="images"/>
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
  $(function(){
	 var str_image='<div class="appmsg_item"><div class="main_img"><img src="" width="200px" height="200px"/></div></div><div class="hover_area"></div>';
	 var material_image="";
	 if(material_image != 0){
		 $('.image_wrap').html(str_image).show();
		 $('.select_image').hide();
		 $('.image_material .delete').show();
	 }
	initUploadImg({width:100,height:100,callback:function(){
    $('.image_wrap').html('').hide();
    $('.select_image').show();
    $('.image_material .delete').hide();
  }});
	initUploadFile();
    showTab();
	

  $('.image_material .delete').click(function(){
    $('.image_wrap').html('').hide();
    $('.select_image').show();
    $('.image_material .delete').hide();
  })

});
  function selectImageCallback(_this){
    $('.image_wrap').html($(_this).html()).show();
    $('.select_image').hide();
    $('.image_material .delete').show();
    $('input[name="image_id"]').val($(_this).data('image-id'));
    $('.upload-pre-item2').hide();
    $.Dialog.close();
  }
function backList(){
	window.history.go(-1);
}
</script>
</body>
</html>