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
				<li class="current">
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
			 <div class="sidebar">
				<ul class="sidenav">
					<li>
						<a class="sidenav_parent" href="javascript:;"> 关键字回复</a>
						<ul class="sidenav_sub">
							<li class="<?php if($type=='text') echo 'active'?>" >
								<a href="/admin/public/keywords"> 文本回复 </a>
								<b class="active_arrow"></b>
							</li>
							<li class="<?php if($type=='news') echo 'active'?>" >
								<a href="/admin/public/keywords?type=news"> 图文回复 </a>
								<b class="active_arrow"></b>
							</li>
							<li class="<?php if($type=='images') echo 'active'?>" >
								<a href="/admin/public/keywords?type=images"> 图片回复 </a>
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
							<a class="btn" href="/admin/public/add-<?=$type?>">新 增</a>
						</div>
					</div>
				</div>
				<div class="data-table" style="width:80%">
					<div class="table-striped">
						<table cellspacing="1" class="table-bordered">
							<thead>
								<tr>
									<th>关键词ID</th>
									<th>关键词</th>
									<?php if($type=='text'):?>
										<th>文件内容</th>
									<?php elseif($type=='news'):?>
										<th>图文</th>
									<?php else:?>
										<th>图片</th>
									<?php endif;?>
									<th>操作</th>
								</tr>
							</thead>
							<tbody>
							<?php if(!empty($list)): foreach($list as $vo):?>
								<tr>
									<td>
										<?=$vo['id']?>
									</td>
									<td><?=$vo['keyword']?></td>
									<?php if($type=='text'):?>
										<td><?=$vo['content']?></td>
									<?php elseif($type=='news'):?>
										<td><?=$vo['title']?></td>
									<?php else:?>
										<td><img class="list_img" src="<?=$vo['path']?>"></td>
									<?php endif;?>
									<td>
										<a href="<?=Url::toRoute(['public/add-'.$type,'keyword_id'=>$vo['id']])?>" target="_self">编辑</a>
										<a class="confirm" href="<?=Url::toRoute(['public/delete-keywords','keyword_id'=>$vo['id'],'type'=>$type])?>">删除</a>
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
	//搜索功能
	$("#search").click(function(){
		var url = $(this).attr('url');
        var query  = $('.search-form').find('input').serialize();
        query = query.replace(/(&|^)(\w*?\d*?\-*?_*?)*?=?((?=&)|(?=$))/g,'');
        query = query.replace(/^&/g,'');
        if( url.indexOf('?')>0 ){
            url += '&' + query;
        }else{
            url += '?' + query;
        }
        if(query == '' ){
        	url= $(this).attr('url');

        }
		window.location.href = url;
	});

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