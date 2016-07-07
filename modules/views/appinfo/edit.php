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
        'id' => 'appinfo-form',
        'action' => 'save',
        'method' => 'post']); ?>


    <div class="row">
        <div class="col-lg-5">

            <?= $form->field($model, 'app_code')->label("应用编码") ?>
            <?= $form->field($model, 'app_name')->label("应用名称") ?>
            <?= $form->field($model, 'app_desc')->label("应用描述") ?>
            <?= $form->field($model, 'app_icon')->label("应用图标") ?>

            <?= $form->field($model, 'is_show')->checkbox() ->label("是否显示") ?>
            <input type="hidden" name="id" value="<?=$model['id']?>">
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="提交">
                <input type="button" class="btn btn-primary" onclick="backList()" value="返回">
            </div>
        </div>
        </div>


    <?php ActiveForm::end(); ?>

</div>

<script>

    function getMenu(app_code){

        var url = "/admin/menu/get-menu";
        $.ajax({
            type: 'post',
            url: url,
            data: {app_code: app_code},
            dataType: 'json',
            success: function (data) {
                $("#dropMenu").empty();
                var html = "<option value='0'>作为父级菜单</option>"
                for (var i = 0; i < data.length; i++) {
                    var json = data[i];
                     html += "<option value=" + json.key + ">" + json.value + "</option>"

                    if(0 == i){
                        $("#dropMenu").val(json.key);
                    }
                }

                $("#dropMenu").append(html);
            },
            error: function (data) {

            }
        })
    }

    function backList (){
        window.location = "/admin/menu/index";
    }

</script>
</body>
</html>