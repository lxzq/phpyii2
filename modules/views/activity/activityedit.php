<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

if (empty($model["activity_id"])) {
    $this->title = '活动新增';
} else {
    $this->title = '活动编辑';
}
$this->params['breadcrumbs'][] = $this->title;
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title>活动</title>

    <?= Html::cssFile('@web/css/bootstrap.min.css')?>
    <?= Html::cssFile('@web/css/bootstrap-datetimepicker.min.css') ?>
    <!--编辑器css-->
    <?= Html::cssFile('http://libs.useso.com/js/font-awesome/4.0.3/css/font-awesome.min.css') ?>
    <?= Html::cssFile('@web/css/editor.css') ?>
    <!--裁剪-->
    <?= Html::cssFile('@web/css/jquery.Jcrop.css') ?>


    <?= Html::jsFile('@web/Js/jquery-1.10.2.js') ?>
    <?= Html::jsFile('@web/Js/bootstrap.js') ?>
    <?= Html::jsFile('@web/Js/bootstrap-datetimepicker.js') ?>
    <?= Html::jsFile('@web/Js/locales/bootstrap-datetimepicker.zh-CN.js') ?>
    <!--编辑器js-->
    <?= Html::jsFile('@web/Js/editor.js') ?>

    <?= Html::jsFile('@web/Js/jquery.form.js') ?>
    <?= Html::jsFile('@web/Js/jquery.Jcrop.min.js') ?>
	 <style type="text/css">
      img{padding:10px;}
	  #images{float:left}
	  #images div.imgst{float:left;position: relative}
	  #images img{width:200px;align:left;border: 1px #cccccc dashed}
      #images span{width:20px;height: 20px;top:-10px;cursor: pointer;position: absolute;}
  </style>
</head>

<script>
    var url = "http://" + window.location.host;
    var g_oJCrop = null;
    function openFile() {
        $("#upfile").click();
        return;
    }
    function backList(){
        window.location =url + '/admin/activity/activitylist';
   //     window.history.back();
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
            if(g_oJCrop != null){
                g_oJCrop.destroy();
                g_oJCrop = null;
            }
            $("#upload_ing").show();
            $("#activityimg").hide();
            $("#jcorpvideoimg").hide();
            // 为表单绑定异步上传的事件
            $("#fileForm").ajaxSubmit({
                url: url + "/admin/video/upload",
                secureuri: false,
                fileElementId: 'upfile',
                dataType: 'text',
                success: function (data) {

                    var tt = data.split('.');
                    var t = tt[tt.length - 1];
                    $("#t").val(t);
                    $("#f").val(data);
                    $("#activityimg").attr('src', data);
                    $("#upload_ing").hide();
                    $("#activityimg").show();
                    //初始化裁剪区
                    $("#activityimg").Jcrop({
                            onChange: updatePreview,
                            onSelect: updatePreview,
                            aspectRatio: 0,
                            allowSelect: false,
                            allowResize: false
                        },
                        function () {
                            var targ_w = 1080;
                            var targ_h = 570;
                            g_oJCrop = this;
                            var bounds = g_oJCrop.getBounds();
                            var x1, y1, x2, y2;
                            if (bounds[0] / bounds[1] > targ_w / targ_h) {
                                y1 = 0;
                                y2 = bounds[1];

                                x1 = (bounds[0] - targ_w * bounds[1] / targ_h) / 2;
                                x2 = bounds[0] - x1;
                            }
                            else {
                                x1 = 0;
                                x2 = bounds[0];

                                y1 = (bounds[1] - targ_h * bounds[0] / targ_w) / 2;
                                y2 = bounds[1] - y1;
                            }
                            g_oJCrop.setSelect([x1, y1, x2, y2]);//1080*570
                        }
                    );
                },
                error: function (data, status, e) {
                    alert("图片上传失败..");
                }
            });
        } else {
            alert("图片上传格式错误");
        }
    }
    function openImage() {
        $("#upfile").attr('rel','images');
        $("#upfile").click();
        return;
    }
    function imageupload() {
        if ($("#upfile").val() == "") {
            alert("亲！还没有选择图片哦！");
            return false;
        }
        var file = $("#upfile").val();
        var type = file.split('.');
        var fileType = type[type.length - 1];
        if (fileType == 'png' || fileType == 'jpg' || fileType == 'PNG' || fileType == 'JPG') {
            if(g_oJCrop != null){
                g_oJCrop.destroy();
                g_oJCrop = null;
            }
            $("#upload_ing").show();
            $("#jcorpvideoimg").hide();
            // 为表单绑定异步上传的事件
            $("#fileForm").ajaxSubmit({
                url: url + "/admin/video/uploadoss",
                secureuri: false,
                fileElementId: 'upfile',
                dataType: 'text',
                success: function (data) {
                    var num=$("#images").find('img').length+1;

                    $("#images").append("<div class='imgst col-lg-4'><input type='hidden' name='images[]' id='image_"+num+"' value='"+data+"'>"+
                    "<img src='"+data+"' rel='"+num+"'/><span class='glyphicon glyphicon-remove'></span></div>");
                    $(".images").before('');
                    $("#upload_ing").hide();
                },
                error: function (data, status, e) {
                    alert("图片上传失败..");
                }
            });
        } else {
            alert("图片上传格式错误");
        }
    }
    function shareImage() {
        $("#upfile").attr('rel','share');
        $("#upfile").click();
        return;
    }
    function shareupload() {
        if ($("#upfile").val() == "") {
            alert("亲！还没有选择图片哦！");
            return false;
        }
        var file = $("#upfile").val();
        var type = file.split('.');
        var fileType = type[type.length - 1];
        if (fileType == 'png' || fileType == 'jpg' || fileType == 'PNG' || fileType == 'JPG') {
            if(g_oJCrop != null){
                g_oJCrop.destroy();
                g_oJCrop = null;
            }
            $("#upload_ing").show();
            $("#jcorpvideoimg").hide();
            // 为表单绑定异步上传的事件
            $("#fileForm").ajaxSubmit({
                url: url + "/admin/video/uploadoss",
                secureuri: false,
                fileElementId: 'upfile',
                dataType: 'text',
                success: function (data) {

                    $("#share_image").html("<img src='"+data+"'/>");
                    $(".share_image").val(data);
                    $("#upload_ing").hide();
                },
                error: function (data, status, e) {
                    alert("图片上传失败..");
                }
            });
        } else {
            alert("图片上传格式错误");
        }
    }
    //更新裁剪图片信息
    function updatePreview(c) {
        if (parseInt(c.w) > 0) {
            $('#x').val(c.x);
            $('#y').val(c.y);
            $('#w').val(c.w);
            $('#h').val(c.h);
        }
    }
    function cutpicimg() {
        var w = parseInt($("#w").val());
        if (!w) {
            w = 0;
        }
        if (w > 0) {
            g_oJCrop.destroy();
            $("#upload_ing").show();
            $("#activityimg").hide();
            // 为表单绑定异步上传的事件
            $("#frm").ajaxSubmit({
                url: url + "/admin/video/cutpic",
                secureuri: false,
                dataType: 'text',
                success: function (data) {
                    alert(data);
                    $("#activity_image").val(data);
                    $("#w").val(0)
                    $("#jcorpvideoimg").attr('src', data);
                    $("#upload_ing").hide();
                    $("#jcorpvideoimg").show();
                },
                error: function (data, status, e) {
                    alert("图片上传失败..");
                }
            });
        } else {
            alert('亲！还没有选择裁剪区域哦！');
        }
    }

    function actiImage(url) {
        var type=$("#upfile").attr('rel');
        if(!type){
            $("#actiImage").val(url);
        }else{
            $("#upfile").attr('rel','');
        }

    }
</script>
<body>
<div style="margin-left: 5%;margin-top: 1%;margin-right: 5%">
    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin([
                'id' => 'activity-form',
                'action' => 'save',
                'method' => 'post']); ?>
            <?= $form->field($model, 'activity_name')->label("活动标题") ?>
            <?= $form->field($model, 'activity_theme')->label("活动主题") ?>
            <?= $form->field($model, 'activity_number')->label("活动人数") ?>
            <?= $form->field($model, 'activity_address')->label("活动地址") ?>
            <?= $form->field($model, 'activity_host')->label("主办单位") ?>
            <?= $form->field($model, 'activity_tel')->label("活动热线") ?>

            <label>开始时间</label>
            <div class="input-group date form_datetime col-md-8" id="form_datetime" data-date-format="yyyy-mm-dd hh:ii:ss">
                <input class="form-control" size="16" type="text" name="activity_starttime" value="<?php if(!empty($model['activity_starttime'])) echo Yii::$app->formatter->asDatetime($model['activity_starttime']); ?>" readonly/>
                <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
            </div>
            <label>结束时间</label>
            <div class="input-group date form_datetime col-md-8" id="form_datetime"
                 data-date-format="yyyy-mm-dd hh:ii:ss">
                <input class="form-control" size="16" type="text" name="activity_endtime"
                       value="<?php if(!empty($model['activity_starttime'])) echo Yii::$app->formatter->asDatetime($model['activity_endtime']); ?>" readonly>
                <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
            </div>
            <br>
            <label>活动图片</label><br>
            <input type="text" id="actiImage" readonly="readonly" value="<?= $model["activity_image"] ?>">
            <input type="hidden" name="activity_image" id="activity_image">
            <input type="button" class="btn btn-primary" onclick="openFile()" value="图片...">
            <input type="button" class="btn btn-primary" onclick="fileupload()" value="上传">
            <input type="button" class="btn btn-primary" onclick="cutpicimg()" value="确认裁剪(1080*570)"/><br>
            <label>活动相册</label><br>
            <input type="button" class="btn btn-primary images"  onclick="openImage()" value="图片...">
            <input type="button" class="btn btn-primary" onclick="imageupload()" value="上传">
           <br>
            <?= $form->field($model, 'intro')->textarea(['rows'=>5])->label("活动简介")?>

            <input type="hidden" name="activity_id" value="<?= $model["activity_id"] ?>">
            <input type="hidden" name="activity_centent" id="activity_centent" >
            <label>活动详情</label>
            <textarea id="editor"></textarea>
            <label>分享标题</label>
            <input type="text" name="share[title]" class="form-control" value="<?php if(!empty($share['title'])):echo $share['title'];endif;?>">
            <label>分享链接</label>
            <input type="text" name="share[link]" class="form-control" value="<?php if(!empty($share['link'])):echo $share['link'];endif;?>">
            <label>分享图片</label>
            <input type="text" name="share[image]" class="form-control share_image"  readonly="readonly"  value="<?php echo $share['image_url'];?>">
            <input type="button" class="btn btn-primary" onclick="shareImage()" value="图片...">
            <input type="button" class="btn btn-primary" onclick="shareupload()" value="上传"><br>
            <label>分享描述</label>
            <textarea class="form-control" name="share[description]" rows="5"><?=$share['description']?></textarea>
        </div>
        <div class="col-lg-7">
            <div align="center" class="table table-bordered">
                    <h4>活动图片</h4>
                <img id="activityimg" width="400"
                    <?php if (strncasecmp($model["activity_image"], "http", 4) == 0) { ?>
                        src="<?= $model["activity_image"] ?>"
                    <?php } else { ?>
                        src="http://image.happycity777.com<?= $model["activity_image"] ?>"
                    <?php } ?>
                >
                <img class="img-rounded" id="jcorpvideoimg" style="display: block">
            </div>
			<div align="center" id="images" class="table table-bordered">
				 <h4>活动相册</h4>
                <?php if(!empty($images)): foreach($images as $ke=>$vo):?>
                <div class='imgst col-lg-4'>
                    <input type="hidden" name="images[]" id="image_<?=$ke+1?>" value="<?=$vo['activity_image']?>">
                    <img class="activity_image" src="<?=$vo['activity_image']?>" rel="<?=$ke+1?>"/>
                    <span class="glyphicon glyphicon-remove"></span>
                </div>
                <?php endforeach;endif;?>
			</div>
            <div align="center"  class="table table-bordered clearfix" style="float: left" >
                <h4>分享图片</h4>
                <div class='imgst' id="share_image">
                <?php if(!empty($share['image_url'])):?>
                    <img src="<?=$share['image_url']?>">
                <?php endif;?>
                </div>
            </div>
            <div align="center" id="upload_ing" style="display: none">
                <img src="/Images/loading2.gif"><h5><b>正在上传...</b></h5>
            </div>
        </div>
    </div>
    <br>
     <div class="form-group" align="left">
        <input type="button" class="btn btn-primary" onclick="editorContent()" value="提交">
         <input type="button" class="btn btn-primary" onclick="backList()" value="返回">
    </div>
    <?php ActiveForm::end(); ?>

    <form id="fileForm" action="/video/upload" method="post" enctype="multipart/form-data">
        <input type="hidden" name="_csrf" value="<?= Yii::$app->request->csrfToken ?>">
        <input type="file" name="upfile" accept="image/jpeg,image/png" style="display: none" id="upfile"
               onchange="actiImage(this.value)">
    </form>
    <form id="frm" action="#" method="post">
        <input type="hidden" id="x" name="x"/>
        <input type="hidden" id="y" name="y"/>
        <input type="hidden" id="w" name="w"/>
        <input type="hidden" id="h" name="h"/>
        <input type="hidden" id="f" name="f"/>
        <input type="hidden" id="t" name="t"/>
    </form>
</div>
</body>
<script type="text/javascript">
    $('.form_datetime').datetimepicker({
        language: 'zh-CN',
        weekStart: 1,
        todayBtn: 1,
        autoclose: 1,
        todayHighlight: 1,
        startView: 2,
        forceParse: 0,
        showMeridian: 1
    });
    $(function(){
        $('#images').on('click','span',function(){
            $(this).parent().remove();
        })
    });
    $("#editor").Editor({"print": false, "insert_table": true});
    $("#editor").Editor("setText", '<?=$model["activity_centent"] ?>');
    function editorContent() {
        var te = $("#editor").Editor("getText");
        $("#activity_centent").val(te);
        $("#activity-form").submit();
    }

</script>
</html>