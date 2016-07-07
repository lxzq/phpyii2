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
    <?= Html::cssFile('@web/css/bootstrap-datetimepicker.min.css') ?>

    <?=Html::jsFile('@web/Js/jquery-1.10.2.js')?>

    <?= Html::jsFile('@web/Js/bootstrap.js') ?>
    <?= Html::jsFile('@web/Js/bootstrap-datetimepicker.js') ?>
    <?= Html::jsFile('@web/Js/locales/bootstrap-datetimepicker.zh-CN.js') ?>

</head>
<script >
    function backList (){
       // window.location = "/admin/child/list";
        window.history.back();
    }
</script>
<body>
<div style="margin-left: 8%;margin-top: 1%">
    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(
                ['id' => 'video-form',
                    'action' => 'save',
                    'method'=> 'post'
                ]); ?>
            <?= $form->field($model, 'shopId')->dropDownList($shops)->label("所属店铺") ?>

            <?php if(empty($model["id"])){ ?>
            <?= $form->field($model, 'userName')->textInput(['maxlength'=>50])->label("爸爸") ?>
            <?= $form->field($model, 'phone')->textInput(['maxlength'=>11])->label("手机号") ?>
            <?= $form->field($model, 'work')->textInput(['maxlength'=>100])->label("工作单位") ?>

            <?= $form->field($model, 'secondName')->textInput(['maxlength'=>50])->label("妈妈") ?>
            <?= $form->field($model, 'secondPhone')->textInput(['maxlength'=>11])->label("手机号") ?>
            <?= $form->field($model, 'secondWork')->textInput(['maxlength'=>100])->label("工作单位") ?>

            <?php } ?>

            <?= $form->field($model, 'childName')->textInput(['maxlength'=>100])->label("宝贝姓名") ?>
            <?= $form->field($model,'childSex')->radioList(['0'=>'女','1'=>'男'])->label('性别') ?>
             <label>出生日期</label>
            <div class="input-group date form_datetime col-md-8" id="form_datetime"
                 data-date-format="yyyy-mm-dd">
                <input class="form-control" size="10" type="text" name="birthday" id="birthday"
                       value="<?= $model["birthday"] ?>" readonly>
                <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
            </div>
            <br>
            <?= $form->field($model, 'address')->textInput(['maxlength'=>100])->label("联系地址") ?>
            <?= $form->field($model, 'school')->textInput(['maxlength'=>100])->label("就读学校") ?>
            <?= $form->field($model, 'class')->textInput(['maxlength'=>100])->label("年级") ?>
            <input type="hidden" name="id" value="<?=$model["id"] ?>">
            <input type="hidden" name="_csrf" value="<?= Yii::$app->request->csrfToken ?>">
            <br>
            <div class="form-group">
                <input type="button" class="btn btn-primary" onclick="formValidate()"  value="提交">
                <input type="button" class="btn btn-primary" onclick="backList()" value="返回">
            </div>
            <?php ActiveForm::end(); ?>
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

    function formValidate(){
        var phone = $("#childform-phone").val();
        var secondphone = $("#childform-secondphone").val();
        var birthday = $("#birthday").val();
        var childName = $("#childform-childname").val();
        if(phone == '' && secondphone == '' ){
            alert('手机号必须填写');
            return;
        }

        if(childName == ''){
            alert('宝贝姓名必须填写')
            return;
        }
        if(birthday == ''){
            alert('出生日期必须填写')
            return;
        }
        $("#video-form").submit();
    }

</script>
</html>