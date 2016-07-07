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
				<li class="current">
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
              <div class="table-bar">
                  <div class="fl">
                      <div class="tools"> <a href="javascript:void(0);" class="btn setting_group">设置用户组</a> &nbsp; <a href="javascript:void(0);" class="btn get_weixin_user">拉取微信用户信息到本地</a> 
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
                              <th>头像</th>
                              <th>用户昵称</th>
                              <th>备注</th>
                              <th>性别</th>
                              <th>分组</th>
                              <th>状态</th>
                              <th>操作</th>
                          </tr>
                          </thead>

                          <!-- 列表 -->
                          <tbody>
                          <?php foreach( $list as  $row): ?>
                              <tr>
                                  <td><input type="checkbox" id="check_<?= $row['id'] ?>" name="ids[]" value="<?=$row['id']?>" class="ids regular-checkbox">
                                      <label for="check_<?= $row['id'] ?>"></label></td>
                                  <td><img src="<?= $row["userface"] ?>" width="50px" height="50px"></td>
                                  <td><?= $row['nickname'] ?></td>
                                  <td><?= $row['remark'] ?></td>
                                  <td><?php if(1===$row['sex']){echo '男';}elseif($row['sex']==2){echo '女';}else{echo '未知';} ?></td>
                                  <td><?= $group_list[$row['weixin_group_id']]['group_name']?></td>
                                  <td><?php if($row['is_del']==1):echo '已关注';else:echo '已取消关注';endif;?></td>
                                  <td>
                                     <!-- <a href="/admin/user/detail?user_id=<?/*=$row['id']*/?>" target="_self">详细资料</a>-->

                                      <a href="javascript:void(0);" class=" btn set_remark" rel="<?=$row['id']?>">备注</a>

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
      <!-- 用户分组弹框 -->
      <div class="group_html" style="display:none">
          <div class="manage_group normal_dialog">
              <div class="content">
                  <select name="group" id="select_group" style="width:100%">
                      <?php foreach( $group_list as  $va): ?>
                          <option value="<?=$va['weixin_group_id']?>"><?=$va['group_name']?></option>
                      <?php endforeach; ?>
                  </select>
                  <div class="btn_wrap"><button class="btn setting_group" url="/admin/user/change-group" target-form="ids">确定</button></div>
              </div>
          </div>
      </div>
      <!-- 备注用户名 -->
      <div class="remark_html" style="display:none">
          <div class="manage_group normal_dialog">
              <div class="content">
                  <input name="remark" id="set_remark" value="" placeholder="请输入备注信息" class="text"  />
                  <div class="btn_wrap"><button class="btn setting_remark" url="/admin/user/set-remark">确定</button></div>
              </div>
          </div>
      </div>
  </div>
</div>
    <div class="loading" style="display: none">
        <div class="box_overlay"></div>
        <div style="top:30%;left:40%;height:200px;width:100px;overflow-y:auto;overflow-x:hidden;z-index: 10;background-color: none;position: fixed"><ul class="mt_10"><center><br/><br/><br/><img src="/weixin/images/loading.gif"/></center></ul></div>
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
	$('select[name=group]').change(function(){
		location.href = this.value;
	});	
	//设置分组
	$('.setting_group').click(function(){
		var html = $($('.group_html').html());
		query = $('.ids').serialize();
		if(query==""){
			alert('请选择用户');
			return;
		}
		$.Dialog.open('设置用户分组',{width:300,height:160},html);
		//$.thinkbox(html);
		$('button',html).click(function(){
			that = this;
			target = $(that).attr('url');
			query = query + "&group_id="+$('#select_group', html).val();
			$(that).addClass('disabled').attr('autocomplete','off').prop('disabled',true);
            $.post(target,query).success(function(data){
				//location.reload();
                if(data==1){
                    updateAlert("设置成功！" ,'alert-success');
                }else{
                    updateAlert("设置失败，请重试！");
                }
                setTimeout(function(){
                    location.reload();
                },1500);
				$('.thinkbox-modal-blackout-default,.thinkbox-default').hide();
            });
		})
	})
	//拉取微信用户信息到本地
	$('.get_weixin_user').click(function(){
        $('.loading').fadeIn();
		$.get('/admin/user/get-weixin-user').success(function(data){
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
	$('.set_remark').click(function(){
		var html = $($('.remark_html').html());
		var uid = $(this).attr('rel');
		$.post("/admin/user/get-remark",{'uid':uid},function(re){
			$("input[name='remark']").val(re);
		});
		$.Dialog.open('设置用户备注',{width:300,height:160},html);
		//$.thinkbox(html);
		$('button',html).click(function(){
			that = this;
			target = $(that).attr('url');
			query = "uid="+uid+"&remark="+$('#set_remark', html).val() ;
			$(that).addClass('disabled').attr('autocomplete','off').prop('disabled',true);
            $.post(target,query).success(function(data){
				location.reload();
				$('.thinkbox-modal-blackout-default,.thinkbox-default').hide();
            });
		})
	})	
	
})
</script>
</body>
</html>