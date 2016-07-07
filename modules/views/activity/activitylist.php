<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Alert;
use yii\widgets\LinkPager;

?>
<!doctype html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title>活动管理</title>
    <?= Html::cssFile('@web/css/bootstrap.min.css') ?>
    <?= Html::jsFile('@web/Js/jquery.js') ?>
    <?= Html::jsFile('@web/Js/bootstrap.js') ?>
</head>
<script>
    function add() {
        window.location = "/admin/activity/activityedit";
    }
    function edit(id) {
        window.location = "/admin/activity/activityedit?activity_id=" + id;
    }
    function del(id) {
        if (window.confirm('确定要删除吗?')) {
            window.location = "/admin/activity/del?activity_id=" + id;
            return true;
        } else {
            return false;
        }
    }
    function sort(id, num) {
        window.location = "/admin/activity/sort?activity_id=" + id + "&sort=" + num;
    }
    function setlunbo(id, num) {
        window.location = "/admin/activity/setlunbo?activity_id=" + id + "&lunbo=" + num;
    }
    function searchYuyue() {
        document.getElementById("searchform").submit();
    }
</script>
<body>
<div style="margin-left: 1%;margin-top: 1%">
      <div class="row">
        <div class="col-lg-7">
            <form class="form-inline" action="activitylist" method="post" role="form" id="searchform">
                <input type="hidden" name="_csrf" value="<?= Yii::$app->request->csrfToken ?>">
                <div class="form-group">
                    <input type="text" class="form-control" name="name" placeholder="活动标题">
                    <input type="button" class="btn btn-primary" onclick="searchYuyue()" value="查询">
                    <input type="button" class="btn btn-primary" style="margin-right: 10px" onclick="add()" value="新增">
                </div>
            </form>
        </div>
    </div>
    <br>
    <table class="table table-bordered table-hover">
        <tr class="info">
            <td align="center">活动图片</td>
            <td width="20%" align="center">活动标题</td>
           <!-- <td align="center">活动主题</td>-->
            <td align="center">活动人数</td>
            <td align="center">活动时间</td>
            <td width="20%" align="center">活动地址</td>
            <td width="8%" align="center">报名人数</td>
            <td width="8%" align="center">评论数</td>
            <td align="center">活动热线</td>
            <td width="10%" align="center">操作</td>
        </tr>
        <tbody>
        <?php foreach ($activityInfo as $activity) { ?>
            <tr class="active" align="center">
                <td align="center"><img src="<?= $activity["activity_image"] ?>" width="100px" height="50px"></td>
                <td align="center"><?php echo $activity["activity_name"] ?></td>
                <td align="center"><?php echo $activity["activity_number"] ?></td>
                <td align="center"><?= Yii::$app->formatter->asDatetime($activity["activity_starttime"])  ?>
                <br>
                    至
                 <br>
                    <?= Yii::$app->formatter->asDatetime($activity["activity_endtime"]) ?>
                </td>
                <td align="center"><?php echo $activity["activity_address"] ?></td>
                <td align="center"><a href="/admin/activity/activityuser?activityId=<?=$activity["activity_id"]?>"><?= $activity['activity_user']  ?>人</a></td>
                <td align="center"><a href="/admin/activity/activitycomment?activityId=<?=$activity["activity_id"]?>"><?= $activity['activity_comment']  ?></a></td>
                <td align="center"><?php echo $activity["activity_tel"] ?></td>
                <td  align="center">
                  <!--  <?php /*if ($activity["lunbo"] == 1) { */?>
                        <button class="btn btn-primary" onclick="setlunbo(<?/*= $activity["activity_id"] */?>,0);">取消轮播
                        </button>
                    <?php /*} else { */?>
                        <button class="btn btn-primary" onclick="setlunbo(<?/*= $activity["activity_id"] */?>,1);">设为轮播
                        </button>
                    --><?php /*} */?>
                    <button class="btn btn-primary" onclick="edit(<?= $activity["activity_id"] ?>);">编辑</button>
                    <button class="btn btn-danger" onclick="del(<?= $activity["activity_id"] ?>)">删除</button>
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