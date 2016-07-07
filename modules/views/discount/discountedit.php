<?php
/**
 * Created by PhpStorm.
 * User: WWW
 * Date: 2015-12-09
 * Time: 16:58
 */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <?= Html::cssFile('@web/css/bootstrap.min.css') ?>
    <?= Html::cssFile('@web/css/bootstrap-datetimepicker.min.css') ?>
    <!--裁剪-->
    <?= Html::cssFile('@web/css/jquery.Jcrop.css') ?>

    <?= Html::jsFile('@web/Js/jquery.js') ?>
    <?= Html::jsFile('@web/Js/bootstrap.js') ?>
    <?= Html::jsFile('@web/Js/bootstrap-datetimepicker.js') ?>
    <?= Html::jsFile('@web/Js/locales/bootstrap-datetimepicker.zh-CN.js') ?>
    <!--裁剪-->
    <?= Html::jsFile('@web/Js/jquery.form.js') ?>
    <?= Html::jsFile('@web/Js/jquery.Jcrop.min.js') ?>
</head>
<script>
    var url = "http://" + window.location.host;
    var g_oJCrop = null;
    function openFile() {
        $("#upfile").click();
        return;
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
            if (g_oJCrop != null) {
                g_oJCrop.destroy();
                g_oJCrop = null;
            }
            $("#upload_ing").show();
            $("#orgimg_show").hide();
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
                    $("#orgimg_show").attr('src', data);
                    $("#upload_ing").hide();
                    $("#orgimg_show").show();
                    //初始化裁剪区
                    $("#orgimg_show").Jcrop({
                            onChange: updatePreview,
                            onSelect: updatePreview,
                            aspectRatio: 0,
                            allowSelect: false,
                            allowResize: false
                        },
                        function () {
                            var targ_w = 720;
                            var targ_h = 100;
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
        var w = 10;
        if ($("#upfile").val() == "") {
            alert("亲！还没有选择图片哦！");
            return false;
        }

        if (w > 0) {

            $("#upload_ing").show();
            $("#orgimg_show").hide();
            // 为表单绑定异步上传的事件
            $("#fileForm").ajaxSubmit({
                url: url + "/admin/video/uploadoss",
                secureuri: false,
                fileElementId: 'upfile',
                dataType: 'text',
                success: function (data) {
                    $("#discount_image").val(data);
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

    function tempImage(url) {
        $("#discount_image").val(url);
    }
</script>
<body>
<div style="margin-left: 5%;margin-top: 1%;margin-right: 5%">
    <?php $form = ActiveForm::begin([
        'id' => 'coursediscount-form',
        'action' => 'savediscount',
        'method' => 'post']); ?>

    <div class="row">
        <div class="col-lg-5">
            <input type="hidden" name="id" id="id" value="<?= $model["id"] ?>">
            <?= $form->field($model, 'discount_describe')->label("优惠描述") ?>
            <label>折扣类型</label><br>
            <select name="discount_pattern" id="discount_pattern" class="form-control">
                <option value="1"
                    <?php if (1 == $model["discount_pattern"]) { ?>
                        selected="selected" <?php } ?> >按金额
                </option>
                <option value="2"
                    <?php if (2 == $model["discount_pattern"]) { ?>
                        selected="selected" <?php } ?> >按比例
                </option>
            </select>

            <?= $form->field($model, 'discount_condition')->label("折扣条件") ?>
            <?= $form->field($model, 'discount_value')->label("折扣值") ?>

            <label>开始时间</label>

            <div class="input-group date form_datetime col-md-8" id="form_datetime"
                 data-date-format="yyyy-mm-dd hh:ii">
                <input class="form-control" size="16" type="text" name="start_time"
                       value="<?= $model["start_time"] ?>" readonly>
                <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
            </div>
            <label>结束时间</label>

            <div class="input-group date form_datetime col-md-8" id="form_datetime"
                 data-date-format="yyyy-mm-dd hh:ii">
                <input class="form-control" size="16" type="text" name="end_time"
                       value="<?= $model["end_time"] ?>" readonly>
                <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
            </div>

            <div>
                <label>优惠图片</label><br>
                <input type="text" id="discount_image" name="discount_image" value="<?= $model["discount_image"] ?>">
                <input type="button" class="btn btn-primary" onclick="openFile()" value="图片...">
                <input type="button" class="btn btn-primary" onclick="cutpicimg()" value="上传">
                <!--<input type="button" class="btn btn-primary" onclick="cutpicimg()" value="确认裁剪(1080*570)"/>-->
            </div>
            <br>
        </div>
        <div class="col-lg-7">
            <div>
                <img id="orgimg_show"
                    <?php if (strncasecmp($model["discount_image"], "http", 4) == 0) { ?>
                        src="<?= $model["discount_image"] ?>"
                    <?php } else { ?>
                        src="http://image.happycity777.com<?= $model["discount_image"] ?>"
                    <?php } ?>
                >
                <img class="img-rounded" id="jcorpvideoimg" style="display: block">

                <div align="center" id="upload_ing" style="display: none">
                    <img src="/Images/loading2.gif"><h5><b>正在上传...</b></h5>
                </div>
            </div>
        </div>
    </div>

    <br>

    <div class="form-group" align="left">
        <input type="submit" class="btn btn-primary" value="提交">
        <input type="button" class="btn btn-primary" onclick="backlist()"
               value="返回">
    </div>
    <?php ActiveForm::end(); ?>


    <form id="fileForm" action="/video/upload" method="post" enctype="multipart/form-data">
        <input type="hidden" name="_csrf" value="<?= Yii::$app->request->csrfToken ?>">
        <input type="file" name="upfile" accept="image/jpeg,image/png" style="display: none" id="upfile"
               onchange="tempImage(this.value)">
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

    function backlist() {
        window.location = '/admin/discount/discountlist';
    }

</script>
</body>
</html>