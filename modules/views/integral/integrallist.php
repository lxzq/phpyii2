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
            <form class="form-inline" action="integral-list" method="post" role="form" id="searchform">
                <input type="hidden" name="_csrf" value="<?= Yii::$app->request->csrfToken ?>">
                <div class="form-group">
                    <input type="text" class="form-control" name="name" placeholder="会员姓名">
                    <input type="button" class="btn btn-primary" onclick="searchYuyue()" value="查询">
                </div>
            </form>
        </div>
    </div>
    <br>
    <table class="table table-bordered table-hover">
        <tr class="info">
            <td align="center">所属店铺</td>
            <td align="center">会员姓名</td>
            <td align="center">手机号</td>
            <td align="center">积分名称</td>
            <td align="center">积分值</td>
            <td align="center">积分日期</td>
       </tr>
        <tbody>
        <?php foreach ($list as $entity) { ?>
            <tr class="active" align="center">
                <td>
                    <?php
                    foreach($shops as $shop){
                        if($shop["id"] ==$entity["child"]["shop_id"] ){
                            echo $shop["name"];
                        }
                    }
                    ?>
                </td>
                <td><?= $entity["child"]["nick_name"] ?></td>
                <td><?= $entity["child"]["phone"] ?></td>
                <td><?= $entity["integral_name"] ?></td>
                <td align="center">
                   <?php  if($entity["integral_val"] >= 0) { ?>
                       <font color="green">+<?=$entity["integral_val"]?>分</font>
                   <?php } else {?>
                       <font color="red"><?=$entity["integral_val"]?>分</font>
                   <?php }?>
                </td>
                <td align="center"><?= $entity["integral_date"] ?></td>
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