<?php
/**
 * Created by PhpStorm.
 * User: WWW
 * Date: 2015-12-09
 * Time: 10:26
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
    <title>机构管理</title>
    <?= Html::cssFile('@web/css/bootstrap.min.css') ?>
    <?= Html::jsFile('@web/Js/jquery.js') ?>
    <?= Html::jsFile('@web/Js/bootstrap.js') ?>
</head>
<body>
<div style="margin:1%">
    <script>
        function add() {
            window.location = "/admin/organization/orgedit";
        }
        function edit(id) {
            window.location = "/admin/organization/orgedit?id=" + id;
        }
        function del(id) {
            if (window.confirm('确定要删除吗?')) {
                window.location = "/admin/organization/del?id=" + id;
                return true;
            } else {
                return false;
            }
        }

        function searchOrg() {
            document.getElementById("searchform").submit();
        }
    </script>

    <div class="row">
        <div class="col-lg-12">
            <form class="form-inline" action="orglist" method="post" role="form" id="searchform">
                <input type="hidden" name="_csrf" value="<?= Yii::$app->request->csrfToken ?>">

                <div class="form-group">
                    <input type="text" class="form-control" name="name" placeholder="机构名称">
                    <input type="button" class="btn btn-primary" onclick="searchOrg()" value="查询">
                    <input type="button" class="btn btn-primary" style="margin-right: 10px" onclick="add()" value="新增">
                </div>
            </form>

        </div>

    </div>
    <br>

    <table class="table table-bordered table-hover">
        <tr class="info">
            <td align="center">机构名称</td>
            <!--<td align="center">机构图片</td>-->
            <td align="left">签约店铺</td>
            <td align="center">操作</td>
        </tr>
        <tbody>
        <?php foreach ($orglist as $info) { ?>
            <tr class="active">
                 <td class="col-md-3" align="center"><?php echo $info["name"] ?></td>
               <!-- <td class="col-md-2" align="center"><img
                        <?php /*if (strncasecmp($info["logo"], "http", 4) == 0) { */?>
                            src="<?/*= $info["logo"] */?>"
                        <?php /*} else { */?>
                            src="http://image.happycity777.com<?/*= $info["logo"] */?>"
                        <?php /*} */?>
                        width="100px" height="50px"></td>-->

                <td class="col-md-3" align="left">
                   <?php
                    $checkshops = \app\models\OrgShopInfo::find()->where(['=','org_id',$info["id"]])->all();
                    $index = 0;
                    foreach($checkshops as $checkshop){
                        $checkshopid = $checkshop["shop_id"];
                        foreach($shops as $shopname){
                            if($checkshopid == $shopname["id"]){
                                echo '【'.$shopname["name"].'】';
                                if($index > 0 && $index%2 == 0){
                                 echo '<br>';
                                }
                                 $index++;
                            }
                        }
                    }
                  ?>
                </td>
                <td class="col-md-3" align="center">
                    <button class="btn btn-primary" onclick="edit(<?= $info["id"] ?>);">编辑</button>
                    <button class="btn btn-danger" onclick="del(<?= $info["id"] ?>)">删除</button>
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