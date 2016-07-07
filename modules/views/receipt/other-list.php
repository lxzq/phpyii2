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
    <div class="row">

        <div class="col-lg-10">
            <form class="form-inline" action="other-list" method="post" role="form" id="searchform">
                <input type="hidden" name="_csrf" value="<?= Yii::$app->request->csrfToken ?>">
                <div class="form-group">
                    <select name="shop" class="form-control">
                        <option value="" >全部店铺</option>
                        <?php  foreach($shops as $k=>$shop) {?>
                            <option value="<?=$k?>"  <?php if($k==$key) { ?>selected="selected"  <?php } ?>  ><?=$shop?></option>
                        <?php } ?>
                    </select>
                    
                    <select name="pay_type" class="form-control">
                        <option value="" >支付方式</option>
                        <option value="1"  <?php if($key_pay==1) { ?>selected="selected"  <?php } ?>  >现金</option>
                        <option value="2"  <?php if($key_pay==2) { ?>selected="selected"  <?php } ?>  >银联</option>
                        <option value="3"  <?php if($key_pay==3) { ?>selected="selected"  <?php } ?>  >信用卡</option>
                        <option value="4"  <?php if($key_pay==4) { ?>selected="selected"  <?php } ?>  >微信支付</option>
                        <option value="5"  <?php if($key_pay==5) { ?>selected="selected"  <?php } ?>  >支付宝</option>
                    </select>
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
            <td align="center">序号</td>
            <td align="center">所属店铺</td>
            <td align="center">日期</td>
            <td align="center">交款单位</td>
            <td align="center">收款事由</td>
            <td align="center">收据编号</td>
            <td align="center">金额</td>
            <td align="center">支付方式</td>
            <td align="center">备注</td>
            <td align="center">状态</td>
        </tr>
        <tbody>
        <?php foreach ($list as $k=>$entity) { ?>
            <tr class="active" align="center">
                <td align="center"><?php echo ($n-1)*8+$k+1;?></td>
                <td align="center"><?=$entity["shop"]["name"]?></td>
                <td align="center">
                    <?=$entity["add_date"]?>
                </td>

                <td align="center"><?=$entity["pay_name"]?></td>
                <td align="center"><?=$entity['notes']?></td>
                <td align="center"><?=$entity['receipt_id']?></td>

                <td align="center">
                    <b><font color="#FF6600">¥ <?=$entity["pay_money"]?></font></b>
                </td>
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
                    ?>
                </td>
                <td align="center"></td>
                <td>
                    <?php if ($entity['check_status'] == 0) { ?>
                    <button class="btn btn-warning" onclick="tuihui(<?=$entity["id"] ?>,this)">退回修改</button><br>
                    <?php }else{ ?>
                        已退回
                    <?php } ?>
                </td>
            </tr>
        <?php } ?>
        <tr>
            <td colspan="2" align="center">合计：<?php echo $heji['num']."条记录";?></td>
            <td></td>
            <td colspan="3"></td>
            <td align="center"><b><font color="#FF0000">¥ <?php echo $heji['heji'];?></font></b></td>
            <td colspan="3"></td>
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
    function tuihui(id,obj){
        var cancel = confirm("确定退回修改么?");
        if (cancel) {
        $.post("/admin/receipt/tuihui-other", { "recordId": id },
            function(data){
                if (data == 1) {
                    $(obj).parent().html("已退回");
                    $(obj).parent().html("");
                }
            });
        }
    }
</script>
</html>