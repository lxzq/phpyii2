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
				<li class="current">
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
              <div class="table-bar">
                  <div class="fl">
                      <div class="tools"> <a class="btn" href="/admin/user-group/add">新 增</a> &nbsp;
					  <!--<button class="btn ajax-post confirm" url="/admin/user-group/delete" target-form="ids">删 除</button>-->
                          <a href="javascript:void(0);" class="btn get_weixin_group">拉取微信用户分组到本地</a>
                      </div>
                  </div>
                  <!-- 高级搜索 -->
                  <div class="search-form fr cf">

                      <div class="sleft">
                          <input type="text" placeholder="请输入关键字"  class="search-input" name="nickname">
                          <a url="/admin/user/list" id="search" href="javascript:;" class="sch-btn"><i class="btn-search"></i></a> </div>
                  </div>
                  <!-- 多维过滤 -->
              </div>
              <!-- 数据列表 -->
              <div class="data-table">
                      <table cellspacing="1" class="table table-bordered ">
                          <!-- 表头 -->
                          <thead>
                          <tr>
                              <th class="row-selected row-selected"> <input type="checkbox" class="check-all regular-checkbox" id="checkAll"><label for="checkAll"></label></th>
                              <th>分组名称</th>
                              <th>描述</th>
							  <th>微信用户数</th>
                              <th>添加时间</th>
                              <th>修改时间</th>
                              <th>操作</th>
                          </tr>
                          </thead>

                          <!-- 列表 -->
                          <tbody>
                          <?php foreach( $list as  $row): ?>
                              <tr>
                                  <td><input type="checkbox" id="check_<?= $row['id'] ?>" name="ids[]" value="<?=$row['id']?>" class="ids regular-checkbox">
                                      <label for="check_<?= $row['id'] ?>"></label></td>
                                  <td><?= $row["group_name"] ?></td>
                                  <td><?= $row['description'] ?></td>
                                  <td><?= $row['user_count']?></td>
                                  <td><?=Yii::$app->formatter->asDate($row['add_time'])?></td>
                                  <td><?=Yii::$app->formatter->asDate($row['update_time'])?></td>
                                  <td>
                                      <a class="btn" href="/admin/user-group/change?id=<?=$row['id']?>" target="_self">编辑</a>
                                      <a class="btn btn-danger confirm" class="confirm" href="/admin/user-group/delete?id=<?=$row['id']?>">删除</a>

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
            //拉取微信用户信息到本地
            $('.get_weixin_group').click(function(){
                $.get('/admin/user-group/get-weixin-group').success(function(data){
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