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
              <div class="table-bar">
                  <div class="fl">
                      <div class="tools">
                          <a href="javascript:void(0);" class="btn get_template">拉取微信模板消息到本地</a>
                          <a href="/admin/public/template-add" class="btn btn-warning">新增模板</a>
                      </div>
					 
                  </div>
              </div>
              <!-- 数据列表 -->
              <div class="data-table">
                      <table cellspacing="1" class="table table-bordered ">
                          <!-- 表头 -->
                          <thead>
                          <tr>
                              <th>模板id</th>
                              <th>模板标题</th>
                              <th>一级行业</th>
                              <th>二级行业</th>
                              <th>设置推送</th>
                              <th>操作</th>
                          </tr>
                          </thead>

                          <!-- 列表 -->
                          <tbody>
                          <?php foreach( $list as $vo): ?>
                              <tr>
                                  <td><?=$vo['template_id']?></td>
                                  <td><?= $vo["title"] ?></td>
                                  <td><?= $vo['primary_industry'] ?></td>
                                  <td><?= $vo['deputy_industry'] ?></td>
                                  <td><?php if($vo['flag']==1):echo "是";else:echo "否";endif;?></td>
                                  <td>
                                     <a href="/admin/public/template-detail?id=<?=$vo['id']?>" class="btn" target="_self">详细资料</a>
                                  <?php if(!empty($vo['template_id'])):?>
                                    <a href="/admin/public/template-change?id=<?=$vo['id']?>" class="btn btn-warning" rel="<?=$vo['id']?>">修改</a>
                                  <?php else:?>
                                      <a href="/admin/public/template-add?id=<?=$vo['id']?>" class="btn btn-warning" rel="<?=$vo['id']?>">修改</a>
                                  <?php endif;?>
                                      <a href="javascript:void(0);" class="btn btn-danger delete_template" rel="<?=$vo['id']?>">删除</a>
                                  </td>
                              </tr>
                          <?php endforeach; ?>
                          </tbody>
                      </table>
              </div>
              <div class="row">
                  <div class="col-lg-12" align="right">
                      <?= LinkPager::widget(['pagination' => $pages]); ?>
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
	//拉取微信模板消息到本地
	$('.get_template').click(function(){
		$.get('/admin/public/get-template').success(function(data){
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
    //删除模板消息
    $('.delete_template').click(function(){
        var template_id=$(this).attr('rel');
        $.get('/admin/public/template-delete?id='+template_id).success(function(data){
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
</script>
</body>
</html>