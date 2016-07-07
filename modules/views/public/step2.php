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
					 <a class="step step_cur " href="<?= Url::toRoute(['public/step1','public_id'=>$id]) ?>">1.增加基本信息</a>
					 <a class="step step_cur_1" href="<?= Url::toRoute(['public/step2','public_id'=>$id]) ?>" style="cursor:deault" >2.配置公众平台</a>
					 <a class="step " href="<?= Url::toRoute(['public/step3','public_id'=>$id]) ?>" style="cursor:deault">3.保存接口配置</a> 
			  </div>  
			   <div class="tab-content bind_step_form"> 
					<div class="item_wrap" style="width:600px; padding:90px 0;">
					  <strong>请在公众平台开发者中心里的服务器配置录入以下参数</strong>
					  <p>URL(服务器地址)：<span style="color: #FF0000">
					  <?=Yii::$app->urlManager->createAbsoluteUrl(['/admin/wechat/index','public_id'=>$id]) ?></span><br>
					  Token(令牌)：<span style="color: #F00">happycity777</span><br>
					  EncodingAESKey(消息加解密密钥)：点击随机生成得到密钥，不需要自己填写<br>
					  消息加解密方式： 根据自己的需要选择其中一种
					  </p>
					  <p>&nbsp;</p>
					</div>
					  <div class="bind_step_form_next_item">
						<a class="btn submit-btn" href="<?= Url::toRoute(['public/step3','public_id'=>$id]) ?>">下一步</a>
						
					  </div>
					
			  </div>
          </section>
      </div>
  </div>
</div>

</body>
</html>