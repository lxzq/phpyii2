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
                ['id' => 'customervisit-form',
                    'action' => 'savereturn',
                    'method'=> 'post'
                ]); ?>
            <input type="hidden" name="id" value="<?=$model["id"] ?>">
            <input type="hidden" name="cusromer_record_id" value="<?=$model["cusromer_record_id"] ?>">
            <div style="font-size: 20px"><?=$visitor['name'] ?></div><br>
            <?/*= $form->field($visitor, 'name')->textInput(['maxlength'=>50])->label("访客姓名") */?>
            <?= $form->field($model, 'content')->textarea(['maxlength'=>500])->label("回访内容") ?>
            <label>回访时间</label>
            <div class="input-group date form_datetime col-md-8" id="form_datetime"
                 data-date-format="yyyy-mm-dd">
                <input class="form-control" type="text" name="add_time"
                       value="<?= $model["add_time"] ?>" readonly>
                <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
            </div>
            <br>
            <?= $form->field($model, 'user')->textInput(['maxlength'=>50])->label("记录人") ?>
            <?= $form->field($model, 'notes')->textarea(['maxlength'=>200])->label("备注") ?>
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
        weekStart: 0,
        todayBtn: 1,
        autoclose: true,
        todayHighlight: 1,
        viewSelect : 'month',
        startView: 2,
        forceParse: 0,
        showMeridian: 1,
        format : 'yyyy-mm-dd hh:ii'
    });
</script>
</html>