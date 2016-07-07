<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\VideoForm */


use yii\helpers\Html;
use yii\bootstrap\ActiveForm;


?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <?=Html::cssFile('@web/css/bootstrap.min.css')?>
    <?=Html::jsFile('@web/Js/bootstrap.js')?>
</head>


<body>
<div style="margin-left: 8%;margin-top: 1%">
    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(
                ['id' => 'video-form',
                    'action' => 'set-password',
                    'method'=> 'post',
                ]); ?>

            <label>新密码</label><br>
            <input type="password" name="pass1"   style="width: 200px;" size="10" maxlength="10"  >
            <br><label>确认新密码</label><br>
            <input type="password" name="pass2"   style="width: 200px;" size="10" maxlength="10">
            <br>
            <br>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="提交">
           </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
</body>
<script>
    var error = '<?=$error?>';
    if(error == 'no'){
        alert('输入密码不一致');
    }
    if(error == 'yes'){
        alert('密码修改成功');
    }
</script>
</html>