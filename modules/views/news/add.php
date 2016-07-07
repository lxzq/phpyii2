<?php
/**
 * Created by PhpStorm.
 * User: WWW
 * Date: 2015-12-09
 * Time: 10:28
 */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;


?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title>新增新闻</title>
    <!--编辑器css-->
    <?=Html::cssFile('@web/css/bootstrap.min.css')?>
    <?= Html::cssFile('http://libs.useso.com/js/font-awesome/4.0.3/css/font-awesome.min.css') ?>
    <?= Html::cssFile('@web/css/editor.css') ?>
    <!--裁剪-->
    <?= Html::cssFile('@web/css/jquery.Jcrop.css') ?>

    <?= Html::jsFile('@web/Js/jquery-1.10.2.js') ?>
    <?= Html::jsFile('@web/Js/bootstrap.js') ?>
    <!--编辑器js-->
    <?= Html::jsFile('@web/Js/editor.js') ?>
    <!--裁剪-->
    <?= Html::jsFile('@web/Js/jquery.form.js') ?>
    <?= Html::jsFile('@web/Js/jquery.Jcrop.min.js') ?>
</head>


<script>
    var url = "http://" + window.location.host;

    function openFile() {
        $("#upfile").click();
        return;
    }
    function backList(){
       window.location =url + '/admin/news/list';
     }
    function fileupload() {
        if ($("#upfile").val() == "") {
            alert("亲！还没有选择图片哦！");
            return false;
        }
        var file = $("#upfile").val();
        var type = file.split('.');
        var fileType = type[type.length - 1];
        if (fileType == 'png' || fileType == 'jpg' || fileType == 'PNG' || fileType == 'JPG') {
            $("#upload_ing").show();
            $("#orgimg_show").hide();
                   // 为表单绑定异步上传的事件
            $("#fileForm").ajaxSubmit({
                url: url + "/admin/video/uploadoss",
                secureuri: false,
                fileElementId: 'upfile',
                dataType: 'text',
                success: function (data) {
                    $("#orgimg_show").attr('src', data);
                    $("#image").val(data);
                    $("#upload_ing").hide();
                    $("#orgimg_show").show();
                 },
                error: function (data, status, e) {
                    alert("图片上传失败..");
                }
            });
        } else {
            alert("图片上传格式错误");
        }
    }

    function codeChange(code){
        $("#image").val(code);
    }
</script>

<body>
<div style="margin-left: 5%;margin-top: 1%;margin-right: 5%">

    <?php $form = ActiveForm::begin([
        'id' => 'organization-form',
        'action' => 'save',
        'method' => 'post']); ?>
    <div class="row">
        <div class="col-lg-5">
            <?= $form->field($model, 'title')->label("新闻标题") ?>
            <label>新闻图片</label><br>
            <input type="text" id="image" name="image" value="<?= $model["image"] ?>" height="100px">
            <input type="button" class="btn btn-primary" onclick="openFile()" value="图片...">
            <input type="button" class="btn btn-primary" onclick="fileupload()" value="上传">
            <br>
            <?= $form ->field($model , 'content') -> textarea(['rows' => 2,'maxlength'=>150])->label("新闻简介") ?>
            <label>新闻详情</label><br>
            <textarea id="editor"></textarea>
        </div>
        <div class="col-lg-7">
            <div>
                <img id="orgimg_show" width="500px"
                    <?php if (strncasecmp($model["image"], "http", 4) == 0) { ?>
                        src="<?= $model["image"] ?>"
                    <?php } else { ?>
                        src="http://image.happycity777.com<?= $model["image"] ?>"
                    <?php } ?>
                >
           </div>
            <div align="center" id="upload_ing" style="display: none">
                <img src="/Images/loading2.gif"><h5><b>正在上传...</b></h5>
            </div>
        </div>
    </div>
    <input type="hidden" name="id" id="id" value="<?= $model["id"] ?>">
    <input type="hidden" name="details" id="details">
    <br>
    <div class="form-group" align="left">
        <input type="button" class="btn btn-primary" onclick="submitOrg()" value="提交">
        <input type="button" class="btn btn-primary" onclick="backList()" value="返回">
    </div>
    <?php ActiveForm::end(); ?>
    <form id="fileForm" action="/video/upload" method="post" enctype="multipart/form-data">
        <input type="hidden" name="_csrf" value="<?= Yii::$app->request->csrfToken ?>">
        <input type="file" name="upfile" accept="image/jpeg,image/png" style="display: none" id="upfile" onchange="codeChange(this.value)" >
    </form>
</div>
<script type="text/javascript">
    $("#editor").Editor({"print": false, "insert_table": false});
    $("#editor").Editor("setText", '<?= $model["details"] ?>');
    function submitOrg() {
        var te = $("#editor").Editor("getText");
        $("#details").val(te)
        $("#organization-form").submit();
    }
</script>
</body>
</html>