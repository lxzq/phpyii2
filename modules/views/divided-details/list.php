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
    $(function(){
        var selected_m = $("#month").val();
        var last_m = <?php echo "'".$last_month."'";?>;
        if (selected_m == last_m) {
            $("#fencheng").show();
        }else{
            $("#fencheng").hide();
        }
    });
    function searchYuyue() {
        $("#searchform").attr("action","list");
        document.getElementById("searchform").submit();
    }

    function submitBeforeMonth(){
        var shop = $("#shop option:selected").val();
        var yes = confirm("确定结算该门店上个月分成么？");
        if (yes) {
            $.post("/admin/divided-details/submit-last-month",{"shopId":shop},
                function(data){
                    if (data.code == 1) {
                        alert("结算完成");
                    }else{
                        alert("结算失败");
                    }
                },"json")
        }
    }
    function daochu(){
        $("#searchform").attr("action","export");
        $("#searchform").submit();
    }

</script>

<body>
<div style="margin-left: 1%;margin-top: 1%">
    <div class="row">

        <div class="col-md-12">
            <form class="form-inline" action="list" method="post" role="form" id="searchform">
                <input type="hidden" name="_csrf" value="<?= Yii::$app->request->csrfToken ?>">
                <div class="form-group col-md-12">
                    <?php if($is_shopId > 0){ ?>
                        <select id="shop" name="shop" class="form-control">
                        <?php  foreach($shops as $shop) {?>
                            <?php if($shop['id'] == $is_shopId) {?>
                            <option value="<?=$is_shopId?>"><?=$shop["name"]?></option>
                            <?php } ?>
                        <?php } ?>
                        </select>
                    <?php }else{ ?>
                    <select id="shop" name="shop" class="form-control">
                        <!-- <option value="" >全部店铺</option> -->
                        <?php  foreach($shops as $shop) {?>
                            <option value="<?=$shop["id"]?>"<?php if($shop["id"]==$key) { ?> selected="selected"<?php } ?>><?=$shop["name"]?></option>
                        <?php } ?>
                    </select>
                    <?php } ?>
                    <div class="input-group date form_datetime col-md-2" id="form_datetime"
                         data-date-format="yyyy-mm" >
                        <input class="form-control" size="20" type="text" name="month" id="month"
                               value="<?= $month ?>" placeholder="开始日期" readonly">
                        <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                        <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                    </div>
                    <input type="button" class="btn btn-primary" onclick="searchYuyue()" value="查询">
                    <?php if(empty($is_shopId)){?>
                    <input style="display:none" id="fencheng" type="button" class="btn btn-success" onclick="submitBeforeMonth()" value="结算分成">
                    <?php } ?>
                    <input id="" type="button" class="btn btn-danger" onclick="daochu()" value="导出Excel">
                </div>
            </form>
        </div>
    </div>
    <br>
    <table class="table table-bordered table-hover">
        <thead>
        <tr class="info">
            <td align="center" style="vertical-align:middle;">序号</td>
            <td align="center" style="vertical-align:middle;">合作机构</td>
            <td align="center" style="vertical-align:middle;">课程</td>
            <td align="center" style="vertical-align:middle;">数量(人次)</td>
            <td align="center" style="vertical-align:middle;">收款金额(元)</td>
            <td align="center" style="vertical-align:middle;">总收款(元)</td>
            <td align="center" style="vertical-align:middle;">税后总收款(元)</td>
            <td align="center" style="vertical-align:middle;">分成比例<br>(我方在前)</td>
            <td align="center" style="vertical-align:middle;">机构分成<br>金额(税后)</td>
            <td align="center" style="vertical-align:middle;">履约保证<br>金比例</td>
            <td align="center" style="vertical-align:middle;">扣除课程履约金后<br>实际应付机构金额(元)</td>
            <td align="center" style="vertical-align:middle;">课程履约金(元)</td>
            <td align="center" style="vertical-align:middle;">备注</td>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($list as $k=>$entity) { ?>
            <tr class="active" align="center">
                <!-- 序号 -->
                <td align="center" style="vertical-align:middle;"><?php echo $k+1;?></td>
                <!-- 合作机构 -->
                <td align="center" style="vertical-align:middle;"><?=$orgs[$entity['org_id']]?></td>
                <!-- 课程 -->
                <td align="center">
                    <table class="table table-hover" style="margin-bottom:0px;">
                        <?php foreach ($entity['course_info'] as $k2 => $v2) {?>
                            <tr class="active" align="center">
                                <td><?=$v2['name']?></td>
                            </tr>
                        <?php } ?>
                    </table>
                </td>
                <!-- 数量(人次) -->
                <td align="center">
                    <table class="table table-hover" style="margin-bottom:0px;">
                        <?php foreach ($entity['course_info'] as $k2 => $v2) {?>
                            <tr class="active" align="center">
                                <td><?php if($v2['child_num']) echo $v2['child_num']; else echo 0;?></td>
                            </tr>
                        <?php } ?>
                    </table>
                </td>
                <!-- 收款金额(元) -->
                <td align="center">
                    <table class="table table-hover" style="margin-bottom:0px;">
                        <?php foreach ($entity['course_info'] as $k2 => $v2) {?>
                            <tr class="active" align="center">
                                <td><?php if($v2['total_price']) echo $v2['total_price']; else echo 0;?></td>
                            </tr>
                        <?php } ?>
                    </table>
                </td>
                <!-- 总收款 -->
                <td align="center" style="vertical-align:middle;"><?=$entity['org_all_money'];?></td>
                <!-- 税后总收款 -->
                <td align="center" style="vertical-align:middle;">
                    <?php echo round($entity['org_all_money']*0.9429,2);?>
                </td>
                <!-- 分成比例 -->
                <td align="center" style="vertical-align:middle;">
                    <?php echo ((1-$entity['divide_proportion'])*10)."/".($entity['divide_proportion']*10);?>
                </td>
                <!-- 机构分成金额(税后) -->
                <td align="center" style="vertical-align:middle;">
                    <?php echo round($entity['org_all_money']*0.9429*$entity['divide_proportion'],2);?>
                </td>
                <!-- 履约保证金比例 -->
                <td align="center" style="vertical-align:middle;">
                    <?php echo 100*$entity['margin_proportion']."%";?>
                </td>
                <!-- 实际应付 -->
                <td align="center" style="vertical-align:middle;">
                    <?php echo round($entity['org_all_money']*0.9429*$entity['divide_proportion']*(1-$entity['margin_proportion']),2);?>
                </td>
                <!-- 课程履约金 -->
                <td align="center" style="vertical-align:middle;">
                    <?php echo round($entity['org_all_money']*0.9429*$entity['divide_proportion']*$entity['margin_proportion'],2);?>
                </td>
                <!-- 备注 -->
                <td align="center" style="vertical-align:middle;"></td>
            </tr>
        <?php } ?>
            <tr class="danger" align="center">
                <td colspan="3" align="center" style="vertical-align:middle;"><font color="#FF6600">合计：</font></td>
                <td align="center" style="vertical-align:middle;"><font color="#FF6600">
                <?php echo $heji['num'];?></font></td>
                <td align="center" style="vertical-align:middle;"></td>
                <td align="center" style="vertical-align:middle;"><font color="#FF6600">
                <?php echo $heji['heji'];?></font></td>
                <td align="center" style="vertical-align:middle;"><font color="#FF6600">
                <?php echo round($heji['heji']*0.9429,2);?></font></td>
                <td align="center" style="vertical-align:middle;"></td>
                <td align="center" style="vertical-align:middle;"><font color="#FF6600">
                <?php echo $heji['org_divide_money'];?></font></td>
                <td></td>
                <td align="center" style="vertical-align:middle;"><font color="#FF6600">
                <?php echo $heji['org_margin_money'];?></font></td>
                <td align="center" style="vertical-align:middle;"><font color="#FF6600">
                <?php echo $heji['margin_money'];?></font></td>
                <td align="center" style="vertical-align:middle;"></td>
            </tr>
            <tr class="info" align="center">
            <td colspan="3" align="center" style="vertical-align:middle;">营业税金及附加：5.71%：(暂行)</td>
            <td align="center" style="vertical-align:middle;"></td>
            <td align="center" style="vertical-align:middle;"></td>
            <td align="center" style="vertical-align:middle;"></td>
            <td align="center" style="vertical-align:middle;"></td>
            <td align="center" style="vertical-align:middle;"></td>
            <td align="center" style="vertical-align:middle;"></td>
            <td align="center" style="vertical-align:middle;"></td>
            <td align="center" style="vertical-align:middle;"></td>
            <td align="center" style="vertical-align:middle;"></td>
            <td align="center" style="vertical-align:middle;"></td>
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
        format: 'yyyy-mm',
        autoclose: true,
        startView: 'year',
        minView:'year',
        maxView:'decade'
    });

</script>
</html>