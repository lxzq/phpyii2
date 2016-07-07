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
    <?=Html::jsFile('@web/Js/bootstrap.min.js')?>
    <?= Html::jsFile('@web/weixin/js/admin_common.js') ?>


</head>
<body>
<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">设置角色</h4>
            </div>
            <div class="modal-body">
                <table cellspacing="1" class="table table-bordered ">
                    <!-- 表头 -->
                    <thead>
                    <tr>
                        <th class="row-selected row-selected"> <input type="checkbox" class="check-all regular-checkbox" id="checkAll"><label for="checkAll"></label></th>
                        <th>编号</th>
                        <th>角色名称</th>
                        <th>角色描述</th>
                    </tr>
                    </thead>
                    <!-- 列表 -->
                    <tbody>
                    <?php foreach( $role as  $row): ?>
                        <tr>
                            <td><input type="checkbox" id="check_<?= $row['id'] ?>" name="ids[]" value="<?=$row['id']?>" class="roles regular-checkbox">
                                <label for="check_<?= $row['id'] ?>"></label></td>
                            <td><?= $row['id'] ?></td>
                            <td><?= $row['role_name'] ?></td>
                            <td><?= $row['role_desc'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                <button type="button" class="btn btn-primary set_role">保存</button>
            </div>
        </div>
    </div>
</div>
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
                          <button type="button" class="btn btn-primary btn-lg " data-toggle="modal" data-target="#myModal">
                              设置角色
                          </button>
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
                          <th>头像</th>
                          <th>用户手机</th>
                          <th>用户昵称</th>
                          <th>备注</th>
                          <th>性别</th>
                      </tr>
                      </thead>

                      <!-- 列表 -->
                      <tbody>
                      <?php foreach( $list as  $row): ?>
                          <tr>
                              <td><input type="checkbox" id="check_<?= $row['id'] ?>" name="ids[]" value="<?=$row['id']?>" class="ids regular-checkbox">
                                  <label for="check_<?= $row['id'] ?>"></label></td>
                              <td><img src="<?= $row["userface"] ?>" width="50px" height="50px"></td>
                              <td><?= $row['phone'] ?></td>
                              <td><?= $row['nickname'] ?></td>
                              <td><?= $row['remark'] ?></td>
                              <td><?php if(1===$row['sex']){echo '男';}else{echo '女';} ?></td>
                          </tr>
                      <?php endforeach; ?>
                      </tbody>
                  </table>
              </div>
              <div class="row">
                  <div class="col-lg-12" align="right">
                      <?= LinkPager::widget(['pagination' => $pages]); ?>
                  </div>
              </div>
          </section>
      </div>

      <script type="text/javascript">


          $('.set_role').click(function(){
              count=$('.ids:checked').length;
              userid=$('.ids:checked').val();
              if(count>1)
              {
                  alert('最多只能选择一个菜单');
                  return false;
              }
              window.location = "/admin/role/set?id="+userid;
          });


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