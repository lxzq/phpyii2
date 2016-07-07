<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */



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
       // window.location = "/admin/child/list";
        window.history.back();
    }
</script>
<body>
<div style="margin-left: 8%;margin-top: 1%">
    <div class="row">
        <div class="col-lg-6">
            <?php $form = ActiveForm::begin(
                ['id' => 'video-form',
                    'action' => 'saveclass',
                    'method'=> 'post'
                ]); ?>
            <label>申报课程</label><br>
            <table class="table table-bordered table-hover">
                <input type="button" class="btn btn-success" id="add_course" value="添加课程" onclick="addCourse()">&nbsp;&nbsp;
                <input type="button" class="btn btn-danger" id="del_course" value="删除课程" onclick="delCourse()">
                <br><br>
                <tbody id="tablel">
                <tr class="info">
                    <td align="center" width="50%" >课程名称</td>
                    <td align="center" width="50%">课时/价格</td>
                </tr>
                </tbody>
            </table>
            <label>折扣优惠</label><br>
             <?php
              $index = 0 ;
               foreach($disOption as $op){ ?>
               <div class="disoption<?=$op["type"]?>">
               <input type="checkbox"   value="<?=$op["type"]?>|<?=$op["option"]?>|<?=$op["option_val"]?>" name="option" onchange="changePrice()" class="disoption<?=$op["type"]?>">
                <?=$op["option_name"]?>
                    <?php if($op["option"] == 1) echo '(立减'.$op["option_val"] . ')'; else if($op["option"] == 2)  echo '('.$op["option_val"] .'折)' ; ?>
                 </div>
            <?php $index++ ;} ?>
            <br><label>其他优惠</label>
            <input type="number" id="yhPrice">元
            <input type="button" class="btn btn-primary" onclick="jsPrice()" value="立减">

            <br><br><label>实付金额：</label>&nbsp;&nbsp;<font color="#FF6600" id="tempPrice">¥ <?=$model["price"]?> </font>
          <br><label>报名时间</label><br>
            <div class="input-group date form_datetime col-md-8" id="form_datetime"
                 data-date-format="yyyy-mm-dd HH:mm:ss">
                <input class="form-control" size="10" type="text" name="add_date"
                       value="<?=$model["addDate"] ?>" readonly>
                <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
            </div>
            <br>
            <?= $form->field($model, 'receiptId')->textInput(['maxlength'=>50])->label("收据编号") ?>
            <?= $form->field($model, 'moneyType')->radioList(['1'=>'全款','2'=>'定金'])->label('结算方式') ?>
            <?= $form->field($model, 'payType')->radioList(['1'=>'现金','2'=>'银联','3'=>'信用卡','4'=>'微信支付','5'=>'支付宝'])->label("付款方式") ?>
            <?/*= $form->field($model, 'yy')->textInput(['maxlength'=>100])->label("折扣/礼品/优惠") */?><!--
            <?/*= $form->field($model, 'classAp')->textInput(['maxlength'=>100])->label("班级安排") */?>
            --><?/*= $form->field($model, 'classGw')->textInput(['maxlength'=>100])->label("课程顾问") */?>
            <?= $form->field($model, 'notes')->textarea(['maxlength'=>100])->label("备注") ?>

            <input type="hidden" name="price" id="price"  value="<?=$model["price"]?>">
            <input type="hidden" name="id" value="<?=$childId ?>">
            <input type="hidden" name="userId" value="<?=$userId ?>">
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
        weekStart: 1,
        todayBtn: 1,
        autoclose: 1,
        todayHighlight: 1,
        startView: 2,
        forceParse: 0,
        showMeridian: 1
    });
    $('.disoption2').hide();
    var courseData = '<?=$courseList?>';
    var courseList = eval(courseData);
</script>
<script type="text/javascript" src="/Js/class.js?v=1"></script>
</html>