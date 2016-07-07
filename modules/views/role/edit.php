<?php


use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

?>
<!DOCTYPE HTML>
<html lang="zh-CN">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <!--编辑器css-->
    <?=Html::cssFile('@web/css/bootstrap.min.css')?>
    <?=Html::jsFile('@web/Js/jquery-1.10.2.js')?>
</head>

<body>

<div class="container">

    <?php $form = ActiveForm::begin([
        'id' => 'role-form',
        'action' => 'save',
        'method' => 'post']); ?>


    <div class="row">
        <div class="col-lg-5">
            <?= $form->field($model, 'role_name')->label("角色名称") ?>
            <?= $form->field($model, 'role_desc')->label("角色描述") ?>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="提交">
                <input type="button" class="btn btn-primary" onclick="backList()" value="返回">
            </div>
        </div>
        </div>


    <?php ActiveForm::end(); ?>

</div>

<script>

    function backList (){
        window.location = "/admin/role/index";
    }

</script>
</body>
</html>