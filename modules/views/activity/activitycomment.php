<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Alert;
use yii\widgets\LinkPager;

?>
<!doctype html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title>活动管理</title>
    <?= Html::cssFile('@web/css/bootstrap.min.css') ?>
    <?= Html::jsFile('@web/Js/jquery.js') ?>
    <?= Html::jsFile('@web/Js/bootstrap.js') ?>
</head>

<body>
<div class="col-lg-12">
     <!-- <div class="row">
        <div class="col-lg-7">
            <form class="form-inline" action="activitylist" method="post" role="form" id="searchform">
                <input type="hidden" name="_csrf" value="<?/*= Yii::$app->request->csrfToken */?>">
                <div class="form-group">
                    <input type="text" class="form-control" name="name" placeholder="活动标题">
                    <input type="button" class="btn btn-primary" onclick="searchYuyue()" value="查询">
                </div>
            </form>
        </div>
    </div>-->
    <br>
    <table class="table table-bordered table-hover">
        <tr class="info">
            <!--<td width="20%" align="center">活动标题</td>-->
            <td align="center">人员头像</td>
            <td align="center">人员昵称</td>
            <td align="center">手机号</td>
            <td align="center">评轮内容</td>
            <td align="center">评轮时间</td>
            <td align="center">操作</td>
       </tr>
        <tbody>
        <?php foreach ($activityInfo as $activity) { ?>
            <tr class="active" align="center" id="comment_<?=$activity['id']?>">
               <!-- <td align="center"><?php /*echo $activity["activity"]["activity_name"] */?></td>-->
                <td align="center"><img src="<?= $activity["face"] ?>" width="50px" height="50px"></td>
                <td align="center"><?php echo $activity["nickname"] ?></td>
                <td align="center"><?php echo $activity["phone"] ?></td>
                <td align="center"><?php echo $activity["content"] ?>
                    <?php if(!empty($activity['images'])): foreach($activity['images'] as $v):?>
                            <img width="40"  src="<?=$v['image']?>">
                    <?php endforeach;endif;?>
                </td>
                <td align="center"><?= $activity["add_time"] ?></td>
                <td align="center">
                    <?php if($activity['status']==2):echo '已删除';else:?>
                        <button class="btn btn-danger" onclick="del(<?= $activity["id"] ?>)">删除</button>
                    <?php endif;?>
                </td>
            </tr>
            <?php
        } ?>
        </tbody>
    </table>
    <div class="row">
        <div class="col-lg-12" align="right">
            <?= LinkPager::widget(['pagination' => $pages]); ?>
        </div>

    </div>
</div>
<?= Html::jsFile('@web/Js/jquery.js') ?>
<script>

    function del(id) {
        if (window.confirm('确定要删除吗?')) {
            $.get("/admin/activity/del-comment?id=" + id).success(function(data){
                var data= $.parseJSON(data);
                if(data.status!=1){
                    alert(data.info);
                }else{
                    location.reload();
                }
            });
        } else {
            return false;
        }
    }
</script>
</body>

</html>