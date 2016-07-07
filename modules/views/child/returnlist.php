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
    function add(visitorid) {
        window.location = "/admin/child/returnedit?cusromer_record_id="+ visitorid;
    }
    function edit(id) {
        window.location = "/admin/child/returnedit?id=" + id;
    }
    function del(id,visitorid) {
        if (window.confirm('确定要删除吗?')) {
            window.location = "/admin/child/delreturn?id=" + id +"&visitorid="+visitorid;
            return true;
        } else {
            return false;
        }
    }
    function backList (){
        // window.location = "/admin/child/list";
        window.history.back();
    }
</script>
<body>
<div style="margin-left: 1%;margin-top: 1%">
    <!--<div class="row">

        <div class="col-lg-7">
            <form class="form-inline" action="visitorlist" method="post" role="form" id="searchvisitor">
                <input type="hidden" name="_csrf" value="<? /*= Yii::$app->request->csrfToken */ ?>">

                <div class="form-group">
                    <select name="shop_id" class="form-control" onchange="searchYuyue()">
                        <option value="">全部店铺</option>
                        <?php /*foreach ($shops as $shop) { */ ?>
                            <option value="<? /*= $shop["id"] */ ?>"
                                    <?php /*if ($shop["id"] == $shopId) { */ ?>selected="selected" <?php /*} */ ?> ><? /*= $shop["name"] */ ?></option>
                        <?php /*} */ ?>
                    </select>
                    <input type="text" class="form-control" name="name" placeholder="学员姓名">
                    <input type="button" class="btn btn-primary" onclick="searchYuyue()" value="查询">
                    <input type="button" class="btn btn-primary" style="margin-right: 10px" onclick="add()" value="新增">
                </div>
            </form>
        </div>
    </div>-->
    <input type="button" class="btn btn-primary" style="margin-right: 10px" onclick="add(<?= $visitorid ?>)" value="新增">
    <input type="button" class="btn btn-primary" onclick="backList()" value="返回">
    <br>
    <br>
    <table class="table table-bordered table-hover">
        <tr class="info">
            <td align="center">访客姓名</td>
            <td align="center">回访内容</td>
            <td align="center">回访时间</td>
            <td align="center">记录人</td>
            <td align="center">备注</td>
            <td align="center">操作</td>
        </tr>
        <tbody>
        <?php foreach ($returnlist as $entity) { ?>
            <tr class="active" align="center">
                <td><?= $entity["visitor"]["name"] ?></td>
                <td><?= $entity["content"] ?></td>
                <td align="center"><?= $entity["add_time"] ?></td>
                <td align="center"><?= $entity["user"] ?></td>
                <td align="center"><?= $entity["notes"] ?></td>
                <td align="center">
                    <button class="btn btn-primary" onclick="edit(<?= $entity["id"] ?>)">编辑</button>
                    <button class="btn btn-danger" onclick="del(<?= $entity["id"] ?>,<?= $visitorid ?>)">删除</button>
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