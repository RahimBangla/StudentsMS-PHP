<!-- Main Banner Starts -->
<div class="main-banner" style="background: url(<?php echo base_url('uploads/frontend/banners/' . $page_data['banner_image']); ?>) center top;">
    <div class="container px-md-0">
        <h2><span><?php echo $page_data['page_title']; ?></span></h2>
    </div>
</div>
<!-- Main Banner Ends -->
<!-- Breadcrumb Starts -->
<div class="breadcrumb">
    <div class="container px-md-0">
        <ul class="list-unstyled list-inline">
            <li class="list-inline-item"><a href="<?php echo base_url('home') ?>">Home</a></li>
            <li class="list-inline-item active"><?php echo $page_data['page_title']; ?></li>
        </ul>
    </div>
</div>
<!-- Breadcrumb Ends -->
<!-- Main Container Starts -->
<div class="container px-md-0 main-container">
    <h3 class="main-heading2 mt-0"><?php echo $page_data['title']; ?></h3>
    <?php echo $page_data['description']; ?>
    <div class="box2 form-box">
        <div class="tabs-panel tabs-product">
            <div class="nav nav-tabs">
                <a class="nav-item nav-link active" data-toggle="tab" href="#new-patient" role="tab" aria-controls="tab-details" aria-selected="true">New Admission</a>
            </div>
            <div class="tab-content clearfix">
                <div class="tab-pane fade show active" id="new-patient" role="tabpanel" aria-labelledby="tab-new-patient">
                    <?php echo form_open($this->uri->uri_string(), array('class' => 'form-horizontal frm-submit-data')); ?>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label><?=translate('school_name')?> <span class="required">*</span></label>
                                    <input type="text" class="form-control" name="schoolname" value="<?php echo get_type_name_by_id('branch', $branchID, 'school_name'); ?>" readonly />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label"><?=translate('class')?> <span class="required">*</span></label>
                                    <?php
                                        $arrayClass = $this->app_lib->getClass($branchID);
                                        echo form_dropdown("class_id", $arrayClass, set_value('class_id'), "class='form-control' data-plugin-selectTwo onchange='getSectionByClass(this.value)'");
                                    ?>
                                    <span class="error"></span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label"><?=translate('section')?></label>
                                    <?php
                                        $arraySection = $this->app_lib->getSections(set_value('class_id'), false);
                                        echo form_dropdown("section_id", $arraySection, set_value('section_id'), "class='form-control' data-plugin-selectTwo id='section_id' ");
                                    ?>
                                    <span class="error"></span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-sm">
                                <div class="form-group">
                                    <label class="control-label"> <?=translate('first_name')?> <span class="required">*</span></label>
                                    <input type="text" class="form-control" name="first_name" value="<?=set_value('first_name')?>" autocomplete="off" />
                                    <span class="error"></span>
                                </div>
                            </div>
                            <div class="col-md-4 mb-sm">
                                <div class="form-group">
                                    <label class="control-label"> <?=translate('last_name')?> <span class="required">*</span></label>
                                    <input type="text" class="form-control" name="last_name" value="<?=set_value('last_name')?>" autocomplete="off" />
                                    <span class="error"></span>
                                </div>
                            </div>
                            <div class="col-md-4 mb-sm">
                                <div class="form-group">
                                    <label class="control-label"> <?=translate('gender')?> <span class="required">*</span></label>
                                    <?php
                                        $arrayGender = array(
                                            '' => translate('select'),
                                            'male' => translate('male'),
                                            'female' => translate('female')
                                        );
                                        echo form_dropdown("gender", $arrayGender, set_value('gender'), "class='form-control' data-plugin-selectTwo ");
                                    ?>
                                    <span class="error"></span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="birthday"><?=translate('birthday')?> <span class="required">*</span></label>
                                    <input type="text" class="form-control" data-plugin-datepicker name="birthday" readonly value="<?php echo set_value('birthday'); ?>" id="birthday" autocomplete="off" />
                                    <span class="error"></span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="mobile_no"><?=translate('mobile_no')?> <span class="required">*</span></label>
                                    <input type="text" name="mobile_no" class="form-control" value="<?php echo set_value('mobile_no'); ?>" autocomplete="off" />
                                    <span class="error"></span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="email"><?=translate('email')?> <span class="required">*</span></label>
                                    <input type="text" name="email" class="form-control" value="<?php echo set_value('email'); ?>" autocomplete="off" />
                                    <span class="error"></span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label"><?=translate('address')?> <span class="required">*</span></label>
                                    <textarea class="form-control" id="address" name="address" rows="2" placeholder="Enter Address"><?php echo set_value('address'); ?></textarea>
                                    <span class="error"><?=form_error('class_id')?></span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label"><?=translate('guardian_name')?> <span class="required">*</span></label>
                                    <input type="text" class="form-control" name="guardian_name" value="<?php echo set_value('guardian_name'); ?>" autocomplete="off" />
                                    <span class="error"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label"><?=translate('relation')?> <span class="required">*</span></label>
                                    <input type="text" name="guardian_relation" class="form-control" value="<?php echo set_value('guardian_relation'); ?>" autocomplete="off" />
                                    <span class="error"></span>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="father_name"><?=translate('father_name')?></label>
                                    <input type="text" name="father_name" class="form-control" value="<?php echo set_value('father_name'); ?>" autocomplete="off" />
                                    <span class="error"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="mother_name"><?=translate('mother_name')?></label>
                                    <input type="text" name="mother_name" class="form-control" value="<?php echo set_value('mother_name'); ?>" autocomplete="off" />
                                    <span class="error"></span>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label"><?=translate('occupation')?> <span class="required">*</span></label>
                                    <input type="text" class="form-control" name="grd_occupation" value="<?=set_value('grd_occupation')?>" autocomplete="off" />
                                    <span class="error"></span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label"><?=translate('income')?></label>
                                    <input class="form-control" name="grd_income" value="<?=set_value('grd_income')?>" type="text" autocomplete="off" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label"><?=translate('education')?></label>
                                    <input type="text" class="form-control" name="grd_education" value="<?=set_value('grd_education')?>" autocomplete="off" />
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label"><?=translate('guardian') . " " . translate('email')?></label>
                                    <input type="text" class="form-control" name="grd_email" value="<?=set_value('grd_email')?>" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label"><?=translate('guardian') . " " . translate('mobile_no')?> <span class="required">*</span></label>
                                    <input type="text" class="form-control" name="grd_mobile_no" value="<?=set_value('grd_mobile_no')?>" autocomplete="off" />
                                    <span class="error"></span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="message"><?=translate('guardian') . " " . translate('address')?> <span class="required">*</span></label>
                            <textarea class="form-control" id="grd_address" name="grd_address" placeholder="Enter Address"><?php echo set_value('grd_address'); ?></textarea>
                            <span class="error"></span>
                        </div>
                        <!--custom fields details-->
                        <div class="row" id="customFields">
                            <?php echo render_custom_Fields('online_admission', $branchID); ?>
                        </div>
                        <?php if ($cms_setting['captcha_status'] == 'enable'): ?>
                        <div class="form-group">
                            <?php echo $recaptcha['widget']; echo $recaptcha['script']; ?>
                            <span class="error"></span>
                        </div>
                        <?php endif; ?>
                        <?php if (!empty($page_data['terms_conditions_title'])) {?>
                        <div class="accordion mb-md" id="accordion-faqs">
                            <div class="card">
                                <div class="card-header" id="faq1">
                                    <h5 class="card-title">
                                        <a data-toggle="collapse" data-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                            <?php echo $page_data['terms_conditions_title']; ?> 
                                        </a>
                                    </h5>
                                </div>
                                <div id="collapseOne" class="collapse" aria-labelledby="faq1" data-parent="#accordion-faqs">
                                    <div class="card-body">
                                        <?php echo $page_data['terms_conditions_description'] ?>
                                    </div>
                                </div>                 
                            </div>
                        </div>
                    <?php } ?>
                        <button type="submit" class="btn btn-1" data-loading-text="<i class='fas fa-spinner fa-spin'></i> Processing"><i class="fas fa-plus-circle"></i> <?=translate('submit')?></button>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>
</div>