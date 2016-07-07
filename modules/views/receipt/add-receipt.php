<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\VideoForm */


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
                    'action' => 'add-receipt',
                    'method'=> 'post'
                ]); ?>
            <?= $form->field($model, 'total_money[1]')->textInput(['maxlength'=>50])->label("申报课程  到账现金") ?>
            <?= $form->field($model, 'total_money[2]')->textInput(['maxlength'=>50])->label("其他收入  到账现金") ?>
            <div class="input-group date form_datetime col-md-12" id="form_datetime" data-date-format="yyyy-mm-dd" >
                <input class="form-control" type="text" name="add_time" id="add_time"
                       value="" placeholder="存款日期" readonly>
                <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
            </div>
            <input type="hidden" name="shop_id" value="<?=$shop_id?>">
            <input type="hidden" name="type" value="<?=$shop_id?>">
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
        format : 'yyyy-mm-dd h:i:s'
    });

    function formValidate(){
        // var add_time = $("#add_time").val();
        // var course_money = $("#shopcashrecord-total_money").val();
        // var other_money = $("#shopcashrecord-total_money-2").val();
        // if(add_time == ''){
        //     alert('时间必须填写');
        //     return;
        // }

        // if(!course_money && !other_money){
        //     alert('到账金额必须填写一个')
        //     return;
        // }
        
        $("#video-form").submit();
    }

</script>
</html>