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
                    <input type="text" class="form-control" name="phone" value="" placeholder="手机号">
                    <input type="button" class="btn btn-primary" onclick="searchYuyue()" value="查询">
                </div>
            </form>
        </div>
    </div>
    <br>
    <table class="table table-bordered table-hover">
        <tr class="info">
            <td align="center">宝宝姓名</td>
            <td width="20%" align="center">年龄</td>
            <!-- <td align="center">活动主题</td>-->
            <td align="center">性别</td>
            <td align="center">手机号</td>
            <td align="center">所选项目</td>
            <td align="center">备注</td>
            <td align="center">个人简历</td>
            <td align="center">创建时间</td>
        </tr>
        <tbody>
        <?php foreach ($data as $activity) { ?>
            <tr class="active" align="center">
                <td align="center"><?php echo $activity["name"] ?></td>
                <td align="center"><?php echo $activity["age"] ?></td>
                <td align="center"><?php  if($activity["sex"]==1){
                        echo "男";
                    }else{
                        echo "女";
                    } ?></td>
                <td align="center"><?php echo $activity["phone"] ?></td>
                <td align="center"><?php echo $activity["projectname"] ?></td>
                <td align="center"><?php echo $activity["remarks"] ?></td>
                <td align="center"><?php echo $activity["resume"] ?></td>
                <td align="center"><?php echo $activity["createtime"] ?></td>
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