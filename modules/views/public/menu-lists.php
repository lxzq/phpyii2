<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\VideoForm */

use yii\helpers\Html;
use yii\widgets\LinkPager;
use yii\helpers\Url;
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
    <?= Html::jsFile('@web/Js/jquery.dragsort-0.5.2.min.js') ?>

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
  <div class="main_body" style="min-height:300px;">
      <div class="span9 page_message">
          <section id="contents">
              <!-- 数据列表 -->
			  <ul class="tab-nav nav">
				<li class="">
					<a href="<?= Url::toRoute(['public/config']);?>">
						欢迎语设置
						<span class="arrow fa fa-sort-up"></span>
					</a>
				</li>
				<li class="current">
					<a href="<?=Url::toRoute(['public/menu-lists']);?>">
						自定义菜单
						<span class="arrow fa fa-sort-up"></span>
					</a>
				</li>
				<li class="">
					<a href="<?=Url::toRoute(['public/keywords'])?>">
						关键字回复
						<span class="arrow fa fa-sort-up"></span>
					</a>
				</li>
				<li class="">
					<a href="<?=Url::toRoute(['public/unkown'])?>">
						未识别回复
						<span class="arrow fa fa-sort-up"></span>
					</a>
				</li>
				<li class="">
					<a href="<?=Url::toRoute(['user/list'])?>">
						用户管理
						<span class="arrow fa fa-sort-up"></span>
					</a>
				</li>
				<li class="">
					<a href="<?=Url::toRoute(['user-group/list'])?>">
						分组管理
						<span class="arrow fa fa-sort-up"></span>
					</a>
				</li>
				<li class="">
					<a href="<?=Url::toRoute(['material/index'])?>">
						素材管理
						<span class="arrow fa fa-sort-up"></span>
					</a>
				</li>
				  <li class="">
					  <a href="<?=Url::toRoute(['public/template'])?>">
						  模板消息管理
						  <span class="arrow fa fa-sort-up"></span>
					  </a>
				  </li>
				  <li class="">
					  <a href="<?=Url::toRoute(['public/message'])?>">
						  群发消息
						  <span class="arrow fa fa-sort-up"></span>
					  </a>
				  </li>
			 </ul>
			  <div style="overflow:hidden; zoom:1;margin-top:20px;">
			  	  <h3 style=" float:left;margin-bottom:15px;"><img style="vertical-align:middle; height:30px" src="/weixin/images/weixin.png"/> 自定义菜单</h3>
				  <div style="margin:0 0 15px 0; float:right">
					<!--<a class="btn" href="/admin/public/add-menu-group">+新增菜单组</a> -->
					 <a href="javascript:void(0);" class=" btn add_menu_group" >+新增菜单组</a>
				  </div>
			  </div>
			  <div class="data-table" style="margin:0;">
			  <?php if(!empty($list)): ?>
				<div class="table-striped">
				  <table cellspacing="1" class="table table-bordered ">
					<!-- 表头 -->
					<thead>
					  <tr>
						<th width="8%">菜单组ID</th>
						<th  width="15%">菜单组名称</th>
						<th  width="42%">用户分组</th>
						<th  width="35%">操作</th>
					  </tr>
					</thead>
					
					<!-- 列表 -->
					<tbody>
					<?php foreach($list as $data): ?>
					  <tr>
						<td><?= $data['id'] ?></td>
						<td><?= $data['name'] ?></td>
						<td><?= $data['group_id'] ?></td>
						<td>
						<a href="<?= Url::toRoute(['public/menu', 'menu_group_id' => $data['id']]) ?>" target="_self">进入管理</a>&nbsp;&nbsp;&nbsp;
						<a href="javascript:void(0);" class="edit_menu_group" rel="<?= $data['id']?>">编辑</a>&nbsp;&nbsp;&nbsp;
						<a href="javascript:void(0);" class="set_menu" rel="<?= $data['id']?>">发布</a>&nbsp;&nbsp;&nbsp;
						<a href="<?= Url::toRoute(['public/del-menu-group', 'menu_group_id' => $data['id']]) ?>" class="confirm">删除</a> </td>
					  </tr>
					  <?php endforeach; ?>
					</tbody>
				  </table>
				</div>
			  <?php else: ?>
				<div style="padding:100px; text-align:center;"><img style="vertical-align:middle;" src="/weixin/images/weixin.png"/>  你还没有添加菜单组<p><a href="javascript:void(0);" class="btn get_menu">拉取微信端自定义菜单</a></p></div>
			  <?php endif; ?>
			  </div>
          </section>
      </div>
  </div>
</div>
<!-- 备注用户名 -->
      <div class="menu_group_html" style="display:none">
          <div class="manage_group normal_dialog">
              <div class="content">
				  <div class="form-group col-md-7">
					<label class="control-label" for="name">分组名称</label>
					<input id="menu_group_name" class="form-control" type="text" name="name" placeholder="请输入菜单组名称">
					<p class="help-block help-block-error"></p>
				 </div>
				 <div class="form-group col-md-7">
					<label class="control-label" for="sort">分组排序</label>
					<input id="menu_group_sort" class="form-control" type="text" name="sort" placeholder="请输入菜单组排序">
					<p class="help-block help-block-error"></p>
				 </div>
				 <?php if(!empty($group_list)):?>
				 <div class="form-group col-md-12">
					<label class="control-label" for="sort">用户分组</label>
					<div class="from-group input_checkbox">
					<?php foreach($group_list as $vo): ?>
						<label class="checkbox-inline">
						  <input type="checkbox" class="user_group_id toggle-data" value="<?php if(!empty($vo['weixin_group_id'])):echo $vo['weixin_group_id'];endif;?>" id="group_<?php if(!empty($vo['weixin_group_id'])):echo $vo['weixin_group_id'];endif;?>"  name="group_id[]"> <?= $vo['group_name'] ?>
						</label>
                    <?php endforeach;?>
					</div>
					<p class="help-block help-block-error"></p>
				 </div>
				<?php endif;?>
                  <div class="btn_wrap form-group col-md-7"> 
				    <input type="hidden" name="id" id="menu_group_id" value="">
					<button class="btn add_menu_group col-md-6 col-md-offset-2" url="/admin/public/add-menu-group">确定</button>
				</div>
              </div>
          </div>
      </div>
	  <script type="text/javascript">
		  $('.add_menu_group').click(function(){
			var html = $($('.menu_group_html').html());
			$.Dialog.open('添加自定义菜单组',{width:600,height:450},html);
			//$.thinkbox(html);
			$('button',html).click(function(){
				that = this;
				target = $(that).attr('url');
				name=$('#menu_group_name', html).val();
				var group_id=[];
				if(name==''){
					alert('请填写菜单组名称');
					return;
				}
				$('input[name="group_id[]"]:checked').each(function(){    
				   group_id.push($(this).val());
				 }); 
				 if(!group_id){
					 alert('请选择用户分组');
					 return;
				 }
				query = "name="+name+"&sort="+$('#menu_group_sort',html).val()+"&group_id="+group_id ;
				$(that).addClass('disabled').attr('autocomplete','off').prop('disabled',true);
				$.post(target,query).success(function(data){
					data=$.parseJSON(data);
					updateAlert(data.info ,'alert-success');
					setTimeout(function(){
						location.reload();
					},1500);
					$('.thinkbox-modal-blackout-default,.thinkbox-default').hide();
				});
			})
		})
		$('.edit_menu_group').click(function(){
			var html = $($('.menu_group_html').html());
			var menu_group_id=$(this).attr('rel');
			$.post("/admin/public/get-menu-group",{'id':menu_group_id},function(info){
				info=$.parseJSON(info);
				$('#menu_group_name',html).val(info.name);
				$('#menu_group_sort',html).val(info.sort);
				$("#menu_group_id",html).val(info.id);
				$(info.group_id).each(function(i,j){
					$('#group_'+j,html).prop('checked',true);
				})
			});
			$.Dialog.open('添加自定义菜单组',{width:600,height:450},html);
			$('button',html).click(function(){
				that = this;
				target = $(that).attr('url');
				name=$('#menu_group_name', html).val();
				var group_id=[];
				if(name==''){
					alert('请填写菜单组名称');
					return;
				}
				$('input[name="group_id[]"]:checked').each(function(){    
				   group_id.push($(this).val());
				 }); 
				 if(!group_id){
					 alert('请选择用户分组');
					 return;
				 }
				query = "name="+name+"&sort="+$('#menu_group_sort',html).val()+"&group_id="+group_id+"&id="+$('#menu_group_id',html).val();
				$(that).addClass('disabled').attr('autocomplete','off').prop('disabled',true);
				$.post(target,query).success(function(data){
					data=$.parseJSON(data);
					updateAlert(data.info ,'alert-success');
					setTimeout(function(){
						location.reload();
					},1500);
					$('.thinkbox-modal-blackout-default,.thinkbox-default').hide();
				});
			})
		})
		$('.set_menu').click(function(){
			var menu_group_id=$(this).attr('rel');
			var url="/admin/public/set-menu?menu_group_id="+menu_group_id;
			$.get(url).success(function(data){
                updateAlert('菜单发布成功！','alert-success');
            });
		})
		$('.get_menu').click(function(){
			var url="/admin/public/get-menu";
			$.get(url).success(function(data){
				var data=$.parseJSON(data);
				if(data.status==1){
					updateAlert(data.info,'alert-success');
	                setTimeout(function(){
						location.reload();
					},1500);
				}else{
					updateAlert(data.info);
				}
                
            });
		})
	</script>	
</body>
</html>