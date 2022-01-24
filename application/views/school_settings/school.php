<?php if (is_superadmin_loggedin() && empty($branchID)) { ?>
<div class="row">
    <div class="col-md-12">
        <section class="panel">
            <header class="panel-heading">
                <h4 class="panel-title"><i class="fas fa-school"></i> <?=translate('school') . " " . translate('list')?></h4>
            </header>
            <div class="panel-body">
                    <table class="table table-bordered table-hover table-condensed mb-none table_default">
                        <thead>
                            <tr>
                                <th width="50"><?=translate('sl')?></th>
                                <th><?=translate('branch_name')?></th>
                                <th><?=translate('school_name')?></th>
                                <th><?=translate('email')?></th>
                                <th><?=translate('address')?></th>
                                <th><?=translate('action')?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                $count = 1;
                                $branchs = $this->db->get('branch')->result();
                                foreach($branchs as $row):
                            ?>
                            <tr>
                                <td><?php echo $count++; ?></td>
                                <td><?php echo $row->name;?></td>
                                <td><?php echo $row->school_name;?></td>
                                <td><?php echo $row->email;?></td>
                                <td><?php echo $row->address;?></td>
                                <td class="min-w-c">
                                    <!--update link-->
                                    <a href="<?=base_url('school_settings?branch_id='.$row->id)?>" class="btn btn-default btn-circle">
                                        <i class="fas fa-sliders-h"></i> Configuration
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
            </div>
        </section>
    </div>
</div>
<?php } ?>
<?php if (!empty($branchID)) { ?>
<div class="row">
	<div class="col-md-3">
        <?php $this->load->view('school_settings/sidebar'); ?>
    </div>
    <div class="col-md-9">
        <section class="panel">
            <header class="panel-heading">
                <h4 class="panel-title"><i class="fas fa-school"></i> <?=translate('school_setting')?></h4>
            </header>
            <?php echo form_open_multipart('school_settings' . get_request_url(), array('class' => 'form-horizontal  frm-submit-data')); ?>
            
                <div class="panel-body">
                    <div class="form-group mt-md">
                        <label class="col-md-3 control-label"><?=translate('branch_name')?> <span class="required">*</span></label>
                        <div class="col-md-6">
                            <input type="text" class="form-control" name="branch_name" value="<?=$school['name']?>" />
                            <span class="error"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label"><?=translate('school_name')?> <span class="required">*</span></label>
                        <div class="col-md-6">
                            <input type="text" class="form-control" name="school_name" value="<?=$school['school_name']?>" />
                            <span class="error"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label"><?=translate('email')?> <span class="required">*</span></label>
                        <div class="col-md-6">
                            <input type="email" class="form-control" name="email" value="<?=$school['email']?>" />
                            <span class="error"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label"><?=translate('mobile_no')?></label>
                        <div class="col-md-6">
                            <input type="text" class="form-control" name="mobileno" value="<?=$school['mobileno']?>" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label  class="col-md-3 control-label"><?=translate('currency')?> <span class="required">*</span></label>
                        <div class="col-md-6">
                            <input type="text" class="form-control" name="currency" value="<?=$school['currency']?>" />
                            <span class="error"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label"><?=translate('currency_symbol')?> <span class="required">*</span></label>
                        <div class="col-md-6">
                            <input type="text" class="form-control" name="currency_symbol" value="<?=$school['symbol']?>" />
                            <span class="error"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label"><?=translate('city')?></label>
                        <div class="col-md-6">
                            <input type="text" class="form-control" name="city" value="<?=$school['city']?>" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label"><?=translate('state')?></label>
                        <div class="col-md-6">
                            <input type="text" class="form-control" name="state" value="<?=$school['state']?>" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label  class="col-md-3 control-label"><?=translate('address')?></label>
                        <div class="col-md-6">
                            <textarea type="text" rows="3" class="form-control" name="address"><?=$school['address']?></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-offset-3 col-md-6">
                            <div class="checkbox-replace">
                                <label class="i-checks">
                                    <input type="checkbox" name="teacher_restricted" <?=($school['teacher_restricted'] == 1 ? 'checked' : '');?>>
                                    <i></i> <?=translate('teacher_restricted')?>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-offset-3 col-md-6">
                            <div class="checkbox-replace">
                                <label class="i-checks">
                                    <input type="checkbox" name="generate_student" id="generate_student_cb" <?=($school['stu_generate'] == 1 ? 'checked' : '');?>>
                                    <i></i> Automatically Generate Student Login Details.
                                </label>
                            </div>
                        </div>
                    </div>
                    <div style="<?=($school['stu_generate'] == 0 ? 'display: none' : '');?>" id="stu_generate">
                        <div class="form-group">
                            <label class="col-md-3 control-label"><?=translate('username') . " " . translate('prefix') ?> <span class="required">*</span></label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" name="stu_username_prefix" value="<?=$school['stu_username_prefix']?>" />
                                <span class="error"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label"><?=translate('default')  . " " . translate('password')?> <span class="required">*</span></label>
                            <div class="col-md-6 mb-md">
                                <input type="text" class="form-control" name="stu_default_password" value="<?=$school['stu_default_password']?>" />
                                <span class="error"></span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-offset-3 col-md-6">
                            <div class="checkbox-replace">
                                <label class="i-checks">
                                    <input type="checkbox" name="generate_guardian" id="generate_guardian_cb" <?=($school['grd_generate'] == 1 ? 'checked' : '');?>>
                                    <i></i> Automatically Generate Guardian Login Details.
                                </label>
                            </div>
                        </div>
                    </div>
                    <div style="<?=($school['grd_generate'] == 0 ? 'display: none' : '');?>" id="guardian_generate">
                        <div class="form-group">
                            <label class="col-md-3 control-label"><?=translate('username') . " " . translate('prefix') ?> <span class="required">*</span></label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" name="grd_username_prefix" value="<?=$school['grd_username_prefix']?>" />
                                <span class="error"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label"><?=translate('default')  . " " . translate('password')?> <span class="required">*</span></label>
                            <div class="col-md-6 mb-md">
                                <input type="text" class="form-control" name="grd_default_password" value="<?=$school['grd_default_password']?>" />
                                <span class="error"></span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-offset-3 col-md-3">
                            <label class="control-label pt-none"><?=translate('system_logo');?></label>
                            <input type="file" name="logo_file" class="dropify" data-allowed-file-extensions="png" data-default-file="<?=$this->application_model->getBranchImage($school['id'], 'logo')?>" />
                        </div>
                        <div class="col-md-3 mb-md">
                            <label class="control-label pt-none"><?=translate('text_logo');?></label>
                            <input type="file" name="text_logo" class="dropify" data-allowed-file-extensions="png" data-default-file="<?=$this->application_model->getBranchImage($school['id'], 'logo-small')?>" />
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-offset-3 col-md-3">
                            <label class="control-label pt-none"><?=translate('printing_logo');?></label>
                            <input type="file" name="print_file" class="dropify" data-allowed-file-extensions="png" data-default-file="<?=$this->application_model->getBranchImage($school['id'], 'printing-logo')?>" />
                        </div>
                        <div class="col-md-3 mb-md">
                            <label class="control-label pt-none"><?=translate('report_card');?></label>
                            <input type="file" name="report_card" class="dropify" data-allowed-file-extensions="png" data-default-file="<?=$this->application_model->getBranchImage($school['id'], 'report-card-logo')?>" />
                        </div>
                    </div>
                </div>
                <div class="panel-footer">
                    <div class="row">
                        <div class="col-md-3 col-sm-offset-3">
                            <button type="submit" class="btn btn btn-default btn-block" data-loading-text="<i class='fas fa-spinner fa-spin'></i> Processing">
                                <i class="fas fa-plus-circle"></i> <?=translate('save');?>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </section>
     </div>
</div>
<?php } ?>

<script type="text/javascript">
    $('#generate_student_cb').on('click', function(){
        if (this.checked) {
            $('#stu_generate').show(300); 
        } else {
           $('#stu_generate').hide(300); 
        }
    });

    $('#generate_guardian_cb').on('click', function(){
        if (this.checked) {
            $('#guardian_generate').show(300); 
        } else {
           $('#guardian_generate').hide(300); 
        }
    });
</script>