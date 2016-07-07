<?php
/**
 * Created by PhpStorm.
 * User: WWW
 * Date: 2015-12-09
 * Time: 16:58
 */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Alert;
use yii\widgets\LinkPager;

?>
<!doctype html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title>价格管理</title>
    <?= Html::cssFile('@web/css/bootstrap.min.css') ?>
    <?= Html::jsFile('@web/Js/jquery.js') ?>
    <?= Html::jsFile('@web/Js/bootstrap.js') ?>
</head>
<body>
<div style="margin:1%">
    <script>
        function add(course_id, class_id) {
            window.location = "/admin/organization/priceedit?course_id=" + course_id + "&class_id=" + class_id;
        }
        function edit(id, course_id, class_id) {
            window.location = "/admin/organization/priceedit?id=" + id + "&course_id=" + course_id + "&class_id=" + class_id;
        }
        function del(id, course_id, class_id) {
            if (window.confirm('确定要删除吗?')) {
                window.location = "/admin/organization/delprice?id=" + id + "&course_id=" + course_id + "&class_id=" + class_id;
                return true;
            } else {
                return false;
            }
        }

        function back(course_id) {
            window.location = "/admin/organization/classlist?course_id=" + course_id;
        }
    </script>

    <div class="row">
        <div class="col-lg-11">
            <input type="button" class="btn btn-primary" onclick="add(<?= $course_id ?>,<?= $class_id ?>)" value="新增">
            <input type="button" class="btn btn-primary" onclick="back(<?= $course_id ?>)" value="返回">
        </div>
    </div>
    <br>
    <table class="table table-bordered table-hover">
        <tr class="info">

            <td align="center">课次</td>
            <td align="center">周次</td>
            <td align="center">原价</td>
            <td align="center">折后价</td>
            <td align="center">操作</td>
        </tr>
        <tbody>
        <?php foreach ($pricelist as $info) { ?>
            <tr class="active">

                <td class="col-md-2" align="center"><?php echo $info["course_nums"] ?></td>
                <td class="col-md-2" align="center"><?php echo $info["week_nums"] ?></td>
                <td class="col-md-2" align="center"><?php echo $info["org_price"] ?></td>
                <td class="col-md-2" align="center"><?php echo $info["discount_price"] ?></td>
                <td class="col-md-4" align="center">
                    <button class="btn btn-primary"
                            onclick="edit(<?= $info["id"] ?>,<?= $course_id ?>,<?= $info["class_id"] ?>);">编辑
                    </button>

                    <button class="btn btn-danger"
                            onclick="del(<?= $info["id"] ?>,<?= $course_id ?>,<?= $info["class_id"] ?>)">删除
                    </button>
                </td>
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