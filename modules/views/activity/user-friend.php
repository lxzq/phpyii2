<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Alert;
use yii\widgets\LinkPager;

?>
<!doctype html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title>活动朋友</title>
    <?= Html::cssFile('@web/css/bootstrap.min.css') ?>
    <?= Html::jsFile('@web/Js/jquery.js') ?>
    <?= Html::jsFile('@web/Js/bootstrap.js') ?>
</head>
<script>
    function back() {
       window.history.go(-1);
    }
</script>
<body>
<div class="col-lg-12">
      <div class="row" style="margin-top: 2%">
        <div class="col-lg-7">
            <form class="form-inline" action="activitylist" method="post" role="form" id="searchform">
                <input type="hidden" name="_csrf" value="<?/*= Yii::$app->request->csrfToken */?>">
                <div class="form-group">

                    <input type="button" class="btn btn-primary" onclick="back()" value="返回">
                </div>
            </form>
        </div>
    </div>
    <br>
    <table class="table table-bordered table-hover">
        <tr class="info">
            <td align="center">姓名</td>
            <td align="center">手机号</td>
            <td align="center">报名时间</td>
        </tr>
        <tbody>
        <?php foreach ($activityInfo as $activity) { ?>
            <tr class="active" align="center">
                <td align="center"><?php echo $activity["nick_name"] ?></td>
                <td align="center"><?php echo $activity["phone"] ?></td>
                <td align="center"><?= $activity["add_time"] ?></td>
            </tr>
            <?php
        } ?>
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