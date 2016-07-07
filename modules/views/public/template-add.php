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
					  <?php $form=ActiveForm::begin(['id'=>'form','action'=>Url::toRoute(['public/template-add']),'method'=>'post','options'=>['class'=>'form-horizontal form-center']]);?>

					  <div class="form-item cf">
						  <label class="item-label">标题</label>
						  <div class="controls">
							  <input class="text input-large" type="text" value="<?=$info['title']?>" name="title" />
						  </div>
					  </div>
					 <!-- <div class="form-item cf">
						  <label class="item-label">一级行业</label>
						  <div class="controls">
							  <input class="text input-large" type="text" value="<?/*=$info['primary_industry']*/?>" name="primary_industry" readonly/>
						  </div>
					  </div>
					  <div class="form-item cf">
						  <label class="item-label">二级行业</label>
						  <div class="controls">
							  <input class="text input-large" type="text" value="<?/*=$info['deputy_industry']*/?>" name="deputy_industry" readonly/>
						  </div>
					  </div>-->
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
					  <div class="form-item cf border">
						  <?php if(!empty($info['content'])):foreach($info['content'] as $key=>$vo):?>
								  <div class="form-group keywords">
									  <label style="float: left;width: 140px;text-align: right;padding-left: 8px;"><?=$key?></label>
									  <?php if(!empty($vo['type'])):?>
										  <label class="item-lable" style="display: block;">
											  <div class="check-item" style="padding-left: 16px;">
												  <input type="radio" name="content[<?=$key?>][type]" value="1" class="regular-radio" id="<?=$key?>[type]_1" <?php if($vo['type']==1): echo "checked='checked'";endif;?>>
												  <label for="<?=$key?>[type]_1"></label>文字
											  </div>
											  <div class="check-item">
												  <input type="radio" name="content[<?=$key?>][type]" value="2" class="regular-radio" id="<?=$key?>[type]_2" <?php if($vo['type']==2): echo "checked='checked'";endif;?>>
												  <label for="<?=$key?>[type]_2"></label>表字段
											  </div>
											  <div class="check-item delete-item"><a class="btn btn-default" href="javascript:;">
													  <i class="glyphicon glyphicon-trash"></i></a></div>
										  </label>
										  <label style="float: left;width: 140px;text-align: right;padding-left: 8px;"></label>
										  <div class="col-md-3 show show1" <?php if($vo['type']==1): echo 'style="display:block;"';else:echo 'style="display:none;"';endif;?>>
											  <input class="form-control" name="content[<?=$key?>][content]" value="<?php if(!empty($vo['content'])):echo $vo['content'];endif;?>"/>
										  </div>
										  <div class="col-md-3 show show2" <?php if($vo['type']==2): echo 'style="display:block;"';else:echo 'style="display:none;"';endif;?>>
											  <select class="form-control tables" name="content[<?=$key?>][table]" data-field="<?=$key?>">
												  <option value="">请选择表</option>
												  <?php foreach($tables as $table):?>
													  <option value="<?=$table['Tables_in_jiajiabang']?>" <?php if( !empty($vo['table']) && $vo['type']==2 && $vo['table']==$table['Tables_in_jiajiabang']):echo "selected";endif;?>><?=$table['Tables_in_jiajiabang']?></option>
												  <?php endforeach;?>
											  </select>
										  </div>
										  <div class="col-md-3 <?=$key?> show show2" <?php if($vo['type']==2): echo 'style="display:block;"';else:echo 'style="display:none;"';endif;?>>
											  <?php if( $vo['type']==2 && !empty($vo['column']) && !empty($vo['field'])):?>													<select class="form-control" name="content[<?=$key?>][column]">
												  		<option value="">请选择表字段</option>
												  		<?php foreach($vo['field'] as $v):?>
															<option value="<?=$v['name']?>" <?php if($vo['column']==$v['name']):echo "selected";endif;?>><?=$v['comment']?></option>
														<?php endforeach;?>
											  		</select>
											  <?php endif;?>
										  </div>
									  <?php endif;?>
								  </div>
							  <?php endforeach;?>
							  <div class="btn-add"><label style="float: left;width: 140px;text-align: right;padding-left: 8px;"></label><button class="btn btn-warning" type="button">添加条件</button></div>
						  <?php else:?>
							  <div class="form-group keywords">
								  <label style="float: left;width: 140px;text-align: right;padding-left: 8px;">keyword1</label>
								  <label class="item-lable" style="display: block;">
									  <div class="check-item" style="padding-left: 16px;">
										  <input type="radio" name="content[keyword1][type]" value="1" class="regular-radio" id="keyword1[type]_1" checked="checked">
										  <label for="keyword1[type]_1"></label>文字
									  </div>
									  <div class="check-item">
										  <input type="radio" name="content[keyword1][type]" value="2" class="regular-radio" id="keyword1[type]_2">
										  <label for="keyword1[type]_2"></label>表字段
									  </div>
									  <div class="check-item delete-item"><a class="btn btn-default" href="javascript:;">
											  <i class="glyphicon glyphicon-trash"></i></a></div>
								  </label>
								  <label style="float: left;width: 140px;text-align: right;padding-left: 8px;"></label>
								  <div class="col-md-3 show show1"> <input class="form-control" name="content[keyword1][content]"/></div>
								  <div class="col-md-3 show show2" style="display: none;">
									  <select class="form-control tables" name="content[keyword1][table]" data-field="keyword1">
										  <option value="">请选择表</option>
										  <?php foreach($tables as $table):?>
											  <option value="<?=$table['Tables_in_jiajiabang']?>" ><?=$table['Tables_in_jiajiabang']?></option>
										  <?php endforeach;?>
									  </select>
								  </div>
								  <div class="col-md-3 show show2" style="display: none;">
								  </div>
							  </div>
							  <div class="btn-add"><label style="float: left;width: 140px;text-align: right;padding-left: 8px;"></label><button class="btn btn-warning" type="button">添加条件</button></div>
						  <?php endif;?>
					  </div>
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
		  $('.border').on('click','.regular-radio',function(){
			  $(this).parents('.keywords').find(".show").hide();
			  var val = $(this).val();
			  $(this).parents('.keywords').find('.show'+val).show();
		  })
		  $(".border").on('change','.tables',function(){
			  var table= $(this).val();
			  var _this=$(this);
			  if(table!=''){
				  var field=$(this).data('field');
				  $.post('/admin/public/get-column','table='+table).success(function(data){
					  var data= $.parseJSON(data);
					  var str='<select class="form-control" name="content['+field+'][column]"><option value="">请选择表字段</option>';
					  $.each(data,function(i,j){
						  str+='<option value="'+ j.name+'">'+ j.comment+'</option>';
					  })
					  str+=' </select>';
					  _this.parent('.show2').siblings('.show2').html(str);
				  })
			  }
		  })
		  $('.border').on('click','.delete-item',function(){
			  $(this).parents('.keywords').remove();
		  })
		  $('.btn-add').click(function(){
			  var num=$('.keywords').length+1;
			  str='<div class="form-group keywords"><label style="float: left;width: 140px;text-align: right;padding-left: 8px;">keyword'+num+'</label><label class="item-lable" style="display: block;"><div class="check-item" style="padding-left: 16px;"> <input type="radio" name="content[keyword'+num+'][type]" value="1" class="regular-radio" checked="checked" id="keyword'+num+'[type]_1"><label for="keyword'+num+'[type]_1"></label>文字</div><div class="check-item"><input type="radio" name="content[keyword'+num+'][type]" value="2" class="regular-radio" id="keyword'+num+'[type]_2"> <label for="keyword'+num+'[type]_2"></label>表字段</div><div class="check-item delete-item"><a class="btn btn-default" href="javascript:;"><i class="glyphicon glyphicon-trash"></i></a></div></label><label style="float: left;width: 140px;text-align: right;padding-left: 8px;"></label><div class="col-md-3 show show1"> <input class="form-control" name="content[keyword'+num+'][content]"/></div><div class="col-md-3 show show2" style="display: none;"><select class="form-control tables" name="content[keyword'+num+'][table]" data-field="keyword'+num+'">'+select+'</select></div><div class="col-md-3 show show2" style="display: none;"></div></div>';
			  $(this).before(str);
		  })
		  var select='<option value="">请选择表</option><?php foreach($tables as $table):?><option value="<?=$table['Tables_in_jiajiabang']?>" ><?=$table['Tables_in_jiajiabang']?></option><?php endforeach;?>';
	  });
	  function backList(){
		  window.history.go(-1);
	  }

	</script>
</body>
</html>
