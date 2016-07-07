<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

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
    function add() {
        window.location = "/admin/child/visitoredit";
    }
    function edit(id) {
        window.location = "/admin/child/visitoredit?id=" + id;
    }
    function returnlist(id) {
        window.location = "/admin/child/returnlist?id=" + id;
    }
    function del(id) {
        if (window.confirm('确定要删除吗?')) {
            window.location = "/admin/child/delvisitor?id=" + id;
            return true;
        } else {
            return false;
        }
    }
    function searchYuyue() {
        document.getElementById("searchvisitor").submit();
    }

</script>
<body>
<div style="margin-left: 1%;margin-top: 1%">
    <div class="row">

        <div class="col-lg-7">
            <form class="form-inline" action="visitorlist" method="post" role="form" id="searchvisitor">
                <input type="hidden" name="_csrf" value="<?= Yii::$app->request->csrfToken ?>">

                <div class="form-group">
                    <select name="shop_id" class="form-control">
                        <option value="">全部店铺</option>
                        <?php foreach ($shops as $shop) { ?>
                            <option value="<?= $shop["id"] ?>"
                                    <?php if ($shop["id"] == $shopId) { ?>selected="selected" <?php } ?> ><?= $shop["name"] ?></option>
                        <?php } ?>
                    </select>
                    <input type="text" class="form-control" size="16" name="name" placeholder="访客姓名">
                    <input type="text" class="form-control" size="16" name="phone" placeholder="手机号">
                    <input type="button" class="btn btn-primary" style="margin-right: 10px" onclick="add()" value="新增"><br><br>
                    <input type="text" class="form-control" size="16" name="childClass" placeholder="年级">
                    <input type="text" class="form-control" size="16" name="content" value="<?=$content?>" placeholder="咨询内容">
                    <input type="text" class="form-control" size="16" name="notes" value="<?=$notes?>" placeholder="备注">
                    <input type="button" class="btn btn-primary" onclick="searchYuyue()" value="查询">

                </div>
            </form>
        </div>
    </div>
    <br>
    <table class="table table-bordered table-hover">
        <tr class="info">
            <td align="center">所属店铺</td>
            <td align="center">访客姓名</td>
            <td align="center">宝宝性别</td>
            <td align="center">出生日期</td>
            <td align="center">手机号</td>
            <td align="center">咨询内容</td>
            <td align="center">咨询时间</td>
            <td align="center">年级</td>
            <td width="15%" align="center">备注</td>
            <td align="center">记录人</td>
            <td align="center">操作</td>
        </tr>
        <tbody>
        <?php foreach ($visitorlist as $entity) { ?>
            <tr class="active" align="center">
                <td>
                    <?php
                    foreach ($shops as $shop) {
                        if ($shop["id"] == $entity["shop_id"]) {
                            echo $shop["name"];
                        }
                    }
                    ?>
                </td>
                <td><?= $entity["name"] ?></td>
                <td><?php if($entity["sex"] == 1) echo '男';else echo '女'; ?> </td>
                <td><?= $entity["birth_date"] ?></td>
                <td align="center">
                    <?= $entity["tel"] ?>
                </td>
                <td><?= $entity["content"] ?></td>
                <td align="center"><?= $entity["add_date"] ?></td>
                <td align="center"><?= $entity["child_class"] ?></td>
                <td align="center"><?= $entity["notes"] ?></td>
                <td align="center"><?= $entity["user"]["nickname"] ?></td>
                <td align="center">
                    <button class="btn btn-primary" onclick="returnlist(<?= $entity["id"] ?>)">回访</button>
                    <button class="btn btn-primary" onclick="edit(<?= $entity["id"] ?>)">编辑</button>
                    <button class="btn btn-danger" onclick="del(<?= $entity["id"] ?>)">删除</button>
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