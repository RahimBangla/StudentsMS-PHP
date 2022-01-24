<?php if (is_superadmin_loggedin() ): ?>
	<?php $this->load->view('frontend/branch_select'); ?>
<?php endif; if (!empty($branch_id)) { ?>
<div class="row">
	<div class="col-md-3 mb-md">
		<?php include 'sidebar.php'; ?>
	</div>
	<div class="col-md-9">
		<section class="panel">
			<div class="tabs-custom">
				<ul class="nav nav-tabs">
					<li class="active">
						<a href="#about" data-toggle="tab"><?php echo translate('about'); ?></a>
					</li>
					<li>
						<a href="#service" data-toggle="tab"><?php echo translate('service'); ?></a>
					</li>
					<li>
						<a href="#cta" data-toggle="tab"><?php echo translate('call_to_action_section'); ?></a>
					</li>
					<li>
						<a href="#option" data-toggle="tab"><?php echo translate('options'); ?></a>
					</li>
				</ul>
				<div class="tab-content">
					<div class="tab-pane active" id="about">
						<?php echo form_open_multipart('frontend/section/aboutSave' . get_request_url(), array('class' => 'form-horizontal form-bordered frm-submit-data')); ?>
							<div class="form-group">
								<label class="col-md-2 control-label"><?php echo translate('title'); ?> <span class="required">*</span></label>
								<div class="col-md-9">
									<input type="text" class="form-control" name="title" value="<?php echo set_value('title', $about['title']); ?>" />
									<span class="error"></span>
								</div>
							</div>
							<div class="form-group mt-md">
								<label class="col-md-2 control-label"><?php echo translate('subtitle'); ?> <span class="required">*</span></label>
								<div class="col-md-9">
									<input type="text" class="form-control" name="subtitle" value="<?php echo set_value('subtitle', $about['subtitle']); ?>" />
									<span class="error"></span>
								</div>
							</div>
							<div class="form-group mt-md">
								<label class="col-md-2 control-label"><?php echo translate('content'); ?> <span class="required">*</span></label>
								<div class="col-md-9">
									<textarea name="content" class="summernote"><?php echo set_value('content', $about['content']); ?></textarea>
									<span class="error"></span>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-2 control-label"><?php echo translate('about_photo'); ?> <span class="required">*</span></label>
								<div class="col-md-4">
									<input type="hidden" name="old_photo" value="<?php echo $about['about_image']; ?>">
									<input type="file" name="photo" class="dropify" data-height="150" data-default-file="<?php echo base_url('uploads/frontend/about/' . $about['about_image']); ?>" />
									<span class="error"></span>
								</div>
							</div>
							<footer class="panel-footer mt-lg">
								<div class="row">
									<div class="col-md-2 col-md-offset-2">
										<button type="submit" class="btn btn-default btn-block" data-loading-text="<i class='fas fa-spinner fa-spin'></i> Processing">
											<i class="fas fa-plus-circle"></i> <?php echo translate('save'); ?>
										</button>
									</div>
								</div>
							</footer>
						<?php echo form_close(); ?>
					</div>
					<div class="tab-pane" id="service">
						<?php echo form_open_multipart('frontend/section/aboutServiceSave' . get_request_url(), array('class' => 'form-horizontal form-bordered frm-submit-data')); ?>
							<div class="form-group">
								<label class="col-md-2 control-label"><?php echo translate('title'); ?> <span class="required">*</span></label>
								<div class="col-md-9">
									<input type="text" class="form-control" name="title" value="<?php echo set_value('title', $service['title']); ?>" />
									<span class="error"><?php echo form_error('title'); ?></span>
								</div>
							</div>
							<div class="form-group mt-md <?php if (form_error('subtitle')) echo 'has-error'; ?>">
								<label class="col-md-2 control-label"><?php echo translate('subtitle'); ?> <span class="required">*</span></label>
								<div class="col-md-9">
									<input type="text" class="form-control" name="subtitle" value="<?php echo set_value('subtitle', $service['subtitle']); ?>" />
									<span class="error"><?php echo form_error('subtitle'); ?></span>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-2 control-label"><?php echo translate('parallax_photo'); ?> <span class="required">*</span></label>
								<div class="col-md-4">
									<input type="hidden" name="old_photo" value="<?php echo $service['parallax_image']; ?>">
									<input type="file" name="photo" class="dropify" data-height="150" data-default-file="<?php echo base_url('uploads/frontend/about/' . $service['parallax_image']); ?>" />
								</div>
							</div>
							<footer class="panel-footer mt-lg">
								<div class="row">
									<div class="col-md-2 col-md-offset-2">
										<button type="submit" class="btn btn-default btn-block" data-loading-text="<i class='fas fa-spinner fa-spin'></i> Processing">
											<i class="fas fa-plus-circle"></i> <?php echo translate('save'); ?>
										</button>
									</div>
								</div>
							</footer>
						<?php echo form_close(); ?>
					</div>
					<div class="tab-pane" id="cta">
						<?php echo form_open('frontend/section/aboutCtaSave' . get_request_url(), array('class' => 'form-horizontal form-bordered frm-submit')); ?>
							<div class="form-group">
								<label class="col-md-2 control-label"><?php echo translate('cta') . " " . translate('title'); ?> <span class="required">*</span></label>
								<div class="col-md-9">
									<input type="text" class="form-control" name="cta_title" value="<?php $elements = json_decode($about['elements'], true); echo set_value('cta_title', $elements['cta_title']); ?>" />
									<span class="error"></span>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-2 control-label"><?php echo translate('button_text'); ?> <span class="required">*</span></label>
								<div class="col-md-9">
									<input type="text" class="form-control" name="button_text" value="<?php echo set_value('button_text', $elements['button_text']); ?>" />
									<span class="error"></span>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-2 control-label"><?php echo translate('button_url'); ?> <span class="required">*</span></label>
								<div class="col-md-9">
									<input type="text" class="form-control" name="button_url" value="<?php echo set_value('button_url', $elements['button_url']); ?>" />
									<span class="error"></span>
								</div>
							</div>
							<footer class="panel-footer mt-lg">
								<div class="row">
									<div class="col-md-2 col-md-offset-2">
										<button type="submit" class="btn btn-default btn-block" data-loading-text="<i class='fas fa-spinner fa-spin'></i> Processing">
											<i class="fas fa-plus-circle"></i> <?php echo translate('save'); ?>
										</button>
									</div>
								</div>
							</footer>
						<?php echo form_close(); ?>
					</div>
					<div class="tab-pane" id="option">
						<?php echo form_open_multipart('frontend/section/aboutOptionsSave' . get_request_url(), array('class' => 'form-horizontal form-bordered frm-submit-data')); ?>
							<div class="form-group <?php if (form_error('page_title')) echo 'has-error'; ?>">
								<label class="col-md-2 control-label"><?php echo translate('page') . " " . translate('title'); ?> <span class="required">*</span></label>
								<div class="col-md-9">
									<input type="text" class="form-control" name="page_title" value="<?php echo set_value('page_title', $about['page_title']); ?>" />
									<span class="error"></span>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-2 control-label"><?php echo translate('banner_photo'); ?> <span class="required">*</span></label>
								<div class="col-md-9">
									<input type="hidden" name="old_photo" value="<?php echo $about['banner_image']; ?>">
									<input type="file" name="photo" class="dropify" data-height="150" data-default-file="<?php echo base_url('uploads/frontend/banners/' . $about['banner_image']); ?>" />
									<span class="error"></span>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-2 control-label"><?php echo translate('meta') . " " . translate('keyword'); ?></label>
								<div class="col-md-9">
									<input type="text" class="form-control" name="meta_keyword" value="<?php echo set_value('meta_keyword', $about['meta_keyword']); ?>" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-2 control-label"><?php echo translate('meta') . " " . translate('description'); ?></label>
								<div class="col-md-9">
									<input type="text" class="form-control" name="meta_description" value="<?php echo set_value('meta_description', $about['meta_description']); ?>" />
								</div>
							</div>
							<footer class="panel-footer mt-lg">
								<div class="row">
									<div class="col-md-2 col-md-offset-2">
										<button type="submit" class="btn btn-default btn-block" data-loading-text="<i class='fas fa-spinner fa-spin'></i> Processing">
											<i class="fas fa-plus-circle"></i> <?php echo translate('save'); ?>
										</button>
									</div>
								</div>
							</footer>
						<?php echo form_close(); ?>
					</div>
				</div>
			</div>
		</section>
	</div>
</div>
<?php } ?>