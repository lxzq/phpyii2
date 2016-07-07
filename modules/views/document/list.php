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
    <?= Html::jsFile('@web/Js/jquery.js') ?>
    <?= Html::jsFile('@web/Js/bootstrap.js') ?>

</head>
<script>
    function add() {
        window.location = "/admin/document/add";
    }
    function del(id) {
        if (window.confirm('确定要删除吗?')) {
            window.location = "/admin/document/del?id=" + id;
            return true;
        } else {
            return false;
        }
    }
    function searchYuyue() {
        document.getElementById("searchform").submit();
    }
    function down(path) {
        window.location = path;
    }
</script>
<body>
<div style="margin-left: 1%;margin-top: 1%">
    <div class="row">

        <div class="col-lg-12">
            <form class="form-inline" action="list" method="post" role="form" id="searchform">
                <input type="hidden" name="_csrf" value="<?= Yii::$app->request->csrfToken ?>">

                <div class="form-group">
                    <input type="text" class="form-control" name="name" placeholder="文件名称">
                    <input type="button" class="btn btn-primary" onclick="searchYuyue()" value="查询">
                    <input type="button" class="btn btn-primary" style="margin-right: 10px" onclick="add()" value="新增">
                </div>
            </form>
        </div>
    </div>
    <br>
    <table class="table table-bordered table-hover">
        <tr class="info">
            <td align="center">文件名称</td>
            <td align="center">文件路径</td>
            <td align="center">上传时间</td>
            <td align="center" width="25%">上传说明</td>
            <td align="center">上传人员</td>
            <td align="center">操作</td>
        </tr>
        <tbody>
        <?php foreach ($list as $entity) { ?>
            <tr class="active" align="center">
                <td><?= $entity["name"] ?></td>
                <td><?= $entity["path"] ?></td>
                <td align="center"><?= $entity["add_time"] ?></td>
                <td align="center">
                   <?= $entity["notes"] ?>
                </td>
                <td align="center"><?= $entity["user"]["nickname"] ?></td>
                <td align="center">
                    <button class="btn btn-primary" onclick="down('<?= $entity["path"] ?>')">下载</button>
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