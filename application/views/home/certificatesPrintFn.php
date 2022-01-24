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
			.certificate{
			<?php if ($template['page_layout'] == 2) { ?>
				width: 296mm;
				height: 210mm;
			<?php } else { ?>
				width: 210mm;
				height: 296mm;
			<?php } ?>
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
			.pagebreak {
				page-break-before: always;
			}
		}
</style>
<div class="certificate">
	<?=$this->certificate_model->tagsReplace($user_type, $userID, $template, $print_date)?>
</div>

