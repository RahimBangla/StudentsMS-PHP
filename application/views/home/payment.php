<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Admission Fees Payment</title>
    <link rel="shortcut icon" href="<?php echo base_url('uploads/frontend/images/' . $cms_setting['fav_icon']); ?>">
    <link href="<?php echo base_url() ?>assets/frontend/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo base_url('assets/vendor/font-awesome/css/all.min.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/vendor/select2/css/select2.min.css'); ?>">
	<link rel="stylesheet" href="<?php echo base_url('assets/vendor/sweetalert/sweetalert-custom.css');?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/frontend/css/style_red.css'); ?>">
    <script src="<?php echo base_url('assets/vendor/jquery/jquery.min.js'); ?>"></script>
</head>

<body>
    <div class="sr-root">
        <div class="sr-main">
            <section class="container">
                <div class="row">
                    <div class="col-md-6" style="margin: auto 0;">
                        <h1>Admission Fees Payment</h1>
                        <h4>The amount of admission fee at <strong style="color: #5e5e5e;"><?php echo $get_student['first_name'] . " " . $get_student['last_name'] ?></strong> </h4>
                        <span style="font-weight: bold; color: #4d4d4d; font-size: 22px;"><?php echo $get_student['symbol'] .  number_format($get_student['fee_elements']['amount'], 2, '.', ''); ?></span>
                        <div class="pasha-image"> 
                            <img src="<?=$this->application_model->getBranchImage($get_student['branch_id'], 'logo')?>" width="280" height="auto" />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h1>Payer Details</h1>
                        <?php echo form_open('admissionpayment/checkout/', array('class' => 'form-horizontal frm-submit' )); ?>
                        <input type="hidden" name="student_id" value="<?php echo $get_student['id'] ?>">
                        <div class="form-group">
                            <label class="control-label"> <?=translate('name')?> <span class="required">*</span></label>
                            <input type="text" class="form-control" name="name" value="<?=set_value('name')?>" autocomplete="off" />
                            <span class="error"></span>
                        </div>
                        <div class="form-group">
                            <label class="control-label"> <?=translate('email')?> <span class="required">*</span></label>
                            <input type="text" class="form-control" name="email" value="<?=set_value('email')?>" autocomplete="off" />
                            <span class="error"></span>
                        </div>
                        <div class="form-group">
                            <label class="control-label"> <?=translate('post_code')?> <span class="required">*</span></label>
                            <input type="text" class="form-control" name="post_code" value="<?=set_value('post_code')?>" autocomplete="off" />
                            <span class="error"></span>
                        </div>
                        <div class="form-group">
                            <label class="control-label"> <?=translate('mobile_no')?> <span class="required">*</span></label>
                            <input type="text" class="form-control" name="mobile_no" value="<?=set_value('mobile_no')?>" autocomplete="off" />
                            <span class="error"></span>
                        </div>
                        <div class="form-group">
                            <label class="control-label"> <?=translate('state')?> <span class="required">*</span></label>
                            <input type="text" class="form-control" name="state" value="<?=set_value('state')?>" autocomplete="off" />
                            <span class="error"></span>
                        </div>

                        <div class="form-group">
                            <label class="control-label"><?=translate('address')?> <span class="required">*</span></label>
                            <textarea class="form-control" id="address" name="address" rows="2" placeholder="Enter Address"><?php echo set_value('address'); ?></textarea>
                            <span class="error"><?=form_error('class_id')?></span>
                        </div>
                        <div class="form-group">
                            <label class="control-label"> <?=translate('payment_method')?> <span class="required">*</span></label>
                            <?php
                                $config = $this->home_model->getPaymentConfig($get_student['branch_id']);
                                    $payvia_list = array('' => translate('select_payment_method'));
                                    if ($config['paypal_status'] == 1)
                                        $payvia_list['paypal'] = 'Paypal';
                                    if ($config['stripe_status'] == 1)
                                        $payvia_list['stripe'] = 'Stripe';
                                    if ($config['payumoney_status'] == 1)
                                        $payvia_list['payumoney'] = 'PayUmoney';
                                    if ($config['paystack_status'] == 1)
                                        $payvia_list['paystack'] = 'Paystack';
                                    if ($config['razorpay_status'] == 1)
                                        $payvia_list['razorpay'] = 'Razorpay';
                                    if ($config['sslcommerz_status'] == 1)
                                        $payvia_list['sslcommerz'] = 'SSLcommerz';
                                    if ($config['jazzcash_status'] == 1)
                                        $payvia_list['jazzcash'] = 'Jazzcash';
                                    if ($config['midtrans_status'] == 1)
                                        $payvia_list['midtrans'] = 'Midtrans';
                                    echo form_dropdown("payment_method", $payvia_list, set_value('payment_method'), "class='form-control'  data-plugin-selectTwo id='pay_via'
                                    data-minimum-results-for-search='Infinity' ");
                                ?>
                            <span class="error"></span>
                        </div>
                        <button type="submit" class="btn btn-block btn-red" data-loading-text="<i class='fas fa-spinner fa-spin'></i> Processing">Pay Now</button>
                        <?php echo form_close();?>
                    </div>
                </div>
            </section>
            <div id="error-message"></div>
        </div>
    </div>
    <script src="<?php echo base_url('assets/frontend/js/bootstrap.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/vendor/select2/js/select2.full.min.js'); ?>"></script>
	<script src="<?php echo base_url('assets/vendor/sweetalert/sweetalert.min.js');?>"></script>
    <script src="<?php echo base_url('assets/frontend/js/payment.js'); ?>"></script>
<?php
$alertclass = "";
if($this->session->flashdata('alert-message-success')){
    $alertclass = "success";
} else if ($this->session->flashdata('alert-message-error')){
    $alertclass = "error";
} else if ($this->session->flashdata('alert-message-info')){
    $alertclass = "info";
}
if($alertclass != ''):
    $alert_message = $this->session->flashdata('alert-message-'. $alertclass);
?>
    <script type="text/javascript">
        swal({
            toast: true,
            position: 'top-end',
            type: '<?php echo $alertclass?>',
            title: '<?php echo $alert_message?>',
            confirmButtonClass: 'btn btn-1',
            buttonsStyling: false,
            timer: 8000
        })
    </script>
<?php endif; ?>
</body>
</html>