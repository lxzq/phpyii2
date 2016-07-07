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
        window.location = "/admin/child/add";
    }
    function edit(id) {
        window.location = "/admin/child-class-record/edit?record=" + id;
    }
    function del(id) {
        if (window.confirm('确定要删除吗?')) {
            window.location = "/admin/child/delclass?id=" + id;
        }
    }
    function searchYuyue() {
        document.getElementById("searchform").submit();
    }

    function quit(id){
        window.location = "/admin/quit/quit?recordId=" + id;
    }
    function check(id){
        if (window.confirm('确定提交分成吗?')) {
            window.location = "/admin/child/check-money-type?recordId=" + id;
        }
    }
    function addMoney(id){
        window.location = "/admin/child-class-record/record?record=" + id;
    }

    function sendQuit(id){
        if (window.confirm('确定申请退课吗?')) {
            window.location = "/admin/quit/send-quit?recordId=" + id;
        }
    }

</script>
<body>
<div style="margin-left: 1%;margin-top: 1%">
    <div class="row">

        <div class="col-lg-10">
            <form class="form-inline" action="record" method="post" role="form" id="searchform">
                <input type="hidden" name="_csrf" value="<?= Yii::$app->request->csrfToken ?>">
                <div class="form-group">
                    <select name="shop" class="form-control">
                        <option value="" >全部店铺</option>
                        <?php  foreach($shops as $shop) {?>
                            <option value="<?=$shop["id"]?>"  <?php if($shop["id"]==$key) { ?>selected="selected"  <?php } ?>  ><?=$shop["name"]?></option>
                        <?php } ?>
                    </select>
                    <select name="courseId" class="form-control" style="width: 140px">
                        <option value="" >全部课程</option>
                        <?php  foreach($courseList as $shop) {?>
                            <option value="<?=$shop["id"]?>"  <?php if($shop["id"]==$courseId) { ?>selected="selected"  <?php } ?>  ><?=$shop["name"]?></option>
                        <?php } ?>
                    </select>
                    <input type="text" class="form-control"  name="name" placeholder="会员姓名" style="width: 100px" >
                    <div class="input-group date form_datetime col-md-3" id="form_datetime"
                         data-date-format="yyyy-mm-dd" >
                        <input class="form-control" size="20" type="text" name="start_time" id="start_time"
                               value="<?= $startTime ?>" placeholder="开始日期" readonly>
                        <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                        <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                    </div>
                    <div class="input-group date form_datetime col-md-3" id="form_datetime"
                         data-date-format="yyyy-mm-dd">
                        <input class="form-control" size="20" type="text" name="end_time" id="end_time"
                               value="<?=$endTime ?>" placeholder="截止日期" readonly>
                        <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                        <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                    </div>
                     <input type="button" class="btn btn-primary" onclick="searchYuyue()" value="查询">
                </div>
            </form>
        </div>
    </div>
    <br>
    <table class="table table-bordered table-hover">
        <tr class="info">
            <td align="center">所属店铺</td>
            <td align="center">申报名称</td>
            <td align="center">申报课程</td>
            <td align="center">申报时间</td>
            <td align="center">支付方式</td>
            <td align="center">支付金额</td>
            <td align="center">结算方式</td>
            <td align="center">收据编号</td>
            <td align="center">分成状态</td>
            <td align="center">申报录入员</td>
            <td align="center">操作</td>
        </tr>
        <tbody>
        <?php foreach ($list as $entity) { ?>
            <tr class="active" align="center">
                <td align="center"><?=$entity["shop"]["name"]?></td>
                <td align="center">
                    <?php  if($entity["is_delete"] == 1) {?>
                    <?=$entity["pay_name"]?>
                    <?php } else {?>
                        <font color="#a9a9a9"><?=$entity["pay_name"]?></font>
                    <?php } ?>
                </td>

                <td align="center"><?php
                   if($entity["is_delete"] == 1){
                    if(empty($entity["course"])){
                          echo $entity['notes'];
                     }
                     foreach($entity["course"] as $course) {
                         echo '《' . $course["name"] . '》<br>';
                     }
                     }else {
                         echo '<font color="#a9a9a9">退课</font>';
                     }
                   ?> </td>
                <td align="center"><?=substr($entity["add_time"],0,10)?></td>
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

                <td align="center">
                    <?php if($entity["is_delete"] == 1) {?>
                    <b><font color="#FF6600">¥ <?=$entity["total_money"]?></font></b>
                    <?php } else {?>
                     <font color="#a9a9a9">¥ <?=$entity["total_money"]?></font>
                    <?php } ?>
                </td>
                <td align="center">
                    <?php if($entity["money_type"] == 1) {?>
                        <font color="#008B00"><b>全款</b></font>
                    <?php } else if($entity["money_type"] == 2) {?>
                        <font color="#00BFFF"><b>定金</b></font>
                    <?php } else if($entity["money_type"] == 3) {?>
                     余款
                    <?php } ?>
                </td>
                <td align="center">
                   <?=$entity['receipt_id']?>
                </td>
                <td align="center"><?php
                    if($entity["check_status"] == 0){
                        echo '<p style="height:22px;"><font color="#a9a9a9">未分成</font></p>';
                    }else if($entity["check_status"] == 1){
                        echo '<p style="height:22px;">待分成</p>';
                    }else if($entity["check_status"] == 2 || $entity["check_status"] == 3 ){
                        echo '<p style="height:22px;">已分成</p>';
                    }
                    ?></td>
                <td align="center"><?=$entity["yiiuser"]["nickname"] ?> </td>
                <td align="center">
                     <?php if($entity["check_status"] == 0) { ?>
                         <?php if(date('Y-m-d', time()) == substr($entity["add_time"],0,10) &&  $entity['is_delete'] == 1){ ?>
                         <button class="btn btn-danger" onclick="del(<?=$entity["id"] ?>)">删除</button>
                         <?php }?>
                         <?php if($entity["money_type"] == 2 && count($entity['record']) < 2 && $entity["is_quit"] != 3) {?>
                             <button class="btn btn-info" onclick="addMoney(<?=$entity["id"] ?>)">余款</button>
                         <?php }?>
                         <?php if($entity["is_delete"] == 1) {?>
                             <?php if($entity["is_quit"] == 0 && $entity["money_type"] != 3) {?>
                                 <button class="btn btn-info" onclick="sendQuit(<?=$entity["id"] ?>)">退课申请</button>
                                 <button class="btn btn-success" onclick="check(<?=$entity["id"] ?>)">提交分成</button>
                             <?php } else if($entity["is_quit"] == 0 && $entity["money_type"] == 3 ) {?>
                                 <button class="btn btn-success" onclick="check(<?=$entity["id"] ?>)">提交分成</button>
                             <?php } else if($entity["is_quit"] == 2) {?>
                                 <font color="#a9a9a9">退课申请审核中</font>
                             <?php }else if($entity["is_quit"] == 1){?>
                                 <button class="btn btn-info" onclick="quit(<?=$entity["id"] ?>)">退课</button>
                             <?php } else if($entity["is_quit"] == 3){?>
                                 <font color="#a9a9a9">已退课</font>
                             <?php } else{?>

                             <?php }?>
                         <?php } else {?>
                             <font color="#a9a9a9">已退课</font>
                         <?php } ?>
                  <?php } else if($entity["check_status"] == -1){?>
                         <button class="btn btn-warning" onclick="edit(<?=$entity["id"] ?>)">修改</button>
                   <?php } ?>

                </td>
            </tr>
        <?php } ?>
        <tr>
            <td align="center">合计</td>
            <td align="center"></td>
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