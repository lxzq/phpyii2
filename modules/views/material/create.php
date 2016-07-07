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
<html lang="zh-CN">
<head>
	<meta charset="UTF-8">
    <?= Html::cssFile('@web/css/bootstrap.min.css') ?>
    <?= Html::cssFile('@web/font-awesome/css/font-awesome.min.css') ?>
    <?= Html::cssFile('@web/weixin/css/base.css') ?>
    <?= Html::cssFile('@web/weixin/css/module.css') ?>

    <?= Html::cssFile('@web/weixin/css/weixin.css') ?>
    <?= Html::cssFile('@web/css/emoji.css') ?>
    <?=Html::jsFile('@web/Js/jquery-2.0.3.min.js')?>
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
		<li class="current">
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
	 <div class="sidebar">
		<ul class="sidenav">
			<li>
				<a class="sidenav_parent" href="javascript:;"> 素材管理</a>
				<ul class="sidenav_sub">
					<li class="active" >
						<a href="<?= Url::to(['material/index']);?>"> 图文素材</a>
						<b class="active_arrow"></b>
					</li>
					<li class="" >
						<a href="<?= Url::to(['material/picture']);?>"> 图片素材 </a>
						<b class="active_arrow"></b>
					</li>
					<!--<li class="" >
						<a href="/admin/public/keywords?type=images"> 语音素材 </a>
						<b class="active_arrow"></b>
					</li>
					<li class="" >
						<a href="/admin/public/keywords?type=images"> 视频素材 </a>
						<b class="active_arrow"></b>
					</li>-->
					<li class="" >
						<a href="<?= Url::to(['material/text']);?>"> 文本素材 </a>
						<b class="active_arrow"></b>
					</li>
				</ul>
			</li>
		</ul>
  </div>
  <div class="main_body">
      <div class="span9 page_message">
		<section id="contents">
		   <div class="tab-content"> 
			  <h3>新建图文消息</h3>
			  <!-- 表单 -->
			  <div id="form" action="/admin/material/create<?php if(!empty($first)):echo '?group_id='.$group_id;endif;?>" class="form-horizontal form-center">
				<div class="material_form">
					<div class="preview_area">
					<?php if(empty($first)){ ?>
						<form class="appmsg_item edit_item editing" data-index="0" enctype="multipart/form-data" method="post" action="/admin/material/create">
							<p class="time">时间</p>
							<div class="main_img">
								<img src="/weixin/images/no_cover_pic.png" data-coverid="0"/>
								<h6 class="title">这是标题</h6>
							</div>
							<p class="intro"></p>
							 <input type="hidden" name="title" placeholder="这是标题" />
							  <input type="hidden" name="cover_id" value="0"/>
							<input type="hidden" name="introduction" placeholder="这是摘要描述"/>
							<input type="hidden" name="author" placeholder="作者"/>
							<input type="hidden" name="link" placeholder="外链"/>
							<textarea style="display:none" name="content"></textarea>
							<div class="hover_area"><a href="javascript:;" onClick="editItem(this)">编辑</a></div>
						</form>
					<?php }else{ ?>
						<form class="appmsg_item edit_item" data-index="0" enctype="multipart/form-data" method="post" action="/admin/material/create?group_id=1">
							<p class="time"><?=Yii::$app->formatter->asDatetime($first['add_time'])?></p>
							<div class="main_img">
								<img src="<?=$first['path']?>" data-coverid="<?=$first['cover_id']?>"/>
								<h6 class="title"><?=$first['title']?></h6>
							</div>
							<p class="intro"><?= $first['introduction']?></p>
							<input type="hidden" name="id" value="<?=$first['id']?>"/>
							 <input type="hidden" name="title" value="<?=$first['title']?>" />
							  <input type="hidden" name="cover_id" value="<?=$first['cover_id']?>"/>
							<input type="hidden" name="introduction" value="<?=$first['introduction']?>"/>
							<input type="hidden" name="author" value="<?=$first['author']?>"/>
							<input type="hidden" name="link" value="<?=$first['link']?>"/>
							<textarea style="display:none" name="content"><?=$first['content']?></textarea>
							<div class="hover_area"><a href="javascript:;" onClick="editItem(this)">编辑</a></div>
						</form>
						<?php if(!empty($others)): foreach($others as $key=> $vo): ?>
							<form id="w0" class="appmsg_sub_item edit_item" data-index="<?= $key+1?>" enctype="multipart/form-data" method="post" action="/admin/material/create?group_id=1">
							<p class="title"><?=$vo['title']?></p>
							<div class="main_img">
								<img src="<?=$vo['path']?>" data-coverid="<?=$vo['path']?>"/>
							</div>
							<input type="hidden" name="id" value="<?=$vo['id']?>"/>
							 <input type="hidden" name="title" value="<?=$vo['title']?>"/>
							<input type="hidden" name="cover_id" value="<?=$vo['cover_id']?>"/>
							<input type="hidden" name="introduction" value="<?=$vo['introduction']?>"/>
							<input type="hidden" name="author" value="<?=$vo['author']?>"/>
							<input type="hidden" name="link" value="<?=$vo['link']?>"/>
							<textarea style="display:none" name="content"><?=$vo['content']?></textarea>
							<div class="hover_area"><a href="javascript:;" onClick="editItem(this)">编辑</a><a href="javascript:;" onClick="deleteItem(this)">删除</a></div>
						</form>
						<?php endforeach;endif;?>
					<?php } ?>
						<div class="appmsg_edit_action">
							<a href="javascript:;" onClick="addMsg();">添加</a>
						</div>
					</div>
					<div class="edit_area">
						<em class="area_arrow"></em>
						<div class="">
							<ul class="tab-pane in appmsg_edit_group">
								<li class="form-item cf">
									<label class="item-label"><span class="need_flag">*</span>标题<span class="check-tips"></span></label>
									<div class="controls">
									  <input type="text" class="text input-large" name="p_title" value="">
									</div>
								  </li>  
								  <li class="form-item cf">
									<label class="item-label">作者<span class="check-tips"></span></label>
									<div class="controls">
									  <input type="text" class="text input-large" name="p_author" value="">
									</div>
								  </li>  
								  <li class="form-item cf">
										<label class="item-label"><span class="need_flag">*</span>封面图片<span class="check-tips">图片900X500</span></label>
										<div class="controls uploadrow2" title="点击修改图片" rel="p_cover">
											<input type="file" id="upload_picture_p_cover">
											<input type="hidden" name="p_cover" id="cover_id_p_cover" data-callback="uploadImgCallback" value=""/>
											<div class="upload-img-box" rel="img" style="display:none">
											  <div class="upload-pre-item2"><img width="100" height="100" src=""/></div>
												<em class="edit_img_icon">&nbsp;</em>
											</div>
									  </div>
								  </li>
								  <li class="form-item cf">
										<label class="item-label">摘要<span class="check-tips"></span></label>
										<div class="controls">
										  <label class="textarea input-large">
										  <textarea class="text input-large" name="p_introduction" ></textarea>
										  </label>
										</div>
								   </li>   
								   <li class="form-item cf">
										<label class="item-label"><span class="need_flag">*</span>正文<span class="check-tips"></span></label>
										<div class="controls">
										  <label class="textarea">
											<textarea style="width:405px; height:200px;" name="p_content" ></textarea>
											<input type="hidden" name="parse" value="0">
											<?= Html::jsFile('@web/ueditor/ueditor.config.js') ?>
											<?= Html::jsFile('@web/ueditor/ueditor.all.js') ?>
											<?= Html::jsFile('@web/ueditor/lang/zh-cn/zh-cn.js') ?>
											<script type="text/javascript">
												$('textarea[name="p_content"]').attr('id', 'editor_id_p_content');
												window.UEDITOR_HOME_URL = "/ueditor";
												window.UEDITOR_CONFIG.initialFrameHeight = parseInt('500px');
												window.UEDITOR_CONFIG.scaleEnabled = true;
												window.UEDITOR_CONFIG.imagePath = '';
												window.UEDITOR_CONFIG.imageFieldName = 'imgFile';//图片在线管理的处理地址
												window.UEDITOR_CONFIG.imageManagerPath='';        
												var imageEditor = UE.getEditor('editor_id_p_content',{
														toolbars: [['fullscreen','source', 'undo', 'redo','bold', 'italic', 'underline', 'fontborder', 'strikethrough', 'removeformat', 'formatmatch', 'autotypeset', 'blockquote', 'forecolor', 'backcolor', 'insertorderedlist','lineheight', 'customstyle', 'paragraph', 'fontfamily', 'fontsize', 'indent','justifyleft', 'justifycenter', 'justifyright','link', 'unlink',  'insertimage', 'emotion', 'insertvideo', 'music', 'attachment', 'map']
														],
														autoHeightEnabled: false,
														autoFloatEnabled: true,
														initialFrameHeight:300
													});
												imageEditor.styleUrl = "/admin/material/get-style";
											</script>
											<?= Html::jsFile('@web/ueditor/h5/main.js') ?>
										  </label>
										  </label>
										</div>
								   </li>   
									<li class="form-item cf">
									<label class="item-label">外链<span class="check-tips"></span></label>
									<div class="controls">
									  <input type="text" class="text input-large" name="p_link" value="">
									</div>
								  </li>  
						  </ul>
						</div>
					</div>
				</div>
				<div class="form-item form_bh">
				  <button class="btn ajax-post" id="submit" type="submit">确 定</button>
				</div>
			  </div>
			</div>
		</section>
  </div>
   <script type="text/javascript">
		$('#submit').click(function(){
			var postUrl = $('#form').attr('action');
			var dataJson = [];
			$('.edit_item').each(function() {
				dataJson.push($(this).serializeArray());
			});
			//$(this).addClass('disabled');
			//提交数组字符串 php解析后进行保存
			$.post(postUrl,{'dataStr':JSON.stringify(dataJson)},function(data){
				var data=$.parseJSON(data);
				$('#submit').removeClass('disabled');
				if(data.status==1){
					updateAlert(data.info,'success');
					setTimeout(function(){
						  location.href=data.url;
					},1500);
				}else{
					updateAlert(data.info);
				}
			})
			return false;
		});
		$(function(){
			//初始化上传图片插件
			initUploadImg();

			showTab();

			//动态预览
			$('input[name="p_title"]').keyup(function(){
				$('.editing').find('.title').text($(this).val());
				$('.editing').find('input[name="title"]').val($(this).val());
			});
			$('input[name="p_author"]').keyup(function(){
				$('.editing').find('.author').text($(this).val());
				$('.editing').find('input[name="author"]').val($(this).val());
			});
			$('input[name="p_link"]').keyup(function(){
				$('.editing').find('.link').text($(this).val());
				$('.editing').find('input[name="link"]').val($(this).val());
			});
			$('textarea[name="p_introduction"]').keyup(function(){
				$('.editing').find('.introduction').text($(this).val());
				$('.editing').find('input[name="introduction"]').val($(this).val());
			});
			imageEditor.addListener("contentChange",function(){
				$('.editing').find('textarea[name="content"]').val(imageEditor.getContent());
			});
			imageEditor.addListener("ready", function () {
			   initForm($('.edit_item').eq(0));
			});
			
			
		});
		function addMsg(){
			var curCount = $('.edit_item').size();
			if(curCount>=8){
				updateAlert('你最多只可以增加8条图文信息');
				return false;
			}
			//console.log(curCount);
			var addHtml = $('<form data-index="'+curCount+'" class="appmsg_sub_item edit_item">'+
							'<p class="title"></p>'+
							'<div class="main_img">'+
								'<img src="/weixin/images/no_cover_pic_s.png" data-coverid="0"/>'+
							'</div>'+
							'<input type="hidden" name="title" placeholder="这是标题"/>'+
							'<input type="hidden" name="cover_id" value="0"/>'+
							'<input type="hidden" name="introduction" placeholder="这是摘要描述"/>'+
							'<input type="hidden" name="author" placeholder="作者"/>'+
							'<input type="hidden" name="link" placeholder="外链"/>'+
							'<textarea style="display:none" name="content"></textarea>'+
							'<div class="hover_area"><a href="javascript:;" onClick="editItem(this)">编辑</a><a href="javascript:;" onClick="deleteItem(this)">删除</a></div>'+
						'</form>');
			addHtml.insertBefore($('.appmsg_edit_action'));
		}
		function editItem(_this){
			$(_this).parents('.edit_item').addClass('editing');
			$(_this).parents('.edit_item').siblings().removeClass('editing');
			var index = $(_this).parents('.edit_item').data('index');
			if(index==0){
				$('.edit_area').css('margin-top',0);
			}else{
				$('.edit_area').css('margin-top',index*110+120);
			}
			initForm($(_this).parents('.edit_item'));
		}
		function deleteItem(_this){
			if(!confirm('确认删除？')) return false;
			
			var item_id = $(_this).parents('.edit_item').find('input[name="id"]').val();
			if(item_id){
				$.post("/admin/material/delete",{id:item_id});
			}
			
			$(_this).parents('.edit_item').remove();
			var curCount = $('.edit_item').size();
			if(curCount==1){
				$('.edit_area').css('margin-top',0);
			}else{
				$('.edit_area').css('margin-top',(curCount-1)*110+120);
			}
			initForm($('.edit_item').eq(curCount-1));
		}
		function uploadImgCallback(name,id,src){
			$('.editing img').attr('src',src);
			$('.editing input[name="cover_id"]').val(id);
		}
		function initForm(_item){
			var title = $(_item).find('input[name="title"]').val();
			var author = $(_item).find('input[name="author"]').val();
			var link = $(_item).find('input[name="link"]').val();
			var intro = $(_item).find('input[name="introduction"]').val();
			var content = $(_item).find('textarea[name="content"]').val();
			var src = $(_item).find('img').attr('src');
			$('input[name="p_title"]').val(title);
			$('input[name="p_author"]').val(author);
			$('input[name="p_link"]').val(link);
			$('textarea[name="p_introduction"]').val(intro);
			if(!content)content=" ";
			if(content){
				imageEditor.setContent(content);
			}
			$('.upload-img-box').show().find('img').attr('src',src);
		}
	</script> 
</body>
</html>

