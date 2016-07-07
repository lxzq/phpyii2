<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\VideoForm */

use yii\helpers\Html;
?>
<!DOCTYPE HTML>
<html>
<head>
	<?= Html::jsFile('@web/ueditor/dialogs/internal.js') ?>
	<?=Html::jsFile('@web/Js/jquery-2.0.3.min.js')?>
    <style type="text/css">
    	.style_list{ box-shadow:0 0 3px #ccc inset; background:#fff}
		.style_list .tabs{ height:40px; border-bottom:1px solid #ddd;}
		.style_list .tabs a{ line-height:40px; padding:0 15px; float:left; color:#444; border-right:1px solid #ddd;}
		.style_list .tabs a.current{ background:#3C0; color:#fff;}
		.style_wrap{ margin:15px auto; width:500px;}
		#styleList li{ padding:10px 0; cursor:pointer;border:3px solid #fff}
		
    </style>
</head>
<body>
	<div class="style_list">
    	<div class="tabs">
			<?php foreach($group_list as $vo):?>
        		<a class="<?php if(!empty($vo['class'])): echo $vo['class'];endif;?>" href="/admin/material/get-style?group_id=<?=$vo['id']?>"><?=$vo['group_name']?></a>
			<?php endforeach;?>
        </div>
        <div class="style_wrap">
        	<ul id="styleList">
				<?php foreach($list as $va):?>
                	<li><?= $va['style']?></li>
				<?php endforeach;?>
            </ul>
        </div>

    </div>
	
</body>
<script type="text/javascript">
$('#styleList li').click(function(){
	 editor.execCommand("inserthtml", $(this).html());
	 dialog.close();
})
</script>
</html>

