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
    <?= Html::cssFile('@web/css/emoji.css') ?>
    <?=Html::jsFile('@web/Js/jquery-1.10.2.js')?>
    <?= Html::jsFile('@web/uploadify/jquery.uploadify.min.js') ?>
    <?= Html::jsFile('@web/zclip/ZeroClipboard.min.js') ?>
    <?= Html::jsFile('@web/weixin/js/dialog.js') ?>
    <?= Html::jsFile('@web/weixin/js/admin_common.js') ?>
    <?= Html::jsFile('@web/weixin/js/admin_image.js') ?>
    <?= Html::jsFile('@web/masonry/masonry.pkgd.min.js') ?>
    <?= Html::jsFile('@web/Js/jquery.dragsort-0.5.2.min.js') ?>


	<script>
		var _gaq = _gaq || [];
		_gaq.push(['_setAccount', 'UA-43092768-1']);
		_gaq.push(['_trackPageview']);
		(function () {
			var ga = document.createElement('script');
			ga.type = 'text/javascript';
			ga.async = true;
			ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
			var s = document.getElementsByTagName('script')[0];
			s.parentNode.insertBefore(ga, s);
		})();
	</script>
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
					<?php $form=ActiveForm::begin(['id'=>'form','action'=>Url::toRoute(['push/trigger-add']),'method'=>'post','options'=>['class'=>'form-horizontal form-center']]);?>
						<div class="form-item cf toggle-keyword">
							<label class="item-label">
								<span class="need_flag">*</span>触发器名称
							</label>
							<div class="controls">
								<input class="form-control" type="text" value="<?=$model['trigger_name']?>" name="title" />
							</div>
						</div>
						<div class="form-item cf toggle-keyword">
							<label class="item-label">
								<span class="need_flag">*</span>资源
							</label>
							<div class="controls">
								<select class="form-control tables" name="source">
									<option value="">请选择资源</option>
									<?php foreach($plist as $source):?>
										<option value="<?=$source['SourceID']?>"><?=$source['SourceName']?></option>
									<?php endforeach;?>
								</select>
							</div>
						</div>
						<div class="form-item cf toggle-keyword groups">
							<label class="item-label">
								<span class="need_flag">*</span>条件规则
							</label>
							<!--<div class="controls border">
								<div class="controls rule">
									<div class="controls col-md-3">
										<select class="form-control" name="rule[field]">
											<option value="">请选择条件</option>
											<option value="user">当前用户</option>
											<option value="role">当前角色</option>
											<option value="dept">当前部门</option>
											<option value="shop">当前门店</option>
										</select>
									</div>
									<div class="controls col-md-3">
										<select class="form-control option" name="rule[option]">
											<option value="">请选择条件</option>
											<option value="=">等于</option>
											<option value="in">包含</option>
										</select>
									</div>
									<div class="controls col-md-4">
										<input type="text" class="form-control" name="rule[value]"/>
									</div>
									<div class="controls col-md-1">
										<a class="btn btn-primary button" href="javascript:;">选择</a>
									</div>
								</div>
							</div>-->
							<div class="controls group">
								<div class="controls border rule">
									<div class="controls fields">
										<div class="controls field">
											<div class="controls col-md-2">
												<select class="form-control" name="group[rule][0][field]">
													<option value="">请选择字段</option>
													<?php foreach($clist as $vo):?>
														<option value="<?=$vo['SourceID']?>"><?=$vo['SourceName']?></option>
													<?php endforeach;?>
												</select>
											</div>
											<div class="controls col-md-2">
												<select class="form-control option" name="group[rule][0][option]">
													<option value="">请选择条件</option>
													<option value="=">等于</option>
													<option value=">">大于</option>
													<option value="<">小于</option>
													<option value="in">包含</option>
												</select>
											</div>
											<div class="controls col-md-2">
												<div class="bootstrap-switch" >
													<span class="bootstrap-switch-handle-on bootstrap-switch-primary" >选择</span>
													<span class="bootstrap-switch-handle-off" >文本</span>
												</div>
											</div>
											<div class="controls col-md-4 rule_value">
												<select class="form-control show" name="group[rule][0][value]">
													<option value="">请选择字段</option>
													<?php foreach($clist as $vo):?>
														<option value="<?=$vo['SourceID']?>"><?=$vo['SourceName']?></option>
													<?php endforeach;?>
												</select>
												<input type="text" class="form-control hidden" name=""/>
											</div>
											<div class="controls col-md-1">
												<a class="btn btn-danger delete" href="javascript:;">
													<i class="glyphicon glyphicon-trash"></i></a>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="controls">
								<div class="controls col-md-6"></div>
								<div class="controls col-md-1">
									<select class="form-control show" name="group[option]">
										<option value="and">并且</option>
										<option value="or">或者</option>
									</select>
								</div>
								<div class="controls col-md-4 rule_button">
									<button class="btn btn-warning add_group" type="button" data-rule="group[group]">增加分组</button>
									<button class="btn btn-success add_fields" type="button" data-field="group[rule]">增加条件</button>
								</div>
							</div>
						</div>
						<div class="form-item col-md-3" style="padding-top:30px;">
							<?php if(!empty($model['id'])):?>
						  		<input type="hidden" name="id" value="<?=$model['id']?>"/>
						  	<?php endif;?>
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
	$(function(){
		$('.rule').on('click','.button',function(){
			var field=$(this).parent().siblings().find('.field').val();
			var option=$(this).parent().siblings().find('.option').val();
			if(field=='' || option== ''){
				alert("请选择条件");
				return;
			}
			var $contentHtml = $('<div class="appmsg_dialog" style="padding:10px; max-height:560px;overflow-y:auto;overflow-x:hidden;"><ul class="mt_10"><center><br/><br/><br/><img src="/weixin/images/loading.gif"/></center></ul></div>');
			$.Dialog.open("选择推送条件",{width:800,height:640},$contentHtml);
			$.Dialog.foot('<div class="dialog-foot" style="margin-left: 100px;"><button type="button" class="btn btn-primary">确 定</button></div>');
			$.ajax({
			 url:'/admin/push/select-data',
			 data:{'type':option,'field':field},
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
		$('.group').on('click','.bootstrap-switch span',function(){
			$(this).addClass('bootstrap-switch-primary').siblings().removeClass('bootstrap-switch-primary');
			var name=$(this).parents('.field').find('.rule_value .show').attr('name');
			$(this).parents('.field').find('.rule_value .show').removeClass('show').addClass('hidden').attr('name','').siblings().removeClass('hidden').addClass('show').attr('name',name);
			$(this).parents('.field').find('.hidden').val('');
		})
		$('.group').on('click','.delete',function(){
			$(this).parents('.field').remove();
		})
		$(".group").on('click','.delete_rule',function(){
			$(this).parent().parent().parent().remove();
		})
		$('.group').on('click','.add_rule',function(){
			var i=$(this).parent().parent().siblings().children('.rule').length;
			var rule=$(this).data('rule');
			var data_rule=rule+'[group]';
			var data_field=rule+'[rule]';
			var name=rule+"[rule]["+i+"]";
			var str='<div class="controls border rule"><div class="controls fields"><div class="controls field"><div class="controls col-md-2"><select class="form-control" name="'+name+'[field]">'+field_select+'</select></div><div class="controls col-md-2"> <select class="form-control option" name="'+name+'[option]"><option value="">请选择条件</option><option value="=">等于</option> <option value=">">大于</option><option value="<">小于</option><option value="in">包含</option></select></div><div class="controls col-md-2"><div class="bootstrap-switch" ><span class="bootstrap-switch-handle-on bootstrap-switch-primary" >选择</span><span class="bootstrap-switch-handle-off" >文本</span></div></div><div class="controls col-md-4 rule_value"> <select class="form-control show" name="'+name+'[value]">'+field_select+'</select><input type="text" class="form-control hidden" name=""/></div><div class="controls col-md-1"><a class="btn btn-danger delete" href="javascript:;"><i class="glyphicon glyphicon-trash"></i></a></div</div></div></div></div><div class="controls"><div class="controls col-md-6"></div><div class="controls col-md-1"><select class="form-control show" name="'+rule+'[option]"><option value="and">并且</option><option value="or">或者</option></select></div><div class="controls col-md-4 rule_button"><button class="btn btn-warning add_rule" type="button" data-rule="'+data_rule+'">增加分组</button><button class="btn btn-success add_field" type="button" style="margin-left: 6px;" data-field="'+data_field+'">增加条件</button><button class="btn btn-danger delete_rule" type="button" style="margin-left: 6px;">删除分组</button></div></div></div>'
			$(this).parent().parent().parent().children('.fields').append(str);
		})
		$(".group").on('click','.add_field',function(){
			var i=$(this).parent().parent().siblings().children('.field').length;
			var name=$(this).data('field')+'['+i+']';
			var str='<div class="controls field"><div class="controls col-md-2"><select class="form-control" name="'+name+'[field]">'+field_select+'</select></div><div class="controls col-md-2"> <select class="form-control option" name="'+name+'[option]"><option value="">请选择条件</option><option value="=">等于</option> <option value=">">大于</option><option value="<">小于</option><option value="in">包含</option></select></div><div class="controls col-md-2"><div class="bootstrap-switch" ><span class="bootstrap-switch-handle-on bootstrap-switch-primary" >选择</span><span class="bootstrap-switch-handle-off" >文本</span></div></div><div class="controls col-md-4 rule_value"> <select class="form-control show" name="'+name+'[value]">'+field_select+'</select><input type="text" class="form-control hidden" name=""/></div><div class="controls col-md-1"><a class="btn btn-danger delete" href="javascript:;"><i class="glyphicon glyphicon-trash"></i></a></div</div></div>';
			$(this).parent().parent().siblings('.fields').append(str);

		})
		$(".groups").on('click','.add_group',function(){
			var i=$(this).parent().parent().siblings().children('.rule').length-1;
			var rule=$(this).data('rule');
			var data_rule=rule+'[group]';
			var data_field=rule+'[rule]';
			var name=rule+"[rule]["+i+"]";
			var str='<div class="controls border rule"><div class="controls fields"><div class="controls field"><div class="controls col-md-2"><select class="form-control" name="'+name+'[field]">'+field_select+'</select></div><div class="controls col-md-2"> <select class="form-control option" name="'+name+'[option]"><option value="">请选择条件</option><option value="=">等于</option> <option value=">">大于</option><option value="<">小于</option><option value="in">包含</option></select></div><div class="controls col-md-2"><div class="bootstrap-switch" ><span class="bootstrap-switch-handle-on bootstrap-switch-primary" >选择</span><span class="bootstrap-switch-handle-off" >文本</span></div></div><div class="controls col-md-4 rule_value"> <select class="form-control show" name="'+name+'[value]">'+field_select+'</select><input type="text" class="form-control hidden" name=""/></div><div class="controls col-md-1"><a class="btn btn-danger delete" href="javascript:;"><i class="glyphicon glyphicon-trash"></i></a></div</div></div></div></div><div class="controls"><div class="controls col-md-6"></div><div class="controls col-md-1"><select class="form-control show" name="'+rule+'[option]"><option value="and">并且</option><option value="or">或者</option></select></div><div class="controls col-md-4 rule_button"><button class="btn btn-warning add_rule" type="button" data-rule="'+data_rule+'">增加分组</button><button class="btn btn-success add_field" type="button" style="margin-left: 6px;" data-field="'+data_field+'">增加条件</button><button class="btn btn-danger delete_rule" type="button" style="margin-left: 6px;">删除分组</button></div></div></div>'
			$(this).parent().parent().parent().children('.group').append(str);

		});
		$(".groups").on('click','.add_fields',function(){
			var i=$(this).parent().parent().siblings().children().children().children('.field').length;
			var name=$(this).data('field')+'['+i+']';
			var str='<div class="controls field"><div class="controls col-md-2"><select class="form-control" name="'+name+'[field]">'+field_select+'</select></div><div class="controls col-md-2"> <select class="form-control option" name="'+name+'[option]"><option value="">请选择条件</option><option value="=">等于</option> <option value=">">大于</option><option value="<">小于</option><option value="in">包含</option></select></div><div class="controls col-md-2"><div class="bootstrap-switch" ><span class="bootstrap-switch-handle-on bootstrap-switch-primary" >选择</span><span class="bootstrap-switch-handle-off" >文本</span></div></div><div class="controls col-md-4 rule_value"> <select class="form-control show" name="'+name+'[value]">'+field_select+'</select><input type="text" class="form-control hidden" name=""/></div><div class="controls col-md-1"><a class="btn btn-danger delete" href="javascript:;"><i class="glyphicon glyphicon-trash"></i></a></div</div></div>';
			$(this).parent().parent().siblings().children().children('.fields').append(str);

		})
		var field_select='<option value="">请选择字段</option><?php foreach($clist as $vo):?><option value="<?=$vo['SourceID']?>"><?=$vo['SourceName']?></option><?php endforeach;?>';
		//var field='<div class="controls field"><div class="controls col-md-2"><select class="form-control" name="rule[][field]"><option value="">请选择字段</option><?php foreach($clist as $vo):?><option value="<?=$vo['SourceID']?>"><?=$vo['SourceName']?></option><?php endforeach;?></select></div><div class="controls col-md-2"> <select class="form-control option" name="rule[][option]"><option value="">请选择条件</option><option value="=">等于</option> <option value=">">大于</option><option value="<">小于</option><option value="in">包含</option></select></div><div class="controls col-md-2"><div class="bootstrap-switch" ><span class="bootstrap-switch-handle-on bootstrap-switch-primary" >选择</span><span class="bootstrap-switch-handle-off" >文本</span></div></div><div class="controls col-md-4 rule_value"> <select class="form-control show" name="rule[][value]"><option value="">请选择字段</option><?php foreach($clist as $vo):?><option value="<?=$vo['SourceID']?>"><?=$vo['SourceName']?></option><?php endforeach;?></select><input type="text" class="form-control hidden" name="rule[][value]"/></div><div class="controls col-md-1"><a class="btn btn-danger delete" href="javascript:;"><i class="glyphicon glyphicon-trash"></i></a></div</div></div>';
	});
	//var rule='<div class="controls border rule"><div class="controls fields"><div class="controls field"><div class="controls col-md-2"><select class="form-control" name="group[rule][][field]"><option value="">请选择字段</option><?php foreach($clist as $vo):?><option value="<?=$vo['SourceID']?>"><?=$vo['SourceName']?></option><?php endforeach;?></select></div><div class="controls col-md-2"> <select class="form-control option" name="group[rule][][option]"><option value="">请选择条件</option><option value="=">等于</option> <option value=">">大于</option><option value="<">小于</option><option value="in">包含</option></select></div><div class="controls col-md-2"><div class="bootstrap-switch" ><span class="bootstrap-switch-handle-on bootstrap-switch-primary" >选择</span><span class="bootstrap-switch-handle-off" >文本</span></div></div><div class="controls col-md-4 rule_value"> <select class="form-control show" name="group[rule][][value]"><option value="">请选择字段</option><?php foreach($clist as $vo):?><option value="<?=$vo['SourceID']?>"><?=$vo['SourceName']?></option><?php endforeach;?></select><input type="text" class="form-control hidden" name="group[rule][][value]"/></div><div class="controls col-md-1"><a class="btn btn-danger delete" href="javascript:;"><i class="glyphicon glyphicon-trash"></i></a></div</div></div></div></div><div class="controls"><div class="controls col-md-6"></div><div class="controls col-md-1"><select class="form-control show" name="group[rule][][option]"><option value="and">并且</option><option value="or">或者</option></select></div><div class="controls col-md-4 rule_button"><button class="btn btn-warning add_rule" type="button" >增加分组</button><button class="btn btn-success add_field" type="button" style="margin-left: 6px;">增加条件</button><button class="btn btn-danger delete_rule" type="button" style="margin-left: 6px;">删除分组</button></div></div></div>';
	/* 选择推送条件 */
	function openSelectPush(dataUrl,callback,title){

	}
	function selectPushCallback(_this){

		//$('input[name="group_id"]').val($(_this).data('id'));
		$.Dialog.close();
	}
	function get_cloumn(){
		var table= $('.tables').val();
		if(table!=''){
			$.post('/admin/public/get-column','table='+table).success(function(data){
				var data= $.parseJSON(data);
				var str='<select class="form-control" name="content['+field+'][column]"><option value="">请选择表字段</option>';
				$.each(data,function(i,j){
					str+='<option value="'+ j.name+'">'+ j.comment+'</option>';
				})
				str+=' </select>';
				$('.'+field).html(str);
			})
		}
	}
	function backList(){
		window.history.go(-1);
	}
</script>
</body>
</html>