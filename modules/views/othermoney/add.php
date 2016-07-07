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
       // window.location = "/admin/child/list";
        window.history.back();
    }
</script>
<body>
<div style="margin-left: 8%;margin-top: 1%">
    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(
                ['id' => 'video-form',
                    'action' => 'save',
                    'method'=> 'post'
                ]); ?>
            <?= $form->field($model, 'shopId')->dropDownList($shops)->label("所属店铺") ?>
            <?= $form->field($model, 'payName')->textInput(['maxlength'=>100])->label("交款单位") ?>
            <label>收入时间</label>
            <div class="input-group date form_datetime col-md-8" id="form_datetime"
                 data-date-format="yyyy-mm-dd">
                <input class="form-control" size="10" type="text" name="addDate" id="addDate"
                       value="<?= $model["addDate"] ?>" readonly>
                <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
            </div>
            <br>
            <?= $form->field($model, 'receiptId')->textInput(['maxlength'=>50])->label("收据编号") ?>
            <?= $form->field($model, 'payType')->radioList(['1'=>'现金','2'=>'银联','3'=>'信用卡','4'=>'微信支付','5'=>'支付宝'])->label("支付方式") ?>
            <?= $form->field($model, 'payMoney')->textInput(['maxlength'=>20])->label("支付金额") ?>
            <?= $form->field($model, 'notes')->textInput(['maxlength'=>100])->label("收款事由") ?>
           <input type="hidden" name="_csrf" value="<?= Yii::$app->request->csrfToken ?>">
            <br>
            <div class="form-group">
                <input type="button" class="btn btn-primary" onclick="formValidate()"  value="提交">
                <input type="button" class="btn btn-primary" onclick="backList()" value="返回">
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
  </div>
</body>

<script type="text/javascript">
    $('.form_datetime').datetimepicker({
        language: 'zh-CN',
        weekStart: 0,
        todayBtn: 1,
        autoclose: true,
        todayHighlight: 1,
        viewSelect : 'month',
        startView: 2,
        forceParse: 0,
        showMeridian: 1,
        format : 'yyyy-mm-dd'
    });

    function formValidate(){
        var payName = $("#othermoneyrecordform-payname").val();
        var payMoney = $("#othermoneyrecordform-paymoney").val();
        if(payName == ''){
            alert('收入名称必须填写')
            return;
        }
        if(payMoney == ''){
            alert('支付金额必须填写')
            return;
        }
        $("#video-form").submit();
    }
</script>
</html>