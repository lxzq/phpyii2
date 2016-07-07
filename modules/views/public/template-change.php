<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\VideoForm */

use yii\helpers\Html;
use yii\widgets\LinkPager;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
?>
<!DOCTYPE HTML>
<html lang="zh-CN" xmlns="http://www.w3.org/1999/html">
<head>
	<meta charset="UTF-8">
    <?= Html::cssFile('@web/css/bootstrap.min.css') ?>
    <?= Html::cssFile('@web/font-awesome/css/font-awesome.min.css') ?>
    <?= Html::cssFile('@web/weixin/css/base.css') ?>
    <?= Html::cssFile('@web/weixin/css/module.css') ?>

    <?= Html::cssFile('@web/weixin/css/weixin.css') ?>
    <?= Html::cssFile('@web/css/emoji.css') ?>
    <?=Html::jsFile('@web/Js/jquery-1.10.2.js')?>
    <?= Html::jsFile('@web/zclip/ZeroClipboard.min.js') ?>
    <?= Html::jsFile('@web/weixin/js/dialog.js') ?>
    <?= Html::jsFile('@web/weixin/js/admin_common.js') ?>
    <?= Html::jsFile('@web/weixin/js/admin_image.js') ?>
    <?= Html::jsFile('@web/masonry/masonry.pkgd.min.js') ?>
    <?= Html::jsFile('@web/Js/jquery.dragsort-0.5.2.min.js') ?>
	<?= Html::jsFile('@web/weixin/js/autosize.min.js') ?>

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
	<ul class="tab-nav nav">
				<li class="">
					<a href="<?= Url::toRoute(['public/config']);?>">
						欢迎语设置
						<span class="arrow fa fa-sort-up"></span>
					</a>
				</li>
				<li class="">
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
				<li class="current">
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
	  <div class="main_body" style="min-height:300px;">
		  <div class="span9 page_message">
			  <section id="contents">
				  <div class="tab-content">
					  <?php $form=ActiveForm::begin(['id'=>'form','action'=>Url::toRoute(['public/template-change']),'method'=>'post','options'=>['class'=>'form-horizontal form-center']]);?>
					  <div class="form-item cf">
						  <label class="item-label">模板id</label>
						  <div class="controls">
							  <input class="text input-large" type="text" value="<?=$info['template_id']?>" name="template_id" disabled="disabled"/>
						  </div>
					  </div>
					  <div class="form-item cf">
						  <label class="item-label">标题</label>
						  <div class="controls">
							  <input class="text input-large" type="text" value="<?=$info['title']?>" name="title" disabled="disabled"/>
						  </div>
					  </div>
					  <div class="form-item cf">
						  <label class="item-label">一级行业</label>
						  <div class="controls">
							  <input class="text input-large" type="text" value="<?=$info['primary_industry']?>" name="primary_industry" readonly/>
						  </div>
					  </div>
					  <div class="form-item cf">
						  <label class="item-label">二级行业</label>
						  <div class="controls">
							  <input class="text input-large" type="text" value="<?=$info['deputy_industry']?>" name="deputy_industry" readonly/>
						  </div>
					  </div>
					  <div class="form-item cf toggle-content">
						  <label class="item-label">模板内容</label>
						  <div class="controls">
							  <label class="textarea input-large">
								  <textarea  disabled="disabled" name="content" ><?=$info['template_data']?></textarea>
							  </label>
						  </div>
					  </div>
					  <div class="form-item cf toggle-content">
						  <label class="item-label">内容示例</label>
						  <div class="controls">
							  <label class="textarea input-large">
								  <textarea name="example"  disabled="disabled"><?=$info['example']?></textarea></label>
						  </div>
					  </div>
					  <div class="form-item cf">
						  <label class="item-label">跳转链接URL</label>
						  <div class="controls">
							  <input class="text input-large" type="text" value="<?=$info['url']?>" name="url"/>
						  </div>
					  </div>
					  <div class="form-item cf">
						  <label class="item-label">设置推送</label>
						  <div class="controls">
							  <select class="textarea input-large" name="flag">
								  <option value="1" <?php if($info['flag']==1):echo "selected";endif;?>>是</option>
								  <option value="2" <?php if($info['flag']==2):echo "selected";endif;?>>否</option>
							  </select>
						  </div>
					  </div>
					  <!--<div class="form-item cf border">
						  <?php /*if(!empty($info['content'])):foreach($info['content'] as $key=>$vo):*/?>
								  <div class="form-group ">
									  <label style="float: left;width: 140px;text-align: right;padding-left: 8px;"><?/*=$key*/?></label>
									  <?php /*if($key=='remark'):*/?>
										  <label class="col-md-3"> <textarea class="form-control" name="content[remark][content]"><?php /*if(!empty($vo['content'])):echo $vo['content'];endif;*/?></textarea></label>
									  <?php /*elseif(!empty($vo['type'])):*/?>
										  <label class="item-lable" style="display: block;">
											  <div class="check-item" style="padding-left: 16px;">
												  <input type="radio" name="content[<?/*=$key*/?>][type]" value="1" class="regular-radio" id="<?/*=$key*/?>[type]_1" onClick="changeOption()" <?php /*if($vo['type']==1): echo "checked='checked'";endif;*/?>>
												  <label for="<?/*=$key*/?>[type]_1"></label>文字
											  </div>
											  <div class="check-item">
												  <input type="radio" name="content[<?/*=$key*/?>][type]" value="2" class="regular-radio" id="<?/*=$key*/?>[type]_2" onClick="changeOption()" <?php /*if($vo['type']==2): echo "checked='checked'";endif;*/?>>
												  <label for="<?/*=$key*/?>[type]_2"></label>表字段
											  </div>
										  </label>
										  <label style="float: left;width: 140px;text-align: right;padding-left: 8px;"></label>
										  <div class="col-md-3 show show1"> <input class="form-control" name="content[<?/*=$key*/?>][content]" value="<?php /*if(!empty($vo['content'])):echo $vo['content'];endif;*/?>"/></div>
										  <div class="col-md-3 show show2">
											  <select class="form-control tables" name="content[<?/*=$key*/?>][table]" data-field="<?/*=$key*/?>">
												  <option value="">请选择表</option>
												  <?php /*foreach($tables as $table):*/?>
													  <option value="<?/*=$table['Tables_in_jiajiabang']*/?>" <?php /*if( !empty($vo['table']) && $vo['type']==2 && $vo['table']==$table['Tables_in_jiajiabang']):echo "selected";endif;*/?>><?/*=$table['Tables_in_jiajiabang']*/?></option>
												  <?php /*endforeach;*/?>
											  </select>
										  </div>
										  <label class="col-md-3 <?/*=$key*/?> show show2">
											  <?php /*if( $vo['type']==2 && !empty($vo['column']) && !empty($vo['field'])):*/?>													<select class="form-control" name="content[<?/*=$key*/?>][column]">
												  		<option value="">请选择表字段</option>
												  		<?php /*foreach($vo['field'] as $v):*/?>
															<option value="<?/*=$v['name']*/?>" <?php /*if($vo['column']==$v['name']):echo "selected";endif;*/?>><?/*=$v['comment']*/?></option>
														<?php /*endforeach;*/?>
											  		</select>
											  <?php /*endif;*/?>
										  </label>
									  <?php /*else:*/?>
										  <label class="col-md-3">
											  <select class="form-control tables" name="content[<?/*=$key*/?>][table]" data-field="<?/*=$key*/?>">
												  <option value="">请选择表</option>
												  <?php /*foreach($tables as $table):*/?>
													  <option value="<?/*=$table['Tables_in_jiajiabang']*/?>" <?php /*if( !empty($vo['table']) && $vo['table']==$table['Tables_in_jiajiabang']):echo "selected";endif;*/?>><?/*=$table['Tables_in_jiajiabang']*/?></option>
												  <?php /*endforeach;*/?>
											  </select>
										  </label>
										  <label class="col-md-3 <?/*=$key*/?>">
											  <?php /*if(!empty($vo['column']) && !empty($vo['field'])):*/?>																	<select class="form-control" name="content[<?/*=$key*/?>][column]">
												  <option value="">请选择表字段</option>
												  <?php /*foreach($vo['field'] as $v):*/?>
													  <option value="<?/*=$v['name']*/?>" <?php /*if($vo['column']==$v['name']):echo "selected";endif;*/?>><?/*=$v['comment']*/?></option>
												  <?php /*endforeach;*/?>
											  </select>
											  <?php /*endif;*/?>
										  </label>
									  <?php /*endif;*/?>
								  </div>
							  <?php /*endforeach;*/?>
						  <?php /*else:*/?>
						  <?php /*foreach($field as $key=> $vo):*/?>
							  <div class="form-group ">
								  <label style="float: left;width: 140px;text-align: right;padding-left: 8px;"><?/*=$vo*/?></label>
								  <?php /*if($vo=='remark'):*/?>
									  <label class="col-md-3"> <textarea class="form-control tables" name="content[remark][content]"></textarea></label>
								  <?php /*elseif($key==0):*/?>
									  <label class="item-lable" style="display: block;">
										  <div class="check-item" style="padding-left: 16px;">
											  <input type="radio" name="content[<?/*=$vo*/?>][type]" value="1" class="regular-radio" id="<?/*=$vo*/?>[type]_1" onClick="changeOption()" data-name="<?/*=$vo*/?>[type]">
											  <label for="<?/*=$vo*/?>[type]_1"></label>文字
										  </div>
										  <div class="check-item">
											  <input type="radio" name="content[<?/*=$vo*/?>][type]" value="2" class="regular-radio" id="<?/*=$vo*/?>[type]_2" onClick="changeOption()" data-name="<?/*=$vo*/?>[type]">
											  <label for="<?/*=$vo*/?>[type]_2"></label>表字段
										  </div>
									  </label>
										  <label style="float: left;width: 140px;text-align: right;padding-left: 8px;"></label>
										  <div class="col-md-3 show show1"> <input class="form-control" name="content[<?/*=$vo*/?>][content]" /></div>
										  <div class="col-md-3 show show2">
											  <select class="form-control tables" name="content[<?/*=$vo*/?>][table]" data-field="<?/*=$vo*/?>">
												  <option value="">请选择表</option>
												  <?php /*foreach($tables as $table):*/?>
													  <option value="<?/*=$table['Tables_in_jiajiabang']*/?>"><?/*=$table['Tables_in_jiajiabang']*/?></option>
												  <?php /*endforeach;*/?>
											  </select>
										  </div>
										  <label class="col-md-3 <?/*=$vo*/?> show show2"></label>
								  <?php /*else:*/?>
								  <label class="col-md-3">
									  <select class="form-control tables" name="content[<?/*=$vo*/?>][table]" data-field="<?/*=$vo*/?>">
										  <option value="">请选择表</option>
										  <?php /*foreach($tables as $table):*/?>
											  <option value="<?/*=$table['Tables_in_jiajiabang']*/?>"><?/*=$table['Tables_in_jiajiabang']*/?></option>
										  <?php /*endforeach;*/?>
									  </select>
								  </label>
								  <label class="col-md-3 <?/*=$vo*/?>">
								  </label>
								  <?php /*endif;*/?>
							  </div>
						  <?php /*endforeach;*/?>
						  <?php /*endif;*/?>
					  </div>-->
					  <div class="form-item col-md-3" style="padding-top:30px;">
							  <input type="hidden" name="id" value="<?=$info['id']?>"/>
						  <button class="btn " type="submit" target-form="form-horizontal">确 定</button>
						  <input type="button" class="btn btn-info" onclick="backList()" value="返回">
					  </div>
					  <?php ActiveForm::end();?>
				  </div>
			  </section>
		  </div>
	  </div>
	  </div>
    
    
  </section>
  </div>

 
  <script type="text/javascript">
	  $(function(){
		  autosize(document.querySelectorAll('textarea'));
		  $('#submit').click(function(){
			  $('#form').submit();
		  });
		  changeOption();
		  $('.tables').change(function(){
			 var table= $(this).val();
			  if(table!=''){
				  var field=$(this).data('field');
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

		  })
	  });
	  function backList(){
		  window.history.go(-1);
	  }
	  function changeOption(){
		  $(".show").hide();
		  var val = $('.regular-radio:checked').val();
		  $('.show'+val).show();
	  }
	</script>
</body>
</html>
