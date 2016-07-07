<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\VideoForm */

use yii\helpers\Html;
use yii\helpers\Url;
?>
<ul class="tab-nav nav">
		<li class="current">
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
		<li class="">
			<a href="<?=Url::toRoute(['public/template'])?>">
				模板消息管理
				<span class="arrow fa fa-sort-up"></span>
			</a>
		</li>
</ul>