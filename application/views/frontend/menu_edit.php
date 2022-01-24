<?php
	$branch_id 		= '';
	$title 			= '';
	$parent_menu 	= '';
	$parent_id 		= 0;
	$publish 		= true;
	if ($menu['system']) {
		if (is_superadmin_loggedin()) {
			$branch_id = $this->uri->segment(5);
		} else {
			$branch_id = get_loggedin_branch_id();
		}
		$query = $this->db->select('*')
		->from("front_cms_menu_visible")
		->where(array('menu_id' => $menu['id'], 'branch_id' => $branch_id))
		->get()->row();
		if (!empty($query->name)) {
			$title = $query->name;
		} else {
			$title = $menu['title'];
		}
		if (!empty($query->invisible)) {
			if ($query->invisible !== 0) {
				$publish = false;
			}
		}
		if (!empty($query->parent_id)) {
			$parent_id = $query->parent_id;
		} else {
			$parent_id = $menu['parent_id'];
		}
	} else {
		$branch_id 	= $menu['branch_id'];
		$title 		= $menu['title'];
		$publish 	= $menu['publish'];
		$parent_id 	= $menu['parent_id'];
		if (!is_superadmin_loggedin()) {
			if (get_loggedin_branch_id() !== $menu['branch_id']) {
				redirect ('404_override');
			}
		}
	}
	
	$query_submenus = $this->db->select('id')
	->from("front_cms_menu")
	->where(array('parent_id' => $menu['id']))
	->get()->num_rows();
	if ($query_submenus !== 0) {
		$parent_menu = 'disabled';
	}
?>
<section class="panel">
	<div class="tabs-custom">
		<ul class="nav nav-tabs">
			<li>
				<a href="<?php echo base_url('frontend/menu'); ?>"><i class="fas fa-list-ul"></i> <?php echo translate('menu') . " " . translate('list'); ?></a>
			</li>
			<li class="active">
				<a href="#edit" data-toggle="tab"><i class="far fa-edit"></i> <?php echo translate('edit') . " " . translate('menu'); ?></a>
			</li>
		</ul>
		<div class="tab-content active" id="edit">
			<?php echo form_open($this->uri->uri_string(), array('class' => 'form-horizontal form-bordered frm-submit')); ?>
				<input type="hidden" name="menu_id" value="<?php echo $menu['id']; ?>">
				<input type="hidden" name="branch_id" value="<?php echo $branch_id; ?>">
				<?php if (is_superadmin_loggedin()): ?>
					<div class="form-group">
						<label class="col-md-3 control-label"><?=translate('branch')?> <span class="required">*</span></label>
						<div class="col-md-6">
							<?php
							$arrayBranch = $this->app_lib->getSelectList('branch');
							echo form_dropdown("branch_id", $arrayBranch, $branch_id, "class='form-control' data-width='100%'
							data-plugin-selectTwo  data-minimum-results-for-search='Infinity'");
							?>
							<span class="error"></span>
						</div>
					</div>
				<?php endif; ?>
				<div class="form-group">
					<label class="col-md-3 control-label"><?php echo translate('title'); ?> <span class="required">*</span></label>
					<div class="col-md-6">
						<input type="text" class="form-control" name="title" value="<?php echo set_value('title', $title); ?>" />
						<span class="error"></span>
					</div>
				</div>
				<div class="form-group">
					<label  class="col-md-3 control-label"><?php echo translate('position'); ?> <span class="required">*</span></label>
					<div class="col-md-6">
						<input type="text" class="form-control" name="position" value="<?php echo set_value('position', $menu['ordering']); ?>" />
						<span class="error"></span>
					</div>
				</div>
				<div class="form-group">
					<label  class="col-md-3 control-label"><?php echo translate('publish'); ?></label>
					<div class="col-md-6">
	                    <div class="material-switch">
	                        <input class="switch_lang" name="publish" id="publish" type="checkbox" <?php echo set_checkbox('publish', '1', $publish ? true : false); ?> />
	                        <label for="publish" class="label-primary"></label>
	                    </div>
					</div>
				</div>
				<?php if (!$menu['system']): ?>
				<div class="form-group">
					<label  class="col-md-3 control-label"><?php echo translate('target_new_window'); ?></label>
					<div class="col-md-6">
	                    <div class="material-switch">
	                        <input name="new_tab" id="new_tab" type="checkbox" value="1" <?php echo set_checkbox('new_tab', '1', $menu['open_new_tab'] ? true : false); ?> />
	                        <label for="new_tab" class="label-primary"></label>
	                    </div>
					</div>
				</div>
				<div class="form-group">
					<label  class="col-md-3 control-label"><?php echo translate('external_url'); ?></label>
					<div class="col-md-6">
	                    <div class="material-switch">
	                        <input class="ext_url" name="external_url" id="external_url" type="checkbox" value="1" <?php echo set_checkbox('external_url', '1', $menu['ext_url'] ? true : false); ?> />
	                        <label for="external_url" class="label-primary"></label>
	                    </div>
					</div>
				</div>
				<div class="form-group">
					<label  class="col-md-3 control-label"><?php echo translate('external_link'); ?></label>
					<div class="col-md-6">
	                    <input type="text" class="form-control" name="external_link" id="external_link" value="<?php echo set_value('external_link', $menu['ext_url_address']); ?>" <?php echo (!set_value('external_url',$menu['ext_url'])) ? 'disabled' : ''; ?> />
						<span class="error"><?php echo form_error('external_link'); ?></span>
					</div>
				</div>
				<?php endif; ?>
				<div class="form-group">
					<label class="col-md-3 control-label"><?=translate('parent_menu')?></label>
					<div class="col-md-6">
						<?php
						$getMenuList = $this->frontend_model->getMenuList($branch_id);
			            $array = array(0 => translate('select'));
			            foreach ($getMenuList as $row) {
			            	if ($row['id'] == $menu['id']) continue;
			                $array[$row['id']] = ' - ' . $row['title'];
			            }
						echo form_dropdown("parent_id", $array, $parent_id, "class='form-control' data-width='100%'  " . $parent_menu . "
						data-plugin-selectTwo  data-minimum-results-for-search='Infinity'");
						?>
						<span class="error"></span>
					</div>
				</div>
				<footer class="panel-footer mt-lg">
					<div class="row">
						<div class="col-md-2 col-md-offset-3">
							<button type="submit" class="btn btn-default btn-block" data-loading-text="<i class='fas fa-spinner fa-spin'></i> Processing">
								<i class="fas fa-edit"></i> <?php echo translate('update'); ?>
							</button>
						</div>
					</div>	
				</footer>
			<?php echo form_close(); ?>
		</div>
	</div>
</section>