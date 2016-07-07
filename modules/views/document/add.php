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
    <?=Html::jsFile('@web/Js/jquery.form.js')?>
    <?=Html::jsFile('@web/Js/bootstrap.js')?>
</head>
<script >

    function backList (){
        window.location = "/admin/document/list";
    }
    function openFile(){
        $("#upfile").click();
    }
    function fileupload(){
        if($("#upfile").val()==""){
            alert("亲！还没有选择文件哦！");
            return false;
        }
        var file = $("#upfile").val();
        var type =  file.split('.');
        var fileType = type[type.length - 1];
        //if(fileType == 'doc' || fileType == 'docx' || fileType == 'xls' || fileType == 'xlsx' ||fileType=='pdf' ){
            $("#upload_ing").show();
            $("#tempfile").hide();
            // 为表单绑定异步上传的事件
            $("#fileForm").ajaxSubmit({
                url:"/admin/document/upload",
                secureuri : false,
                fileElementId : 'upfile',
                dataType : 'json',
                success : function(data) {
                    var name = data.name;
                    var path = data.path;
                    $("#name").val(name);
                    $("#path").val(path);
                    $("#tempfile").val(name);
                    $("#upload_ing").hide();
                    $("#tempfile").show();
              },
                error : function(data, status, e) {
                    $("#upload_ing").hide();
                    $("#tempfile").hide();
                    alert("上传失败..");
                }
            });
    }

    function temp(path){
        $("#tempImage").val(path);
    }


</script>
<body>
<div style="margin-left: 8%;margin-top: 1%">
    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(
                ['id' => 'video-form',
                    'action' => 'save',
                    'method'=> 'post',
                ]); ?>

            <label>上传文件</label><br>
            <input type="text"  id="tempImage"  readonly="readonly">
            <input type="hidden"  name="name" id="name" >
            <input type="hidden"  name="path" id="path" >
            <input type="button" class="btn btn-primary" onclick="openFile()" value="文件..">
            <input type="button" class="btn btn-primary" onclick="fileupload()" value="上传（小于8M）">
            <?= $form ->field($model , 'notes') -> textarea(['rows' => 3,'maxlength'=>50])->label("上传说明") ?>
            <div class="form-group">
            <input type="submit" class="btn btn-primary" value="提交">
            <input type="button" class="btn btn-primary" onclick="backList()" value="返回">
           </div>
            <?php ActiveForm::end(); ?>
        </div>
        <div class="col-lg-7">
            <h5><b>文件</b></h5>
            <br>
            <input id="tempfile" readonly="readonly" size="100" style="display: none;border: none">
            <div align="center" id="upload_ing" style="display: none" >
                <img src="/Images/loading2.gif"><h5><b>正在上传...</b></h5>
            </div>
        </div>
    </div>
    <form  id="fileForm"action="upload" method="post"  enctype="multipart/form-data">
        <input type="hidden" name="_csrf" value="<?= Yii::$app->request->csrfToken ?>">
        <input type="file" name="upfile" accept=".pdf,.xls,.xlsx,.doc,.docx,.rar,.zip" style="display: none" id="upfile"  onchange="temp(this.value)" >
    </form>
 </div>
</body>
</html>