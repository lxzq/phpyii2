<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\VideoForm */
use app\assets\AppAsset;
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
<script >
    function add (){
        window.location = "/admin/teacher/add";
    }
    function edit(id){
        window.location = "/admin/teacher/add?id="+id;
    }
    function del(id){
        if(window.confirm('确定要删除吗?')){
            window.location = "/admin/teacher/del?id="+id;
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
<div style="margin-left: 1%;margin-top: 1%">
    <div class="row">

        <div class="col-lg-12">
            <form class="form-inline" action="list" method="post" role="form" id="searchform">
                <input type="hidden" name="_csrf" value="<?= Yii::$app->request->csrfToken ?>">
                <div class="form-group">
                    <input type="text" class="form-control"  name="name" placeholder="教师名称">
                    <select name="org" class="form-control" placeholder="机构名称">
                        <option value="">全部机构</option>
                        <?php foreach ($orgs as $entity) { ?>
                        <option value="<?= $entity["id"] ?>"  <?php if($orgId == $entity["id"] ){ ?> selected="selected" <?php } ?>  ><?= $entity["name"] ?></option>
                        <?php } ?>
                    </select>
                     <input type="button" class="btn btn-primary"  onclick="searchYuyue()" value="查询">
                     <input type="button" class="btn btn-primary" style="margin-right: 10px" onclick="add()" value="新增">
                </div>
            </form>
        </div>
    </div>
    <br>
    <table class="table table-bordered table-hover">
       <tr class="info">
           <td align="center">所属机构</td>
           <td align="center">教师名称</td>
           <td align="center">手机号</td>
           <td align="center">微信昵称</td>
           <td align="center">性别</td>
           <td align="center">教龄</td>
           <td align="center">家庭地址</td>
           <td align="center">操作</td>
       </tr>

        <tbody>
        <?php foreach ($list as $entity) { ?>
            <tr class="active" align="center">
                <td align="center">
                    <?php foreach ($orgs as $org) {
                        if($org["id"] == $entity["org_id"] ){
                            echo $org["name"];
                        }
                    }?>
                </td>
            <td ><?=$entity["name"] ?></td>
            <td ><?=$entity["phone"] ?></td>
            <td ><?=$entity["weixinUser"]['nickname'] ?></td>
            <td align="center">
                <?php
                if( 0 == $entity["sex"] ){
                    echo '女';
                }else {
                    echo '男';
                }
                ?>
            </td>
            <td align="center"> <?=$entity["work_years"] ?>年教龄</td>
             <td > <?=$entity["address"] ?></td>
            <td align="center">
                <button  class="btn btn-primary" onclick="edit(<?=$entity["id"] ?>)">编辑 </button>
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