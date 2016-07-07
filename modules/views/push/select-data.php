<?php

use yii\helpers\Html;
?>
<?= Html::cssFile('@web/weixin/css/module.css') ?>
<?php if(empty($list)): ?>
	<div class="empty_container"><p>相关数据为空，请先添加相关数据</p></div>
<?php else: ?>
	<div class="data-table">
        <div class="table-striped">
          <table cellspacing="1" class="table table-bordered">
            <!-- 表头 -->
            <thead>
              <tr>
				<th>选择</th>
                <th >编号</th>
                <th width='75%'>文本素材内容</th>
              </tr>
            </thead>
            <!-- 列表 -->
            <tbody>
              <?php foreach($list as $key=>$vo):?>
                <tr class="text_select">
                  <td>
                    	<input type="radio" id="check_<?=$vo['id']?>" name="ids[]" value="<?=$vo['id']?>" class="ids regular-radio">
                    	<label for="check_<?=$vo['id']?>"></label>
                  </td>
                  <td><?=$vo['id']?></td>
                  <td><?=$vo['name']?></td>
                </tr>
             <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
	  <script type="text/javascript">
			$(function(){
				$('.text_select').click(function(){
					var is_check=$(this).find("input").prop('checked');
					if(!is_check){
						$('input[type=radio]').prop('checked',false);
						$(this).find("input").prop('checked',true);
					}
				})
			})
			</script>
<?php endif; ?>
