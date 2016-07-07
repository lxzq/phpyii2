<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

use app\assets\AppAsset;
use yii\helpers\Html;
use yii\widgets\LinkPager;

?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <?= Html::cssFile('@web/css/bootstrap.min.css') ?>
    <?= Html::jsFile('@web/Js/bootstrap.js') ?>

</head>
<script>
    function add() {
        window.location = "/admin/video/video";
    }
    function edit(id) {
        window.location = "/admin/video/video?id=" + id;
    }
    function del(id, isdel) {
        if (isdel == 0) {
            window.location = "/admin/video/del?id=" + id + "&status=" + isdel;
        } else {
            if (window.confirm('确定要删除吗?')) {
                window.location = "/admin/video/del?id=" + id + "&status=" + isdel;
                return true;
            } else {
                return false;
            }
        }
    }
    function sort(id, num) {
        window.location = "/admin/video/sort?id=" + id + "&sort=" + num;
    }
    function lb(id, num) {
        window.location = "/admin/video/lb?id=" + id + "&lb=" + num;
    }
    function searchYuyue() {
        document.getElementById("searchform").submit();
    }
</script>
<body>
<div style="margin-left: 1%;margin-top: 1%">
    <div class="row">
        <div class="col-lg-12">
            <form class="form-inline" action="list" method="post" role="form" id="searchform">
                <input type="hidden" name="_csrf" value="<?= Yii::$app->request->csrfToken ?>">
                <div class="form-group">
                    <select name="shop" class="form-control" placeholder="店铺名称">
                        <option value="">全部店铺</option>
                        <?php foreach ($shops as $entity) { ?>
                            <option
                                value="<?= $entity["id"] ?>" <?php if ($shopId == $entity["id"]) { ?> selected="selected" <?php } ?> ><?= $entity["name"] ?></option>
                        <?php } ?>
                    </select>
                    <input type="text" name="nickname" class="form-control" value="<?=$nickname?>" placeholder="员工姓名">

                    <input type="button" class="btn btn-primary" onclick="searchYuyue()" value="查询">

                </div>
            </form>
        </div>
    </div>
    <br>
    <table class="table table-bordered table-hover">
        <tr class="info">
            <!--<td align="center">所属店铺</td>-->
            <td align="center">微信头像</td>
            <td align="center">员工姓名</td>
            <td align="center">签到时间</td>
            <td align="center">签到地址</td>
        </tr>
        <tbody>
        <?php foreach ($data as $entity) { ?>
            <tr class="active">
                <!--<td><?/*= $entity['user']['shop_info']['name'] */?></td>-->
                <td align="center"><img src="<?= $entity["user"]['userface'] ?>" width="50px" height="50px"></td>
                <td align="center"><?= $entity["user"]['nickname'] ?></td>
                <td align="center"><?= $entity["add_time"] ?></td>
                <td align="center"><?= $entity["sign_address"] ?></td>

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