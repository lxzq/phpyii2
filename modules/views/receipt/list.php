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
    function search() {
        document.getElementById("searchform").submit();
    }

    function tuihui(id,obj){
        var cancel = confirm("确定退回修改么?");
        if (cancel) {
        $.post("/admin/receipt/tuihui", { "recordId": id },
            function(data){
                if (data == 1) {
                    $(obj).parent().prev("td").html("已退回");
                    $(obj).parent().html("");
                }
            });
        }
    }

    function cancel_fencheng(id,obj){
        var cancel = confirm("确定取消分成么?");
        if (cancel) {
            $.post("/admin/receipt/cancel-fencheng", { "recordId": id },
            function(data){
                if (data.code == 1) {
                    $(obj).parent().prev("td").html("未提交分成");
                    $(obj).parent().html("<button class='btn btn-warning' onclick=tuihui("+data.desc+",this)>退回修改</button>");
                }else if( data.code == -1){
                    alert(data.desc);
                }
            },"json")
        }
    }


    function tuike(od,obj,type) {
        var cancel = confirm("确定允许退课么?");
        if (cancel) {
            $.post("/admin/receipt/cancel-fencheng", { "recordId": id,"type":type },
            function(data){
                if (data.code == 1) {
                    var html = '';
                    if (type == 1) {
                        html = "允许退课";
                    }else if(type == 0){
                        html = "拒绝退课"
                    }
                    $(obj).parent().prev("td").html(html);
                }else if( data.code == -1){
                    alert(data.desc);
                }
            },"json")
        }
    }

</script>
<body>
<div style="margin-left: 1%;margin-top: 1%">
    <div class="row">

        <div class="col-lg-12">
            <form class="form-inline" action="check-list" method="post" role="form" id="searchform">
                <input type="hidden" name="_csrf" value="<?= Yii::$app->request->csrfToken ?>">
                <div class="form-group col-lg-12">
                    <select name="shop" class="form-control">
                        <option value="" >全部店铺</option>
                        <?php  foreach($shops as $shop) {?>
                            <option value="<?=$shop["id"]?>"  <?php if($shop["id"]==$key) { ?>selected="selected"  <?php } ?>  ><?=$shop["name"]?></option>
                        <?php } ?>
                    </select>
                    
                    <select name="pay_type" class="form-control">
                        <option value="" >支付方式</option>
                        <option value="1"  <?php if($key_pay==1) { ?>selected="selected"  <?php } ?>  >现金</option>
                        <option value="2"  <?php if($key_pay==2 || $key_pay==3) { ?>selected="selected"  <?php } ?>  >刷卡</option>
                        <option value="4"  <?php if($key_pay==4) { ?>selected="selected"  <?php } ?>  >微信支付</option>
                        <option value="5"  <?php if($key_pay==5) { ?>selected="selected"  <?php } ?>  >支付宝</option>
                    </select>
                    <select name="money_type" class="form-control">
                        <option value="" >收款类型</option>
                        <option value="1"  <?php if($key_money==1) { ?>selected="selected"  <?php } ?>  >全款</option>
                        <option value="2"  <?php if($key_money==2) { ?>selected="selected"  <?php } ?>  >定金</option>
                        <option value="3"  <?php if($key_money==3) { ?>selected="selected"  <?php } ?>  >余款</option>
                    </select>
                    <select name="org_select" class="form-control" onchange="getCourse($(this).val())">
                        <option value="" >全部机构</option>
                        <?php  foreach($orgs as $v) {?>
                            <option value="<?=$v["id"]?>"  <?php if($v["id"]==$key_org) { ?>selected="selected"  <?php } ?>  ><?=$v["name"]?></option>
                        <?php } ?>
                    </select>
                    <select name="course_id" class="form-control" id="select_course">
                        <option value="" >选择课程</option>
                        <?php 
                            if(!empty($course_arr)){
                                foreach ($course_arr as $k => $v) { ?>
                                    <option value="<?=$v["id"]?>"  <?php if($v["id"]==$key_course) { ?>selected="selected"  <?php } ?>  ><?=$v["name"]?></option>
                                    <?php 
                                }
                            }
                        ?>
                    </select>
                    <select name="check_status" class="form-control">
                        <option value="1"  <?php if($key_check==1) { ?>selected="selected"  <?php } ?>>分成状态</option>
                        <option value="2"  <?php if($key_check==2) { ?>selected="selected"  <?php } ?>  >未分成</option>
                        <option value="3"  <?php if($key_check==3) { ?>selected="selected"  <?php } ?>  >待分成</option>
                        <option value="4"  <?php if($key_check==4) { ?>selected="selected"  <?php } ?>  >已分成</option>
                        <option value="6"  <?php if($key_check==6) { ?>selected="selected"  <?php } ?>  >已结算保证金</option>
                        <option value="5"  <?php if($key_check==5) { ?>selected="selected"  <?php } ?>  >已退回</option>
                    </select>
                    <div class="input-group date form_datetime col-md-2" id="form_datetime"
                         data-date-format="yyyy-mm-dd" >
                        <input class="form-control" type="text" name="start_time" id="start_time"
                               value="<?= $startTime ?>" placeholder="开始日期" readonly>
                        <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                        <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                    </div>
                    <div class="input-group date form_datetime col-md-2" id="form_datetime"
                         data-date-format="yyyy-mm-dd">
                        <input class="form-control" type="text" name="end_time" id="end_time"
                               value="<?=$endTime ?>" placeholder="截止日期" readonly>
                        <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                        <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                    </div>
                     <input type="button" class="btn btn-primary" onclick="search()" value="查询">
                </div>
            </form>
        </div>
    </div>
    <br>
    <table class="table table-bordered table-hover">
        <tr class="info">
            <td align="center">序号</td>
            <td align="center">所属店铺</td>
            <td align="center" width="7%">日期</td>
            <td align="center" width="11%">收入名称</td>
            <td align="center">收款明细</td>
            <td align="center">收据编号</td>
            <td align="center" width="8%">金额</td>
            <td align="center">支付方式</td>
            <td align="center">收款类型</td>
            <td align="center">机构</td>
            <td align="center">备注</td>
            <td align="center" width="5%">状态</td>
            <td align="center" width="7%">操作</td>
        </tr>
        <tbody>
        <?php foreach ($list as $k=>$entity) { ?>
            <tr class="active" align="center">
                <td align="center"><?php echo ($n-1)*8+$k+1;?></td>
                <td align="center"><?=$entity["shopName"]?></td>
                <td align="center">
                    <?=$entity["add_time"]?>
                </td>

                <td align="center"><?php  if($entity["recordDelete"] == 1) {?>
                    <?=$entity["pay_name"]?>
                    <?php } else {?>
                        <font color="#a9a9a9"><?=$entity["pay_name"]?></font>
                    <?php } ?></td>
                <td align="center"><?php if($entity['courseName']){echo "&nbsp;《".$entity['courseName']."》";}else {echo $entity['recordNotes'];}?></td>
                <td align="center"><?=$entity['receipt_id']?></td>

                <td align="center">
                    <?php if($entity["recordDelete"] == 1) {?>
                    <b><font color="#FF6600">¥ <?=$entity["total_money"]?></font></b>
                    <?php } else {?>
                     <font color="#a9a9a9">¥ <?=$entity["total_money"]?></font>
                    <?php } ?>
                </td>
                <td align="center"><?php
                    if($entity["payType"] == 2){
                        echo '刷卡';
                    }else if($entity["payType"] == 3){
                        echo '刷卡';
                    }else if($entity["payType"] == 4){
                        echo '微信支付';
                    }else if($entity["payType"] == 5){
                        echo '支付宝';
                    }elseif($entity['payType'] == 1) {
                        echo '现金';
                    }
                    ?></td>
                <td align="center">
                    <?php
                    if($entity["money_type"] == 1){
                        echo '<font color="#008B00"><b>全款</b></font>';
                    }else if($entity["money_type"] == 2){
                        echo '<font color="#00BFFF"><b>定金</b></font>';
                    }else if($entity["money_type"] == 3){
                        echo '余款';
                    }
                    ?>
                </td>
                <td align="center"><?php echo $entity['orgName']?> </td>
                <td align="center">
                        <?php echo $entity['recordNotes'] ?>
                </td>
                <td align="center">
                    <?php if ($entity['check_status'] == -1) { ?>
                    已退回
                    <?php }elseif ($entity['check_status'] == 3) { ?>
                        <?php echo date("Y-m",strtotime($entity['update_time']));?> 已结算保证金
                    <?php }elseif ($entity['check_status'] == 2) { ?>
                        <?php echo date("Y-m",strtotime($entity['update_time']));?> 已分成
                    <?php }elseif ($entity['check_status'] == 1) { ?>
                    待分成
                    <?php }elseif ($entity['check_status'] == 0) { ?>
                    未分成
                    <?php } ?>
                </td>
                <td>
                    <?php if ($entity['is_quit'] == 3) { ?>
                        已退课
                    <?php }else{ ?>
                        <?php if ($entity['check_status'] == 0) { ?>
                        <button class="btn btn-warning" onclick="tuihui(<?=$entity["recordId"] ?>,this)">退回修改</button><br>
                        <?php }?>
                        <?php if ($entity['is_quit'] == 2) { ?>
                        <button class="btn btn-success" onclick="tuike(<?=$entity["recordId"] ?>,this,1)">允许退课</button>
                        <button class="btn btn-danger" onclick="tuike(<?=$entity["recordId"] ?>,this,2)">拒绝退课</button>
                        <?php } ?>
                    <?php } ?>
                </td>
            </tr>
        <?php } ?>
            <tr class="active" align="center">
                <td colspan=3 align="center" style="vertical-align:middle;">人次合计（人）：</td>
                <td align="center" style="vertical-align:middle;"><?php echo $heji['num'];?></td>
                <td colspan=2 align="center" style="vertical-align:middle;">金额合计（元）：</td>
                <td align="center" style="vertical-align:middle;"><?php echo "¥ ".$heji['heji_total'];?></td>
                <td colspan=5 align="center" style="vertical-align:middle;"></td>
                <td colspan=5 align="center" style="vertical-align:middle;"></td>
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
        language:  'zh-CN',
        format: 'yyyy-mm-dd',
        autoclose: true,
        startView: 'month',
        minView:'year',
        maxView:'decade'
    });

    function getCourse(org_id){
        var html ='';
        $.post("/admin/receipt/get-course", { "org_id": org_id },
            function(data){
                if (data.code == 1) {
                    var o = data.desc;
                    $.each(o,function(i,value){
                        html+= "<option value="+value.id+">"+value.name+"</option>";
                    })
                    $("#select_course").html("<option value=''>选择课程</option>");
                    $("#select_course").append(html);
                }
                }, "json");
    }

</script>
</html>