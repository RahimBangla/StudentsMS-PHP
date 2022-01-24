<div class="row">
	<div class="col-md-12">
<?php if (is_superadmin_loggedin() ): ?>
	<section class="panel">
		<header class="panel-heading">
			<h4 class="panel-title"><?=translate('select_ground')?></h4>
		</header>
		<?php echo form_open($this->uri->uri_string(), array('id' => 'frmsection', 'class' => 'validate'));?>
		<div class="panel-body">
			<div class="row mb-sm">
				<div class="col-md-offset-3 col-md-6">
					<div class="form-group">
						<label class="control-label"><?=translate('branch')?> <span class="required">*</span></label>
						<?php
							$arrayBranch = $this->app_lib->getSelectList('branch');
							echo form_dropdown("branch_id", $arrayBranch, $branch_id, "class='form-control' id='branch_id' required
							data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
						?>
					</div>
				</div>
			</div>
		</div>
		<footer class="panel-footer">
			<div class="row">
				<div class="col-md-offset-10 col-md-2">
					<button type="submit" class="btn btn-default btn-block"> <i class="fas fa-filter"></i> <?=translate('filter')?></button>
				</div>
			</div>
		</footer>
		<?php echo form_close();?>
	</section>
<?php endif; if (!empty($branch_id)): ?>
		<section class="panel">
			<header class="panel-heading">
				<h4 class="panel-title"><i class="fas fa-globe"></i> <?php echo translate('website') . " " . translate('settings'); ?></h4>
			</header>

            <?php echo form_open_multipart('frontend/setting/save' . get_request_url(), array('class' => 'form-horizontal form-bordered frm-submit-data')); ?>
				<div class="panel-body">
					<div class="form-group mt-md">
						<label class="col-md-3 control-label"><?php echo translate('cms') . " " . translate('title'); ?> <span class="required">*</span></label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="application_title" value="<?php echo set_value('application_title', $setting['application_title']); ?>" />
							<span class="error"><?php echo form_error('application_title'); ?></span>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo translate('cms') . " " . translate('url_alias'); ?> <span class="required">*</span></label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="url_alias" value="<?php echo set_value('url_alias', $setting['url_alias']); ?>" />
							<span class="error"><?php echo form_error('url_alias'); ?></span>
						</div>
					</div>
					<div class="form-group">
						<label  class="col-md-3 control-label"><?php echo translate('cms_frontend'); ?> <span class="required">*</span></label>
						<div class="col-md-6">
							<div class="radio-custom radio-success radio-inline mb-xs">
								<input type="radio" value="1" name="cms_frontend_status" <?=($setting['cms_active'] == 1 ? 'checked' : '')?> id="sstatus_1">
								<label for="sstatus_1"><?=translate('enabled')?></label>
							</div>

							<div class="radio-custom radio-danger radio-inline">
								<input type="radio" value="0" name="cms_frontend_status" <?=($setting['cms_active'] == 0 ? 'checked' : '')?> id="sstatus_2">
								<label for="sstatus_2"><?=translate('disabled')?></label>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label  class="col-md-3 control-label"><?php echo translate('online_admission'); ?> <span class="required">*</span></label>
						<div class="col-md-6">
							<div class="radio-custom radio-success radio-inline mb-xs">
								<input type="radio" value="1" <?=($setting['online_admission'] == 1 ? 'checked' : '')?> name="online_admission" id="astatus_1">
								<label for="astatus_1"><?=translate('enabled')?></label>
							</div>

							<div class="radio-custom radio-danger radio-inline">
								<input type="radio" value="0" <?=($setting['online_admission'] == 0 ? 'checked' : '')?> name="online_admission" id="astatus_2">
								<label for="astatus_2"><?=translate('disabled')?></label>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label  class="col-md-3 control-label"><?php echo translate('receive_email_to'); ?> <span class="required">*</span></label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="receive_email_to" value="<?php echo set_value('receive_email_to', $setting['receive_contact_email']); ?>" />
							<span class="error"><?php echo form_error('receive_email_to'); ?></span>
						</div>
					</div>
					<div class="form-group">
						<label  class="col-md-3 control-label"><?php echo translate('captcha_status'); ?> <span class="required">*</span></label>
						<div class="col-md-6">
							<?php
								$array = array(
									"" => translate('select'),
									"disable" => translate('disable'),
									"enable" => translate('enable')
								);
								echo form_dropdown("captcha_status", $array, set_value('captcha_status', $setting['captcha_status']), "class='form-control' data-plugin-selectTwo
								data-width='100%' id='captchaStatus' data-minimum-results-for-search='Infinity'");
							?>
							<span class="error"><?php echo form_error('captcha_status'); ?></span>
						</div>
					</div>
					<div class="form-group" id="recaptcha_site_key">
						<label  class="col-md-3 control-label"><?php echo translate('recaptcha_site_key'); ?> <span class="required">*</span></label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="recaptcha_site_key" value="<?php echo set_value('recaptcha_site_key', $setting['recaptcha_site_key']); ?>" />
							<span class="error"><?php echo form_error('recaptcha_site_key'); ?></span>
						</div>
					</div>
					<div class="form-group" id="recaptcha_secret_key">
						<label  class="col-md-3 control-label"><?php echo translate('recaptcha_secret_key'); ?> <span class="required">*</span></label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="recaptcha_secret_key" value="<?php echo set_value('recaptcha_secret_key', $setting['recaptcha_secret_key']); ?>" />
							<span class="error"><?php echo form_error('recaptcha_secret_key'); ?></span>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo translate('working_hours'); ?> <span class="required">*</span></label>
						<div class="col-md-6">
							<textarea class="form-control" id="working_hours" name="working_hours" placeholder="" rows="3" ><?php echo set_value('working_hours', $setting['working_hours']); ?></textarea>
							<span class="error"><?php echo form_error('working_hours'); ?></span>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo translate('logo'); ?> <span class="required">*</span></label>
						<div class="col-md-6">
							<input type="file" name="logo" class="dropify" data-height="90" data-allowed-file-extensions="png jpg jpeg" data-default-file="<?php echo base_url('uploads/frontend/images/' . $setting['logo']); ?>" />
							<span class="error"><?php echo form_error('logo'); ?></span>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo translate('fav_icon'); ?> <span class="required">*</span></label>
						<div class="col-md-6">
							<input type="file" name="fav_icon" class="dropify" data-height="90" data-allowed-file-extensions="png ico" data-default-file="<?php echo base_url('uploads/frontend/images/' . $setting['fav_icon']); ?>" />
							<span class="error"><?php echo form_error('fav_icon'); ?></span>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo translate('address'); ?> <span class="required">*</span></label>
						<div class="col-md-6">
							<textarea class="form-control" id="address" name="address" placeholder="" rows="3" ><?php echo set_value('address', $setting['address']); ?></textarea>
							<span class="error"><?php echo form_error('address'); ?></span>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo translate('theme'); ?> <span class="required">*</span></label>
						<div class="col-md-6">
							<ul class="list-unstyled thememenu-sy">
								<li>
									<div class="theme-box">
										<label> 
											<input name="theme_color" value="blue" type="radio" <?=($setting['theme'] == null ? 'checked' : '');?> <?=($setting['theme'] == 'blue' ? 'checked' : '');?>>
											<div class="theme-img">
												<img src="<?=base_url('assets/frontend/images/theme/blue.png')?>">
											</div>
										</label>
									</div>
								</li>
								<li>
									<div class="theme-box">
										<label >
											<input name="theme_color" value="red" type="radio" <?=($setting['theme'] == 'red' ? 'checked' : '');?> >
											<div class="theme-img">
												<img src="<?=base_url('assets/frontend/images/theme/red.png')?>">
											</div>
										</label>
									</div>
								</li>
							</ul>
						</div>
					</div>
					<div class="form-group">
						<label  class="col-md-3 control-label"><?php echo translate('mobile_no'); ?> <span class="required">*</span></label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="mobile_no" value="<?php echo set_value('mobile_no', $setting['mobile_no']); ?>" />
							<span class="error"><?php echo form_error('mobile_no'); ?></span>
						</div>
					</div>
					<div class="form-group">
						<label  class="col-md-3 control-label"><?php echo translate('email'); ?> <span class="required">*</span></label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="email" value="<?php echo set_value('email', $setting['email']); ?>" />
							<span class="error"><?php echo form_error('email'); ?></span>
						</div>
					</div>
					<div class="form-group">
						<label  class="col-md-3 control-label"><?php echo translate('fax'); ?> <span class="required">*</span></label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="fax" value="<?php echo set_value('fax', $setting['fax']); ?>" />
							<span class="error"><?php echo form_error('fax'); ?></span>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo translate('footer_about_text'); ?> <span class="required">*</span></label>
						<div class="col-md-6">
							<textarea class="form-control" id="footer_about_text" name="footer_about_text" placeholder="" rows="3" ><?php echo set_value('footer_about_text', $setting['footer_about_text']); ?></textarea>
							<span class="error"><?php echo form_error('footer_about_text'); ?></span>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo translate('copyright_text'); ?> <span class="required">*</span></label>
						<div class="col-md-6">
							<textarea class="form-control" id="copyright_text" name="copyright_text" placeholder="" rows="3" ><?php echo set_value('copyright_text', $setting['copyright_text']); ?></textarea>
							<span class="error"><?php echo form_error('copyright_text'); ?></span>
						</div>
					</div>
					<div class="form-group">
						<label  class="col-md-3 control-label"><?php echo translate('facebook_url'); ?></label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="facebook_url" value="<?php echo set_value('facebook_url', $setting['facebook_url']); ?>" />
						</div>
					</div>
					<div class="form-group">
						<label  class="col-md-3 control-label"><?php echo translate('twitter_url'); ?></label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="twitter_url" value="<?php echo set_value('twitter_url', $setting['twitter_url']); ?>" />
						</div>
					</div>
					<div class="form-group">
						<label  class="col-md-3 control-label"><?php echo translate('youtube_url'); ?></label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="youtube_url" value="<?php echo set_value('youtube_url', $setting['youtube_url']); ?>" />
						</div>
					</div>
					<div class="form-group">
						<label  class="col-md-3 control-label"><?php echo translate('google_plus'); ?></label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="google_plus" value="<?php echo set_value('google_plus', $setting['google_plus']); ?>" />
							<span class="error"><?php echo form_error('google_plus'); ?></span>
						</div>
					</div>
					<div class="form-group">
						<label  class="col-md-3 control-label"><?php echo translate('linkedin_url'); ?></label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="linkedin_url" value="<?php echo set_value('linkedin_url', $setting['linkedin_url']); ?>" />
						</div>
					</div>
					<div class="form-group">
						<label  class="col-md-3 control-label"><?php echo translate('pinterest_url'); ?></label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="pinterest_url" value="<?php echo set_value('pinterest_url', $setting['pinterest_url']); ?>" />
						</div>
					</div>
					<div class="form-group <?php if (form_error('instagram_url')) echo 'has-error'; ?>">
						<label  class="col-md-3 control-label"><?php echo translate('instagram_url'); ?></label>
						<div class="col-md-6 mb-md">
							<input type="text" class="form-control" name="instagram_url" value="<?php echo set_value('instagram_url', $setting['instagram_url']); ?>" />
						</div>
					</div>
				</div>
				<div class="panel-footer">
					<div class="row">
						<div class="col-md-2 col-md-offset-3">
							<button type="submit" class="btn btn-default btn-block" data-loading-text="<i class='fas fa-spinner fa-spin'></i> Processing">
								<i class="fas fa-plus-circle"></i> <?php echo translate('save'); ?>
							</button>
						</div>
					</div>
				</div>
			<?php echo form_close(); ?>
		</section>
	</div>
</div>

<script type="text/javascript">
	// frontend setting captcha status 
	$('#captchaStatus').on('change', function(){
	    var status = $(this).val();
	    if(status == "enable") {
	        $('#recaptcha_site_key').show(300); 
	        $('#recaptcha_secret_key').show(300);  
	    } else {
	        $('#recaptcha_site_key').hide(300); 
	        $('#recaptcha_secret_key').hide(300); 
	    }
	});

	<?php if($setting['captcha_status'] == "enable") { ?>
		$('#recaptcha_site_key').show(); 
		$('#recaptcha_secret_key').show();
	<?php } else { ?>
		$('#recaptcha_site_key').hide(); 
		$('#recaptcha_secret_key').hide(); 
	<?php } ?>
</script>

<?php endif; ?>