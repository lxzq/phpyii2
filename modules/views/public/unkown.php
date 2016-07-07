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
				<li class="current">
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
			 <div class="tab-content">
			  <?php $form = ActiveForm::begin(['id'=>'form','options'=>['class'=>'form-horizontal'],'action'=>'/admin/public/unkown','method'=>'post']); ?>
				<div class="form-item cf">
				  <label class="item-label"> 类型: </label>
				  <div class="controls">
				    	<div class="check-item">
							<input type="radio" name="config[type]" value="1" class="regular-radio" id="config[type]_1" onClick="changeOption()">
							<label for="config[type]_1"></label>文本
						</div>
						<div class="check-item">
							<input type="radio" name="config[type]" value="2" class="regular-radio" id="config[type]_2" onClick="changeOption()">
							<label for="config[type]_2"></label>图文
						</div>
				  </div>
				</div>
				<div class="form-item cf show show2">
				  <label class="item-label"> 标题: </label>
				  <div class="controls">
					<input type="text" name="config[title]" class="text input-large" value="<?php if(!empty($info['title']))echo $info['title'];?>">
				  </div>
				</div>
				<div class="form-item cf show show2 show1">
				  <label class="item-label"> 内容: </label>
				  <div class="controls">
				    <label class="textarea input-large">
				      <textarea name="config[description]"><?php if(!empty($info['description'])) echo $info['description'];?></textarea>
				    </label>
				  </div>
				</div>
				<div class="form-item cf show show2">
					  <label class="item-label"> 图片: <span class="check-tips">图片链接，支持JPG、PNG格式，较好的效果为大图360*200，小图200*200</span> </label>
					  <div class="controls uploadrow2" title="点击修改图片" rel="pic_url">
						<input type="file" id="upload_picture_pic_url">
						<input type="hidden" name="config[pic_url]" id="cover_id_pic_url" value="<?php if(!empty($info['pic_url']))echo $info['pic_url'];?>"/>
						<div class="upload-img-box">
							<?php if(!empty($info['path'])):?>
								<div class="upload-pre-item2"><img width="100" height="100" src="<?php if(!empty($info['path']))echo $info['path'];?>"/></div>
								<em class="edit_img_icon">&nbsp;</em>
							<?php endif;?>
						</div>
					  </div>

					</div>
					<div class="form-item cf show show2">
					  <label class="item-label"> 链接: <span class="check-tips">点击图文消息跳转链接</span> </label>
					  <div class="controls">
						<input type="text" name="config[url]" class="text input-large" value="<?php if(!empty($info['url'])) echo $info['url'];?>">
					  </div>
					</div>
				<button class="btn submit-btn ajax-post" target-form="form-horizontal" type="submit">确 定</button>
				<?php ActiveForm::end(); ?>
			  </div>
          </section>
      </div>
  </div>
</div>

	<!-- /主体 -->

	<!-- 底部 -->
	<script type="text/javascript">
		$(function(){
			 initUploadImg();
			
			 })
		function changeOption(){
			$(".show").each(function(){
				$(this).hide();
				
			});
			
			var val = $("input[name='config[type]']:checked").val();
			$('.show'+val).each(function(){
				$(this).show();
			});
		}
		$(function(){
			var type = "<?php if(!empty($info['type'])): echo $info['type'];else: echo '0';endif;?>";
			if(type=="0")
				type = 1;
			$("input[name='config[type]'][value="+type+"]").attr("checked",true); 
			changeOption();
		})
	</script>

</body>
</html>