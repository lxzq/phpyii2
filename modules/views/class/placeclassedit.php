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
    $this->title = '分班新增';
} else {
    $this->title = '分班编辑';
}
$this->params['breadcrumbs'][] = $this->title;
?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title>分班</title>

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

    function backList(){
//        window.location =url + '/admin/organization/orglist';
        window.history.back();
    }
    function addTeacher(courseId){
        $.ajax({
            url:'/admin/class/ajax-teacher',// 跳转到 action
            data :{courseId:courseId},
            type:'get',
            dataType:"json",
            success:function(data) {
                $("#teacher").empty();
                for (var i = 0; i < data.length; i++) {
                    var id = data[i].id;
                    var name = data[i].name;
                    var  html = '<option value="'+id+'">'+name+'</option>';
                    $("#teacher").append(html);
                }
            },
            error : function(data) {
            }
        });
    }
</script>

<body>
<div style="margin-left: 5%;margin-top: 1%;margin-right: 5%">

    <?php $form = ActiveForm::begin([
        'id' => 'courseplaceclass-form',
       // 'action' => 'save',
        'method' => 'post']); ?>

    <div class="row">
        <div class="col-lg-5">
            <input type="hidden" name="id" id="id" value="<?= $model["id"] ?>">
            <?= $form->field($model, 'name')->label("班级名称") ?>
             <label>班级课程</label><br>
            <select name="course_id" id="class_id" class="form-control" placeholder="课程" onchange="addTeacher(this.value)">
                <option value="0">--选择课程--</option>
                <?php foreach ($course as $value){?>
                <option value="<?=$value['id']?>"  <?php if($value['id'] == $model['course_id'] ){ ?>  selected="selected" <?php }?>  ><?=$value['name']?></option>
                <?php }?>
            </select>
            <label>班级老师</label><br>
            <select name="teacher" id="teacher" class="form-control">
               <?php if(!empty($teacherList)) {
                   foreach($teacherList as $teacher){
                   ?>
                   <option value="<?=$teacher['id']?>" <?php if($teacher['id'] == $model['teacher'] ){ ?>  selected="selected" <?php }?>  ><?=$teacher['name']?></option>
               <?php } }?>
            </select>
            <?= $form->field($model, 'notes')->textarea()->label("备注") ?>
         </div>
    </div>
    <br>
    <div class="form-group" align="left">
        <input type="submit" class="btn btn-primary" value="提交">
        <input type="button" class="btn btn-primary" onclick="backList()" value="返回">
    </div>
    <?php ActiveForm::end(); ?>

</div>
</body>
</html>