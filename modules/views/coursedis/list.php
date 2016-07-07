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
        window.location = "/admin/coursedis/add";
    }
   function del(id) {
        if (window.confirm('确定要删除吗?')) {
            window.location = "/admin/coursedis/del?id=" + id;
        }
    }
    function searchYuyue() {
        document.getElementById("searchform").submit();
    }
    function course(id){
        window.location = "/admin/coursedis/course?check=0&id="+id;
    }
</script>
<body>
<div style="margin-left: 1%;margin-top: 1%">
    <div class="row">

        <div class="col-lg-10">
            <form class="form-inline" action="list" method="post" role="form" id="searchform">
                <input type="hidden" name="_csrf" value="<?= Yii::$app->request->csrfToken ?>">
                <div class="form-group">

                    <div class="input-group date form_datetime col-md-8" id="form_datetime"
                         data-date-format="yyyy-mm-dd" >
                        <input class="form-control" size="20" type="text" name="startDate" id="startDate"
                               value="<?= $startDate ?>" placeholder="开始日期" readonly>
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
            <td align="center">优惠标题</td>
            <td align="center">优惠开始日期</td>
            <td align="center">优惠截止日期</td>
            <td align="center">优惠状态</td>
            <td align="center">课程数量</td>
            <td align="center">审核状态</td>
            <td align="center">录入员</td>
            <td align="center">操作</td>
        </tr>
        <tbody>
        <?php foreach ($data as $entity) { ?>
            <tr class="active" align="center">
                <td align="center"><?=$entity["shop"]["name"]?></td>
                <td align="center"><?=$entity["title"]?></td>
                <td align="center"><?=$entity['start_date']?></td>
                <td align="center"><?=$entity['end_date']?></td>
                <td align="center"><?php
                    $currDate = strtotime(date('Y-m-d', time()));
                    $startDate = strtotime($entity['start_date']);
                    $endDate = strtotime($entity['end_date']);
                    if($startDate <= $currDate &&  $currDate <= $endDate){
                        echo '<font color="green">进行中</font>';
                    }else if($currDate < $startDate){
                        echo '<font color="#a9a9a9">未开始</font>';
                    }else {
                        echo '<font color="#B7B7B7">已结束</font>';
                    }
                    ?> </td>
                <td align="center"><?=count($entity["course"])?></td>
                <td align="center"><?php
                    if($entity['status'] == 0){
                        echo '<font color="#a9a9a9">待审核</font>';
                    }else if($entity['status'] == 1){
                        echo '<font color="green">通过</font>';
                    }else {
                        echo '<font color="black">否定</font>';
                    }
                    ?></td>
                <td align="center"><?=$entity["user"]["nickname"]?></td>
               <td align="center">
                   <button class="btn btn-info" onclick="course(<?=$entity["id"] ?>)">查看</button>
                   <?php if($entity['status'] == 0) {?>
                   <button class="btn btn-danger" onclick="del(<?=$entity["id"] ?>)">删除</button>
                   <?php }?>
               </td>
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