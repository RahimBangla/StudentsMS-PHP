<?php if (is_superadmin_loggedin() ): ?>
	<?php $this->load->view('frontend/branch_select'); ?>
<?php endif; if (!empty($branch_id)): ?>
<div class="row">
	<div class="col-md-3 mb-md">
		<?php include 'sidebar.php'; ?>
	</div>
	<div class="col-md-9">
		<section class="panel">
			<div class="tabs-custom">
				<ul class="nav nav-tabs">
					<li class="active">
						<a href="#contact" data-toggle="tab"><?php echo translate('contact'); ?></a>
					</li>
					<li>
						<a href="#options" data-toggle="tab"><?php echo translate('options'); ?></a>
					</li>
				</ul>
				<div class="tab-content">
					<div class="tab-pane active" id="contact">
					    <?php echo form_open_multipart('frontend/section/contactSave' . get_request_url(), array('class' => 'form-horizontal form-bordered frm-submit-data')); ?>
							<div class="form-group">
								<label class="col-md-2 control-label"><?php echo translate('box_title'); ?> <span class="required">*</span></label>
								<div class="col-md-8">
									<input type="text" class="form-control" name="box_title" value="<?php echo set_value('box_title', $contact['box_title']); ?>" />
									<span class="error"></span>
								</div>
							</div>
							<div class="form-group mt-md">
								<label class="col-md-2 control-label"><?php echo translate('box_description'); ?> <span class="required">*</span></label>
								<div class="col-md-8">
									<textarea name="box_description" class="form-control" rows="4"><?php echo set_value('box_description', $contact['box_description']); ?></textarea>
									<span class="error"></span>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-2 control-label"><?php echo translate('box_photo'); ?> <span class="required">*</span></label>
								<div class="col-md-8">
									<input type="file" name="photo" class="dropify" data-height="150" data-allowed-file-extensions="png jpg jpeg" data-default-file="<?php echo base_url('uploads/frontend/images/' . $contact['box_image']); ?>" />
									<span class="error"></span>
								</div>
							</div>
							<div class="form-group mt-md">
								<label class="col-md-2 control-label"><?php echo translate('form_title'); ?> <span class="required">*</span></label>
								<div class="col-md-8">
									<input type="text" class="form-control" name="form_title" value="<?php echo set_value('form_title', $contact['form_title']); ?>" />
									<span class="error"></span>
								</div>
							</div>
							<div class="form-group mt-md">
								<label class="col-md-2 control-label"><?php echo translate('address'); ?> <span class="required">*</span></label>
								<div class="col-md-8">
									<textarea name="address" class="form-control" rows="4"><?php echo set_value('address', $contact['address']); ?></textarea>
									<span class="error"></span>
								</div>
							</div>
							<div class="form-group mt-md">
								<label class="col-md-2 control-label"><?php echo translate('phone'); ?> <span class="required">*</span></label>
								<div class="col-md-8">
									<textarea name="phone" class="form-control" rows="4"><?php echo set_value('phone', $contact['phone']); ?></textarea>
									<span class="error"></span>
								</div>
							</div>
							<div class="form-group mt-md">
								<label class="col-md-2 control-label"><?php echo translate('email'); ?> <span class="required">*</span></label>
								<div class="col-md-8">
									<textarea name="email" class="form-control" rows="4"><?php echo set_value('email', $contact['email']); ?></textarea>
									<span class="error"></span>
								</div>
							</div>
							<div class="form-group mt-md">
								<label class="col-md-2 control-label"><?php echo translate('submit_button_text'); ?> <span class="required">*</span></label>
								<div class="col-md-8">
									<input type="text" class="form-control" name="submit_text" value="<?php echo set_value('submit_text', $contact['submit_text']); ?>" />
									<span class="error"></span>
								</div>
							</div>
							<div class="form-group mt-md">
								<label class="col-md-2 control-label"><?php echo translate('map_iframe'); ?> <span class="required">*</span></label>
								<div class="col-md-8">
									<textarea name="map_iframe" class="form-control" rows="4"><?php echo set_value('map_iframe', $contact['map_iframe']); ?></textarea>
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
					<div class="tab-pane" id="options">
					    <?php echo form_open_multipart('frontend/section/contactOptionSave' . get_request_url(), array('class' => 'form-horizontal form-bordered frm-submit-data')); ?>
							<div class="form-group <?php if (form_error('page_title')) echo 'has-error'; ?>">
								<label class="col-md-2 control-label"><?php echo translate('page') . " " . translate('title'); ?> <span class="required">*</span></label>
								<div class="col-md-8">
									<input type="text" class="form-control" name="page_title" value="<?php echo set_value('page_title', $contact['page_title']); ?>" />
									<span class="error"><?php echo form_error('page_title'); ?></span>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-2 control-label"><?php echo translate('banner_photo'); ?> <span class="required">*</span></label>
								<div class="col-md-8">
									<input type="hidden" name="old_photo" value="<?php echo $contact['banner_image']; ?>">
									<input type="file" name="photo" class="dropify" data-height="150" data-default-file="<?php echo base_url('uploads/frontend/banners/' . $contact['banner_image']); ?>" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-2 control-label"><?php echo translate('meta') . " " . translate('keyword'); ?></label>
								<div class="col-md-8">
									<input type="text" class="form-control" name="meta_keyword" value="<?php echo set_value('meta_keyword', $contact['meta_keyword']); ?>" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-2 control-label"><?php echo translate('meta') . " " . translate('description'); ?></label>
								<div class="col-md-8">
									<input type="text" class="form-control" name="meta_description" value="<?php echo set_value('meta_description', $contact['meta_description']); ?>" />
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
<?php endif; ?>