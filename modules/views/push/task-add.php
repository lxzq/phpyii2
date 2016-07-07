<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\VideoForm */

use yii\helpers\Html;
use yii\widgets\LinkPager;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
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
	<?= Html::cssFile('@web/css/bootstrap-datetimepicker.min.css') ?>
    <?= Html::cssFile('@web/css/emoji.css') ?>
    <?=Html::jsFile('@web/Js/jquery-1.10.2.js')?>
    <?= Html::jsFile('@web/uploadify/jquery.uploadify.min.js') ?>
    <?= Html::jsFile('@web/zclip/ZeroClipboard.min.js') ?>
    <?= Html::jsFile('@web/weixin/js/dialog.js') ?>
    <?= Html::jsFile('@web/weixin/js/admin_common.js') ?>
    <?= Html::jsFile('@web/weixin/js/admin_image.js') ?>
    <?= Html::jsFile('@web/masonry/masonry.pkgd.min.js') ?>
    <?= Html::jsFile('@web/Js/jquery.dragsort-0.5.2.min.js') ?>
	<?= Html::jsFile('@web/Js/bootstrap-datetimepicker.js') ?>
	<?= Html::jsFile('@web/Js/locales/bootstrap-datetimepicker.zh-CN.js') ?>
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
				<div class="tab-content">
					<?php $form=ActiveForm::begin(['id'=>'form','action'=>Url::toRoute(['push/task-add']),'method'=>'post','options'=>['class'=>'form-horizontal form-center']]);?>
						<div class="form-item cf toggle-keyword">
							<label class="item-label">
								<span class="need_flag">*</span>推送模板
							</label>
							<div class="controls">
								<select class="textarea input-large template" name="template_id">
									<option value="">请选择推送模板</option>
									<?php foreach($template_list as $vo):?>
										<option value="<?=$vo['id']?>" <?php if($model['template_id']==$vo['id']):echo "selected='selected'";endif;?>><?=$vo['title']?></option>
									<?php endforeach;?>
								</select>
							</div>
						</div>
						<div class="form-item cf">
							<label class="item-label">
								<span class="need_flag">*</span>推送范围
							</label>
							<div class="controls">
								<select class="input-large push-scope" onchange="pushScope()" name="send_scope[type]">
									<option value="">请选择推送范围</option>
									<option value="1" <?php if($model['send_scope']['type']==1):echo "selected='selected'";endif;?>>角色</option>
									<option value="2" <?php if($model['send_scope']['type']==2):echo "selected='selected'";endif;?>>用户</option>
								</select>
								<button class="btn btn-warning show_select show_select2 select-user" style="padding: 7px 10px;margin-bottom: 10px;" type="button">选择推送范围</button>
							</div>
							<div class="controls show_select show_select2">
								<table cellspacing="1" class="table table-bordered">
									<!-- 表头 -->
									<thead>
									<tr>
										<th>用户头像</th>
										<th>用户昵称</th>
										<th>用户手机</th>
										<th>操作</th>
									</tr>
									</thead>
									<!-- 列表 -->
									<tbody class="user_lists" data-name="11">
										<?php if(!empty($model['send_scope']['users'])): foreach($model['send_scope']['users'] as $va):?>
											<tr>
												<td>
													<input type="hidden" name="user[<?=$va['id']?>][id]" value="<?=$va['id']?>"/>
													<input type="hidden" name="user[<?=$va['id']?>][openid]" value="<?=$va['openid']?>"/>
													<img src="<?=$va['userface']?>" width="50px" height="50px">
												</td>
												<td><?=$va['nickname']?></td>
												<td><?=$va['phone']?></td>
												<td><button class="btn btn-danger delete-user" type="button">删除</button></td>
											</tr>
										<?php endforeach;endif;?>
									</tbody>
								</table>
							</div>
							<div class="controls show_select show_select1">
								<select class="textarea input-large" name="send_scope[role_id]">
									<option value="">请选择推送角色</option>
									<?php foreach($role_list as $role):?>
										<option value="<?=$role['id']?>" <?php if($model['send_scope']['role_id']==$role['id']):echo "selected='selected'";endif;?>><?=$role['role_name']?></option>
									<?php endforeach;?>
								</select>
							</div>
						</div>
						<div class="form-item cf toggle-keyword">
							<label class="item-label">
								<span class="need_flag">*</span>推送时间
							</label>
							<div class="input-group input-large date form_datetime" id="form_datetime"
								 data-date-format="yyyy-mm-dd hh:ii" >
								<input class="form-control"  type="text" name="send_time" id="send_time" value="<?php if(!empty($model['send_time'])):echo Yii::$app->formatter->asDatetime($model['send_time']);endif;?>" readonly="readonly">
								<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
								<span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
							</div>
						</div>
						<div class="form-item cf toggle-keyword">
							<label class="item-label">
								URL链接（详情）
							</label>
							<div class="input-group input-large">
								<input class="form-control"  type="text" name="url" value="<?=$model['url']?>">
							</div>
						</div>
						<div class="form-item cf">
							<label class="item-label">
								<span class="need_flag">*</span>推送内容
							</label>
							<div class="controls form-group push-content border">
								<?php if(!empty($model['content'])): foreach($model['content'] as $ke=>$vo):?>
									<div class="controls">
										<label style="float: left;width: 140px;text-align: right;padding-left: 8px;"><?=$vo['label']?></label>
									<div class="col-md-6">
										<input class="form-control" name="content[<?=$ke?>][content]" value="<?=$vo['content']?>"/>
										<input type="hidden" name="content[<?=$ke?>][label]" value="<?=$vo['label']?>"/></div>
									</div>
								<?php endforeach; endif;?>
							</div>
						</div>
						<div class="form-item col-md-3" style="padding-top:30px;">
							<?php if(!empty($model['id'])):?>
						  		<input type="hidden" name="id" value="<?=$model['id']?>"/>
						  	<?php endif;?>
							<input type="hidden" name="weixin_template_id" id="weixin_template_id" value=""/>
							<button class="btn " type="submit" target-form="form-horizontal">确 定</button>
							<input type="button" class="btn btn-info" onclick="backList()" value="返回">
						</div>
					<?php ActiveForm::end();?>
				</div>
          </section>
      </div>
  </div>
</div>

	<!-- /主体 -->

	<!-- 底部 -->
<script type="text/javascript">
	$(function() {
		pushScope();
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
		$('.template').change(function(){
			var id=$(this).val();
			if(id!=''){
				$.post('/admin/push/get-template-content','id='+id).success(function(data){
					var data= $.parseJSON(data);
					var x= 1,str='';
					$.each(data.content,function(i,j){
						if(j.type==undefined){
							if(i=='remark'){
								str+='<div class="controls"><label style="float: left;width: 140px;text-align: right;padding-left: 8px;">备注：</label><div class="col-md-6"> <input class="form-control" name="content['+i+'][content]" value=""/><input type="hidden" name="content['+i+'][label]" value="备注："/></div></div>';
							}else{
								str+='<div class="controls"><label style="float: left;width: 140px;text-align: right;padding-left: 8px;">'+j+'</label><div class="col-md-6"> <input class="form-control" name="content['+i+'][content]" value=""/><input type="hidden" class="form-control" name="content['+i+'][label]" value="'+j+'"/></div></div>';
							}
						}else if(j.type!=''){
							str+='<div class="controls"><label style="float: left;width: 140px;text-align: right;padding-left: 8px;">开头语:</label><div class="col-md-6"> <input class="form-control" name="content['+i+'][content]" value=""/> <input type="hidden" name="content['+i+'][label]" value="开头语"/></div></div>';
						}

					})
					$("#weixin_template_id").val(data.template_id);
					$(".push-content").html(str);
				})
			}
		});
		$('.select-user').on('click',function () {
			var $contentHtml = $('<div class="appmsg_dialog" style="padding:10px; max-height:560px;overflow-y:auto;overflow-x:hidden;"><ul class="mt_10"><center><br/><br/><br/><img src="/weixin/images/loading.gif"/></center></ul></div>');
			$.Dialog.open("选择推送范围", {width: 800, height: 640}, $contentHtml);
			$.Dialog.foot('<div class="dialog-foot" style="padding-top:10px;"><button type="button" class="btn btn-primary commit">确 定</button><button type="button" class="btn btn-default" style="margin-left:40px;" onclick="$.Dialog.close();">关闭</button></div>');
			$.ajax({
				url: '/admin/push/task-select-user',
				data: {},
				dataType: 'html',
				success: function (data) {
					$data = $(data);
					$('ul', $contentHtml).html($data);
					$('li', $contentHtml).on('click', function () {
						callback(this);
					});
				}
			})
		})
		$('.user_lists').on('click','.delete-user',function(){
			$(this).parents('tr').remove();
		})
	})
	function pushScope(){
		$('.show_select').hide();
		var val= $('.push-scope').val();
		if(val!=''){
			$('.show_select'+val).show();
		}

	}
	/* 选择推送条件 */
	function openSelectPush(dataUrl,callback,title){

	}
	function selectPushCallback(_this){

		//$('input[name="group_id"]').val($(_this).data('id'));
		$.Dialog.close();
	}

	function backList(){
		window.history.go(-1);
	}
</script>
</body>
</html>