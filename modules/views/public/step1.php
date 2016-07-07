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
  <div class="main_body">
      <div class="span9 page_message">
          <section id="contents">
              <div class="setting_step app_setting">
					 <a class="step step_cur_1" href="<?= Url::toRoute(['public/step1','public_id'=>$id]) ?>">1.增加基本信息</a>
					 <a class="step " style="cursor:deault" >2.配置公众平台</a>
					 <a class="step " style="cursor:deault">3.保存接口配置</a> 
			  </div>  
			  <div class="tab-content"> 
				<!-- 表单 -->
				<?php $form = ActiveForm::begin(['id'=>'form','class'=>'form-horizontal bind_step_form','action'=>'/admin/public/step1','method'=>'post']); ?>
				  <!-- 基础文档模型 -->
				  <div class="tab-pane in tab1" id="tab1">
					<div class="item_wrap">
					 <div class="form-item cf">
					  <label class="item-label"> <span class="need_flag">*</span> 公众号类型 <span class="check-tips"> （请正确选择，公众号类型对应的接口如果没有权限，相关的功能将不显示）</span></label>
					  <div class="controls">
							<select name="type">
							   <option value="0" <?php if(!empty($model['type']) && $model['type']==0)echo "selected" ?> >普通订阅号</option>
							   <option value="1" <?php if(!empty($model['type']) && $model['type']==1)echo "selected" ?> >微信认证订阅号</option>
							   <option value="2" <?php if(!empty($model['type']) && $model['type']==2)echo "selected" ?> >普通服务号</option>
							   <option value="3" <?php if(!empty($model['type']) && $model['type']==3)echo "selected" ?> >微信认证服务号</option>
							</select>
					  </div>
					</div>        
					 <div class="form-item cf toggle-public_name">
					  <label class="item-label"> <span class="need_flag">*</span> 公众号名称 <span class="check-tips"> </span></label>
					  <div class="controls">
						<input type="text" value="<?= $model['public_name'] ?>" name="public_name" class="text input-large">
					  </div>
					</div>
					<div class="form-item cf toggle-public_id">
					  <label class="item-label"> <span class="need_flag">*</span> 原始ID <span class="check-tips"> （请正确填写，保存后不能再修改，且无法接收到微信的信息） </span></label>
					  <div class="controls">
						<input type="text" value="<?= $model['public_id'] ?>" name="public_id" class="text input-large">
					  </div>
					</div>
					<div class="form-item cf toggle-wechat">
					  <label class="item-label"> <span class="need_flag">*</span> 微信号 <span class="check-tips"> </span></label>
					  <div class="controls">
						<input type="text" value="<?= $model['wechat'] ?>" name="wechat" class="text input-large">
					  </div>
					</div> 
					</div>     
					<div class="form-item cf mt_10 bind_step_form_next_item">
					  <input type="hidden" name="id" value="<?= $id ?>">
					  <button target-form="form-horizontal" type="submit" id="submit" class="btn">下一步</button>
					  <br/>
					  
					</div>
				  </div>
				<?php ActiveForm::end(); ?>
			  </div>
          </section>
      </div>
  </div>
</div>

</body>
</html>