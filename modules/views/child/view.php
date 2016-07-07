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
        window.location = "/admin/child/course";
    }
    function edit(id) {
        window.location = "/admin/child/course?id=" + id;
    }
    function del(id) {
        if (window.confirm('确定要删除吗?')) {
            window.location = "/admin/child/del?id=" + id;
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
            <form class="form-inline" action="view" method="post" role="form" id="searchform">
                <input type="hidden" name="_csrf" value="<?= Yii::$app->request->csrfToken ?>">
                <div class="form-group">
                    <select name="shop" class="form-control">
                        <option value="" >全部店铺</option>
                        <?php  foreach($shops as $shop) {?>
                            <option value="<?=$shop["id"]?>"  <?php if($shop["id"]==$key) { ?>selected="selected"  <?php } ?>  ><?=$shop["name"]?></option>
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
            <td align="center">所属店铺</td>
            <td align="center">课程标题</td>
            <td align="center">上课教室</td>
            <td align="center">上课班级</td>
            <td align="center">上课时间</td>
            <td align="center">上课老师</td>
            <td align="center">课程简介</td>
            <td align="center">操作</td>
        </tr>
        <tbody>
        <?php foreach ($data as $entity) { ?>
            <tr class="active" align="center">
                <td><?= $entity["shop"]['name'] ?></td>
                <td align="center">
                    <?= $entity["course_name"] ?>
                </td>
                <td><?= $entity["room"]["name"] ?></td>
                <td><?= $entity["class"]["name"] ?></td>
                <td align="center">
                    <?= $entity["course_date"] ?> <br>
                    <?= $entity["start_time"] ?> 时：<?= $entity["start_m"] ?> 分 -
                    <?= $entity["end_time"] ?> 时：<?= $entity["end_m"] ?> 分
                </td>
                <td><?= $entity["teacher"]["name"] ?></td>
                <td align="center">
                    <?php
                    if (mb_strlen($entity["notes"], "utf-8") > 18)
                        echo mb_substr($entity["notes"], 0, 18, 'utf-8') . '...';
                    else echo $entity["notes"];
                    ?>
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