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
            <?= $form->field($model, 'money')->label("实收金额") ?>
            <?= $form->field($model, 'receiptId')->label("收据编号") ?>
           <label>报名时间</label><br>
            <div class="input-group date form_datetime col-md-8" id="form_datetime"
                 data-date-format="yyyy-mm-dd HH:mm:ss">
                <input class="form-control" size="10" type="text" name="addDate"
                       value="<?=$addDate ?>" readonly>
                <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
            </div>
            <br>
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
<script>
    $('.form_datetime').datetimepicker({
        language: 'zh-CN',
        weekStart: 1,
        todayBtn: 1,
        autoclose: 1,
        todayHighlight: 1,
        startView: 2,
        forceParse: 0,
        showMeridian: 1
    });

</script>
</html>