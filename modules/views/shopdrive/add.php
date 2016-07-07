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
    <?=Html::cssFile('@web/css/jquery.Jcrop.css')?>
    <!--编辑器css-->
    <?= Html::cssFile('http://libs.useso.com/js/font-awesome/4.0.3/css/font-awesome.min.css') ?>
    <?= Html::cssFile('@web/css/editor.css') ?>

    <?=Html::jsFile('@web/Js/jquery-1.10.2.js')?>
    <?=Html::jsFile('@web/Js/jquery.form.js')?>
    <?=Html::jsFile('@web/Js/jquery.Jcrop.min.js')?>
    <?=Html::jsFile('@web/Js/bootstrap.js')?>
    <?= Html::jsFile('@web/Js/editor.js') ?>

</head>
<script >
    var url="http://"+window.location.host;
    function backList (){
        window.location = "/admin/shopdrive/list";
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
            <?= $form->field($model, 'name')->textInput(['maxlength'=>100])->label("设备名称") ?>
            <?= $form->field($model, 'shopId')->dropDownList($shops)->label("所属店铺") ?>
            <?= $form->field($model, 'code')->textInput(['maxlength'=>100])->label("设备编号") ?>
            <input type="hidden" name="id" value="<?=$model["id"]?>">
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