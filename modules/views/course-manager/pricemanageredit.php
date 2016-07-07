<?php
/**
 * Created by PhpStorm.
 * User: WWW
 * Date: 2015-12-09
 * Time: 16:58
 */

use app\models\ShopInfo;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <?= Html::cssFile('@web/css/bootstrap.min.css') ?>

    <?= Html::jsFile('@web/Js/jquery.js') ?>
    <?= Html::jsFile('@web/Js/bootstrap.js') ?>
</head>

<body>
<div style="margin-left: 5%;margin-top: 1%;margin-right: 5%">
    <?php $form = ActiveForm::begin([
        'id' => 'course-price-form',
        'action' => 'save-manager-price',
        'method' => 'post']); ?>

    <div class="row">
        <div class="col-lg-5">

            <input type="hidden" name="id" id="id" value="<?= $model["id"] ?>">
            <input type="hidden" name="course_id" id="course_id" value="<?= $course_id ?>">

            <?= $form->field($model, 'course_nums')->label("课次") ?>
            <?= $form->field($model, 'week_nums')->label("周/次") ?>
            <?= $form->field($model, 'org_price')->label("原价") ?>
            <?= $form->field($model, 'discount_price')->label("折后价") ?>
        </div>

    </div>

    <br>

    <div class="form-group" align="left">
        <input type="submit" class="btn btn-primary" value="提交">
        <input type="button" class="btn btn-primary" onclick="backlist()"
               value="返回">
    </div>
    <?php ActiveForm::end(); ?>

</div>
<script type="text/javascript">
    function backlist() {
//        window.location = '/admin/organization/pricelist?course_id=' + course_id + '&class_id=' + class_id;
        window.history.back();
    }
</script>
</body>
</html>