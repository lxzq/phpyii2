<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\VideoForm */

use yii\helpers\Html;
use yii\widgets\LinkPager;

$this->title = '试听课报名名单';
$this->params['breadcrumbs'][] = $this->title;
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <?= Html::cssFile('@web/css/bootstrap.min.css') ?>
    <?= Html::jsFile('@web/Js/bootstrap.js') ?>
</head>
<script>
    function del(id) {
        if (window.confirm('确定要删除吗?')) {
            window.location = "/admin/yuyue/del?id=" + id;
            return true;
        } else {
            return false;
        }
    }
    function searchYuyue() {
        document.getElementById("searchform").submit();
    }
</script>
<body>
<div style="margin-left: 1%;margin-top: 1%">
    <div class="row">
        <!--  <div class="col-lg-2">
            <b style="font-size: 24px"><? /*= Html::encode($this->title) */ ?></b>
        </div>-->

        <div class="col-lg-10">
            <form class="form-inline" action="list" method="post" role="form" id="searchform">
                <input type="hidden" name="_csrf" value="<?= Yii::$app->request->csrfToken ?>">

                <div class="form-group">
                    <input type="text" class="form-control" name="userName" placeholder="学生姓名">
                    <input type="text" class="form-control" name="phone" placeholder="家长手机">
                    <button class="btn btn-primary" style="margin-right: 10px" onclick="searchYuyue()">查询</button>
                </div>
            </form>
        </div>
    </div>
    <br>

    <table class="table table-bordered table-hover">
        <tr class="info">
            <td align="center">学生姓名</td>
            <td align="center">家长手机</td>
            <td align="center">课程名</td>
            <td align="center">所属机构</td>
            <td align="center">所在店铺</td>
        </tr>
        <tbody>
        <?php foreach ($list as $entity) { ?>
            <tr class="active">
                <td align="center"><?= $entity["user"]["bbname"] ?></td>
                <td align="center"><?= $entity["user"]["phone"] ?></td>
                <td align="center"><?= $entity["class"]["title"] ?></td>
                <td align="center"><?php $orgid = $entity["course"]["org_id"];
                    foreach ($orgs as $org) {
                        if ($orgid == $org["id"]) {
                            echo $org["name"];
                        }
                    }
                    ?></td>
                <td align="center"><?php $shopid = $entity["class"]["shop_id"];
                    foreach ($shops as $shop) {
                        if ($shopid == $shop["id"]) {
                            echo $shop["name"];
                        }
                    }
                    ?></td>

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