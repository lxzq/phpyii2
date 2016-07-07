<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

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
    function find(id) {
        window.location = "/admin/activity/activity-user-friend?uId=" + id;
    }
    function searchYuyue() {
        document.getElementById("searchform").submit();
    }
</script>
<body>
<div class="col-lg-12">
     <!-- <div class="row">
        <div class="col-lg-7">
            <form class="form-inline" action="activitylist" method="post" role="form" id="searchform">
                <input type="hidden" name="_csrf" value="<?/*= Yii::$app->request->csrfToken */?>">
                <div class="form-group">
                    <input type="text" class="form-control" name="name" placeholder="活动标题">
                    <input type="button" class="btn btn-primary" onclick="searchYuyue()" value="查询">
                </div>
            </form>
        </div>
    </div>-->
    <br>
    <table class="table table-bordered table-hover">
        <tr class="info">
            <td align="center">头像</td>
            <td align="center">姓名</td>
            <td align="center">性别</td>
            <td align="center">手机号</td>
            <td align="center">报名时间</td>
            <td align="center">操作</td>
       </tr>
        <tbody>
        <?php foreach ($activityInfo as $activity) { ?>
            <?php foreach($activity['child'] as $vo):?>
            <tr class="active" align="center">
                <td align="center"><img src="<?php if(!empty($vo['face'])):echo 'http://image.happycity777.com/' . $vo['face'];else:echo '/Images/ic_facex174.jpg';endif;?>" width="50px" height="50px"></td>
                <td align="center"><?php echo $vo["nick_name"] ?></td>
                <td align="center"><?php if(!empty($vo['sex']) && $vo['sex']==1): echo '男';else:echo '女';endif; ?></td>
                <td align="center"><?php echo $vo["phone"] ?></td>
                <td align="center"><?= Yii::$app->formatter->asDatetime($activity["add_time"])  ?></td>
                <td align="center">
                    <button class="btn btn-primary" onclick="find(<?= $activity["id"] ?>);">查看邀请人</button>
                </td>
            </tr>
            <?php endforeach;?>
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