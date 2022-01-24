
<!-- Header Starts -->
	<header class="main-header">
	<!-- Nested Container Starts -->
	<!-- Top Bar Starts -->
		<div class="top-bar d-none d-md-block">
			<div class="container px-md-0">
			<div class="row">
				<div class="col-md-6 col-sm-12"><?php echo $cms_setting['working_hours']; ?></div>
				<div class="col-md-6 col-sm-12">
					<ul class="list-unstyled list-inline">
						<li class="list-inline-item"><a href="mailto:<?php echo $cms_setting['email']; ?>">
							<i class="far fa-envelope"></i> <?php echo $cms_setting['email']; ?>
						</a></li>
						<li class="list-inline-item"><i class="fas fa-phone-volume"></i> <?php echo $cms_setting['mobile_no']; ?></li>
					<?php if (!is_loggedin()) { ?>
						<li class="list-inline-item"><a href="<?php echo base_url('authentication') . "/index/" . $cms_setting['url_alias']; ?>"><i class="fas fa-user-lock"></i> Login</a></li>
					<?php } else { ?>
						<li class="list-inline-item"><a href="<?php echo base_url('dashboard'); ?>"><i class="fas fa-home"></i> Dashboard</a></li>
					<?php } ?>
					</ul>
				</div>
			</div>
			</div>
		</div>
	<!-- Top Bar Ends -->
	<!-- Navbar Starts -->
		<div class="stricky" id="strickyMenu">
			<div class="container px-md-0">
			<nav id="nav" class="navbar navbar-expand-lg" role="navigation">
				<div class="container px-md-0">
				<!-- Logo Starts -->
					<a href="#" class="navbar-brand">
						<img src="<?php echo base_url('uploads/frontend/images/' . $cms_setting['logo']); ?>" alt="Logo">
					</a>
				<!-- Logo Ends -->
				<!-- Collapse Button Starts -->
					<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation">
						<span class="navbar-toggler-icon fa fa-bars"></span>
					</button>
				<!-- Collapse Button Ends -->
				<!-- Navbar Collapse Starts -->
					<div id="mainNav" class="navbar-collapse collapse">
						<ul class="nav navbar-nav ml-auto navbar-style3">
							<?php
							$school = '';
							$last 	= $this->uri->total_segments();
							$page 	= $this->uri->segment(2);
							if ($page == 'page') {
								if ($last > 3) {
									$school = $this->uri->segment($last);
								}
							} else {
								if ($last > 2) {
									$school = $this->uri->segment($last);
								}
							}
							$result = $this->home_model->menuList($school);
							foreach ($result as $key => $row) {
								$active_menu 	= '';
								$submenu 		= '';
								$op_new_tab 	= '';
								$submenu_active = '';
								$currentURL 	= base_url(uri_string());
								if (uri_string() == '') {
									$currentURL .= 'home/index';
								}
								if (uri_string() == 'home') {
									$currentURL .= '/index';
								}
								if ($currentURL == $row['url']) {
									$active_menu = ' active';
								}

								if (!empty($row['submenu']) && is_array($row['submenu'])) {
									$submenu = ' dropdown';
									$arrayURL = array_column($row['submenu'],'url');
									if (in_array($currentURL, $arrayURL)) {
										$submenu_active = ' active';
									}
									$row['title'] = $row['title'] . '<span class="arrow"></span>';
								}
                                if ($row['open_new_tab']) {
                                    $op_new_tab = "target='_blank'";
                                }
								if ($cms_setting['online_admission'] == 0 && $row['alias'] == 'admission') continue;
							?>
								<li class="nav-item<?php echo $active_menu; echo $submenu; echo $submenu_active; ?>">
									<a href="<?php echo $row['url']; ?>" class="nav-link" <?php echo $op_new_tab; ?>><?php echo $row['title']; ?>  </a>
									<?php if (!empty($row['submenu'])) { ?>
									<ul class="dropdown-menu" role="menu">
										<?php foreach ($row['submenu'] as $row2) { 
											$active_menu 	= '';
											$op_new_tab 	= '';
											if ($currentURL == $row2['url']) {
												$active_menu = ' active';
											}
			                                if ($row2['open_new_tab']) {
			                                    $op_new_tab = "target='_blank'";
			                                }
											?>
										<a class="dropdown-item<?php echo $active_menu; ?>" <?php echo $op_new_tab; ?> href="<?php echo $row2['url']; ?>"><?php echo $row2['title']; ?></a>
										<?php } ?>
									</ul>
								<?php } ?>
								</li>
							<?php } ?>
							<li class="nav-item m-login">
							<?php if (!is_loggedin()) { ?>
								<a href="<?php echo base_url('authentication') . "/index/" . $cms_setting['url_alias']; ?>" class="btn btn-block btn-black mt-sm">Login</a>
							<?php } else { ?>
								<a href="<?php echo base_url('dashboard'); ?>" class="btn btn-block btn-black mt-sm">Dashboard</a>
							<?php } ?>
							</li>
						</ul>
					</div>
				<!-- Navbar Collapse Ends -->
				</div>
			</nav>
		</div>
	</div>
	<!-- Navbar Ends -->
<!-- Nested Container Ends -->
</header>
<!-- Header Ends -->