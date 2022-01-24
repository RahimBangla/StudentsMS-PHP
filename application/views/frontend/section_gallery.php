<?php if (is_superadmin_loggedin() ): ?>
	<?php $this->load->view('frontend/branch_select'); ?>
<?php endif; if (!empty($branch_id)): ?>
<div class="row">
	<div class="col-md-3 mb-md">
		<?php include 'sidebar.php'; ?>
	</div>
	<div class="col-md-9">
		<section class="panel">
			<header class="panel-heading">
				<h4 class="panel-title"><?=translate('gallery')?></h4>
			</header>
			<?php echo form_open_multipart($this->uri->uri_string() . get_request_url(), array('class' => 'form-horizontal form-bordered frm-submit-data')); ?>
			<div class="panel-body">
				<div class="form-group mt-md">
					<label class="col-md-2 control-label"><?php echo translate('page') . " " . translate('title'); ?> <span class="required">*</span></label>
					<div class="col-md-8">
						<input type="text" class="form-control" name="page_title" value="<?php echo set_value('page_title', $admitcard['page_title']); ?>" />
						<span class="error"></span>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 control-label"><?php echo translate('banner_photo'); ?> <span class="required">*</span></label>
					<div class="col-md-8">
						<input type="hidden" name="old_photo" value="<?php echo $admitcard['banner_image']; ?>">
						<input type="file" name="photo" class="dropify" data-height="150" data-default-file="<?php echo base_url('uploads/frontend/banners/' . $admitcard['banner_image']); ?>" />
						<span class="error"></span>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 control-label"><?php echo translate('meta') . " " . translate('keyword'); ?></label>
					<div class="col-md-8">
						<input type="text" class="form-control" name="meta_keyword" value="<?php echo set_value('meta_keyword', $admitcard['meta_keyword']); ?>" />
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 control-label"><?php echo translate('meta') . " " . translate('description'); ?></label>
					<div class="col-md-8">
						<input type="text" class="form-control" name="meta_description" value="<?php echo set_value('meta_description', $admitcard['meta_description']); ?>" />
					</div>
				</div>
			</div>
			<footer class="panel-footer mt-sm">
				<div class="row">
					<div class="col-md-2 col-md-offset-2">
						<button type="submit" class="btn btn-default btn-block" data-loading-text="<i class='fas fa-spinner fa-spin'></i> Processing">
							<i class="fas fa-plus-circle"></i> <?php echo translate('save'); ?>
						</button>
					</div>
				</div>
			</footer>
			<?php echo form_close();?>
		</section>
	</div>
</div>
<?php endif; ?>