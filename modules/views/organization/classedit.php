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

    <?= Html::jsFile('@web/Js/jquery.js') ?>
    <?= Html::jsFile('@web/Js/bootstrap.js') ?>
    <?= Html::jsFile('@web/Js/bootstrap-datetimepicker.js') ?>
    <?= Html::jsFile('@web/Js/locales/bootstrap-datetimepicker.zh-CN.js') ?>
</head>

<body>
<div style="margin-left: 5%;margin-top: 1%;margin-right: 5%">
    <?php $form = ActiveForm::begin([
        'id' => 'courseclass-form',
        'action' => 'saveclass',
        'method' => 'post']); ?>

    <div class="row">
        <div class="col-lg-5">

            <input type="hidden" name="id" id="id" value="<?= $model["id"] ?>">

            <label>选择店铺</label><br>
            <select name="shop_id" id="shop_id" class="form-control" onclick="selectCourse(this.value,<?= $ctype ?>)">
                <option value="">请选择</option>
                <?php foreach ($shops as $entity) { ?>
                    <option
                        value="<?= $entity["id"] ?> "
                        <?php if ($entity["id"] == $shopId) { ?>
                            selected="selected" <?php } ?> >
                        <?= $entity["name"] ?>
                    </option>
                <?php } ?>
            </select><br>
            <label>选择课程</label><br>
            <!--<select name="course_id" id="course_id" class="form-control" onclick="selectCourse(this.value)">

                <?php /*foreach ($cinfo as $entity) { */ ?>
                    <option
                        value="<? /*= $entity["id"] */ ?> "
                        <?php /*if ($entity["id"] == $model["course_id"]) { */ ?>
                            selected="selected" <?php /*} */ ?> >
                        <? /*= $entity["name"] */ ?>
                    </option>
                <?php /*} */ ?>
            </select>-->
            <select name="course_id" id="course_id" class="form-control" placeholder="课程">
                <option value="">请选择</option>
            </select>
            <br>
            <?= $form->field($model, 'title')->label("课次标题") ?>
            <label>分成比例</label><br>
            甲方(七彩世界儿童乐园成长中心)
            <select name="pro_a">
                <?php  for($a = 0 ; $a <= 10 ; $a++) { ?>
                    <option value="<?=$a?>" <?php if($a == $model["proA"]) { ?> selected="selected" <?php } ?>> <?=$a?></option>
                <?php }?>
            </select>
            ：
            <select name="pro_b">
                <?php  for($h = 0 ; $h <= 10 ; $h++) { ?>
                    <option value="<?=$h?>" <?php if($h == $model["proB"]) { ?> selected="selected" <?php } ?>> <?=$h?></option>
                <?php }?>
            </select>
            乙方(教育机构)
            <br><br>
            <label>选择老师</label><br>
            <select name="teacher" id="teacher" class="form-control" placeholder="老师姓名">
                <option value="">请选择</option>
            </select>

            <br>
            <label>报名时间</label>

            <div class="input-group date form_datetime col-md-8" id="form_datetime"
                 data-date-format="yyyy-mm-dd hh:ii">
                <input class="form-control" size="16" type="text" name="start_time"
                       value="<?= $model["start_time"] ?>" readonly>
                <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
            </div>
            <label>截止时间</label>

            <div class="input-group date form_datetime col-md-8" id="form_datetime"
                 data-date-format="yyyy-mm-dd hh:ii">
                <input class="form-control" size="16" type="text" name="end_time"
                       value="<?= $model["end_time"] ?>" readonly>
                <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
            </div>
            <br>
            <?= $form->field($model, 'class_time')->textarea()->label("上课时间") ?>
            <?= $form->field($model, 'kaike_time')->textarea()->label("开课时间") ?>
            <?= $form->field($model, 'age')->textInput(['maxlength' => 100])->label("适合年龄") ?>
            <?= $form->field($model, 'max_nums')->label("最大人数") ?>
            <?= $form->field($model, 'min_nums')->label("最少人数") ?>
            <?= $form->field($model, 'material_price')->label("材料费（元）") ?>

        </div>

    </div>

    <br>

    <div class="form-group" align="left">
        <input type="submit" class="btn btn-primary" value="提交">
        <input type="button" class="btn btn-primary" onclick="backlist(<?= $model["course_id"] ?>)" value="返回">
    </div>
    <?php ActiveForm::end(); ?>

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
//        window.location = '/admin/organization/classlist';
        window.history.back();
    }

    function selectCourse(id, ctype) {
//        $("#org_id").val(id);
        var url = "/admin/organization/shopteacher";
        $.ajax({
            type: 'post',
            url: url,
            data: {id: id, ctype: ctype},
            dataType: 'json',
            success: function (data) {
                $("#teacher").empty();
                $("#course_id").empty();
                var teacher = data[1].teacherlist;
                var course = data[0].coslist;
//                var shop = data[1].shoplist;
                var courseid = '<?= $cinfo["id"]?>';
                var teacherid = '<?= $teacher["id"]?>';
                for (var i = 0; i < teacher.length; i++) {
                    var json = teacher[i];
                    var html = "<option value=" + json.id;
                    if (json.id == teacherid) {
                        html += " selected=selected ";
                    }
                    html += ">" + json.name + "</option>";
                    $("#teacher").append(html);
                }
                for (var i = 0; i < course.length; i++) {
                    var json = course[i];
                    var html = "<option value=" + json.id;
                    if (json.id == courseid) {
                        html += " selected=selected ";
                    }
                    html += ">" + json.name + "</option>";
                    $("#course_id").append(html);
                }
            },
            error: function () {
                alert('错误');
            }
        })
    }
</script>
</body>

<script>

    var shop_id = $("#shop_id").val();
    selectCourse(shop_id, '<?= $ctype?>');

</script>

</html>