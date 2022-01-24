<style type="text/css">
		@page {
			margin: -2px;
		}
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
		}
		@media print {
			.certificate {
				width: <?=$template['layout_width']?>mm;
				height: <?=$template['layout_height']?>mm;
			<?php if (empty($template['background'])) { ?>
				background: #fff;
			<?php } else { ?>
				background-image: url("<?=base_url('uploads/certificate/' . $template['background'])?>") !important;
				background-repeat: no-repeat !important;
				background-size: 100% 100% !important;
			<?php } ?>
				-webkit-print-color-adjust: exact !important; 
				color-adjust: exact !important;
			}
			.certificate hr{
			    height: 0;
			    border-bottom: 1px solid #ddd;
			    margin: 10px 0 10px 0;
			}
			.pagebreak {
				page-break-before: always;
			}
		}
</style>

<div class="certificate">
	<?=$this->card_manage_model->admitCardTagsReplace($userID['id'], $template, $print_date, $exam_id)?>
</div>

