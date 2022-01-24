<section class="panel">
	<div class="tabs-custom">
		<ul class="nav nav-tabs">
			<li>
				<a href="<?php echo base_url('frontend/content'); ?>"><i class="fas fa-list-ul"></i> <?php echo translate('page') . " " . translate('list'); ?></a>
			</li>
			<li class="active">
				<a href="#create" data-toggle="tab"><i class="far fa-edit"></i> <?php echo translate('edit') . " " . translate('page'); ?></a>
			</li>
		</ul>
		<div class="tab-content">
			<div class="tab-pane active" id="create">
				<?php echo form_open_multipart($this->uri->uri_string(), array('class' => 'form-horizontal form-bordered frm-submit-data')); ?>
					<input type="hidden" name="page_id" value="<?php echo $content['id']; ?>">
					<?php if (is_superadmin_loggedin()): ?>
						<div class="form-group">
							<label class="col-md-3 control-label"><?=translate('branch')?> <span class="required">*</span></label>
							<div class="col-md-8">
								<?php
								$arrayBranch = $this->app_lib->getSelectList('branch');
								echo form_dropdown("branch_id", $arrayBranch, $content['branch_id'], "class='form-control' data-width='100%' id='branch_id'
								data-plugin-selectTwo  data-minimum-results-for-search='Infinity'");
								?>
								<span class="error"></span>
							</div>
						</div>
					<?php endif; ?>
					<div class="form-group <?php if (form_error('title')) echo 'has-error'; ?>">
						<label class="col-md-3 control-label"><?php echo translate('page') . " " . translate('title'); ?> <span class="required">*</span></label>
						<div class="col-md-8">
							<input type="text" class="form-control" name="title" value="<?php echo set_value('title', $content['page_title']); ?>" />
							<span class="error"></span>
						</div>
					</div>
					<div class="form-group <?php if (form_error('menu_id')) echo 'has-error'; ?>">
						<label class="col-md-3 control-label"><?php echo translate('select') . " " . translate('menu'); ?> <span class="required">*</span></label>
						<div class="col-md-8">
							<?php
							    $this->db->order_by('ordering', 'asc');
							    $this->db->where('system', 0);
								$result = $this->db->get('front_cms_menu')->result();
								$menuslist = array('' => translate('select'));
								foreach ($result as $row) {
									$menuslist[$row->id] = $row->title;
								}
								echo form_dropdown("menu_id", $menuslist, set_value('menu_id', $content['menu_id']), "class='form-control' data-plugin-selectTwo data-width='100%'");
							?>
							<span class="error"></span>
						</div>
					</div>
					<div class="form-group <?php if (form_error('content')) echo 'has-error'; ?>">
						<label  class="col-md-3 control-label"><?php echo translate('content'); ?> <span class="required">*</span></label>
						<div class="col-md-8">
							<textarea class="summernote" name="content"><?php echo set_value('content', $content['content']); ?></textarea>
							<span class="error"></span>
						</div>
					</div>
					<div class="form-group <?php if (form_error('photo')) echo 'has-error'; ?>">
						<label class="col-md-3 control-label"><?php echo translate('banner_photo'); ?> <span class="required">*</span></label>
						<div class="col-md-8">
							<input type="hidden" name="old_photo" value="<?php echo $content['banner_image']; ?>">
							<input type="file" name="photo" class="dropify" data-height="150" data-default-file="<?php echo base_url('uploads/frontend/banners/' . $content['banner_image']); ?>" />
							<span class="error"></span>
						</div>
					</div>
					<div class="form-group <?php if (form_error('meta_keyword')) echo 'has-error'; ?>">
						<label class="col-md-3 control-label"><?php echo translate('meta') . " " . translate('keyword'); ?></label>
						<div class="col-md-8">
							<input type="text" class="form-control" name="meta_keyword" value="<?php echo set_value('meta_keyword', $content['meta_keyword']); ?>" />
							<span class="error"></span>
						</div>
					</div>
					<div class="form-group <?php if (form_error('meta_description')) echo 'has-error'; ?>">
						<label class="col-md-3 control-label"><?php echo translate('meta') . " " . translate('description'); ?></label>
						<div class="col-md-8">
							<input type="text" class="form-control" name="meta_description" value="<?php echo set_value('meta_description', $content['meta_description']); ?>" />
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
	</div>
</section>