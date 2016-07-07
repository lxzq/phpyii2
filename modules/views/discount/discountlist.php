<?php
/**
 * Created by PhpStorm.
 * User: WWW
 * Date: 2015-12-09
 * Time: 16:58
 */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Alert;
use yii\widgets\LinkPager;

?>
<!doctype html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title>优惠管理</title>
    <?= Html::cssFile('@web/css/bootstrap.min.css') ?>
    <?= Html::jsFile('@web/Js/jquery.js') ?>
    <?= Html::jsFile('@web/Js/bootstrap.js') ?>
</head>
<body>
<div style="margin:1%">
    <script>
        function add() {
            window.location = "/admin/discount/discountedit";
        }
        function edit(id) {
            window.location = "/admin/discount/discountedit?id=" + id;
        }
        function del(id) {
            if (window.confirm('确定要删除吗?')) {
                window.location = "/admin/discount/deldiscount?id=" + id;
                return true;
            } else {
                return false;
            }
        }
    </script>

    <div class="row">
        <div class="col-lg-11">
            <input type="button" class="btn btn-primary" onclick="add()" value="新增">
        </div>
    </div>
    <br>
    <table class="table table-bordered table-hover">
        <tr class="info">

            <td align="center">优惠描述</td>
            <td align="center">图片</td>
            <td align="center">折扣方式</td>
            <td align="center">折扣条件</td>
            <td align="center">折扣值</td>
            <td align="center">开始时间</td>
            <td align="center">结束时间</td>
            <td align="center">操作</td>
        </tr>
        <tbody>
        <?php foreach ($discountlist as $info) { ?>
            <tr class="active">

                <td class="col-md-2" align="center"><?php echo $info["discount_describe"] ?></td>
                <td class="col-md-1" align="center">
                    <img id="orgimg_show" width="100px"
                        <?php if (strncasecmp($info["discount_image"], "http", 4) == 0) { ?>
                            src="<?= $info["discount_image"] ?>"
                        <?php } else { ?>
                            src="http://image.happycity777.com<?= $info["discount_image"] ?>"
                        <?php } ?>
                    ></td>
                <td class="col-md-1" align="center"><?php $type = $info["discount_pattern"];
                    if(1 == $type){
                        echo "按金额";
                    }else if(2 == $type){
                        echo "按比例";
                    }else{
                        echo "未知";
                    }?></td>
                <td class="col-md-1" align="center"><?php echo $info["discount_condition"] ?></td>
                <td class="col-md-1" align="center"><?php echo $info["discount_value"] ?></td>
                <td class="col-md-2" align="center"><?php echo $info["start_time"] ?></td>
                <td class="col-md-2" align="center"><?php echo $info["end_time"] ?></td>
                <td class="col-md-2" align="center">
                    <button class="btn btn-primary"
                            onclick="edit(<?= $info["id"] ?>);">编辑
                    </button>

                    <button class="btn btn-danger"
                            onclick="del(<?= $info["id"] ?>)">删除
                    </button>
                </td>
            </tr>
            <?php
        } ?>
        </tbody>
    </table>
    <div class="row">
        <div class="col-lg-12" align="right">
            <?= LinkPager::widget(['pagination' => $pages]); ?>
        </div>
    </div>
</div>
</body>
</html>