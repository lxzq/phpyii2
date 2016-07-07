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
    <?= Html::cssFile('@web/css/bootstrap-datetimepicker.min.css') ?>
    <?=Html::jsFile('@web/Js/jquery-1.10.2.js')?>

    <?= Html::jsFile('@web/Js/bootstrap.js') ?>
    <?= Html::jsFile('@web/Js/bootstrap-datetimepicker.js') ?>
    <?= Html::jsFile('@web/Js/locales/bootstrap-datetimepicker.zh-CN.js') ?>

</head>
<script >
    function backList (){
        window.location = "/admin/coursedis/list";
     }
</script>
<body>
<div style="margin-left: 8%;margin-top: 1%">
    <div class="row">
        <div class="col-lg-6">
            <?php $form = ActiveForm::begin(
                ['id' => 'video-form',
                    'action' => 'save',
                    'method'=> 'post'
                ]); ?>
            <?= $form->field($model, 'title')->label("优惠标题") ?>
            <label>优惠开始日期</label>
            <div class="input-group date form_datetime col-md-8" id="form_datetime"
                 data-date-format="yyyy-mm-dd">
                <input class="form-control" size="10" type="text" name="startDate"
                       value="<?= $model["startDate"] ?>" readonly>
                <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
            </div>
            <br>
            <label>优惠截止日期</label>
            <div class="input-group date form_datetime col-md-8" id="form_datetime"
                 data-date-format="yyyy-mm-dd">
                <input class="form-control" size="10" type="text" name="endDate"
                       value="<?= $model["endDate"] ?>" readonly>
                <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
            </div>
            <br>
             <label>优惠课程列表</label>
            <br>
            <table class="table table-bordered table-hover">
                <input type="button" class="btn btn-success" value="添加一行" onclick="add()">&nbsp;&nbsp;
                <input type="button" class="btn btn-danger" value="删除一行" onclick="del()">
                <br><br>
            <tbody id="tablel">
                <tr class="info">
                    <td align="center" width="50%" >优惠课程</td>
                    <td align="center" width="10%">课时</td>
                    <td align="center" width="20%">优惠原价</td>
                    <td align="center" width="20%">优惠折扣价</td>
               </tr>
            </tbody>
            </table>
            <input type="hidden" name="_csrf" value="<?= Yii::$app->request->csrfToken ?>">
            <br>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="提交">
                <input type="button" class="btn btn-primary" onclick="backList()" value="返回">
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
</body>
<script type="text/javascript">
    $('.form_datetime').datetimepicker({
        language: 'zh-CN',
        weekStart: 0,
        todayBtn: 1,
        autoclose: true,
        todayHighlight: 1,
        viewSelect : 'month',
        startView: 2,
        forceParse: 0,
        showMeridian: 1,
        format : 'yyyy-mm-dd'
    });
    var courseData = '<?=$data?>';
    var data = eval(courseData);
    function add(){
        var tr = $("<tr/>");
        tr.addClass("active");

        var td1 = $("<td/>");
        var course = $("<select/>");
        course.attr("name","course[]");
        for(var i = 0 ; i < data.length ; i++ ){
            var op = $("<option value=\""+data[i].id+"\">"+data[i].name+"</option>")
            op.appendTo(course);
        }
        course.addClass("form-control");
        course.appendTo(td1);

        var td2 = $("<td/>");
        var num = $("<input/>");
        num.val(0);
        num.attr("name","num[]");
        num.addClass("form-control");
        num.appendTo(td2);

        var td3 = $("<td/>");
        var price1 = $("<input/>");
        price1.val(0);
        price1.attr("name","price1[]");
        price1.addClass("form-control");
        price1.appendTo(td3);

        var td4 = $("<td/>");
        var price2 = $("<input/>");
        price2.val(0);
        price2.attr("name","price2[]");
        price2.addClass("form-control");
        price2.appendTo(td4);

        td1.appendTo(tr);
        td2.appendTo(tr);
        td3.appendTo(tr);
        td4.appendTo(tr);
        tr.appendTo("#tablel");

    }

    function del(){
        var size = $("#tablel tr").size();
       $("#tablel tr").each(function(index,ele){
            if(size -1  == index && index > 0){
                $(this).remove();
            }
       });
    }

</script>
</html>