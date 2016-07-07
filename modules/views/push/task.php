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
          <section>
              <div class="table-bar">
                  <div class="fl">
                      <div class="tools">
                          <a href="/admin/push/task-add" class="btn">添加推送</a>
                      </div>
                  </div>
              </div>
              <!-- 数据列表 -->
              <div class="data-table">
                      <table cellspacing="1" class="table table-bordered ">
                          <!-- 表头 -->
                          <thead>
                          <tr>
                              <th>推送名称</th>
                              <th>推送时间</th>
                              <th>添加时间</th>
                              <th>修改时间</th>
                              <th>操作</th>
                          </tr>
                          </thead>

                          <!-- 列表 -->
                          <tbody>
                          <?php foreach( $info as  $row): ?>
                              <tr>
                                  <td><?= $row->template["title"] ?></td>
                                  <td><?= Yii::$app->formatter->asDatetime($row['send_time']) ?></td>
                                  <td><?= Yii::$app->formatter->asDatetime($row['add_time']) ?></td>
                                  <td><?= Yii::$app->formatter->asDatetime($row['change_time']) ?></td>
                                  <td><a class="btn btn-warning" href="<?=Url::toRoute(['push/task-add','setting_id'=>$row['id']])?>">修改</a>&nbsp;&nbsp;&nbsp;<a class="btn btn-danger task_delete" href="javascript:void(0);" data-id="<?=$row['id']?>">删除</a></td>
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
    $('.task_delete').click(function(){
        var id=$(this).data('id');
        var _this=$(this);
        $.get('/admin/push/task-delete?id='+id).success(function(data){
            var data=$.parseJSON(data);
            if(data.status==1){
                updateAlert(data.info ,'alert-success');
                $(_this).parent().parent().remove();
            }else{
                updateAlert(data.info);
            }
        })
    })


	
})
</script>
</body>
</html>