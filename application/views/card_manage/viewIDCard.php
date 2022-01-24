<style type="text/css">
	.certificate{
	<?php if (empty($template['background'])) { ?>
			background: #fff;
	<?php } else { ?>
		background-image: url("<?=base_url('uploads/certificate/' . $template['background'])?>");
		background-repeat: no-repeat !important;
		background-size: 100% 100% !important;

	<?php } ?>
		padding: <?=$template['top_space'] . 'px ' . $template['right_space'] . 'px ' . $template['bottom_space'] . 'px ' . $template['left_space'] . 'px'?>;
		font-family: Arial;
		width: <?=$template['layout_width']?>mm;
		height: <?=$template['layout_height']?>mm;
		margin: 0 auto;
	}
</style>
<div class="certificate">
	<?=$template['content']?>
</div>
