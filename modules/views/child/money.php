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
    function backList() {
        // window.location = "/admin/child/list";
        window.history.back();
    }
  </script>
<body>
<div style="margin-left: 1%;margin-top: 1%">
    <div class="row">

        <div class="col-lg-7">
            <form class="form-inline" action="list" method="post" role="form" id="searchform">
                <input type="hidden" name="_csrf" value="<?= Yii::$app->request->csrfToken ?>">
                <div class="form-group">

                    <input type="button" class="btn btn-primary" onclick="backList()" value="返回">

                </div>
            </form>
        </div>
    </div>
    <br>
    <table class="table table-bordered table-hover">
        <tr class="info">
            <td align="center">充值单号</td>
            <td align="center">描述</td>
            <td align="center">充值时间</td>
            <td align="center">消费类型</td>
            <td align="center">应收金额(元)</td>
            <td align="center">实收金额(元)</td>
        </tr>
        <tbody>
        <?php foreach ($list as $entity) { ?>
            <tr class="active" align="center">
                <td>
                    <?= $entity["order_no"] ?>
                </td>
                <td>
                  <?= $entity["notes"] ?>
                </td>
                <td><?= $entity["add_time"] ?></td>
                <td align="center">
                    <?php
                    if($entity["type"] == 1 )
                        echo '充值';
                    else echo '优惠'
                    ?>
                </td>
                <td align="center">￥<?= $entity["should_money"] ?></td>
                <td align="center"> <font color="#FF6600">￥<?= $entity["money"] ?></font></td>
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