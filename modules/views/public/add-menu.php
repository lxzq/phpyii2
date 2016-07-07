<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\VideoForm */

use yii\helpers\Html;
use yii\widgets\LinkPager;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
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
  <div class="main_body" style="min-height:300px;">
      <div class="span9 page_message">
		<section id="contents">
			<ul class="tab-nav nav">
				<li class="">
					<a href="<?= Url::toRoute(['public/config']);?>">
						欢迎语设置
						<span class="arrow fa fa-sort-up"></span>
					</a>
				</li>
				<li class="current">
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
				<li class="">
					<a href="<?=Url::toRoute(['public/message'])?>">
						群发消息
						<span class="arrow fa fa-sort-up"></span>
					</a>
				</li>
			 </ul>		
		   <div class="tab-content" style="margin-left:20px;"> 
			<?php $form=ActiveForm::begin(['id'=>'form','action'=>Url::toRoute(['public/add-menu']),'method'=>'post','options'=>['class'=>'form-horizontal form-center']]);?>
                <div class="form-item cf toggle-pid">
					<label class="item-label">一级菜单 <span class="check-tips">（如果是一级菜单，选择“无”即可）</span></label>
					<div class="controls">
						<select name="pid">
							<option value="0" class="toggle-data" <?php if($model->pid===0) echo 'selected';?> toggle-data="" >无</option>  
							<?php if(!empty($parent_list)): foreach($parent_list as $vo):?>
							    <option value="<?= $vo['id'] ?>" <?php if($model->pid==$vo['id']) echo 'selected';?> class="toggle-data"> <?=$vo['title']?></option>
							<?php endforeach;endif;?>
                        </select>              
					</div>
				</div>
				<div class="form-item cf toggle-title">
					<label class="item-label"><span class="need_flag">*</span>菜单名 <span class="check-tips">
					（可创建最多 3 个一级菜单，每个一级菜单下可创建最多 5 个二级菜单。）</span></label>
					<div class="controls">
						<input class="text input-large" name="title" type="text" value="<?=$model->title?>">             
					</div>
                </div>          
				<div class="form-item cf toggle-type">
					<label class="item-label">类型 <span class="check-tips"></span></label>
					<div class="controls">
						<div class="check-item"> 
							<input class="regular-radio toggle-data" value="click" <?php if($model->type=='click') echo 'checked="checked"';?> id="type_click" name="type" toggle-data="keyword@show,url@hide"  type="radio">
							<label for="type_click"></label>
							点击推事件
						</div>
						<div class="check-item"> 
							<input class="regular-radio toggle-data" value="view" <?php if($model->type=='view') echo 'checked="checked"';?> id="type_view" name="type" toggle-data="keyword@hide,url@show" type="radio">
							<label for="type_view"></label>
							跳转URL 
						</div>
						<!--<div class="check-item"> 
							<input class="regular-radio toggle-data" value="scancode_push" <?php if($model->type=='scancode_push') echo 'checked="checked"';?> id="type_scancode_push" name="type" toggle-data="keyword@show,url@hide" type="radio">
							<label for="type_scancode_push"></label>
							扫码推事件 
						</div>
						<div class="check-item"> 
							<input class="regular-radio toggle-data" value="scancode_waitmsg" <?php if($model->type=='scancode_waitmsg') echo 'checked="checked"';?> id="type_scancode_waitmsg" name="type" toggle-data="keyword@show,url@hide" type="radio">
							<label for="type_scancode_waitmsg"></label>
							扫码带提示
						</div>
						<div class="check-item"> 
							<input class="regular-radio toggle-data" value="pic_sysphoto" <?php if($model->type=='pic_sysphoto') echo 'checked="checked"';?> id="type_pic_sysphoto" name="type" toggle-data="keyword@show,url@hide" type="radio">
							<label for="type_pic_sysphoto"></label>
							弹出系统拍照发图 
						</div>
						<div class="check-item">
							<input class="regular-radio toggle-data" value="pic_photo_or_album" <?php if($model->type=='pic_photo_or_album') echo 'checked="checked"';?> id="type_pic_photo_or_album" name="type" toggle-data="keyword@show,url@hide" type="radio">
							<label for="type_pic_photo_or_album"></label>
							弹出拍照或者相册发图
						</div>
						<div class="check-item">
							<input class="regular-radio toggle-data" value="pic_weixin" <?php if($model->type=='pic_weixin') echo 'checked="checked"';?> id="type_pic_weixin" name="type" toggle-data="keyword@show,url@hide" type="radio">
							<label for="type_pic_weixin"></label>
							弹出微信相册发图器
						</div>
						<div class="check-item"> 
							<input class="regular-radio toggle-data" value="location_select" <?php if($model->type=='location_select') echo 'checked="checked"';?> id="type_location_select" name="type" toggle-data="keyword@show,url@hide" type="radio">
							<label for="type_location_select"></label>
							弹出地理位置选择器 
						</div>-->
						<div class="check-item"> 
							<input class="regular-radio toggle-data" value="none" id="type_none" <?php if($model->type=='none') echo 'checked="checked"';?> name="type" toggle-data="keyword@hide,url@hide" type="radio">
							<label for="type_none"></label>
							无事件的一级菜单 
						</div>
					</div>
				</div>          
				<div <?php if($model->type=='noen' || $model->type=='view'):echo 'style="display: none;"';else:echo 'style="display: block;"';endif;?>  class="form-item cf toggle-keyword">
					<label class="item-label">关联关键词 <span class="check-tips"></span></label>
					<div class="controls">
						<input class="text input-large" name="keyword" value="<?= $model['keyword']?>" type="text">
					</div>
				</div>          
				<div <?php if($model->type=='noen'):echo 'style="display: none;"';elseif($model->type=='view'):echo 'style="display: block;"';else: echo 'style="display: none;"';endif;?> class="form-item cf toggle-url">
					<label class="item-label">关联URL <span class="check-tips"></span></label>
					<div class="controls">
						<input class="text input-large" value="<?= $model['url']?>" name="url" type="text">              
					</div>
				</div>          
				<div class="form-item cf toggle-sort">
					<label class="item-label">排序号 <span class="check-tips">（数值越小越靠前）</span></label>
					<div class="controls">
						<input class="text" name="sort" value="<?php if($model->sort):echo $model->sort;else:echo '0';endif;?>"  type="number">              
					</div>
				</div>          
				
				<?php if(empty($model->id)):?>
					<input class="text" name="menu_group_id" value="<?php if(!empty($menu_group_id)) echo $menu_group_id;?>" type="hidden">
				<?php else:?>
					<input class="text" name="id" value="<?= $model->id?>" type="hidden">
				<?php endif;?>
				<div class="form-item form_bh">
					<button class="btn " type="submit" target-form="form-horizontal">确 定</button>
					<input type="button" class="btn btn-danger" onclick="backList()" value="返回">
				</div>
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
    
    showTab();
	
	$('.toggle-data').each(function(){
		var data = $(this).attr('toggle-data');
		if(data=='') return true;		
		
	     if($(this).is(":selected") || $(this).is(":checked")){
			 change_event(this)
		 }
	});
	$('.toggle-data').bind("click",function(){ change_event(this) });
	$('select').change(function(){
		$('.toggle-data').each(function(){
			var data = $(this).attr('toggle-data');
			if(data=='') return true;		
			
			 if($(this).is(":selected") || $(this).is(":checked")){
				 change_event(this)
			 }
		});
	});
	
	$("select[name='from_type']").change(function(){
		
		var fromType=$("select[name='from_type'] option:selected").val();
		if(fromType != 9){
			$("input[name='keyword']").prop('readonly',true);
			$("input[name='url']").prop('readonly',true);
		}else{
			$("input[name='keyword']").prop('readonly',false);
			$("input[name='url']").prop('readonly',false);
		}
	});	
	
	
	
});
function backList(){
		window.history.go(-1);
	}
</script> 
</body>
</html>