<?php
/**
 * Created by PhpStorm.
 * User: WWW
 * Date: 2015-12-09
 * Time: 10:28
 */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;


$this->title = '添加学生';

$this->params['breadcrumbs'][] = $this->title;
?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title>学生</title>

    <!--编辑器css-->
    <?= Html::cssFile('@web/css/bootstrap.min.css') ?>
    <?= Html::cssFile('http://libs.useso.com/js/font-awesome/4.0.3/css/font-awesome.min.css') ?>
    <?= Html::cssFile('@web/css/editor.css') ?>
    <!--裁剪-->
    <?= Html::cssFile('@web/css/jquery.Jcrop.css') ?>

    <?= Html::jsFile('@web/Js/jquery-1.10.2.js') ?>
    <?= Html::jsFile('@web/Js/bootstrap.js') ?>
    <!--编辑器js-->
    <?= Html::jsFile('@web/Js/editor.js') ?>
    <!--裁剪-->
    <?= Html::jsFile('@web/Js/jquery.form.js') ?>
    <?= Html::jsFile('@web/Js/jquery.Jcrop.min.js') ?>
</head>


<script>

    function backList() {
//        window.location =url + '/admin/organization/orglist';
        window.history.back();
    }

</script>

<body>
<div style="margin-left: 5%;margin-top: 1%;margin-right: 5%">

    <?php $form = ActiveForm::begin([
        'id' => 'addstudent-form',
        'action' => 'savestudent',
        'method' => 'post']); ?>

    <div class="row">
        <div class="col-lg-10">
            <?= $placeclass["name"] ?>
            <br>
            <br>
            <?php $index = 0 ; foreach ($studentlist as $info) { ?>
                <input type="checkbox"
                       <?php if($info['is_add'] == 1){?>
                           checked="checked"
                       <?php }?>
                       name="childids[]" value="<?=$info["id"]?>" >
                <?= $info["nick_name"]."(".$info["course_num"]."课次)"?>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <?php
                $index++;
                if($index % 5 == 0) echo '<br>';
            } ?>
        </div>
    </div>
    <input type="hidden" name="id" id="id" value="<?= $placeclass["id"] ?>">
    <br>

    <div class="form-group" align="left">
        <input type="submit" class="btn btn-primary" value="提交">
        <input type="button" class="btn btn-primary" onclick="backList()" value="返回">
    </div>
    <?php ActiveForm::end(); ?>
</div>
</body>
</html>