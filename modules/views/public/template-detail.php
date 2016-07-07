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
<html lang="zh-CN" xmlns="http://www.w3.org/1999/html">
<head>
	<meta charset="UTF-8">
    <?= Html::cssFile('@web/css/bootstrap.min.css') ?>
    <?= Html::cssFile('@web/font-awesome/css/font-awesome.min.css') ?>
    <?= Html::cssFile('@web/weixin/css/base.css') ?>
    <?= Html::cssFile('@web/weixin/css/module.css') ?>

    <?= Html::cssFile('@web/weixin/css/weixin.css') ?>
    <?= Html::cssFile('@web/css/emoji.css') ?>
    <?=Html::jsFile('@web/Js/jquery-1.10.2.js')?>
    <?= Html::jsFile('@web/zclip/ZeroClipboard.min.js') ?>
    <?= Html::jsFile('@web/weixin/js/dialog.js') ?>
    <?= Html::jsFile('@web/weixin/js/admin_common.js') ?>
    <?= Html::jsFile('@web/weixin/js/admin_image.js') ?>
    <?= Html::jsFile('@web/masonry/masonry.pkgd.min.js') ?>
    <?= Html::jsFile('@web/Js/jquery.dragsort-0.5.2.min.js') ?>
	<?= Html::jsFile('@web/weixin/js/autosize.min.js') ?>

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
				<li class="current">
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
	  <div class="main_body" style="min-height:300px;">
		  <div class="span9 page_message">
			  <section id="contents">
				  <div class="tab-content">
					  <div class="form-item cf">
						  <label class="item-label">模板id</label>
						  <div class="controls">
							  <input class="text input-large" type="text" value="<?=$info['template_id']?>" name="template_id" disabled="disabled"/>
						  </div>
					  </div>
					  <div class="form-item cf">
						  <label class="item-label">标题</label>
						  <div class="controls">
							  <input class="text input-large" type="text" value="<?=$info['title']?>" name="title" disabled="disabled"/>
						  </div>
					  </div>
					  <div class="form-item cf">
						  <label class="item-label">一级行业</label>
						  <div class="controls">
							  <input class="text input-large" type="text" value="<?=$info['primary_industry']?>" name="primary_industry" disabled="disabled"/>
						  </div>
					  </div>
					  <div class="form-item cf">
						  <label class="item-label">二级行业</label>
						  <div class="controls">
							  <input class="text input-large" type="text" value="<?=$info['deputy_industry']?>" name="deputy_industry" disabled="disabled"/>
						  </div>
					  </div>
					  <div class="form-item cf toggle-content">
						  <label class="item-label">模板内容</label>
						  <div class="controls">
							  <label class="textarea input-large">
								  <textarea  disabled="disabled" name="content" ><?=$info['template_data']?></textarea>
							  </label>
						  </div>
					  </div>
					  <div class="form-item cf toggle-content">
						  <label class="item-label">内容示例</label>
						  <div class="controls">
							  <label class="textarea input-large">
								  <textarea name="example"  disabled="disabled"><?=$info['example']?></textarea></label>
						  </div>
					  </div>
					  <div class="form-item cf">
						  <label class="item-label">跳转链接URL</label>
						  <div class="controls">
							  <input class="text input-large" type="text" value="<?=$info['url']?>" name="url" disabled="disabled"/>
						  </div>
					  </div>
					  <div class="form-item cf">
						  <label class="item-label">设置推送</label>
						  <div class="controls">
							  <select class="textarea input-large" name="flag" disabled="disabled">
								  <option value="1" <?php if($info['flag']==1):echo "selected";endif;?>>是</option>
								  <option value="2" <?php if($info['flag']==2):echo "selected";endif;?>>否</option>
							  </select>
						  </div>
					  </div>
					  <!--<div class="form-item cf border">
						  <?php /*if(!empty($info['content'])):foreach($info['content'] as $key=>$vo):*/?>
								  <div class="form-group col-md-11">
									  <label style="float: left;width: 120px;text-align: right;"><?/*=$key*/?></label>
									  <?php /*if($key=='remark'):*/?>
										  <label class="col-md-3"> <textarea class="text" name="content[remark][content]" disabled="disabled"><?php /*if(!empty($vo['content'])):echo $vo['content'];endif;*/?></textarea></label>
									  <?php /*elseif(!empty($vo['type'])):*/?>
										 <?php /*if($vo['type']==1):*/?>
										  <div class="col-md-3 show show1"> <input class="text" name="content[<?/*=$key*/?>][content]" value="<?php /*if(!empty($vo['content'])):echo $vo['content'];endif;*/?>" disabled="disabled"/></div>
										 <?php /*else:*/?>
										  <div class="col-md-3 show show2">
											  <input class="text" value="<?/*=$vo['table']*/?>" disabled="disabled"/>
										  </div>
										  <label class="col-md-3 <?/*=$key*/?> show show2">
											  <input class="text" value="<?/*=$vo['column']*/?>" disabled="disabled"/>
										  </label>
											 <?php /*endif;*/?>
									  <?php /*else:*/?>
										  <label class="col-md-3">
											  <input class="text" value="<?/*=$vo['table']*/?>" disabled="disabled"/>
										  </label>
										  <label class="col-md-3 <?/*=$key*/?>">
											  <input class="text" value="<?/*=$vo['column']*/?>" disabled="disabled"/>
										  </label>
									  <?php /*endif;*/?>
								  </div>
						  <?php /*endforeach; endif;*/?>
					  </div>-->
					  <div class="form-item col-md-3" style="padding-top:30px;">
						  <input type="button" class="btn btn-info" onclick="backList()" value="返回">
					  </div>
				  </div>
			  </section>
		  </div>
	  </div>
	  </div>
    
    
  </section>
  </div>

 
  <script type="text/javascript">
	  $(function(){
		  autosize(document.querySelectorAll('textarea'));

	  });
	  function backList(){
		  window.history.go(-1);
	  }
	</script>
</body>
</html>
