<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\VideoForm */

use yii\helpers\Html;
use yii\widgets\LinkPager;

?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <?= Html::cssFile('@web/css/bootstrap.min.css') ?>
    <?= Html::jsFile('@web/Js/jquery.js') ?>
    <?= Html::jsFile('@web/Js/bootstrap.js') ?>

</head>
<script>

    function searchYuyue() {
        document.getElementById("searchform").submit();
    }

</script>
<body>
<div style="margin-left: 1%;margin-top: 1%">
    <div class="row">

        <div class="col-lg-7">
            <form class="form-inline" action="shopreg" method="post" role="form" id="searchform">
                <input type="hidden" name="_csrf" value="<?= Yii::$app->request->csrfToken ?>">
                <div class="form-group">
                    <input type="text" class="form-control" name="username" placeholder="报名姓名">
                    <input type="text" class="form-control" name="phone" placeholder="手机号">
                    <input type="button" class="btn btn-primary" onclick="searchYuyue()" value="查询">

                </div>
            </form>
        </div>
    </div>
    <br>
    <table class="table table-bordered table-hover">
        <tr class="info">
            <td align="center">所属店铺</td>
            <td align="center">报名姓名</td>
            <td align="center">手机号</td>
            <td align="center">报名时间</td>
            <td align="center">报名人数</td>
        </tr>
        <tbody>
        <?php foreach ($list as $entity) { ?>
            <tr class="active" align="center">
                <td><?= $entity["shop"]["name"] ?></td>
                <td><?= $entity["username"] ?></td>
                <td align="center"> <?= $entity["phone"] ?></td>
                <td align="center"> <?= $entity["create_time"] ?></td>
                <td align="center"> <?= $entity["number"] ?></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
    <div class="row">
        <div class="col-lg-12" align="right">
            <?= LinkPager::widget(['pagination' => $pages]); ?>
        </div>
    </div>
</div>
</body>
</html>