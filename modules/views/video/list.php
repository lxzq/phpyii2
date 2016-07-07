<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\VideoForm */
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
        <!--<div class="col-lg-10">
            <b style="font-size: 24px"><? /*= Html::encode($this->title) */ ?></b>
        </div>-->
        <div class="col-lg-12">
            <form class="form-inline" action="list" method="post" role="form" id="searchform">
                <input type="hidden" name="_csrf" value="<?= Yii::$app->request->csrfToken ?>">

                <div class="form-group">
                    <input type="text" class="form-control" name="name" placeholder="直播名称">
                    <select name="shopId" class="form-control" placeholder="店铺名称">
                        <option value="">全部店铺</option>
                        <?php foreach ($shops as $entity) { ?>
                            <option
                                value="<?= $entity["id"] ?>" <?php if ($shopId == $entity["id"]) { ?> selected="selected" <?php } ?> ><?= $entity["name"] ?></option>
                        <?php } ?>
                    </select>

                    <input type="button" class="btn btn-primary" onclick="searchYuyue()" value="查询">
                    <input type="button" class="btn btn-primary" style="margin-right: 10px" onclick="add()" value="新增">
                </div>
            </form>
        </div>
    </div>
    <br>


    <table class="table table-bordered table-hover">

        <tr class="info">
            <td>直播名称</td>
            <td align="center">直播图片</td>
            <td align="center">所属店铺</td>
            <td align="center">直播类型</td>
            <td align="center">轮播图</td>
            <td>备注</td>
            <td>排序</td>
            <td align="center">操作</td>
        </tr>

        <tbody>
        <?php foreach ($list as $entity) { ?>
            <tr class="active">
                <td><?= $entity["name"] ?></td>
                <td align="center"><img src="<?= $entity["image"] ?>" width="100px" height="50px"></td>
                <td align="center">
                    <?php
                    foreach ($shops as $shop) {
                        if ($shop["id"] == $entity["project_id"]) {
                            echo $shop["name"];
                        }
                    }
                    ?>
                </td>
                <td align="center">
                    <?php
                    if (0 == $entity["type"]) {
                        echo '<font color="#228b22">直播</font>';
                    } else if (1 == $entity["type"]) {
                        echo '<font color="red">视频</font>';
                    } else {
                        echo '微吼直播';
                    }
                    ?>
                </td>
                <td align="center">
                    <?php
                    if (0 == $entity["lb"]) {
                        echo '否';
                    } else {
                        echo '是';
                    }
                    ?>
                </td>
                <td> <?= $entity["notes"] ?></td>
                <td>
                    <input type="number" value="<?= $entity["sort_num"] ?>"
                           onchange="sort(<?= $entity["id"] ?>,this.value)">
                </td>
                <td align="center">
                    <button class="btn btn-primary" onclick="lb(<?= $entity["id"] ?>,<?= $entity["lb"] ?>)">轮播图</button>
                    <button class="btn btn-primary" onclick="edit(<?= $entity["id"] ?>)">编辑</button>
                    <?php if ($entity["status"] == 0) { ?>
                        <button class="btn btn-primary"
                                onclick="del(<?= $entity["id"] ?>,<?= $entity["status"] ?>)">开启
                        </button>
                    <?php } else { ?>
                        <button class="btn btn-danger"
                                onclick="del(<?= $entity["id"] ?>,<?= $entity["status"] ?>)">关闭
                        </button>
                    <?php } ?>
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