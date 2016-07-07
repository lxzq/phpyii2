<?php
/**
 * Created by PhpStorm.
 * User: WWW
 * Date: 2015-12-09
 * Time: 10:26
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
    <title>分班管理</title>
    <?= Html::cssFile('@web/css/bootstrap.min.css') ?>
    <?= Html::jsFile('@web/Js/jquery.js') ?>
    <?= Html::jsFile('@web/Js/bootstrap.js') ?>
</head>
<body>
<div style="margin:1%">
    <script>
        function add() {
            window.location = "/admin/class/add";
        }
        function edit(id) {
            window.location = "/admin/class/add?id=" + id;
        }
        function student(id) {
            window.location = "/admin/class/addstudent?placeclass_id=" + id;
        }
        function del(id) {
            if (window.confirm('确定要删除吗?')) {
                window.location = "/admin/class/del?id=" + id;
                return true;
            } else {
                return false;
            }
        }

        function searchOrg() {
            document.getElementById("searchform").submit();
        }
    </script>

    <div class="row">
        <div class="col-lg-11">
            <form class="form-inline" action="placeclasslist" method="post" role="form" id="searchform">
                <input type="hidden" name="_csrf" value="<?= Yii::$app->request->csrfToken ?>">

                <div class="form-group">
                    <input type="text" class="form-control" name="name" placeholder="分班名称">
                    <select name="shop" class="form-control" placeholder="店铺名称">
                        <option value="">全部店铺</option>
                        <?php foreach ($shops as $entity) { ?>
                            <option
                                value="<?= $entity["id"] ?>" <?php if ($entity["id"] == $shopId) { ?> selected="selected" <?php } ?>><?= $entity["name"] ?></option>
                        <?php } ?>
                    </select>
                    <input type="button" class="btn btn-primary" onclick="searchOrg()" value="查询">
                    <input type="button" class="btn btn-primary" style="margin-right: 10px" onclick="add()" value="新增">
                </div>
            </form>

        </div>

    </div>
    <br>

    <table class="table table-bordered table-hover">
        <tr class="info">
            <td align="center">所在店铺</td>
            <td align="center">班级名称</td>
            <td align="center">班级课程</td>
            <td align="center">班级老师</td>
            <td align="center">班级人数</td>
            <td align="center">备注</td>
            <td align="center">操作</td>
        </tr>
        <tbody>
        <?php foreach ($placeclasslist as $info) { ?>
            <tr class="active">
                <td align="center"><?php echo $info["shop"]["name"] ?></td>
                <td align="center"><?php echo $info["name"] ?></td>
                <td align="center"><?php echo $info["course"]["name"] ?></td>
                <td align="center"><?php echo $info["teacher"]["name"] ?></td>
                <td align="center"><?php echo count($info["num"])  ?>人</td>
                <td align="center"><?php echo $info["notes"] ?></td>
                <td class="col-md-3" align="center">
                   <button class="btn btn-primary" onclick="edit(<?= $info["id"] ?>);">编辑</button>
                    <button class="btn btn-primary" onclick="student(<?= $info["id"] ?>);">分配学员</button>
                    <button class="btn btn-danger" onclick="del(<?= $info["id"] ?>)">删除</button>
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