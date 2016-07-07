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
    function add() {
        window.location = "/admin/child/add";
    }
    function edit(id) {
        window.location = "/admin/child/add?id=" + id;
    }
    function sqclass(id,userId){
        window.location = "/admin/child/class?id=" + id+"&userId="+userId;
    }
    function editcard(id,userId){
        window.location = "/admin/child/editcard?id=" + id+"&userId="+userId;
    }
    function card(id,phone) {
         if (window.confirm('确定要办卡吗?')) {
             window.location = "/admin/child/card?id=" + id+"&phone="+phone;
         }
    }
    function searchYuyue() {
        document.getElementById("searchform").submit();
    }
    function child(id){
        window.location = "/admin/child/child?id=" + id;
    }
    function money(id){
        window.location = "/admin/child/money?id=" + id;
    }
</script>
<body>
<div style="margin-left: 1%;margin-top: 1%">
    <div class="row">

        <div class="col-lg-7">
            <form class="form-inline" action="list" method="post" role="form" id="searchform">
                <input type="hidden" name="_csrf" value="<?= Yii::$app->request->csrfToken ?>">
                <div class="form-group">
                    <select name="shop" class="form-control">
                        <option value="" >全部店铺</option>
                        <?php  foreach($shops as $shop) {?>
                            <option value="<?=$shop["id"]?>"  <?php if($shop["id"]==$key) { ?>selected="selected"  <?php } ?>  ><?=$shop["name"]?></option>
                        <?php } ?>
                    </select>
                    <input type="text" class="form-control" size="10" name="name" placeholder="会员姓名">

                    <select name="month" class="form-control">
                        <option value="" >全部月份</option>
                        <?php  for($month = 1 ; $month < 13 ;$month++) {?>
                            <option value="<?=$month?>"  <?php if($month==$monthP) { ?>selected="selected"  <?php } ?>  ><?=$month?>月</option>
                        <?php } ?>
                    </select>

                    <input type="button" class="btn btn-primary" onclick="searchYuyue()" value="查询">
                    <input type="button" class="btn btn-primary" style="margin-right: 10px" onclick="add()" value="新增">
                </div>
            </form>
        </div>
    </div>
    <br>
    <table class="table table-bordered table-hover">
        <tr class="info">
            <td align="center">所属店铺</td>
            <!--<td align="center">家属姓名</td>-->
            <td align="center">会员姓名</td>
            <td align="center">手机号</td>

            <td align="center">性别</td>
            <td align="center">出生日期</td>
            <td align="center">提交办卡</td>
            <td align="center">一卡通卡号</td>
            <!--<td align="center">已报课程</td>-->
            <td align="center">操作</td>
        </tr>
        <tbody>
        <?php foreach ($list as $entity) { ?>
            <tr class="active" align="center">
                <td>
                <?php
                  foreach($shops as $shop){
                      if($shop["id"] ==$entity["shop_id"] ){
                          echo $shop["name"];
                      }
                  }
                ?>
                </td>
                <td><?= $entity["nick_name"] ?></td>
                <!--<td><?/*= $entity["user"]['nickname'] */?></td>-->
                <td align="center">
                    <?= $entity["phone"] ?>
                </td>

                <td align="center">
                  <?php
                  if($entity["sex"] == 1 )
                   echo '男';
                  else echo '女'
                  ?>
                </td>
                <td align="center"><?= $entity["birthday"] ?></td>

                 <td align="center">
                    <?php if($entity["card_id"] == 0)
                        echo '<font color="red">未办卡</font>' ;
                    else echo  '<font color="green">已提交</font>' ?>
                </td>
                <td align="center"><?= $entity["card_code"] ?></td>
               <!-- <td align="center">
                    <?php
/*                    foreach($entity["class"] as $class){
                       echo '《'. $class["title"].'》<br>';
                    }
                    */?>
                </td>-->
                <td align="center">
                    <button class="btn btn-primary" onclick="sqclass(<?= $entity["id"] ?>,<?=$entity["user"]["id"]?> )">申报课程</button>
                    <?php if($entity["card_id"] == 0) {?>
                     <button class="btn btn-primary" onclick="edit(<?= $entity["id"] ?>)">编辑会员</button>
                     <button class="btn btn-danger" onclick="card(<?= $entity["id"] ?>,<?= $entity["phone"] ?>)">提交办卡</button>
                    <?php } else { ?>

                        <button class="btn btn-success" onclick="editcard(<?= $entity["id"] ?>,<?=$entity["user"]["id"]?>)">去充值</button>
                    <?php } ?>

                    <button class="btn btn-primary" onclick="money(<?= $entity["id"] ?>)">充值记录</button>
                    <button class="btn btn-info" onclick="child(<?= $entity["id"] ?>)">学员信息</button>
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
    var error = '<?=$error?>';
    if(error == '1'){
        alert("添加失败,会员已经存在");
    }

</script>
</html>