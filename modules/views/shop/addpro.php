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
    <?=Html::cssFile('@web/css/jquery.Jcrop.css')?>
    <!--编辑器css-->
    <?= Html::cssFile('http://libs.useso.com/js/font-awesome/4.0.3/css/font-awesome.min.css') ?>
    <?= Html::cssFile('@web/css/editor.css') ?>

    <?=Html::jsFile('@web/Js/jquery-1.10.2.js')?>
    <?=Html::jsFile('@web/Js/jquery.form.js')?>
    <?=Html::jsFile('@web/Js/jquery.Jcrop.min.js')?>
    <?=Html::jsFile('@web/Js/bootstrap.js')?>
    <?= Html::jsFile('@web/Js/editor.js') ?>

</head>
<script >
    var url="http://"+window.location.host;
    var g_oJCrop = null;
    function backList (){
        window.location = "/admin/shop/listpro";
    }
    function openFile(){
        $("#upfile").click();
    }
    function fileupload(){
        if($("#upfile").val()==""){
            alert("亲！还没有选择图片哦！");
            return false;
        }
        var file = $("#upfile").val();
        var type =  file.split('.');
        var fileType = type[type.length - 1];
        if(fileType == 'png' || fileType == 'jpg' || fileType == 'PNG' || fileType == 'JPG'){
            if(g_oJCrop != null){
                g_oJCrop.destroy();
                g_oJCrop = null;
            }
            $("#upload_ing").show();
            $("#videoimg").hide();
            $("#jcorpvideoimg").hide();
            // 为表单绑定异步上传的事件
            $("#fileForm").ajaxSubmit({
                url:url+"/admin/video/upload",
                secureuri : false,
                fileElementId : 'upfile',
                dataType : 'text',
                success : function(data) {
                    var tt =  data.split('.');
                    var t = tt[tt.length - 1];
                    $("#t").val(t);
                    $("#f").val(data);
                    $("#videoimg").attr('src',data);
                    $("#upload_ing").hide();
                    $("#videoimg").show();
                    //初始化裁剪区
                    $("#videoimg").Jcrop({
                            onChange: updatePreview,
                            onSelect: updatePreview,
                            aspectRatio: 0,
                            allowSelect :false,
                            allowResize :false
                        },
                        function(){
                            var targ_w = 1080 ;
                            var targ_h = 570 ;
                            g_oJCrop = this;
                            var bounds = g_oJCrop.getBounds();
                            var x1,y1,x2,y2;
                            if(bounds[0]/bounds[1] > targ_w/targ_h)
                            {
                                y1 = 0;
                                y2 = bounds[1];

                                x1 = (bounds[0] - targ_w * bounds[1]/targ_h)/2;
                                x2 = bounds[0]-x1;
                            }
                            else
                            {
                                x1 = 0;
                                x2 = bounds[0];

                                y1 = (bounds[1] - targ_h * bounds[0]/targ_w)/2;
                                y2 = bounds[1]-y1;
                            }
                            g_oJCrop.setSelect([x1,y1,x2,y2]);//1080*570
                        }
                    );
                },
                error : function(data, status, e) {
                    alert("图片上传失败..");
                }
            });
        } else {
            alert("图片上传格式错误");
        }
    }

    function tempImage(url){
        $("#tempImage").val(url);
    }
    //更新裁剪图片信息
    function updatePreview(c) {
        if (parseInt(c.w) > 0){
            $('#x').val(c.x);
            $('#y').val(c.y);
            $('#w').val(c.w);
            $('#h').val(c.h);
        }
    }
    function cutpicimg(){
        var w=parseInt($("#w").val());
        if(!w){
            w=0;
        }
        if(w>0){
            g_oJCrop.destroy();
            $("#upload_ing").show();
            $("#videoimg").hide();
            // 为表单绑定异步上传的事件
            $("#frm").ajaxSubmit({
                url:url+"/admin/video/cutpic",
                secureuri : false,
                dataType : 'text',
                success : function(data) {
                    $("#videoImage").val(data);
                    $("#w").val(0)
                    $("#jcorpvideoimg").attr('src',data);
                    $("#upload_ing").hide();
                    $("#jcorpvideoimg").show();
                },
                error : function(data, status, e) {
                    alert("图片上传失败..");
                }
            });
        }else{
            alert('亲！还没有选择裁剪区域哦！');
        }
    }
</script>
<body>
<div style="margin-left: 8%;margin-top: 1%">
    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(
                ['id' => 'video-form',
                    'action' => 'savepro',
                    'method'=> 'post'
                ]); ?>
            <?= $form->field($model, 'name')->textInput(['maxlength'=>100])->label("项目名称") ?>
            <?= $form->field($model, 'shopId')->dropDownList($shops)->label("所属店铺") ?>
            <label>项目图片</label><br>
            <input type="text"  id="tempImage"  readonly="readonly">
            <input type="hidden"  name="image" id="videoImage" value="<?=$model["image"] ?>" >
            <input type="button" class="btn btn-primary" onclick="openFile()" value="图片..">
            <input type="button" class="btn btn-primary" onclick="fileupload()" value="上传">
            <input type="button"  class="btn btn-primary"  onclick="cutpicimg()"  value="确认裁剪(1080*570)"/>

            <?= $form ->field($model , 'describe') -> textarea(['rows' => 4,'maxlength'=>300])->label("项目介绍") ?>
           <br>
            <label>项目详情</label>
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
        <div class="col-lg-7">
            <h5><b>项目图片</b></h5>
            <br>
            <?php if(!empty($model["image"])) {?>
                <img   id="videoimg" class="img-rounded"   src="<?= $model["image"] ?>">
            <?php } else{?><img  class="img-rounded"   id="videoimg"  >
            <?php  }?>
            <img  class="img-rounded"  id="jcorpvideoimg"   style="display: block" >
            <div align="center" id="upload_ing" style="display: none" >
                <img src="/Images/loading2.gif"><h5><b>正在上传...</b></h5>
            </div>
        </div>
    </div>
    <form  id="fileForm"action="upload" method="post"  enctype="multipart/form-data">
        <input type="hidden" name="_csrf" value="<?= Yii::$app->request->csrfToken ?>">
        <input type="file" name="upfile" accept="image/jpeg,image/png" style="display: none" id="upfile" onchange="tempImage(this.value)" >
    </form>
    <form id="frm" action="#" method="post">
        <input type="hidden" id="x" name="x" />
        <input type="hidden" id="y" name="y" />
        <input type="hidden" id="w" name="w" />
        <input type="hidden" id="h" name="h" />
        <input type="hidden" id="f" name="f" />
        <input type="hidden" id="t" name="t" />
    </form>
</div>
<script type="text/javascript">
    $("#editor").Editor({"print": false, "insert_table": false});
    $("#editor").Editor("setText", '<?= $model["notes"] ?>');
    function editorContent() {
        var te = $("#editor").Editor("getText");
        $("#notes").val(te)
        $("#video-form").submit();
    }
</script>
</body>
</html>