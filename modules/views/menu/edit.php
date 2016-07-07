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
        'id' => 'menu-form',
        'action' => 'save',
        'method' => 'post']); ?>


    <div class="row">
        <div class="col-lg-5">

            <br>
            <label>所属系统</label>
            <select name="app_code" onchange="getMenu(this.value)" class="form-control">
                <option value="">选择系统</option>
                <?php foreach($app_info as $key=>$value){?>
                    <option value="<?=$value['app_code']?>"  <?php if($model['app_code'] == $value['app_code']) { ?> selected="selected" <?php } ?> ><?=$value['app_name']?></option>
                <?php } ?>
            </select>
            <br>
            <label id="drive_label" >父级菜单</label>
            <select name="menu_parent_no" id="dropMenu" class="form-control">
                <option value='0'>作为父级菜单</option>
                <?php foreach($menu_info as $key=>$value){?>
                    <option value="<?=$value['id']?>"  <?php if($model['menu_parent_no'] == $value['id']) { ?> selected="selected" <?php } ?> ><?=$value['menu_name']?></option>
                <?php } ?>
            </select>
            <?= $form->field($model, 'menu_name')->label("菜单名称") ?>
            <?= $form->field($model, 'menu_url')->label("菜单url") ?>
            <?= $form->field($model, 'menu_order')->label("排序") ?>
            <?= $form->field($model, 'IsVisisble')->checkbox() ->label("是否隐藏") ?>
            <?= $form->field($model, 'IsLeaf')->checkbox() ->label("是否叶子节点") ?>
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