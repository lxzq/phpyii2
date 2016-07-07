<?php

use yii\helpers\Html;
?>
<?= Html::cssFile('@web/weixin/css/module.css') ?>
<?= Html::cssFile('@web/weixin/css/weixin.css') ?>
<?php if(empty($list)): ?>
	<div class="empty_container"><p>相关数据为空，请先添加相关数据</p></div>
<?php else: ?>
	<div class="data-table">
      <div class="row">
        <!--  <div class="col-lg-2">
            <b style="font-size: 24px"><? /*= Html::encode($this->title) */ ?></b>
        </div>-->

        <div class="col-lg-11">
            <div class="form-inline form-group">
              <select class="form-control shop" name="shopId">
                <option value="">请选择门店</option>
                <?php foreach($shops as $vo):?>
                  <option value="<?=$vo['id']?>"><?=$vo['name']?></option>
                <?php endforeach;?>
              </select>
              <input type="text" class="form-control nickname" name="nickname" placeholder="用户昵称">
              <input type="text" class="form-control phone" name="phone" placeholder="用户手机号">
              <button class="btn btn-success" style="margin-right: 10px;margin-bottom: 10px;">查询</button>
            </div>
        </div>
      </div>
        <div class="table-striped">
          <table cellspacing="1" class="table table-bordered">
            <!-- 表头 -->
            <thead>
              <tr>
				<th>选择</th>
                <th>用户头像</th>
                <th>用户昵称</th>
                <th>用户手机</th>
              </tr>
            </thead>
            <!-- 列表 -->
            <tbody class="user_list">
              <?php foreach($list as $key=>$vo):?>
                <tr class="text_select">
                  <td>
                    	<input type="checkbox" id="check_<?=$vo['id']?>" name="id" value="<?=$vo['id']?>" class="ids regular-checkbox" >
                    	<label for="check_<?=$vo['id']?>"></label>
                  </td>
                  <td><img src="<?= $vo["userface"] ?>" width="50px" height="50px"></td>
                  <td><?=$vo['nickname']?></td>
                  <td><?=$vo['phone']?></td>
                    <input type="hidden" name="userface" value="<?= $vo["userface"] ?>"/>
                    <input type="hidden" name="nickname" value="<?= $vo["nickname"] ?>"/>
                    <input type="hidden" name="phone" value="<?= $vo["phone"] ?>"/>
                    <input type="hidden" name="openid" value="<?= $vo["openid"] ?>"/>
                </tr>
             <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
	  <script type="text/javascript">
			$(function(){
				$('table').on('click','.text_select',function(){
					var is_check=$(this).find("input").prop('checked');
					if(!is_check){
						$(this).find("input").prop('checked',true);
                        $(this).addClass('actives');
					}else{
                         $(this).find("input").prop('checked',false);
                        $(this).removeClass('actives');
                    }
				})
              $('.btn-success').click(function(){
                    var nickname=$('.nickname').val();
                    var phone=$('.phone').val();
                    var public_id=$('.public_id').val();
                    var paramas="nickname="+nickname+"&phone="+phone+"&public_id="+public_id;
                    $.post('/admin/public/select-user',paramas).success(function(data){
                        var data= $.parseJSON(data);
                        var str="";
                        $.each(data,function(i,j){
                            str+='<tr class="text_select"><td><input type="checkbox" id="check_'+ j.id+'" name="ids[]" value="'+j.id+'" class="ids regular-checkbox"><label for="check_'+ j.id+'"></label></td><td><img src="'+ j.userface+'" width="50px" height="50px"></td><td>'+ j.nickname+'</td><td>'+ j.phone+'</td></tr>';
                        })
                        $('.user_list').html(str);
                    })
              })
              $('.shop').change(function(){
                  var shopId=$(this).val();
                  var public_id=$('.public_id').val();
                  $.post('/admin/public/select-user','shopId='+shopId+"&public_id="+public_id).success(function(data){
                    var data=$.parseJSON(data);
                      if(data.list==''){
                        alert('该门店下没有用户数据');
                      }else{
                          var str="";
                          $.each(data.list,function(i,j){
                              if($.isArray(data.users) && $.inArray(parseInt(j.id),data.users)!='-1'){

                                  str+='<tr class="text_select"><td><input type="checkbox" id="check_'+ j.id+'" name="ids[]" value="'+j.id+'" class="ids regular-checkbox" checked="checked"><label for="check_'+ j.id+'"></label></td><td><img src="'+ j.userface+'" width="50px" height="50px"></td><td>'+ j.nickname+'</td><td>'+ j.phone+'</td></tr>';
                              }else{
                                  str+='<tr class="text_select"><td><input type="checkbox" id="check_'+ j.id+'" name="ids[]" value="'+j.id+'" class="ids regular-checkbox"><label for="check_'+ j.id+'"></label></td><td><img src="'+ j.userface+'" width="50px" height="50px"></td><td>'+ j.nickname+'</td><td>'+ j.phone+'</td></tr>';
                              }

                          })
                          $('.user_list').html(str);
                      }


                })
              })
                $('.commit').click(function(){
                    var select_users='';
                    $('.actives').each(function(){
                        var id,userface,phone,nickname,openid;
                        id=$(this).find('input[name="id"]').val();
                        if($('.user_lists').find("input[value="+id+"]").val()==undefined) {
                            userface = $(this).find('input[name="userface"]').val();
                            phone = $(this).find('input[name="phone"]').val();
                            nickname = $(this).find('input[name="nickname"]').val();
                            openid = $(this).find('input[name="openid"]').val();
                            select_users += '<tr><td><input type="hidden" name="user[' + id + '][id]" value="' + id + '"/><input type="hidden" name="user[' + id + '][openid]" value="' + openid + '"/><img src="' + userface + '" width="50px" height="50px"></td><td>' + nickname + '</td><td>' + phone + '</td><td><button class="btn btn-danger delete-user" type="button">删除</button></td></tr>';
                        }
                    });
                   $('.user_lists').append(select_users);
                })
			})
      </script>
<?php endif; ?>
