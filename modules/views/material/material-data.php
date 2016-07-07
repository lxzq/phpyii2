<?php use yii\helpers\Html;?>
<?= Html::cssFile('@web/weixin/css/weixin.css') ?>
<?php if(empty($list)): ?>
	<div class="empty_container"><p>您的图文素材库为空，<a href="/admin/material/index" target="_blank">请先增加素材</a></p></div>
<?php else: ?>
	<div class="data_container">
		<ul class="material_list">
		  <?php foreach($list as $key=>$vo): if($vo['count']==1): ?>
		  <li class="appmsg_li" data-id="<?= $vo['id']?>" data-group_id="<?= $vo['group_id']?>" style="overflow:hidden">
				<div class="appmsg_item">
					<h6><?= $vo['title']?></h6>
					<p class="title"><?= Yii::$app->formatter->asDate($vo['add_time']) ?></p>
					<div class="main_img">
						<img src="<?= $vo['path'] ?>"/>
					</div>
					<p class="desc"><?= $vo['introduction'] ?></p>
				</div>
				<div class="hover_area"></div>
			</li>
			<?php else: ?>
			<li class="appmsg_li" data-id="<?= $vo['id']?>" data-group_id="<?= $vo['group_id']?>" style="overflow:hidden">
				<div class="appmsg_item">
					<p class="title"><?= Yii::$app->formatter->asDate($vo['add_time']) ?></p>
					<div class="main_img">
						<img src="<?= $vo['path'] ?>"/>
						<h6><?= $vo['title']?></h6>
					</div>
					<p class="desc"><?= $vo['introduction'] ?></p>
				</div>
				<?php foreach($vo['child'] as $vv): ?>
				<div class="appmsg_sub_item">
					<p class="title"><?= $vo['title']?></p>
					<div class="main_img">
						<img src="<?= $vo['path'] ?>"/>
					</div>
				</div>
				<?php endforeach; ?>
				<div class="hover_area"></div>
			</li>
			<?php endif; endforeach; ?>
		</ul>
	</div>
<?php endif; ?>
