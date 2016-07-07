 <?php use yii\helpers\Html;?>
 <?= Html::cssFile('@web/weixin/css/weixin.css') ?>
<?php if(empty($list)): ?>
	<div class="empty_container"><p>您的图片素材库为空，<a href="/admin/material/picture" target="_blank">请先增加素材</a></p></div>
<?php else: ?>
	<div class="data_container">
		<ul class="material_list">
		  <?php foreach($list as $key=>$vo):?>
			<li class="appmsg_li" data-id="<?=$vo['id']?>" data-image-id="<?=$vo['cover_id']?>" style="overflow:hidden">
				<div class="appmsg_item">
					<div class="main_img">
						<img src="<?=$vo['cover_url']?>" width='200px' height='200px'/>
					</div>
				</div>
				<div class="hover_area"></div>
			</li>
			<?php endforeach; ?>
		</ul>
	</div>
<?php endif; ?>
