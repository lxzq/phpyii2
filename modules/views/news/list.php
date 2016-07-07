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
    function add() {
        window.location = "/admin/news/add";
    }
    function edit(id) {
        window.location = "/admin/news/add?id=" + id;
    }
   function del(id) {
         if (window.confirm('确定要删除吗?')) {
             window.location = "/admin/news/del?id=" + id;
         }
    }
    function searchYuyue() {
        document.getElementById("searchform").submit();
    }
</script>
<body>
<div style="margin-left: 1%;margin-top: 1%">
    <div class="row">
        <div class="col-lg-7">
            <form class="form-inline" action="list" method="post" role="form" id="searchform">
                <input type="hidden" name="_csrf" value="<?= Yii::$app->request->csrfToken ?>">
                <div class="form-group">
                    <input type="text" class="form-control" name="title" placeholder="新闻标题">
                    <input type="button" class="btn btn-primary" onclick="searchYuyue()" value="查询">
                    <input type="button" class="btn btn-primary" style="margin-right: 10px" onclick="add()" value="新增">
                </div>
            </form>
        </div>
    </div>
    <br>
    <table class="table table-bordered table-hover">
        <tr class="info">
            <td align="center">新闻图片</td>
            <td width="30%" align="center">新闻标题</td>
            <td align="center">新闻发布人</td>
            <td align="center">发布时间</td>
            <td width="30%" align="center">新闻简介</td>
            <td align="center">操作</td>
        </tr>
        <tbody>
        <?php foreach ($list as $entity) { ?>
            <tr class="active" align="center">
                <td align="center"><img src="<?= $entity["image"] ?>" width="110px" height="50px"></td>
                <td>
                 <?=$entity["title"]?>
                </td>
                <td>
                    <?=$entity["user"]["nickname"]?>
                </td>
               <td align="center">
                    <?= $entity["add_time"] ?>
                </td>
                <td align="center">
                    <?= $entity["content"] ?>
                </td>
                <td align="center">
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