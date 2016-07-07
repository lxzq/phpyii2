<?php


use yii\helpers\Html;
use yii\widgets\LinkPager;

?>
<!DOCTYPE HTML>
<html lang="zh-CN">
<head>
	<meta charset="UTF-8">
    <?= Html::cssFile('@web/css/bootstrap.min.css') ?>
    <?= Html::cssFile('@web/font-awesome/css/font-awesome.min.css') ?>
    <?= Html::cssFile('@web/weixin/css/base.css') ?>
    <?= Html::cssFile('@web/weixin/css/module.css') ?>

    <?= Html::cssFile('@web/weixin/css/weixin.css') ?>
    <?= Html::cssFile('@web/css/emoji.css') ?>
    <?=Html::jsFile('@web/Js/jquery-1.10.2.js')?>
    <?= Html::jsFile('@web/uploadify/jquery.uploadify.min.js') ?>
    <?= Html::jsFile('@web/zclip/ZeroClipboard.min.js') ?>
    <?= Html::jsFile('@web/weixin/js/dialog.js') ?>
    <?= Html::jsFile('@web/weixin/js/admin_common.js') ?>
    <?= Html::jsFile('@web/weixin/js/admin_image.js') ?>
    <?= Html::jsFile('@web/masonry/masonry.pkgd.min.js') ?>
    <?= Html::jsFile('@web/js/jquery.dragsort-0.5.2.min.js') ?>

</head>
<body>
	<!-- 头部 -->
	<!-- 提示 -->
<div id="top-alert" class="top-alert-tips alert-error" style="display: none;">
  <a class="close" href="javascript:;"><b class="fa fa-times-circle"></b></a>
  <div class="alert-content"></div>
</div>

	<!-- /头部 -->

	<!-- 主体 -->

<div id="main-container" class="admin_container">
  <div class="main_body">
      <div class="span9 page_message">
          <section id="contents">
                        <div class="table-bar">
                  <div class="fl">
                      <div class="tools"> <a href="javascript:void(0);" onclick="add()" class="btn add_app_info">新增</a> &nbsp;
                          <a href="javascript:void(0);" class="btn change_app_info">修改</a> &nbsp;
                          <a href="javascript:void(0);" class="btn del_app_info">删除</a> &nbsp;
                      </div>
                  </div>

              </div>
              <!-- 数据列表 -->
              <div class="data-table">
                      <table cellspacing="1" class="table table-bordered ">
                          <!-- 表头 -->
                          <thead>
                          <tr>
                              <th class="row-selected row-selected"> <input type="checkbox" class="check-all regular-checkbox" id="checkAll"><label for="checkAll"></label></th>
                              <th>编号</th>
                              <th>应用编码</th>
                              <th>应用名称</th>
                              <th>应用描述</th>
                              <th>应用图标</th>
                              <th>是否显示</th>
                          </tr>
                          </thead>

                          <!-- 列表 -->
                          <tbody>
                          <?php foreach( $list as  $row): ?>
                              <tr>
                                  <td><input type="checkbox" id="check_<?= $row['id'] ?>" name="ids[]" value="<?=$row['id']?>" class="ids regular-checkbox">
                                      <label for="check_<?= $row['id'] ?>"></label></td>
                                  <td><?= $row['id'] ?></td>
                                  <td><?= $row['app_code'] ?></td>
                                  <td><?= $row['app_name'] ?></td>
                                  <td><?= $row['app_desc'] ?></td>
                                  <td><?= $row['app_icon'] ?></td>
                                  <td><?php if(1==$row['is_show']){echo '显示';}else{echo '隐藏';} ?></td>

                              </tr>
                          <?php endforeach; ?>
                          </tbody>
                      </table>
              </div>
          </section>
      </div>

      <script type="text/javascript">

          $('.add_app_info').click(function(){
              window.location = "/admin/appinfo/edit";
          });

          //修改菜单
          $('.change_app_info').click(function(){
              query = $('.ids').serialize();
              count=$('.ids:checked').length;
              menuid=$('.ids:checked').val();
              if(count>1)
              {
                  alert('最多只能选择一个菜单');
                  return false;
              }
              if(query==""){
                  alert('请选择需要修改的菜单');
                  return false;
              }
              window.location = "/admin/appinfo/edit?id="+menuid;
          })
          $('.del_app_info').click(function()
          {
              query = $('.ids').serialize();
              if(query==""){
                  alert('请选择需要删除的菜单');
                  return false;
              }
              if (window.confirm('确定要删除吗?')) {
                  window.location = "/admin/appinfo/delete?" + query;
                  return true;
              } else {
                  return false;
              }
          });
      </script>
</body>
</html>