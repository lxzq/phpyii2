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
   function edit(id) {
        window.location = "/admin/integral/edit?id=" + id;
   }
</script>
<body>
<div style="margin-left: 1%;margin-top: 1%">
    <div class="row">
        <div class="col-lg-7">
        </div>
    </div>
    <br>
    <table class="table table-bordered table-hover">
        <tr class="info">
            <td align="center">积分编码</td>
            <td align="center">积分名称</td>
            <td align="center">积分值</td>
            <td align="center">操作</td>
        </tr>
        <tbody>
        <?php foreach ($list as $entity) { ?>
            <tr class="active" align="center">
                <td align="center"><?= $entity["code"] ?></td>
                <td align="center"><?= $entity["name"] ?></td>
                <td align="center"><?php
                   if($entity["code"] < 2000){
                   ?>
                <font color="green">+ <?=$entity["op_val"]?> <?php if($entity["op_val"] <= 1) echo '倍' ; else echo '分'?> </font>
                 <?php } else if($entity["code"] > 2000) {?>
                <font color="red">- <?=$entity["op_val"]?><?php if($entity["op_val"] <= 1) echo '倍' ; else echo '分' ?></font>
                <?php } else {?>
                   <font color="red"><?=$entity["op_val"]?></font>
                <?php }?>
                </td>
               <td align="center">
                    <button class="btn btn-primary" onclick="edit(<?= $entity["id"] ?>)">修改积分</button>
                </td>
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