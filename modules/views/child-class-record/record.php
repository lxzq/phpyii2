<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */



use yii\helpers\Html;
use yii\bootstrap\ActiveForm;


?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <?=Html::cssFile('@web/css/bootstrap.min.css')?>
    <?= Html::cssFile('@web/css/bootstrap-datetimepicker.min.css') ?>
    <?=Html::jsFile('@web/Js/jquery-1.10.2.js')?>
    <?= Html::jsFile('@web/Js/bootstrap.js') ?>
    <?= Html::jsFile('@web/Js/bootstrap-datetimepicker.js') ?>
    <?= Html::jsFile('@web/Js/locales/bootstrap-datetimepicker.zh-CN.js') ?>
</head>
<script >
    function backList (){
         window.history.go(-1);
    }
</script>
<body>
<div style="margin-left: 8%;margin-top: 1%">
    <div class="row">
        <div class="col-lg-6">
            <?php $form = ActiveForm::begin(
                ['id' => 'video-form',
                  'method'=> 'post'
                ]); ?>
            <?= $form->field($model, 'money')->label("余款金额") ?>
            <?= $form->field($model, 'receiptId')->label("收据编号") ?>
            <?= $form->field($model, 'payType')->radioList(['1'=>'现金','2'=>'银联','3'=>'信用卡','4'=>'微信支付','5'=>'支付宝'])->label("付款方式") ?>
            <input type="hidden" name="record" value="<?=$model['recordId'] ?>">
            <input type="hidden" name="_csrf" value="<?= Yii::$app->request->csrfToken ?>">
            <br>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="提交">
                <input type="button" class="btn btn-primary" onclick="backList()" value="返回">
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
</body>
</html>