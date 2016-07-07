<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>

    <meta charset="utf-8">
    <?=Html::cssFile('@web/css/bootstrap.min.css')?>
    <!--编辑器css-->

    <?= Html::cssFile('http://libs.useso.com/js/font-awesome/4.0.3/css/font-awesome.min.css') ?>
    <?= Html::cssFile('@web/css/editor.css') ?>

    <?=Html::jsFile('@web/Js/jquery-1.10.2.js')?>
    <?=Html::jsFile('@web/Js/bootstrap.js')?>
    <?= Html::jsFile('@web/Js/editor.js') ?>
</head>
<script >
    function backList (){
        window.location = "/admin/teacher/list";
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

            <?= $form->field($model, 'orgId')->dropDownList($orgs) ->label("所属机构")?>
            <?= $form->field($model, 'name')->textInput(['maxlength'=>20])->label("教师名称") ?>
            <?= $form->field($model, 'phone')->textInput(['maxlength'=>11])->label("手机号") ?>
            <?= $form->field($model, 'weixinUserId')->dropDownList($weixinUser) ->label("关联微信")?>
            <label>性别</label><br>
            <input type="radio" name="sex" checked="checked" value="0">女&nbsp;&nbsp;
            <input type="radio" name="sex" <?php if(1 ==$model["sex"] ) {?>  checked="checked" <?php } ?>  value="1">男<br><br>
            <?= $form->field($model, 'workYears')->input('number')->label("教龄") ?>
            <?= $form->field($model, 'address')->textInput(['maxlength'=>100])->label("家庭地址") ?>
            <label>任教简历</label>
            <textarea id="editor" ></textarea>
            <input type="hidden" name="id" value="<?=$model["id"] ?>">
            <input type="hidden" name="notes" id="notes" value='<?=$model["notes"] ?>'>
            <br>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="提交" onclick="editorContent()">
                <input type="button" class="btn btn-primary" onclick="backList()" value="返回">
            </div>
            <?php ActiveForm::end(); ?>
        </div>

    </div>
</div>

<script type="text/javascript">
    $("#editor").Editor({"print": false, "insert_table": false,"insert_img":false});
    $("#editor").Editor("setText", '<?= $model["notes"] ?>');
    function editorContent() {
        var te = $("#editor").Editor("getText");
        $("#notes").val(te)
        $("#video-form").submit();
    }
</script>
</body>
</html>