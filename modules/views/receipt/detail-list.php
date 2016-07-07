<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

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
    function searchYuyue() {
        document.getElementById("searchform").submit();
    }

    function duizhang(id){
        window.location = "/admin/receipt/other-duizhang?recordId=" + id;
    }

</script>
<body>
<div style="margin-left: 1%;margin-top: 1%">
    <table class="table table-bordered table-hover">
        <tr class="info">
            <td align="center">序号</td>
            <td align="center">店铺</td>
            <td align="center">收入现金(元)</td>
            <td align="center">操作时间</td>
        </tr>
        <tbody>
        <?php foreach ($list as $k=>$entity) { ?>
            <tr class="active" align="center">
                <td align="center"><?php echo $k+1;?></td>
                <td align="center"><?php echo $entity['name'];?></td>
                <td align="center"><?php echo $entity['total_money'];?></td>
                <td align="center"><?php echo $entity['add_time'];?></td>
                
            </tr>
        <?php } ?>
        </tbody>
    </table>
    
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