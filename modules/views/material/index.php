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
              <div class="table-bar">
				<div class="fl">
					<div class="tools">
						<a class="btn" href="<?=Url::to(['material/create'])?>">新 增</a>
						<a class="btn syc_to" href="javascript:void(0);" url="<?=Url::to(['material/syc-to-wechat'])?>">一键上传素材到微信素材库</a>
						<a class="btn syc_from" href="javascript:void(0);" url="<?=Url::to(['material/syc-from-wechat'])?>">一键下载微信素材库到本地</a>
					</div>
				</div>
			  </div>
			  <!-- 数据列表 -->
			  <div class="data-table">			  
				<div class="table-striped">
					<ul class="material_list js-masonry"  data-masonry-options='{ "itemSelector": ".appmsg_li", "columWidth": 308 }'>
					<?php foreach($news_list as $ke=>$vo): ?>
					<?php if($vo['count']==1){ ?>
						<!-- 单图文 -->
						<li class="appmsg_li">
							<div class="appmsg_item">
								<h6 style="overflow: hidden;"><?=$vo['title']?></h6>
								<p class="title"><?= Yii::$app->formatter->asDatetime($vo['add_time'])?></p>
								<div class="main_img">
									<img src="<?=$vo['path']?>"/>
								</div>
								<p class="desc" style="overflow: hidden;"><?=$vo['introduction']?></p>
							</div>
							<div class="appmsg_action">
								<a href="<?=Url::toRoute(['material/create', 'group_id' => $vo['group_id']])?>">编辑</a>
								<a href="javascript:;" onClick="deleteItem(<?=$vo['group_id']?>)">删除</a>	
							</div>
						</li>
					<?php }else{ ?>
						<!-- 多图文 -->
						<li class="appmsg_li">
							<div class="appmsg_item">
								<p class="title"><?= Yii::$app->formatter->asDatetime($vo['add_time'])?></p>
								<div class="main_img">
									<img src="<?=$vo['path']?>"/>
									<h6><?=$vo['title']?></h6>
								</div>
								<p class="desc"><?=$vo['introduction']?></p>
							</div>
							<?php foreach($vo['child'] as $k=>$va): ?>
							<div class="appmsg_sub_item">
								<p class="title"><?=$va['title']?></p>
								<div class="main_img">
									<img src="<?=$va['path']?>"/>
								</div>
							</div>
							<?php endforeach; ?>
							<div class="appmsg_action">
								<a href="<?=Url::toRoute(['material/create', 'group_id' => $vo['group_id']])?>">编辑</a>
								<a href="javascript:;" onClick="deleteItem(<?=$vo['group_id']?>)">删除</a>
							</div>
						</li>
					<?php } ?>
						<?php endforeach; ?>
					</ul>
				</div>
			  </div>
              <div class="row">
                  <div class="col-lg-12" align="right">
                      <?= LinkPager::widget(['pagination' => $pages]); ?>
                  </div>
              </div>
          </section>
      </div>


	<!-- /主体 -->
	  <div class="loading" style="display: none">
		  <div class="box_overlay"></div>
		  <div style="top:30%;left:40%;height:200px;width:100px;overflow-y:auto;overflow-x:hidden;z-index: 10;background-color: none;position: fixed"><ul class="mt_10"><center><br/><br/><br/><img src="/weixin/images/loading.gif"/></center></ul></div>
	  </div>
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
                    var addon="";

                    if(addon){
                        url= $(this).attr('url');
                    }
                }
                window.location.href = url;
            });

            //回车自动提交
            $('.search-form').find('input').keyup(function(event){
                if(event.keyCode===13){
                    $("#search").click();
                }
            });
			$('.syc_to').click(function(){
				$('.loading').fadeIn();
				var url=$(this).attr('url');
				$.get(url).success(function(data){
				 $('.loading').fadeOut();
				 var data=$.parseJSON(data);
				 if(data.status==1){
				 updateAlert(data.info ,'alert-success');
				 setTimeout(function(){
				 location.reload();
				 },1500);
				 }else{
				 updateAlert(data.info);
				 }
				 })
			})
			$('.syc_from').click(function(){
				$('.loading').fadeIn();
				var url=$(this).attr('url');
				$.get(url).success(function(data){
					$('.loading').fadeOut();
					var data=$.parseJSON(data);
					if(data.status==1){
						updateAlert(data.info ,'alert-success');
						setTimeout(function(){
							location.reload();
						},1500);
					}else{
						updateAlert(data.info);
					}
				})
			})
        })

		function deleteItem(group_id){
			if(!confirm('确认删除？')) return false;
			if(group_id){
				$.post("/admin/material/delete",{group_id:group_id},function(data){
					var data=$.parseJSON(data);
					if(data.status==1){
						updateAlert(data.info ,'alert-success');
						setTimeout(function(){
							location.href=data.url;
						},1500);
					}
				});
			}
		}
    </script>

</body>
</html>