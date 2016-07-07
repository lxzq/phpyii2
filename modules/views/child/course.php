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
        window.location = "/admin/child/view";
       // window.history.back();
    }
</script>
<body>
<div style="margin-left: 8%;margin-top: 1%">
    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(
                ['id' => 'video-form',
                    'action' => 'savecourse',
                    'method'=> 'post'
                ]); ?>
            <?= $form->field($model, 'shopId')->dropDownList($shops)->label("所属店铺") ?>
            <?= $form->field($model, 'courseRoom')->dropDownList($rooms)->label("上课教室") ?>
            <?= $form->field($model, 'courseClass')->dropDownList($classs)->label("上课班级") ?>
            <?= $form->field($model, 'courseTeacher')->dropDownList($teachers)->label("上课老师") ?>
            <?= $form->field($model, 'courseName')->textInput(['maxlength'=>100])->label("上课标题") ?>

            <label>上课日期</label>
            <div class="input-group date form_datetime col-md-8" id="form_datetime"
                 data-date-format="yyyy-mm-dd">
                <input class="form-control" size="10" type="text" name="courseDate"
                       value="<?= $model["courseDate"] ?>" readonly>
                <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
            </div>
            <br>
            <label>课时节点</label>
            <br>
            <select name="startTime">
                <?php  for($h = 1 ; $h < 24 ; $h++) { ?>
                <option value="<?=$h?>" <?php if($h == $model["startTime"]) { ?> selected="selected" <?php } ?>> <?=$h?></option>
                <?php }?>
            </select>  时
            :
            <select name="startM">
                <?php  for($h = 0 ; $h < 60 ; $h++) { ?>
                    <option value="<?=$h?>" <?php if($h == $model["startM"]) {?> selected="selected" <?php } ?>><?=$h?></option>
                <?php }?>
            </select>
             分
            -
            <select name="endTime">
                <?php  for($h = 1 ; $h < 24 ; $h++) { ?>
                    <option value="<?=$h?>" <?php if($h == $model["endTime"]) {?> selected="selected" <?php } ?>><?=$h?></option>
                <?php }?>
            </select>
              时
            :
            <select name="endM">
                <?php  for($h = 0 ; $h < 60 ; $h++) { ?>
                    <option value="<?=$h?>" <?php if($h == $model["endM"]) {?> selected="selected" <?php } ?>><?=$h?></option>
                <?php }?>
            </select>
             分
            <br>
            <br>
            <?= $form->field($model, 'notes')->textarea(['maxlength'=>100])->label("课程简介") ?>
            <input type="hidden" name="id" value="<?=$model["id"] ?>">
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
        format : 'yyyy-mm-dd'
    });
</script>
</html>