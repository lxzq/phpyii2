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
    function searchYuyue() {
        document.getElementById("searchform").submit();
    }
</script>
<body>
<div style="margin-left: 1%;margin-top: 1%">
    <div class="row">

        <div class="col-lg-7">
            <form class="form-inline" action="report" method="post" role="form" id="searchform">
                <input type="hidden" name="_csrf" value="<?= Yii::$app->request->csrfToken ?>">
                <div class="form-group">
                    <input type="text" class="form-control" name="year" placeholder="年度">
                    <input type="button" class="btn btn-primary" onclick="searchYuyue()" value="查询">
                </div>
            </form>
        </div>
    </div>
    <br>
    <table class="table table-bordered table-hover">

        <tr class="info">
            <td align="center" colspan="25"><b> <?=$year?>年度报表统计</b></td>

        </tr>
        <tr class="info">
            <td align="center" rowspan="2">店铺名称</td>
            <td align="center" colspan="2">1月</td>
            <td align="center" colspan="2">2月</td>
            <td align="center" colspan="2">3月</td>
            <td align="center" colspan="2">4月</td>
            <td align="center" colspan="2">5月</td>
            <td align="center" colspan="2">6月</td>
            <td align="center" colspan="2">7月</td>
            <td align="center" colspan="2">8月</td>
            <td align="center" colspan="2">9月</td>
            <td align="center" colspan="2">10月</td>
            <td align="center" colspan="2">11月</td>
            <td align="center" colspan="2">12月</td>
        </tr>
        <tr class="info">
            <td align="center">人数</td>
            <td align="center">金额(元)</td>
            <td align="center">人数</td>
            <td align="center">金额(元)</td>
            <td align="center">人数</td>
            <td align="center">金额(元)</td>
            <td align="center">人数</td>
            <td align="center">金额(元)</td>
            <td align="center">人数</td>
            <td align="center">金额(元)</td>
            <td align="center">人数</td>
            <td align="center">金额(元)</td>
            <td align="center">人数</td>
            <td align="center">金额(元)</td>
            <td align="center">人数</td>
            <td align="center">金额(元)</td>
            <td align="center">人数</td>
            <td align="center">金额(元)</td>
            <td align="center">人数</td>
            <td align="center">金额(元)</td>
            <td align="center">人数</td>
            <td align="center">金额(元)</td>
            <td align="center">人数</td>
            <td align="center">金额(元)</td>
        </tr>
        <tbody>
        <?php foreach ($data as $entity) { ?>
            <tr class="active" align="center">
                <td align="center" height="50px">
                    <?=$entity["name"]?>
                </td>
                <?php for($i = 1 ; $i<= 12 ; $i++) {?>
                <td align="center">
                    <?=$entity["num_".$i]?>
                </td>
                <td align="center">
                    <?=$entity["money_".$i]?>
                </td>
                <?php }?>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>
</body>
</html>