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
    function add() {
        window.location = "/admin/othermoney/add";
    }
    function del(id) {
        if (window.confirm('确定要删除吗?')) {
            window.location = "/admin/othermoney/del?id=" + id;
        }
    }
    function searchYuyue() {
        document.getElementById("searchform").submit();
    }
</script>
<body>
<div style="margin-left: 1%;margin-top: 1%">
    <div class="row">

        <div class="col-lg-10">
            <form class="form-inline" action="list" method="post" role="form" id="searchform">
                <input type="hidden" name="_csrf" value="<?= Yii::$app->request->csrfToken ?>">
                <div class="form-group">
                    <input type="text" class="form-control"  name="name" placeholder="收入名称" style="width: 140px" >
                    <div class="input-group date form_datetime col-md-3" id="form_datetime"
                         data-date-format="yyyy-mm-dd" >
                        <input class="form-control" size="20" type="text" name="startTime" id="start_time"
                               value="<?= $startTime ?>" placeholder="开始时间" readonly>
                        <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                        <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                    </div>
                    <div class="input-group date form_datetime col-md-3" id="form_datetime"
                         data-date-format="yyyy-mm-dd">
                        <input class="form-control" size="20" type="text" name="endTime" id="end_time"
                               value="<?=$endTime ?>" placeholder="截止时间" readonly>
                        <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                        <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                    </div>
                    <input type="button" class="btn btn-primary" onclick="searchYuyue()" value="查询">
                    <input type="button" class="btn btn-primary" onclick="add()" value="新增">
                </div>
            </form>
        </div>
    </div>
    <br>
    <table class="table table-bordered table-hover">
        <tr class="info">
            <td align="center">所属店铺</td>
            <td width="20%" align="center">交款单位</td>
            <td align="center">收入时间</td>
            <td align="center">支付方式</td>
            <td align="center">支付金额</td>
            <td align="center">收据编号</td>
            <td align="center">收款事由</td>
            <td align="center">申报状态</td>
            <td align="center">录入员</td>
            <td align="center">操作</td>
        </tr>
        <tbody>
        <?php foreach ($list as $entity) { ?>
            <tr class="active" align="center">
                <td align="center"><?=$entity["shop"]["name"]?></td>
                <td align="center"><?=$entity["pay_name"]?></td>
                <td align="center"><?=$entity["add_date"]?></td>
                <td align="center"><?php
                    if($entity["pay_type"] == 2){
                        echo '银联';
                    }else if($entity["pay_type"] == 3){
                        echo '信用卡';
                    }else if($entity["pay_type"] == 4){
                        echo '微信支付';
                    }else if($entity["pay_type"] == 5){
                        echo '支付宝';
                    }else {
                        echo '现金';
                    }
                    ?></td>
                <td align="center"><b><font color="#FF6600">¥ <?=$entity["pay_money"]?></font></b></td>
                <td align="center">
                    <?=$entity['receipt_id']?>
                </td>
                <td align="center">
                    <?=$entity['notes']?>
                </td>
                <td align="center"><?php
                    if($entity["check_status"] == 0){
                        echo '<font color="#008B00"><b>正常</b></font>';
                    }else if($entity["check_status"] == 1){
                        echo '退回';
                    }
                    ?></td>
                <td align="center"><?=$entity["user"]["nickname"] ?> </td>
                <td align="center">
                    <?php  if($entity["check_status"] == 1) {?>
                        <button class="btn btn-danger" onclick="del(<?=$entity["id"] ?>)">删除</button>
                    <?php } else {?>
                        <?php if(date('Y-m-d') == substr($entity["add_date"],0,10) ){ ?>
                            <button class="btn btn-danger" onclick="del(<?=$entity["id"] ?>)">删除</button>
                        <?php } else{?>
                        <p style="height:25px"><font color="green"  >正常</font></p>
                        <?php }?>
                    <?php } ?>
                </td>
            </tr>
        <?php } ?>
        <tr>
            <td align="center">合计</td>
            <td align="center"></td>
            <td align="center"></td>
            <td align="center"></td>
            <td align="center"><?=$total[0]['total']?></td>
            <td align="center"></td>
            <td align="center"></td>
            <td align="center"></td>
            <td align="center"></td>
            <td align="center"></td>
        </tr>
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