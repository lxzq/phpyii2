<?php
use yii\helpers\Html;
?>
<?= Html::cssFile('@web/weixin/css/weixin.css') ?>
<?= Html::cssFile('@web/weixin/css/module.css') ?>
<?= Html::cssFile('@web/weixin/css/base.css') ?>
<?=Html::jsFile('@web/Js/jquery-1.10.2.js')?>
	<div class="data-table">
      <div class="row">
        <!--  <div class="col-lg-2">
            <b style="font-size: 24px"><? /*= Html::encode($this->title) */ ?></b>
        </div>-->

        <div class="col-lg-11">
            <div class="form-inline form-group">
              <select class="form-control shop" name="group">
                <option value="/admin/public/select-users?group_id=all&isAjax=1">全部用户</option>
                <?php foreach($group_list as $va):?>
                  <option value="/admin/public/select-users?group_id=<?=$va['weixin_group_id']?>&isAjax=1" <?php if($group_id==$va['weixin_group_id']):echo 'selected="selected"';endif;?>><?=$va['group_name']?></option>
                <?php endforeach;?>
              </select>
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
                <th>用户性别</th>
                <th>用户分组</th>
              </tr>
            </thead>
            <!-- 列表 -->
          <?php if(empty($list)): ?>
              <tbody class="user_list">
                <tr class="empty_container"><td colspan="5"><p>没有用户数据</p></td></tr>
              </tbody>
          <?php else: ?>
            <tbody class="user_list">
              <?php foreach($list as $key=>$vo):?>
                <tr class="text_select">
                  <td>
                    	<input type="checkbox" id="check_<?=$vo['id']?>" name="ids[]" value="<?=$vo['id']?>" class="ids regular-checkbox" <?php if(!empty($users[$vo['id']])): echo "checked='checked'";endif;?>>
                    	<label for="check_<?=$vo['id']?>"></label>
                  </td>
                  <td><img src="<?= $vo["userface"] ?>" width="50px" height="50px"></td>
                    <td type="nickname"><?=$vo['nickname']?></td>
                  <td><?php if(1===$vo['sex']){echo '男';}elseif($vo['sex']==2){echo '女';}else{echo '未知';} ?></td>
                  <td><?= $group_list[$vo['weixin_group_id']]['group_name']?></td>
                    <input type="hidden" value="<?=$vo['openid']?>" name="openid">
                </tr>
             <?php endforeach; ?>
            </tbody>
          <?php endif; ?>
          </table>
        </div>
      </div>
	  <script type="text/javascript">
			$(function(){
				$('table').on('click','.text_select',function(){
					var is_check=$(this).find("input").prop('checked');
					if(!is_check){
						$(this).find("input").prop('checked',true);
					}else{
                         $(this).find("input").prop('checked',false);
                    }
				})
                $('select[name=group]').change(function(){
                    location.href = this.value;
                });
			})
      </script>
