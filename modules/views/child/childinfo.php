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
       // window.location = "/admin/child/list";
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
    <table class="table table-bordered " style="width: 65%;margin-left: 10px;">
        <tbody>
         <tr class="info">
             <td colspan="7"><b>学员信息</b> </td>
         </tr>
         <tr  align="center">
             <td align="center"><b>学员姓名</b></td>
             <td align="center"><?=  $childInfo['nick_name'] ?></td>
             <td align="center"><b>性别</b> </td>
             <td align="center"><?php if($childInfo["sex"] == 1) echo '男'; else echo '女' ?></td>
             <td align="center"><b>出生日期</b></td>
             <td align="center" ><?=$childInfo["birthday"] ?></td>
             <td align="center" rowspan="3" width="120px">
                 

                 照片
             </td>
         </tr>
         <tr  align="center">
             <td align="center"><b>联系地址</b> </td>
             <td align="center"colspan="5" ><?=$childInfo["address"] ?></td>
         </tr>

         <tr  align="center">
             <td align="center"><b>就读学校</b> </td>
             <td align="center"colspan="2" ><?=$childInfo["school"] ?></td>
             <td align="center"><b>年级</b> </td>
             <td align="center" colspan="2" ><?=$childInfo["class_cc"] ?></td>
         </tr>
         <tr class="info">
             <td colspan="7"><b>家属信息</b></td>
         </tr>
         <tr  align="center">
             <td align="center"><b>联系人</b></td>
             <td align="center" colspan="1"><b>姓名</b></td>
             <td align="center" colspan="1"><b>联系方式</b></td>
             <td align="center" colspan="4"><b>工作单位</b></td>
         </tr>

         <?php if(!empty($userInfo)) {
             $index = 0;
             foreach ($userInfo as $user){
              ?>
                 <tr align="center">
                     <td align="center">第<?php if($index == 0) echo '一'; else echo '二'?>联络人</td>
                     <td align="center" colspan="1"><?=$user['nickname'] ?></td>
                     <td align="center" colspan="1"><?=$user['phone'] ?></td>
                     <td align="center" colspan="4"><?=$user['location'] ?></td>
                 </tr>
         <?php $index++; } } else { ?>
         <tr align="center">
             <td align="center">第一联络人</td>
             <td align="center" colspan="1"></td>
             <td align="center" colspan="1"></td>
             <td align="center" colspan="4"></td>
         </tr>
         <tr align="center">
             <td align="center">第二联络人</td>
             <td align="center" colspan="1"></td>
             <td align="center" colspan="1"></td>
             <td align="center" colspan="4"></td>
         </tr>
         <?php } ?>
         <tr class="info">
             <td colspan="7"><b>课程信息</b></td>
         </tr>
         <tr  align="center">
             <td align="center"><b>课程名</b></td>
             <td align="center">
                 <?php foreach ($classInfo as $class){
                     echo $class["title"] . '<br>';
                 }?>

             </td>
             <td align="center"><b>课时</b></td>
             <td align="center">
                 <?php foreach ($classInfo as $class){
                     echo $class["course_nums"] . '(个课时)<br>';
                 }?>
             </td>
             <td align="center"><b>报名日期</b></td>
             <td align="center" colspan="2">

                 <?php

                  if(!empty($classInfo)){
                      echo $classInfo[0]["add_date"];
                  }

                 ?>

             </td>
         </tr>
         <tr  align="center">
             <td align="center"><b>课程费用</b></td>
             <td align="center">
                 <?php foreach ($classInfo as $class){
                     echo '<font color="#FF6600">￥' . $class["discount_price"] . '</font><br>';
                 }?>
             </td>
             <td align="center"><b>材料费</b></td>
             <td align="center">
                 <?php

                 if(!empty($classInfo)){
                     echo  '￥' . $classInfo[0]["material_price"];
                 }

                 ?>
                </td>
             <td align="center"><b>折扣/礼品/优惠</b></td>
             <td align="center" colspan="2">
                 <?php

                 if(!empty($classInfo)){
                     echo  $classInfo[0]["yy"];
                 }

                 ?>
                </td>
         </tr>

         <tr >
             <td align="center"><b>实际金额</b></td>
             <td align="center" colspan="2"><font color="#FF6600">￥
                     <?php
                     if(!empty($classInfo)){
                         echo  $classInfo[0]["price"];
                     }
                     ?>
                 </font>

             </td>
             <td align="center"><b>付款方式</b></td>
             <td  colspan="3">
                 <?php
                 if(!empty($classInfo)){

                 ?>
                 <input type="checkbox" <?php if($classInfo[0]['pay_type']== 1) {?> checked="checked" <?php }?> >现金
                 <input type="checkbox" <?php if($classInfo[0]['pay_type']== 2) {?> checked="checked" <?php }?> >银联
                 <input type="checkbox" <?php if($classInfo[0]['pay_type']== 3) {?> checked="checked" <?php }?> >信用卡
                 <input type="checkbox" <?php if($classInfo[0]['pay_type']== 4) {?> checked="checked" <?php }?> >微信支付
                 <input type="checkbox" <?php if($classInfo[0]['pay_type']== 5) {?> checked="checked" <?php }?> >支付宝
                 <?php  }?>
             </td>
         </tr>
         <tr  align="center">
             <td align="center"><b>课程安排</b></td>
             <td align="center" colspan="2">
                 <?php
                 if(!empty($classInfo)){
                 ?>
                 <?=$classInfo[0]["class_ap"]?>
                 <?php  }?>

             </td>
             <td align="center"><b>课程顾问</b></td>
             <td align="center" colspan="3">
                 <?php
                 if(!empty($classInfo)){
                 ?>
                 <?=$classInfo[0]["class_gw"]?>
                 <?php  }?></td>
         </tr>
         <tr >
             <td align="center" height="70px"><b>备注</b></td>
             <td   colspan="6"><?php
                 if(!empty($classInfo)){
                 ?><?=$classInfo[0]["notes"]?><?php  }?></td>
         </tr>
        </tbody>
    </table>

</div>
</body>
</html>