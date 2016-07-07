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
                   // 'action' => 'save',
                    'method'=> 'post'
                ]); ?>

            <label>已报课程</label><br>
            <table class="table table-bordered table-hover">
            <?php
             $count = count($classes);
            foreach($classes as $val) { ?>
            <tr class="info">
                <td>
                    <input type="checkbox" name="courseId[]" checked="checked" value="<?=$val["id"]?>">《<?=$val["title"]?>》
                </td>
             </tr>
            <?php } ?>
            <?php if($count == 0){ ?>
                <tr class="info">
                    <td>
                       没有课程
                    </td>
                </tr>
            <?php } ?>
            </table>
            <?= $form->field($model, 'payType')->radioList(['1'=>'现金','2'=>'银联','3'=>'信用卡','4'=>'微信支付','5'=>'支付宝'])->label("退款方式") ?>
            <label>实收金额</label><br>
            <label><font color="#FF6600">¥ <?=$model["payMoney"]?></font></label><br>
            <?= $form->field($model, 'payMoney')->label("退课金额") ?>
            <?= $form->field($model, 'receiptId')->label("收据编号") ?>
            <?= $form->field($model, 'notes')->textarea()->label("退课原因") ?>
           <label>退课时间</label><br>
            <div class="input-group date form_datetime col-md-8" id="form_datetime"
                 data-date-format="yyyy-mm-dd HH:mm:ss">
                <input class="form-control" size="10" type="text" name="payTime"
                       value="<?=date('Y-m-d H:i:s', time())?>" readonly>
                <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
            </div>
            <input type="hidden" name="recordId" id="recordId"  value="<?=$model["recordId"]?>">
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
<script type="text/javascript">
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