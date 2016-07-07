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
			  <?php if(!empty($list[1])): ?>
			  <div style="overflow:hidden; zoom:1;margin-top:20px;">
			  <h3 style=" float:left;margin-bottom:15px;"><img style="vertical-align:middle; height:30px" src="/weixin/images/weixin.png"/> 我创建的公众号</h3>
			  <div style="margin:0 0 15px 0; float:right">
				<a class="btn" href="/admin/public/step1">+新增公众号</a>
			  </div>
			  </div>
				  <div class="data-table" style="margin:0;">
					<div class="table-striped">
					  <table cellspacing="1" class="table table-bordered ">
						<!-- 表头 -->
						<thead>
						  <tr>
							<th width="8%">公众号ID</th>
							<th  width="32%">公众号名称</th>
							<th  width="15%">Token</th>
							<th  width="10%">管理员数</th>
							<th  width="35%">操作</th>
						  </tr>
						</thead>

						<!-- 列表 -->
						<tbody>
						<?php foreach($list[1] as $data): ?>
						  <tr>
							<td><?= $data['id'] ?></td>
							<td><?= $data['public_name'] ?></td>
							<td><?= $data['token'] ?></td>
							<td><?= $data['num'] ?></td>
							<td>
							<a href="<?= Url::toRoute(['public/config', 'public_id' => $data['id']]) ?>" target="_self">进入管理</a>&nbsp;&nbsp;&nbsp;
							<a class="set_manager" href="javascript:;" data-id="<?=$data['id']?>">管理员配置</a>&nbsp;&nbsp;&nbsp;
							<a href="<?= Url::toRoute(['public/step1', 'public_id' => $data['id']]) ?>" target="_self">编辑</a>&nbsp;&nbsp;&nbsp;
							<a href="<?= Url::toRoute(['public/delete', 'public_id' => $data['id']]) ?>" class="confirm">删除</a> </td>
						  </tr>
						  <?php endforeach; ?>
						</tbody>
					  </table>
					</div>
				  </div>
			  <?php elseif(!empty($list[0])): ?>
			   <h3 style="margin:15px 0;"><img style="vertical-align:middle; height:25px" src="/weixin/images/weixin.png"/> 我加入的公众号</h3>
			  <div class="data-table" style="margin:0">
				<div class="table-striped">
					<table cellspacing="1">
					<!-- 表头 -->
					<thead>
					  <tr>
						<th width="8%">公众号ID</th>
						<th  width="32%">公众号名称</th>
						<th  width="15%">Token</th>
						<th  width="10%">管理员数</th>
						<th  width="35%">操作</th>
					  </tr>
					</thead>
					
					<!-- 列表 -->
					<tbody>
					<?php foreach($list[0] as $data): ?>
					  <tr>
						<td><?= $data['id'] ?></td>
						<td><?= $data['public_name'] ?></td>
						<td><?= $data['token'] ?></td>
						<td><?= $data['num'] ?></td>
						<td><a href="<?= Url::toRoute(['public/config', 'public_id' => $data['id']]) ?>" target="_self">进入管理</a></td>
					  </tr>
					  <?php endforeach; ?>
					</tbody>
				  </table>
				</div>
			  <?php else: ?>
				  <div style="margin:0 0 15px 0; float:right">
					  <a class="btn" href="/admin/public/step1">+新增公众号</a>
				  </div>
				  <div style="padding:100px; text-align:center;"><img style="vertical-align:middle;" src="/weixin/images/weixin.png"/>  你还没有创建公众号</div>

			  <?php endif; ?>
		  </div>
          </section>
      </div>
  </div>
</div>
<script type="text/javascript">
	$(function(){
		$('.set_manager').click(function(){
			var id=$(this).data('id');
			var $contentHtml = $('<div class="appmsg_dialog" style="padding:10px; max-height:560px;overflow-y:auto;overflow-x:hidden;"><ul class="mt_10"><center><br/><br/><br/><img src="/weixin/images/loading.gif"/></center></ul></div>');
			$.Dialog.open("设置公众号管理员",{width:800,height:640},$contentHtml);
			$.Dialog.foot('<div class="dialog-foot"><button type="button" class="btn btn-primary commit">确 定</button></div>');
			$.ajax({
				url:'/admin/public/set-manager',
				data:{'id':id},
				dataType:'html',
				success:function(data){
					$data = $(data);
					$('ul',$contentHtml).html($data);
					$data.find('.material_list').masonry({
						itemSelector : '.appmsg_li'
					});
					$('li',$contentHtml).on('click',function(){
						callback(this);
					});
				}
			})
		})
	})
</script>
</body>
</html>