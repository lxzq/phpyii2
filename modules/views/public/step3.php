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
					 <a class="step " href="<?= Url::toRoute(['public/step1','public_id'=>$id]) ?>">1.增加基本信息</a>
					 <a class="step step_cur" href="<?= Url::toRoute(['public/step2','public_id'=>$id]) ?>" style="cursor:deault" >2.配置公众平台</a>
					 <a class="step step_cur_1" href="<?= Url::toRoute(['public/step3','public_id'=>$id]) ?>">3.保存接口配置</a> 
			  </div>  
			  <div class="tab-content"> 
				<!-- 表单 -->
				<?php $form = ActiveForm::begin(['id'=>'form','class'=>'form-horizontal bind_step_form','action'=> Url::toRoute(['public/step3','public_id'=>$id]),'method'=>'post']); ?>
				   <!-- 基础文档模型 -->
				  <div class="tab-pane in tab1" id="tab1">
					<div class="item_wrap">
					<div class="form-item cf toggle-appid">
					  <label class="item-label"> AppID <span class="check-tips"> （应用ID） </span></label>
					  <div class="controls">
						<input type="text" value="<?= $model['appid'] ?>" name="appid" class="text input-large">
					  </div>
					</div>
					<div class="form-item cf toggle-secret">
					  <label class="item-label"> AppSecret <span class="check-tips"> （应用密钥） </span></label>
					  <div class="controls">
						<input type="text" value="<?= $model['secret'] ?>" name="secret" class="text input-large">
					  </div>
					</div>
					<div class="form-item cf toggle-encodingaeskey">
					  <label class="item-label"> EncodingAESKey <span class="check-tips"> （安全模式下必填） </span></label>
					  <div class="controls">
						<input type="text" value="<?= $model['encodingaeskey'] ?>" name="encodingaeskey" class="text input-large">
					  </div>
					</div>
					
					</div>
					<div class="form-item cf bind_step_form_next_item">
					  <button target-form="form-horizontal" type="submit" id="submit" class="btn">
						保存
					  </button>
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