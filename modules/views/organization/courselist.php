<?php
/**
 * Created by PhpStorm.
 * User: WWW
 * Date: 2015-12-09
 * Time: 16:58
 */

use app\models\OrgInfo;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Alert;
use yii\widgets\LinkPager;

?>
<!doctype html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title>课程管理</title>
    <?= Html::cssFile('@web/css/bootstrap.min.css') ?>
    <?= Html::jsFile('@web/Js/jquery.js') ?>
    <?= Html::jsFile('@web/Js/bootstrap.js') ?>
</head>
<body>
<div style="margin:1%">
    <script>
        function add() {
            window.location = "/admin/organization/courseedit";
        }
        function edit(id) {
            window.location = "/admin/organization/courseedit?id=" + id;
        }
        function classlist(id) {
            window.location = "/admin/organization/classlist?course_id=" + id;
        }
        function del(id) {
            if (window.confirm('确定要删除吗?')) {
                window.location = "/admin/organization/delcourse?id=" + id;
                return true;
            } else {
                return false;
            }
        }
        function searchOrg() {
            document.getElementById("searchform").submit();
        }
        function setlunbo(id, num) {
            window.location = "/admin/organization/setlunbo?id=" + id + "&lunbo=" + num;
        }
    </script>

    <div class="row">
        <div class="col-lg-11">
            <form class="form-inline" action="courselist" method="post" role="form" id="searchform">
                <input type="hidden" name="_csrf" value="<?= Yii::$app->request->csrfToken ?>">

                <div class="form-group">
                    <input type="text" class="form-control" name="name" placeholder="课程名称">
                    <select name="org" class="form-control" placeholder="机构名称">
                        <option value="">全部机构</option>
                        <?php foreach ($orgs as $entity) { ?>
                            <option value="<?= $entity["id"] ?>" <?php if($entity["id"] == $orgId){ ?> selected="selected" <?php } ?>><?= $entity["name"] ?></option>
                        <?php } ?>
                    </select>
                    <input type="button" class="btn btn-primary" onclick="searchOrg()" value="查询">
                    <input type="button" class="btn btn-primary" onclick="add()" value="新增">

                </div>
            </form>
        </div>
    </div>
    <br>
    <table class="table table-bordered table-hover">
        <tr class="info">
            <td align="center">课程图片</td>
            <td align="center">所属机构</td>
            <td align="center">课程名称</td>

            <td align="center">课时价格</td>
            <td align="center">上课老师</td>
            <td align="center">上课时间</td>
         <!--   <td align="center">课程简介</td>-->
            <td align="center">操作</td>
        </tr>
        <tbody>
        <?php foreach ($courselist as $info) { ?>
            <tr class="active">
                <td  align="center">
                    <img src="<?= $info["logo"] ?>"  width="80px" height="50px">
                </td>
                <td  align="center"><?php echo $info["org"]['name'] ?></td>
                <td  align="center"><?php echo $info["name"] ?></td>

                <td  align="center">
                    <?php
                    $index = 0;
                    foreach($info['price'] as $price){
                        echo '【' . $price['course_nums'] . '课时】【原价】<font color="#a9a9a9">¥'.$price['org_price'].'</font>【折扣价】<font color="#FF6600">¥'
                            .$price['discount_price'].'</font><br>';
                        $index++;
                        if($index == 2){
                            echo '......';
                            break;
                        }
                    }
                    ?>
                </td>
                <td  align="center">
                    <?php
                     foreach($info['teacher'] as $teacher){
                         echo $teacher['name'] . '、';
                    }
                    ?>
                </td>
                <td  align="center"><?php echo $info["class_time"] ?></td>
               <!-- <td class="col-md-2" align="center"><?php
/*                   if (mb_strlen($info["describe"], "utf-8") > 15)
                        echo mb_substr($info["describe"], 0, 15, 'utf-8') . '...';
                    else echo $info["describe"] */?>
                    </td>-->
                <td align="center">
                    <button class="btn btn-primary" onclick="pricelist(<?= $info["id"] ?>);">
                        价格
                    </button>
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
<script type="text/javascript">
    function pricelist(course_id) {
        window.location = "/admin/course-manager/price-manager-list?course_id=" + course_id ;
    }
</script>
