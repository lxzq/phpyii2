<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\VideoForm */

use yii\helpers\Html;
use yii\widgets\LinkPager;

$this->title = '已报名单';
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
    function back() {
//        window.location = "/admin/organization/classlist";
        window.history.back();
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
                <input type="button" class="btn btn-primary" onclick="back()" value="返回">
            </form>
        </div>
    </div>
    <br>

    <table class="table table-bordered table-hover">
        <tr class="info">
            <td align="center">学生姓名</td>
            <td align="center">联系方式</td>
            <td align="center">性别</td>
            <td align="center">学校</td>
            <td align="center">年级</td>
            <td align="center">地址</td>
            <td align="center">卡号</td>
            <td align="center">报名时间</td>
        </tr>
        <tbody>
        <?php foreach ($list as $entity) { ?>
            <tr class="active">
                <td align="center"><?= $entity["child"]["nick_name"] ?></td>
                <td align="center"><?= $entity["child"]["phone"] ?></td>
                <td align="center"><?php $sex = $entity["child"]["sex"];
                    if ($sex == 0) {
                        echo "女";
                    } elseif ($sex == 1) {
                        echo "男";
                    } else {

                    }
                    ?></td>
                <td align="center"><?= $entity["child"]["school"] ?></td>
                <td align="center"><?= $entity["child"]["class_cc"] ?></td>
                <td align="center"><?= $entity["child"]["address"] ?></td>
                <td align="center"><?= $entity["child"]["card_code"] ?></td>
                <td align="center"><?= $entity["add_date"] ?></td>
                <!--<td align="center"><?php /*$shopid = $entity["class"]["shop_id"];
                    foreach ($shops as $shop) {
                        if ($shopid == $shop["id"]) {
                            echo $shop["name"];
                        }
                    }
                    */ ?></td>-->
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