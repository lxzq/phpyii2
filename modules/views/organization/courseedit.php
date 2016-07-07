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
    <!--编辑器css-->
    <?= Html::cssFile('@web/css/bootstrap.min.css') ?>
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
<body>
<div style="margin-left: 5%;margin-top: 1%;margin-right: 5%">
    <?php $form = ActiveForm::begin([
        'id' => 'course-form',
        'action' => 'savecourse',
        'method' => 'post']); ?>

    <div class="row">
        <div class="col-lg-5">
            <?= $form->field($model, 'name')->label("课程名称") ?>
            <div>
                <label>课程机构</label><br>
                <select name="org" id="org" class="form-control" placeholder="机构名称"
               onchange="addTeacher(this.value)">
                <option value="0">--请选择--</option>
                <?php foreach ($orgs as $entity) { ?>
                    <option
                        value="<?= $entity["id"] ?> " <?php if ($entity["id"] == $model["org_id"]) { ?> selected="selected" <?php } ?> ><?= $entity["name"] ?></option>
                <?php } ?>
                </select>
            </div>
             <div >
                <label>课程老师</label><br>
                 <div id="teacherList">
                     <?php if(!empty($teacherList)){
                         foreach($teacherList as $teacher){
                         ?>
                         <input type="checkbox" name="teacher[]"
                                  <?php if(!empty($ct)) {
                                   foreach($ct as $val){
                                   if($val['teacher_id'] == $teacher['id'] )   {
                                   ?>
                                 checked="checked"
                                <?php } } }?>
                                value="<?=$teacher['id']?>">&nbsp;<?=$teacher['name']?>
                     <?php } }?>
                 </div>
             </div>
            <div>
                <label>课程图片</label><br>
                <input type="text" id="logo" name="logo" value="<?= $model["logo"] ?>" size="50">
                <input type="button" class="btn btn-primary" onclick="openFile()" value="图片...">
                <input type="button" class="btn btn-primary" onclick="fileupload()" value="上传">
            </div>
            <?= $form->field($model, 'class_time')->label("上课时间") ?>
            <?= $form->field($model, 'describe')->textarea(['rows' => 3])->label("课程简介") ?>

        </div>
        <div class="col-lg-7">
            <div>
                <img id="orgimg_show"
                    <?php if (strncasecmp($model["logo"], "http", 4) == 0) { ?>
                        src="<?= $model["logo"] ?>"
                    <?php } else { ?>
                        src="http://image.happycity777.com/<?= $model["logo"] ?>"
                    <?php } ?>
                >
                <img class="img-rounded" id="jcorpvideoimg" style="display: block">
                <div align="center" id="upload_ing" style="display: none">
                    <img src="/Images/loading2.gif"><h5><b>正在上传...</b></h5>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" name="id" id="id" value="<?= $model["id"] ?>">
    <input type="hidden" name="notes" id="notes">
    <br>
    <div class="form-group" align="left">
        <input type="submit" class="btn btn-primary"  value="提交">
        <input type="button" class="btn btn-primary" onclick="backlist()" value="返回">
    </div>
    <?php ActiveForm::end(); ?>
    <form id="fileForm" action="/video/upload" method="post" enctype="multipart/form-data">
        <input type="hidden" name="_csrf" value="<?= Yii::$app->request->csrfToken ?>">
        <input type="file" name="upfile" accept="image/jpeg,image/png" style="display: none" id="upfile"
               onchange="tempImage(this.value)">
    </form>
 </div>
<script type="text/javascript" src="/Js/course.js"></script>
</body>
</html>
