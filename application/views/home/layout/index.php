<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta name="keyword" content="<?php echo $page_data['meta_keyword']; ?>">
		<meta name="description" content="<?php echo $page_data['meta_description']; ?>">
		<!-- Favicon -->
		<link rel="shortcut icon" href="<?php echo base_url('uploads/frontend/images/' . $cms_setting['fav_icon']); ?>">
		<title><?php echo $page_data['page_title'] . " - " . $cms_setting['application_title']; ?></title>
		<!-- Bootstrap -->
		<link href="<?php echo base_url() ?>assets/frontend/css/bootstrap.min.css" rel="stylesheet">
		<!-- Template CSS Files  -->
		<link rel="stylesheet" href="<?php echo base_url('assets/vendor/font-awesome/css/all.min.css'); ?>">
		<link rel="stylesheet" href="<?php echo base_url('assets/frontend/plugins/animate.min.css'); ?>">
		<link rel="stylesheet" href="<?php echo base_url('assets/frontend/css/responsive.css'); ?>">
		<link rel="stylesheet" href="<?php echo base_url('assets/frontend/css/owl.carousel.min.css'); ?>">
		<link rel="stylesheet" href="<?php echo base_url('assets/vendor/select2/css/select2.min.css'); ?>">
		<link rel="stylesheet" href="<?php echo base_url('assets/vendor/sweetalert/sweetalert-custom.css');?>">
		<link rel="stylesheet" href="<?php echo base_url('assets/vendor/bootstrap-datepicker/css/bootstrap-datepicker.standalone.css'); ?>">
		<link rel="stylesheet" href="<?php echo base_url('assets/frontend/plugins/magnific-popup/magnific-popup.css'); ?>">
	<?php if ($cms_setting['theme'] == 'red') { ?>
		<link rel="stylesheet" href="<?php echo base_url('assets/frontend/css/style_red.css'); ?>">
	<?php } else { ?>
		<link rel="stylesheet" href="<?php echo base_url('assets/frontend/css/style_blue.css'); ?>">
	<?php } ?>
		<script src="<?php echo base_url('assets/vendor/jquery/jquery.min.js'); ?>"></script>
		<!-- If user have enabled CSRF proctection this function will take care of the ajax requests and append custom header for CSRF -->
		<script type="text/javascript">
			var base_url = "<?php echo base_url(); ?>";
			var csrfData = <?php echo json_encode(csrf_jquery_token()); ?>;
			$(function($) {
				$.ajaxSetup({
					data: csrfData
				});
			});
		</script>
	</head>
	<body>
		<!-- Preloader -->
		<div class="loader-container">
			<div class="lds-dual-ring"></div>
		</div>
		<?php $this->load->view('home/layout/header'); ?>
		<?php echo $main_contents; ?>
		<?php $this->load->view('home/layout/footer'); ?>
	</body>
</html>