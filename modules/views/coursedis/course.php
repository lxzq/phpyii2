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
    <?= Html::cssFile('@web/css/bootstrap-datetimepicker.min.css') ?>
    <?= Html::jsFile('@web/Js/jquery.js') ?>
    <?= Html::jsFile('@web/Js/bootstrap.js') ?>
    <?= Html::jsFile('@web/Js/bootstrap-datetimepicker.js') ?>
    <?= Html::jsFile('@web/Js/locales/bootstrap-datetimepicker.zh-CN.js') ?>

</head>
<script>
    var check =<?=$check?>;
    function back() {
        if(check == 0){
            window.location = "/admin/coursedis/list";
        }else{
            window.location = "/admin/coursedis/check-list";
        }
    }
  </script>
<body>
<div style="margin-left: 1%;margin-top: 1%">
    <div class="row">

        <div class="col-lg-10">
            <form class="form-inline" action="record" method="post" role="form" id="searchform">
                <div class="form-group">
                    <input type="button" class="btn btn-primary" onclick="back()" value="返回">
                </div>
            </form>
        </div>
    </div>
    <br>
    <table class="table table-bordered table-hover">
        <tr class="info">
             <td align="center">优惠课程名称</td>
            <td align="center">课时</td>
            <td align="center">优惠原价格</td>
            <td align="center">优惠折扣价</td>
        </tr>
        <tbody>
        <?php foreach ($data as $entity) { ?>
            <tr class="active" align="center">
                <td align="center"><?=$entity["course"]["name"]?></td>
                <td align="center"><?=$entity["course_num"]?></td>
                <td align="center"><?=$entity['price_one']?></td>
                <td align="center"><?=$entity['price_two']?></td>
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
<script type="text/javascript">
    $('.form_datetime').datetimepicker({
        language: 'zh-CN',
        weekStart: 0,
        todayBtn: 1,
        autoclose: true,
        todayHighlight: 1,
        viewSelect : 'month',
        startView: 2,
        forceParse: 0,
        showMeridian: 1,
        format : 'yyyy-mm-dd'
    });

</script>
</html>