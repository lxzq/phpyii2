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

    <?=Html::jsFile('@web/Js/jquery-1.10.2.js')?>
    <?= Html::jsFile('@web/Js/bootstrap.js') ?>

</head>
<script >
    function backList (){
        //window.location = "/admin/child/list";
        window.history.back();
    }
</script>
<body>
<div style="margin-left: 8%;margin-top: 1%">
    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(
                ['id' => 'video-form',
                    'action' => 'savecard',
                    'method'=> 'post'
                ]); ?>

            <?= $form->field($model, 'type')->radioList(['1'=>'充值','2'=>'优惠'])->label("消费类型") ?>
            <?= $form->field($model, 'shouldMoney')->textInput(['maxlength'=>10])->label("应收金额(元)") ?>
            <?= $form->field($model, 'money')->textInput(['maxlength'=>10])->label("实收金额(元)") ?>
            <?= $form->field($model, 'notes')->textarea(['maxlength'=>100])->label("描述") ?>
            <input type="hidden" name="childId" value="<?=$childId?>">
            <input type="hidden" name="userId" value="<?=$userId?>">
            <input type="hidden" name="_csrf" value="<?= Yii::$app->request->csrfToken ?>">
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="提交">
                <input type="button" class="btn btn-primary" onclick="backList()" value="返回">
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
  </div>
</body>
</html>