<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\VideoForm */

use yii\helpers\Html;
use yii\widgets\LinkPager;

$this->title = '预约管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <?= Html::cssFile('@web/css/bootstrap.min.css') ?>
    <?= Html::jsFile('@web/Js/bootstrap.js') ?>
</head>
<script >
    function del(id){
        if(window.confirm('确定要删除吗?')){
            window.location = "/admin/yuyue/del?id="+id;
            return true;
        }else{
            return false;
        }
    }
    function searchYuyue(){
        document.getElementById("searchform").submit();
    }
</script>
<body>
<div style="margin-left: 1%;margin-top: 1%" >
    <div class="row">
      <!--  <div class="col-lg-2">
            <b style="font-size: 24px"><?/*= Html::encode($this->title) */?></b>
        </div>-->

        <div class="col-lg-10">
            <form class="form-inline" action="list" method="post" role="form" id="searchform">
                <input type="hidden" name="_csrf" value="<?= Yii::$app->request->csrfToken ?>">
                <div class="form-group">
                    <input type="text" class="form-control"  name="childName" placeholder="儿童姓名">
                    <input type="text" class="form-control"  name="userName" placeholder="家长姓名">
                    <input type="text" class="form-control"  name="phone" placeholder="家长手机">
                    <button class="btn btn-primary" style="margin-right: 10px" onclick="searchYuyue()">查询</button>
                </div>
            </form>
        </div>
    </div>
    <br>

    <table class="table table-bordered table-hover">
       <tr class="info">
           <td>儿童姓名</td>
           <td align="center">出生日期</td>
           <td align="center">儿童性别</td>
           <td align="center">家长手机</td>
           <td >家长姓名</td>
           <td align="center">预约日期</td>
           <td align="center">预约时间</td>
           <td align="center">操作</td>
       </tr>
        <tbody>
        <?php foreach ($list as $entity) { ?>
            <tr class="active">
            <td ><?=$entity["child_name"] ?></td>
            <td align="center"><?=$entity["birthday"] ?></td>
            <td align="center">
                <?php
                if( 1 == $entity["sex"] ){
                    echo '男';
                }else {
                    echo '女';
                }
                ?>
            </td>
            <td align="center"> <?=$entity["phone"] ?></td>
            <td ><?=$entity["user_name"] ?></td>
            <td align="center"><?=$entity["reg_date"] ?></td>
             <td align="center">
                    <?php
                    if( 1 == $entity["yuyue_time"] ){
                        echo '09:00-12:00';
                    }else if(2 == $entity["yuyue_time"]){
                        echo '13:00-15:30';
                    }
                    else {
                        echo '15:30-18:00';
                    }
                    ?>
                </td>
            <td align="center">
                 <button class="btn btn-danger"  onclick="del(<?=$entity["id"] ?>)" >删除</button>
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
</html>