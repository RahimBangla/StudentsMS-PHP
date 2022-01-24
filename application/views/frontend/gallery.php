<section class="panel">
	<div class="tabs-custom">
		<ul class="nav nav-tabs">
			<li class="<?php echo (!isset($validation_error) ? 'active' : ''); ?>">
				<a href="#list" data-toggle="tab"><i class="fas fa-list-ul"></i> <?php echo translate('gallery') . " " . translate('list'); ?></a>
			</li>
	<?php if (get_permission('frontend_gallery', 'is_add')) { ?>
			<li class="<?php echo (isset($validation_error) ? 'active' : ''); ?>">
				<a href="#create" data-toggle="tab"><i class="far fa-edit"></i> <?php echo translate('add') . " " . translate('gallery'); ?></a>
			</li>
	<?php } ?>
		</ul>
		<div class="tab-content">
			<div id="list" class="tab-pane <?php echo (!isset($validation_error) ? 'active' : ''); ?>">
				<table class="table table-bordered table-hover table-condensed table_default">
					<thead>
						<tr>
							<th><?php echo translate('sl'); ?></th>
<?php if (is_superadmin_loggedin()): ?>
							<th><?=translate('branch')?></th>
<?php endif; ?>
							<th><?php echo translate('thumb_image'); ?></th>
							<th><?php echo translate('gallery') . " " . translate('title'); ?></th>
							<th><?php echo translate('category'); ?></th>
							<th><?php echo translate('description'); ?></th>
							<th><?php echo translate('uploaded'); ?></th>
							<th class="no-sort"><?=translate('show_website')?></th>
							<th><?php echo translate('action'); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php
						$count = 1;
						if (!empty($gallerylist)) {
							foreach ($gallerylist as $row):
								?>
						<tr>
							<td><?php echo $count++; ?></td>
<?php if (is_superadmin_loggedin()): ?>
							<td><?php echo $row['branch_name'];?></td>
<?php endif; ?>
							<td><img class="img-border" src="<?php echo $this->gallery_model->get_image_url($row['thumb_image']); ?>" width="80"/></td>
							<td><?php echo $row['title']; ?></td>
							<td><?php echo get_type_name_by_id('front_cms_gallery_category', $row['category_id']); ?></td>
							<td><?php echo $row['description']; ?></td>
							<td><?php
								$uploadedCount = json_decode($row['elements'], true);
								echo count($uploadedCount); ?></td>
							<td>
							<?php if (get_permission('event', 'is_edit')) { ?>
								<div class="material-switch ml-xs">
									<input class="gallery_website" id="websiteswitch_<?=$row['id']?>" data-id="<?=$row['id']?>" name="evt_switch_website<?=$row['id']?>" 
									type="checkbox" <?php echo ($row['show_web'] == 1 ? 'checked' : ''); ?> />
									<label for="websiteswitch_<?=$row['id']?>" class="label-primary"></label>
								</div>
							<?php } ?>
							</td>
							<td class="min-w-xs">
								<a href="<?php echo base_url('frontend/gallery/album/' . $row['id']); ?>" class="btn btn-default btn-circle icon" data-toggle="tooltip" data-original-title="Add Photos / Videos"> 
									<i class="fas fa-photo-video"></i>
								</a>
								<?php if (get_permission('frontend_gallery', 'is_edit')): ?>
									<a href="<?php echo base_url('frontend/gallery/edit/' . $row['id']); ?>" class="btn btn-default btn-circle icon" data-toggle="tooltip" data-original-title="<?php echo translate('edit'); ?>"> 
										<i class="fas fa-pen-nib"></i>
									</a>
								<?php endif; if (get_permission('frontend_gallery', 'is_delete')): ?>
									<?php echo btn_delete('frontend/gallery/delete/' . $row['id']); ?>
								<?php endif; ?>
							</td>
						</tr>
						<?php endforeach; }?>
					</tbody>
				</table>
			</div>
	<?php if (get_permission('frontend_gallery', 'is_add')) { ?>
			<div class="tab-pane <?php echo (isset($validation_error) ? 'active' : ''); ?>" id="create">
			    <?php echo form_open_multipart($this->uri->uri_string(), array('class' => 'form-horizontal form-bordered frm-submit-data')); ?>
					<?php if (is_superadmin_loggedin()): ?>
					<div class="form-group">
						<label class="col-md-3 control-label"><?=translate('branch')?> <span class="required">*</span></label>
						<div class="col-md-6">
							<?php
								$arrayBranch = $this->app_lib->getSelectList('branch');
								echo form_dropdown("branch_id", $arrayBranch, "", "class='form-control' data-plugin-selectTwo id='branch_id'
								data-width='100%' data-minimum-results-for-search='Infinity'");
							?>
							<span class="error"></span>
						</div>
					</div>
					<?php endif; ?>
					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo translate('gallery') . " " . translate('title'); ?> <span class="required">*</span></label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="gallery_title" value="<?php echo set_value('gallery_title'); ?>" />
							<span class="error"><?php echo form_error('gallery_title'); ?></span>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo translate('description'); ?> <span class="required">*</span></label>
						<div class="col-md-6">
							<textarea class="form-control" id="description" name="description" placeholder="" rows="3" ><?php echo set_value('description'); ?></textarea>
							<span class="error"><?php echo form_error('description'); ?></span>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo translate('category'); ?> <span class="required">*</span></label>
						<div class="col-md-6">
							<?php
								$arrayCategory = $this->app_lib->getSelectByBranch('front_cms_gallery_category', set_value('branch_id'));
								echo form_dropdown("category_id", $arrayCategory, set_value('category_id'), "class='form-control' id='category_id' data-minimum-results-for-search='Infinity'
								data-plugin-selectTwo data-width='100%'");
							?>
							<span class="error"><?php echo form_error('type'); ?></span>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo translate('thumb_image'); ?> <span class="required">*</span></label>
						<div class="col-md-4">
							<input type="file" name="thumb_image" class="dropify" data-height="150" />
							<span class="error"><?php echo form_error('thumb_image'); ?></span>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?=translate('show_website')?></label>
						<div class="col-md-6">
							<div class="material-switch ml-xs">
								<input id="switch_1" name="show_website" 
								type="checkbox" />
								<label for="switch_1" class="label-primary"></label>
							</div>
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
	$('#branch_id').on('change', function() {
		var branchID = $(this).val();
		$.ajax({
			url: base_url + 'ajax/getDataByBranch',
			type: 'POST',
			data: {
				table: "front_cms_gallery_category",
				branch_id: branchID
			},
			success: function (response) {
				$('#category_id').html(response);
			}
		});
	});
</script>