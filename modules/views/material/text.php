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
							<li class="" >
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
							<li class="active" >
								<a href="<?= Url::to(['material/text']);?>"> 文本素材 </a>
								<b class="active_arrow"></b>
							</li>
						</ul>
					</li>
				</ul>
		  </div>
  <div class="main_body" style="min-height:300px;">
      <div class="span9 page_message">
		
          <section id="contents">
              <!-- 数据列表 -->
			  <div class="table-bar">
					<div class="fl">
						<div class="tools">
							<a class="btn" href="/admin/material/add-text">新 增</a>
						</div>
					</div>
				</div>
				<div class="data-table" style="width:80%">
					<div class="table-striped">
						<table cellspacing="1" class="table-bordered">
							<thead>
								<tr>
									<th>文本ID</th>
									<th>文本内容</th>
									<th>操作</th>
								</tr>
							</thead>
							<tbody>
							<?php if(!empty($list)): foreach($list as $vo):?>
								<tr>
									<td>
										<?=$vo['id']?>
									</td>
									<td><?=$vo['content']?></td>
									<td>
										<a href="<?=Url::toRoute(['material/add-text','text_id'=>$vo['id']])?>" target="_self">编辑</a>
										<a class="confirm" href="<?=Url::toRoute(['material/del-text','text_id'=>$vo['id']])?>">删除</a>
									</td>
								</tr>
							<?php endforeach;endif;?>
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
	
})
</script>
</body>
</html>