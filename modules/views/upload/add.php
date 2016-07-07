<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\VideoForm */

use yii\helpers\Html;
use yii\widgets\LinkPager;
use yii\bootstrap\ActiveForm;
?>
<!DOCTYPE HTML>
<html lang="zh-CN">
<head>
	<meta charset="UTF-8">
    <?= Html::cssFile('@web/css/bootstrap.min.css') ?>

    <?=Html::jsFile('@web/Js/jquery-1.10.2.js')?>


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
    <div class="tab-content"> 
      <!-- 表单 -->

        <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data','class'=>'appmsg_item edit_item editing',"id"=>"form"],'action'=>"/admin/upload/upload-video"]) ?>
        <?= $form->field($model, 'file')->fileInput() ?>

        <button type="submit" id="submit">Submit</button>
        <?php ActiveForm::end(); ?>
      		
    </div>
    
    
  </section>
  </div>

 
  <script type="text/javascript">
$('#submit').click(function(){
    $('#form').submit();
});
function backList(){
    window.history.go(-1);
}

</script>
</body>
</html>
