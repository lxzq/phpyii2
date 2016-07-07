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
		 
		  <!-- 数据列表 -->
		  <div class="data-table" style="margin-top:40px; overflow:hidden">
			<div class="" style="float:left;">
				<div id="phone">
					<div id="frame">
						<div class="wx_menu">
							<span class="keyboard"></span>
							<div class="menu">
								<?php if(!empty($list)): foreach($list as $data): if($data['pid']==0): ?>
										<div class="m_a"  href="javascript:;">
											<div class="m_a_i">
											  <img src="/weixin/images/wx_menu_list_icon.png"/>
												<?=$data['title']?>
											</div>
											<div class="sub_menu">
												<div class="sub_menu_inner">
												<?php foreach($list as $va): if($va['pid']==$data['id']):?>
														<a href="#"><?= $va['title']?></a>
												<?php endif;endforeach;?>
												</div>
												<em></em>
											</div>
										</div>
								<?php endif;endforeach; endif;?>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class=" table-striped" style="float:right; width:540px">
			  <table class="table-bordered" cellpadding="0" cellspacing="1">
				<!-- 表头 -->
				<thead>
				  <tr>
						<th><a href="<?= Url::toRoute(['public/add-menu','menu_group_id'=>$menu_group_id]) ?>">+添加</a></th>
						<th>操作</th>
				  </tr>
				</thead>
				
				<!-- 列表 -->
				<tbody>
				<?php foreach($list as $data):?>
					<tr>
					  <td>
						<input class="ids" type="hidden" value="<?= $data['id']?>" name="ids[]">
						<?php if($data['pid']==0):?>
							<strong><?= $data['title'] ?></strong>
						<?php else:?>
							&nbsp;&nbsp;&nbsp;&nbsp; ◆ <?= $data['title'] ?>
						<?php endif;?>
					  </td>
					  <td>
							<a href="<?= Url::toRoute(['public/add-menu','menu_id'=>$data['id']])?>">编辑</a>
							<a href="<?= Url::toRoute(['public/delete-menu','menu_id'=>$data['id']])?>">删除</a>
					  </td>
					</tr>
				<?php endforeach;?>
				</tbody>
			  </table>
			   
			</div>
		  </div>
		   
		</section>
	  </div>
  </div>
</div>

	<!-- /主体 -->

	<!-- 底部 -->
<script type="text/javascript">
	$(function(){
		//回车自动提交
		$('.search-form').find('input').keyup(function(event){
			if(event.keyCode===13){
				$("#search").click();
			}
		});
		
		//初始化菜单样式
		var num=$('.menu').find('div.m_a').length;
		$('.menu').find('div.m_a').width(264/num);
		$('.m_a').each(function(index, element) {
		   var submenu = $(element).find('.sub_menu');
		   if(submenu.find('a').html()==undefined){
			  $(element).find('img').hide();
			  submenu.hide();
		   }else{
			   var mW = $(element).width();
			   var sW = submenu.width()+10+2;
			   submenu.css('margin-left',(mW-sW)/2);
		   }
		});
		$('.m_a').hover(function(){
			$(this).find('.sub_menu').animate({'bottom':58},300);
		},function(){
			$(this).find('.sub_menu').animate({'bottom':-458},300);
		})

	})
</script> 
</body>
</html>