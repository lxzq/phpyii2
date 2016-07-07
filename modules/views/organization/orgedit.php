<?php
/**
 * Created by PhpStorm.
 * User: WWW
 * Date: 2015-12-09
 * Time: 10:28
 */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

if (empty($model["id"])) {
    $this->title = '机构新增';
} else {
    $this->title = '机构编辑';
}
$this->params['breadcrumbs'][] = $this->title;
?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title>机构</title>

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
    var g_oJCrop = null;
    function openFile() {
        $("#upfile").click();
        return;
    }
    function backList(){
//        window.location =url + '/admin/organization/orglist';
        window.history.back();
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
            $("#orgimg_show").hide();
            // 为表单绑定异步上传的事件
            $("#frm").ajaxSubmit({
                url: url + "/admin/video/cutpic",
                secureuri: false,
                dataType: 'text',
                success: function (data) {
                    $("#logo").val(data);
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
        $("#logo").val(url);
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
            <?= $form->field($model, 'name')->label("机构名称") ?>
            <?= $form->field($model, 'shopIds')->checkboxList($shops) ->label("签约店铺") ?>
            <label>机构图片</label><br>
            <input type="text" id="logo" name="logo" value="<?= $model["logo"] ?>" height="100px">
            <input type="button" class="btn btn-primary" onclick="openFile()" value="图片...">
            <input type="button" class="btn btn-primary" onclick="fileupload()" value="上传">
            <input type="button" class="btn btn-primary" onclick="cutpicimg()" value="确认裁剪(1080*570)"/>
            <br>
            <label>机构详情</label><br>
            <textarea id="editor"></textarea>
        </div>

        <div class="col-lg-7">
            <div>
                <img id="orgimg_show"
                    <?php if (strncasecmp($model["logo"], "http", 4) == 0) { ?>
                        src="<?= $model["logo"] ?>"
                    <?php } else { ?>
                        src="http://image.happycity777.com<?= $model["logo"] ?>"
                    <?php } ?>
                >
                <img class="img-rounded" id="jcorpvideoimg" style="display: block">
            </div>
            <div align="center" id="upload_ing" style="display: none">
                <img src="/Images/loading2.gif"><h5><b>正在上传...</b></h5>
            </div>

        </div>
    </div>
     <input type="hidden" name="id" id="id" value="<?= $model["id"] ?>">
    <input type="hidden" name="notes" id="notes">
    <br>
    <div class="form-group" align="left">
        <input type="button" class="btn btn-primary" onclick="submitOrg()" value="提交">
        <input type="button" class="btn btn-primary" onclick="backList()" value="返回">
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
    $("#editor").Editor({"print": false, "insert_table": false});
    $("#editor").Editor("setText", '<?= $model["notes"] ?>');
    function submitOrg() {
        var te = $("#editor").Editor("getText");
        $("#notes").val(te)
        $("#organization-form").submit();
    }
</script>
</body>
</html>