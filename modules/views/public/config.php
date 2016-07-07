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
	<?= Html::jsFile('@web/weixin/js/autosize.min.js') ?>
	 <script type="text/javascript">
        var  IMG_PATH = "/weixin/images";
        var  ROOT = "";
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
  <div class="main_body">
      <div class="span9 page_message">
          <section id="contents">
			<ul class="tab-nav nav">
				<li class="current">
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
				<li class="">
					<a href="<?=Url::toRoute(['public/template'])?>">
						模板消息管理
						<span class="arrow fa fa-sort-up"></span>
					</a>
				</li>
				<li class="">
					<a href="<?=Url::toRoute(['public/template'])?>">
						群发消息
						<span class="arrow fa fa-sort-up"></span>
					</a>
				</li>
			 </ul>
			 <div class="tab-content">
			  <?php $form = ActiveForm::begin(['id'=>'form','options'=>['class'=>'form-horizontal'],'action'=>'/admin/public/config','method'=>'post']); ?>
				<div class="form-item cf">
				  <label class="item-label"> 类型: </label>
				  <div class="controls">
				    	<div class="check-item">
							<input type="radio" name="config[type]" value="1" class="regular-radio" id="config[type]_1" onClick="changeOption()">
							<label for="config[type]_1"></label>文本
						</div>
						<div class="check-item">
							<input type="radio" name="config[type]" value="2" class="regular-radio" id="config[type]_2" onClick="changeOption()">
							<label for="config[type]_2"></label>图文
						</div>
				  </div>
				</div>
				<div class="form-item cf show show1">
				  <label class="item-label"> 内容: </label>
				  <div class="controls">
				    <label class="textarea input-large">
				      <textarea name="config[description]"><?php if(!empty($info['description'])) echo $info['description'];?></textarea>
				    </label>
				  </div>
				</div>
				<div  class="form-item cf show show2 appmsg_area" id="appmsg_area" style="margin:20px 0;">
                	<input type="hidden" name="config[group_id]" value="<?php if(!empty($info['group_id']))echo $info['group_id']; ?>"/>
                    <a class="select_appmsg" href="javascript:;" onClick="$.WeiPHP.openSelectAppMsg('/admin/material/material-data',selectAppMsgCallback)">选择图文</a>
                    <div class="appmsg_wrap" style="height:auto;"></div>
                    <a class="delete" href="javascript:;" style="left: 310px;">删除</a>
				</div>
				<button class="btn submit-btn ajax-post" target-form="form-horizontal" type="submit">确 定</button>
				<?php ActiveForm::end(); ?>
			  </div>
          </section>
      </div>
  </div>
</div>

	<!-- /主体 -->

	<!-- 底部 -->
	<script type="text/javascript">
		function changeOption(){
			autosize(document.querySelectorAll('textarea'));
			$(".show").each(function(){
				$(this).hide();
				
			});
			var type=$("input[name='config[type]']:checked").val();
			if(type==2){
				var group_id=$("input[name='config[group_id]']").val();
				if(group_id){
					$.post("/admin/material/get-news",{'group_id':group_id},function(vo){
						var vo=$.parseJSON(vo);
						var html_str='';
						if(vo.length==1){
							html_str='<div class="appmsg_item"><h6>'+vo[0]['title']+'</h6><div class="main_img"><img src="'+vo[0]['img_url']+'"/></div><p class="desc">'+vo[0]['intro']+'</p></div><div class="hover_area"></div>';
						}else{
							for(var i=0;i<vo.length;i++){
								if(vo[i]['id']==group_id){
									html_str='<div class="appmsg_item"><div class="main_img"><img src="'+vo[i]['img_url']+'"/><h6>'+vo[i]['title']+'</h6></div><p class="desc">'+vo[i]['intro']+'</p></div>';
								}else{
									html_str+=' <div class="appmsg_sub_item"><p class="title">'+vo[i]['title']+'</p><div class="main_img"><img src="'+vo[i]['img_url']+'"/></div></div>';
								}
							}
							html_str+='<div class="hover_area"></div>';
						}
						
						$('.appmsg_wrap').html(html_str).show();
						$('.select_appmsg').hide();
						$('.appmsg_area .delete').show();
					})
				}
				
			}
			
			var val = $("input[name='config[type]']:checked").val();
			$('.show'+val).each(function(){
				$(this).show();
			});
		}
		$(function(){
			var type = "<?php if(!empty($info['type'])):echo $info['type'];else:echo '0';endif;?>";
			if(type=="0"){
				 type = 1;
			}
			$("input[name='config[type]'][value="+type+"]").attr("checked",true); 
			changeOption();
			$('.appmsg_area .delete').click(function(){
				$('.appmsg_wrap').html('').hide();
				$('.select_appmsg').show();
				$('.appmsg_area .delete').hide();
				$('input[name="config[group_id]"]').val(0);
			})
		})
		function selectAppMsgCallback(_this){
			$('.appmsg_wrap').html($(_this).html()).show();
			$('.select_appmsg').hide();
			$('.appmsg_area .delete').show();
			$('input[name="config[group_id]"]').val($(_this).data('group_id'));
			$.Dialog.close();
		}

	</script>

</body>
</html>