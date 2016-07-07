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
    function backList (){
        //window.location = "/admin/child/list";
        window.history.back();
    }
</script>
<body>
<div style="margin-left: 1%;margin-top: 1%">
    <div class="row">

        <div class="col-lg-7">
            <form class="form-inline" action="list" method="post" role="form" id="searchform">
                   <input type="button" class="btn btn-primary" onclick="backList()" value="返回">
        </div>
            </form>
        </div>
    </div>
    <br>
    <table class="table table-bordered table-hover">
        <tbody>
         <tr class="info">
             <td colspan="6">学员信息</td>
         </tr>
         <tr class="active" align="center">
             <td align="center">学员姓名</td>
             <td align="center"><?php echo $childInfo['nick_name'] ?></td>
             <td align="center">性别</td>
             <td align="center"><?php if($childInfo["sex"] == 1) echo '男'; else echo '女' ?></td>
             <td align="center">出生日期</td>
             <td align="center" ><?=$childInfo["birthday"] ?></td>
         </tr>
         <tr class="active" align="center">
             <td align="center">联系地址</td>
             <td align="center"colspan="4" ><?=$childInfo["address"] ?></td>
         </tr>

         <tr class="active" align="center">
             <td align="center">就读学校</td>
             <td align="center"colspan="3" ><?=$childInfo["school"] ?></td>
             <td align="center">年级</td>
             <td align="center" colspan="1" ><?=$childInfo["class_cc"] ?></td>
         </tr>
         <tr class="info">
             <td colspan="6">家属信息</td>
         </tr>
         <tr class="active" align="center">
             <td align="center">联系人</td>
             <td align="center" colspan="1">姓名</td>
             <td align="center" colspan="1">联系方式</td>
             <td align="center" colspan="2">工作单位</td>
         </tr>
         <tr class="active" align="center">
             <td align="center">第一联络人</td>
             <td align="center" colspan="1"><?=$childInfo["user"]['nickname'] ?></td>
             <td align="center" colspan="1"><?=$childInfo["user"]['phone'] ?></td>
             <td align="center" colspan="2"><?=$childInfo["user"]['location'] ?></td>
         </tr>
         <tr class="info">
             <td colspan="6">课程信息</td>
         </tr>
         <tr class="active" align="center">
             <td align="center">课程名</td>
             <td align="center"><?=$classInfo["class"]['title'] ?></td>
             <td align="center">课时</td>
             <td align="center"><?=$classInfo["prices"]['course_nums'] ?></td>
             <td align="center">报名日期</td>
             <td align="center"><?=$classInfo["add_date"]?></td>
         </tr>
         <tr class="active" align="center">
             <td align="center">课程费用</td>
             <td align="center">￥<?=$classInfo["prices"]["discount_price"] ?></td>
             <td align="center">材料费</td>
             <td align="center">￥<?=$classInfo["class"]['material_price'] ?></td>
             <td align="center">折扣/礼品/优惠</td>
             <td align="center"><?=$classInfo["yy"]?></td>
         </tr>

         <tr class="active" align="center">
             <td align="center">实际金额</td>
             <td align="center" colspan="2">￥<?=$classInfo["price"]?></td>
             <td align="center">付款方式</td>
             <td align="center" colspan="2"><?php if($classInfo['pay_type']== 1) echo '现金';else if($classInfo['pay_type']== 2) echo '银联'; else '信用卡' ?> </td>
         </tr>
         <tr class="active" align="center">
             <td align="center">课程安排</td>
             <td align="center" colspan="2"><?=$classInfo["class_ap"]?></td>
             <td align="center">课程顾问</td>
             <td align="center" colspan="2"><?=$classInfo["class_gw"]?></td>
         </tr>
         <tr class="active" align="center">
             <td align="center" rowspan="2" colspan="6"><?=$classInfo["notes"]?></td>
         </tr>
        </tbody>
    </table>

</div>
</body>
</html>