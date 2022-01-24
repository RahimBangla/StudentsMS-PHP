<style type="text/css">
	.sub_menu {
		background: #f0f0f0;
	}
	html.dark .sub_menu {
		background: #3e3c3c;
		color: #ddd;
	}
</style>
<?php if (is_superadmin_loggedin() ): ?>
	<?php $this->load->view('frontend/branch_select'); ?>
<?php endif; 
if (!empty($branch_id)) {
	?>

<section class="panel">
	<div class="tabs-custom">
		<ul class="nav nav-tabs">
			<li class="active">
				<a href="#list" data-toggle="tab"><i class="fas fa-list-ul"></i> <?php echo translate('menu') . " " . translate('list'); ?></a>
			</li>
	<?php if (get_permission('frontend_menu', 'is_add')) { ?>
			<li class="">
				<a href="#create" data-toggle="tab"><i class="far fa-edit"></i> <?php echo translate('add') . " " . translate('menu'); ?></a>
			</li>
	<?php } ?>
		</ul>
		<div class="tab-content">
			<div id="list" class="tab-pane active">
				<table class="table tbr-middle table-condensed table_default" data-order="true" cellspacing="0" width="100%">
					<thead>
						<tr>
							<th><?php echo translate('sl'); ?></th>
							<th><?php echo translate('menu') . " " . translate('type'); ?></th>
							<th><?php echo translate('title'); ?></th>
							<th><?php echo translate('position'); ?></th>
							<th><?php echo "Sub Menu"; ?></th>
							<th><?php echo translate('publish'); ?></th>
							<th><?php echo translate('action'); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php
							$count = 1;
							$menulist = $this->frontend_model->getMenuList($branch_id);
							if (!empty($menulist)) {
								foreach ($menulist as $row):
									$publish = '';
									$edit_branch_id = '';
									if ($row['system']) {
										if (is_superadmin_loggedin()) {
											$edit_branch_id = "/" . $branch_id; 
										}
										if ($row['invisible'] == 0) {
											$publish = 'checked';
										}
									} else {
										if ($row['publish']) {
											$publish = 'checked';
										}
									}
								?>
						<tr>
							<td><?php echo $count++; ?></td>
							<td><?php
							if ($row['system'] == 1) {
								echo "System Menu";
							} else {
								echo "Has Been Added";
							}
							?></td>
							<td><?php echo strip_tags($row['title']); ?></td>
							<td><?php echo $row['ordering']; ?></td>
							<td><?php
							 if (!empty($row['submenu'])) {
							 	echo '<i class="fas fa-arrow-down"></i>';
							 } else {
							 	echo '-';
							 } ?></td>
							<td>
		                        <div class="material-switch ml-xs">
		                            <input class="switch_menu" id="switch_<?php echo $row['id']; ?>" data-menu-id="<?php echo $row['id']; ?>" name="sw_menu<?php echo $row['id']; ?>" type="checkbox" <?php echo $publish; ?> />
		                            <label for="switch_<?php echo $row['id']; ?>" class="label-primary"></label>
		                        </div>
							</td>
							<td class="min-w-xs">
							<?php if (get_permission('frontend_menu', 'is_edit')) { ?>
								<a href="<?php echo base_url('frontend/menu/edit/' . $row['id'] . $edit_branch_id); ?>" class="btn btn-default btn-circle icon" data-toggle="tooltip" data-original-title="<?php echo translate('edit'); ?>"> 
									<i class="fas fa-pen-nib"></i>
								</a>
							<?php } ?>
							<?php
								if ($row['system'] == 0) {
									if (get_permission('frontend_menu', 'is_delete')) {
										echo btn_delete('frontend/menu/delete/' . $row['id']); 
									}
								}
							?>
							</td>
						</tr>
					<?php if (!empty($row['submenu'])) {
						foreach ($row['submenu'] as $key => $value) {
							$publish = '';
							$edit_branch_id = '';
							if ($value['system']) {
								if (is_superadmin_loggedin()) {
									$edit_branch_id = "/" . $branch_id; 
								}
								if ($value['invisible'] == 0) {
									$publish = 'checked';
								}
							} else {
								if ($value['publish']) {
									$publish = 'checked';
								}
							}
					 ?>
						<tr class="sub_menu">
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td><i class="fas fa-angle-double-right"></i> <?php echo $value['title'] ?></td>
							<td>
		                        <div class="material-switch ml-xs">
		                            <input class="switch_menu" id="switch_<?php echo $value['id']; ?>" data-menu-id="<?php echo $value['id']; ?>" name="sw_menu<?php echo $value['id']; ?>" type="checkbox" <?php echo $publish; ?> />
		                            <label for="switch_<?php echo $value['id']; ?>" class="label-primary"></label>
		                        </div>
							</td>
							<td class="min-w-xs">
							<?php if (get_permission('frontend_menu', 'is_edit')) { ?>
								<a href="<?php echo base_url('frontend/menu/edit/' . $value['id'] . $edit_branch_id); ?>" class="btn btn-default btn-circle icon" data-toggle="tooltip" data-original-title="<?php echo translate('edit'); ?>"> 
									<i class="fas fa-pen-nib"></i>
								</a>
							<?php } ?>
							<?php
								if ($value['system'] == 0) {
									if (get_permission('frontend_menu', 'is_delete')) {
										echo btn_delete('frontend/menu/delete/' . $value['id']); 
									}
								}
							?>
							</td>
						</tr>
					<?php }} ?>
						<?php endforeach; }?>
					</tbody>
				</table>
			</div>
	<?php if (get_permission('frontend_menu', 'is_add')) { ?>
			<div class="tab-pane" id="create">
				<?php echo form_open($this->uri->uri_string(), array('class' => 'form-horizontal form-bordered frm-submit')); ?>
					<?php if (is_superadmin_loggedin()): ?>
						<div class="form-group">
							<label class="col-md-3 control-label"><?=translate('branch')?> <span class="required">*</span></label>
							<div class="col-md-6">
								<?php
								$arrayBranch = $this->app_lib->getSelectList('branch');
								echo form_dropdown("branch_id", $arrayBranch, set_value('branch_id'), "class='form-control' data-width='100%'
								data-plugin-selectTwo  data-minimum-results-for-search='Infinity'");
								?>
								<span class="error"></span>
							</div>
						</div>
					<?php endif; ?>
					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo translate('title'); ?> <span class="required">*</span></label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="title" value="<?php echo set_value('title'); ?>" />
							<span class="error"></span>
						</div>
					</div>
					<div class="form-group">
						<label  class="col-md-3 control-label"><?php echo translate('position'); ?> <span class="required">*</span></label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="position" value="<?php echo set_value('position'); ?>" />
							<span class="error"></span>
						</div>
					</div>
					<div class="form-group">
						<label  class="col-md-3 control-label"><?php echo translate('publish'); ?></label>
						<div class="col-md-6">
	                        <div class="material-switch">
	                            <input name="publish" id="publish" type="checkbox" value="1" <?php echo set_checkbox('publish', '1', true); ?> />
	                            <label for="publish" class="label-primary"></label>
	                        </div>
						</div>
					</div>
					<div class="form-group">
						<label  class="col-md-3 control-label"><?php echo translate('target_new_window'); ?></label>
						<div class="col-md-6">
	                        <div class="material-switch">
	                            <input name="new_tab" id="new_tab" type="checkbox" value="1" <?php echo set_checkbox('new_tab', '1'); ?> />
	                            <label for="new_tab" class="label-primary"></label>
	                        </div>
						</div>
					</div>
					<div class="form-group">
						<label  class="col-md-3 control-label"><?php echo translate('external_url'); ?></label>
						<div class="col-md-6">
	                        <div class="material-switch">
	                            <input class="ext_url" name="external_url" id="external_url" type="checkbox" value="1" <?php echo set_checkbox('external_url', '1'); ?> />
	                            <label for="external_url" class="label-primary"></label>
	                        </div>
						</div>
					</div>
					<div class="form-group">
						<label  class="col-md-3 control-label"><?php echo translate('external_link'); ?></label>
						<div class="col-md-6">
	                        <input type="text" class="form-control" name="external_link" id="external_link" value="<?php echo set_value('external_link'); ?>" <?php echo (!set_value('external_url')) ? 'disabled' : ''; ?> />
							<span class="error"></span>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?=translate('parent_menu')?></label>
						<div class="col-md-6">
							<?php
							$getMenuList = $this->frontend_model->getMenuList($branch_id);
				            $array = array(0 => translate('select'));
				            foreach ($getMenuList as $row) {
				                $array[$row['id']] = ' - ' . $row['title'];
				            }
							echo form_dropdown("parent_id", $array, '', "class='form-control' data-width='100%' data-plugin-selectTwo  data-minimum-results-for-search='Infinity'");
							?>
							<span class="error"></span>
						</div>
					</div>
					<footer class="panel-footer mt-lg">
						<div class="row">
							<div class="col-md-2 col-md-offset-3">
								<button type="submit" class="btn btn-default btn-block" data-loading-text="<i class='fas fa-spinner fa-spin'></i> Processing">
									<i class="fas fa-plus-circle"></i> <?php echo translate('save'); ?>
								</button>
							</div>
						</div>	
					</footer>
				<?php echo form_close(); ?>
			</div>
	<?php } ?>
		</div>
	</div>
</section>

<script type="text/javascript">
	var menu_branchID = "<?=$branch_id?>"
</script>
<?php } ?>






