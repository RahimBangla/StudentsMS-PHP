<div class="panel mailbox">
	<div class="panel-body">
		<ul class="nav nav-pills nav-stacked">
			<?php
			$branchID = "";
			if (is_superadmin_loggedin()) {
				$branchID = '?branch_id=' . $branch_id;
			}

			$tab_active = $this->uri->segment(3, 'home');
			$result = web_menu_list('', 1, $branch_id);
			foreach ($result as $row) {
				if ($row['alias'] == 'pages') continue;
				$url = base_url('frontend/section/' . $row['alias'] . $branchID);
			?>
			<li class="<?php echo ($row['alias'] == $tab_active ? 'active' : ''); ?>"> <a href="<?php echo $url; ?>"><i class="far fa-arrow-alt-circle-right"></i> <?php echo $row['title']; ?></a></li>
			<?php } ?>
		</ul>
	</div>
</div>