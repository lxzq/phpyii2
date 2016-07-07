<?php
/**
 * Created by PhpStorm.
 * User: WWW
 * Date: 2015-12-09
 * Time: 16:58
 */

use app\models\ShopInfo;
use app\models\TeacherInfo;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Alert;
use yii\widgets\LinkPager;

?>
<!doctype html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title>班级管理</title>
    <?= Html::cssFile('@web/css/bootstrap.min.css') ?>
    <?= Html::jsFile('@web/Js/jquery.js') ?>
    <?= Html::jsFile('@web/Js/bootstrap.js') ?>
</head>
<body>
<div style="margin:1%">
    <div class="row">
        <div class="col-lg-11">
            <form class="form-inline" action="classlist" method="post" role="form" id="searchform">
                <input type="hidden" name="_csrf" value="<?= Yii::$app->request->csrfToken ?>">

                <div class="form-group">
                    <input type="text" class="form-control" name="name" placeholder="班级名称">
                    <select name="ctype" id="ctype" class="form-control" placeholder="课程类型">
                        <option value="0" <?php if (0 == $ctype) { ?> selected="selected" <?php } ?>>普通课程</option>
                        <option value="1" <?php if (1 == $ctype) { ?> selected="selected" <?php } ?>>试听课程</option>
                    </select>
                    <select name="org" class="form-control" placeholder="机构名称">
                        <option value="">全部机构</option>
                        <?php foreach ($orgs as $entity) { ?>
                            <option
                                value="<?= $entity["id"] ?>" <?php if ($entity["id"] == $orgId) { ?> selected="selected" <?php } ?>><?= $entity["name"] ?></option>
                        <?php } ?>
                    </select>
                    <select name="shop" class="form-control" placeholder="店铺名称">
                        <option value="">全部店铺</option>
                        <?php foreach ($shops as $entity) { ?>
                            <option
                                value="<?= $entity["id"] ?>" <?php if ($entity["id"] == $shopId) { ?> selected="selected" <?php } ?>><?= $entity["name"] ?></option>
                        <?php } ?>
                    </select>
                    <input type="button" class="btn btn-primary" onclick="searchOrg()" value="查询">
                    <input type="button" class="btn btn-primary" onclick="add()" value="新增">

                </div>
            </form>
        </div>
    </div>
    <br>
    <table class="table table-bordered table-hover">
        <tr class="info">

            <td align="center">课次标题</td>
            <td align="center">所在店铺</td>
            <!--<td align="center">授课老师</td>-->
            <td align="center">报名时间</td>
           <!-- <td align="center">截止时间</td>-->
            <td align="center">上课时间</td>
            <td align="center">人数限制</td>
            <td align="center">操作</td>
        </tr>
        <tbody>
        <?php foreach ($classlist as $info) { ?>
            <tr class="active">

                <td class="col-md-2" align="center"><?php echo $info["title"] ?></td>
                <td class="col-md-1" align="center"><?php $shop_id = $info["shop_id"];
                    $shop = ShopInfo::findOne($shop_id);
                    echo $shop["name"] ?></td>
                <!--<td class="col-md-1" align="center"><?php /*$teacher_id = $info["teacher_id"];
                    $shop = TeacherInfo::findOne($teacher_id);
                    echo $shop["name"] */?></td>-->
                <td class="col-md-1" align="center"><?php echo $info["start_time"] ?></td>
                <!--<td class="col-md-2" align="center"><?php /*echo $info["end_time"] */?></td>-->
                <td class="col-md-1" align="center"><?php echo $info["class_time"] ?></td>
                <td class="col-md-1" align="center"><?php echo $info["min_nums"] . "~" . $info["max_nums"] ?></td>
                <td class="col-md-3" align="center">
                    <button class="btn btn-primary" onclick="signuplist(<?= $info["id"] ?>);">
                        查看报名
                    </button>
                    <button class="btn btn-primary" onclick="pricelist(<?= $info["course_id"] ?>,<?= $info["id"] ?>);">
                        价格管理
                    </button>
                    <button class="btn btn-primary" onclick="edit(<?= $info["id"] ?>,<?= $info["course_id"] ?>);">编辑
                    </button>
                    <?php if ($info["status"] == 0) { ?>
                        <button class="btn btn-primary"
                                onclick="del(<?= $info["id"] ?>,<?= $info["course_id"] ?>,<?= $info["status"] ?>)">开启
                        </button>
                    <?php } else { ?>
                        <button class="btn btn-danger"
                                onclick="del(<?= $info["id"] ?>,<?= $info["course_id"] ?>,<?= $info["status"] ?>)">删除
                        </button>
                    <?php } ?>
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
<script>
    function add() {
        var ctype = $("#ctype").val();
//        alert(ctype);
        window.location = "/admin/organization/classedit?ctype=" + ctype;
    }
    function edit(id) {
        var ctype = $("#ctype").val();
        window.location = "/admin/organization/classedit?id=" + id + "&ctype=" + ctype;
    }
    function del(id, course_id, isdel) {

        if (isdel == 0) {
            window.location = "/admin/organization/restart?id=" + id + "&course_id=" + course_id;
        } else {
            if (window.confirm('确定要删除吗?')) {
                window.location = "/admin/organization/delclass?id=" + id + "&course_id=" + course_id;
                return true;
            } else {
                return false;
            }
        }
    }

    function back() {
        window.location = "/admin/organization/courselist";
    }
    function pricelist(course_id, class_id) {
        window.location = "/admin/organization/pricelist?course_id=" + course_id + "&class_id=" + class_id;
    }
    function signuplist(class_id) {
        window.location = "/admin/order/signuplist?class_id=" + class_id;
    }
    function searchOrg() {
        document.getElementById("searchform").submit();
    }
</script>
</html>