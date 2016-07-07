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
					<li class="" >
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
					<li class="active" >
						<a href="<?= Url::to(['material/text']);?>"> 文本素材 </a>
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
					<?php $form=ActiveForm::begin(['id'=>'form','action'=>Url::toRoute(['material/add-text']),'method'=>'post','options'=>['class'=>'form-horizontal form-center']]);?>
						<div class="form-item cf toggle-content">
							<label class="item-label">
								文本内容
								<span class="check-tips"> </span>
							</label>
							<div class="controls">
								<label class="textarea input-large">
									<textarea name="content"><?=$model['content']?></textarea>
								</label>
							</div>
						</div>
						<div class="form-item  col-md-3 center" style="padding-top:30px;">
							<?php if(!empty($model['id'])):?>
						  		<input type="hidden" name="id" value="<?=$model['id']?>"/>
						  	<?php endif;?>
							<button class="btn ajax-post" target-form="form-horizontal" type="submit">确 定</button>
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
	
    //回车自动提交
    $('.search-form').find('input').keyup(function(event){
        if(event.keyCode===13){
            $("#search").click();
        }
    });
	
})

function backList(){
	window.history.go(-1);
}
</script>
</body>
</html>