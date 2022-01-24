<!-- Footer Starts -->
<footer class="main-footer">
    <div class="footer-area" style="background: #383838 url(<?=base_url('assets/frontend/images/07.png')?>);">
        <div class="container px-md-0">
            <div class="row">
                <div class="col-lg-4 col-md-4 col-sm-12">
                    <div class="footer-logo">
                        <img src="<?php echo base_url('uploads/frontend/images/' . $cms_setting['logo']); ?>" alt="Logo">
                    </div>
                    <div class="footer-select">
                        <div class="form-group">
                            <?php
                                $branch_list = $this->home_model->branch_list();
                                $default_branch = $this->home_model->getDefaultBranch();
                                echo form_dropdown("branch_id", $branch_list, $default_branch, "class='form-control' id='activateSchool'
                                data-plugin-selectTwo data-minimum-results-for-search='Infinity'");
                            ?>
                        </div>
                    </div>
                    <p class="footer-dec"><?php echo $cms_setting['footer_about_text']; ?></p>
                    <ul class="social">
                    <?php if (!empty($cms_setting['facebook_url'])) { ?>
                        <li><a href="<?php echo $cms_setting['facebook_url']; ?>" target="_blank"><i class="fab fa-facebook-f"></i></a></li>
                    <?php } if (!empty($cms_setting['twitter_url'])) { ?>
                        <li><a href="<?php echo $cms_setting['twitter_url']; ?>" target="_blank"><i class="fab fa-twitter"></i></a></li>
                    <?php } if (!empty($cms_setting['youtube_url'])) { ?>
                        <li><a href="<?php echo $cms_setting['youtube_url']; ?>" target="_blank"><i class="fab fa-youtube"></i></a></li>
                    <?php } if (!empty($cms_setting['google_plus'])) { ?>
                        <li><a href="<?php echo $cms_setting['google_plus']; ?>" target="_blank"><i class="fab fa-google-plus-g"></i></a></li>
                    <?php } if (!empty($cms_setting['linkedin_url'])) { ?>
                        <li><a href="<?php echo $cms_setting['linkedin_url']; ?>" target="_blank"><i class="fab fa-linkedin-in"></i></a></li>
                    <?php } if (!empty($cms_setting['instagram_url'])) { ?>
                        <li><a href="<?php echo $cms_setting['instagram_url']; ?>" target="_blank"><i class="fab fa-instagram"></i></a></li>
                    <?php } if (!empty($cms_setting['pinterest_url'])) { ?>
                        <li><a href="<?php echo $cms_setting['pinterest_url']; ?>" target="_blank"><i class="fab fa-pinterest-p"></i></a></li>
                    <?php } ?>
                    </ul>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-12">
                    <h4>Address</h4>
                    <ul class="list-unstyled address-list">
                        <li class="clearfix address">
                            <i class="fas fa-map-marker-alt"></i> <?php echo $cms_setting['address']; ?>
                        </li>
                        <li class="clearfix">
                            <i class="fas fa-phone"></i> <?php echo $cms_setting['mobile_no']; ?>
                        </li>
                        <li class="clearfix">
                            <i class="fas fa-fax"></i></i> <?php echo $cms_setting['fax']; ?>
                        </li>
                        <li class="clearfix">
                            <i class="fas fa-envelope"></i> <a href="mailto:<?php echo $cms_setting['email']; ?>"><?php echo $cms_setting['email']; ?></a>
                        </li>
                    </ul>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-12">
                    <h4>Quick Links</h4>
                    <ul class="list-unstyled quick-links">
                        <?php
                            $school = '';
                            $last   = $this->uri->total_segments();
                            $page   = $this->uri->segment(2);
                            if ($page == 'page') {
                                if ($last > 3) {
                                    $school = $this->uri->segment($last);
                                }
                            } else {
                                if ($last > 2) {
                                    $school = $this->uri->segment($last);
                                }
                            }
							$result = web_menu_list(1);
							foreach ($result as $row) {
                                if ($cms_setting['online_admission'] == 0 && $row['alias'] == 'admission') continue;
								$url = "#";
                                if ($row['invisible'] == 0) {
                                    $url = $this->home_model->genURL($row, $school);
							?>
                        <li><a href="<?php echo $url; ?>"><i class="fa fa-angle-right"></i> <?php echo $row['title']; ?></a></li>
                        <?php } } ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="copyright">
        <div class="container px-md-0 clearfix text-center">
            <?php echo $cms_setting['copyright_text']; ?>
        </div>
    </div>
    <!-- Copyright Ends -->
</footer>
<!-- Footer Ends -->

<a href="#" class="back-to-top"><i class="far fa-arrow-alt-circle-up"></i></a>
<!-- JS Files -->
<script src="<?php echo base_url('assets/frontend/js/bootstrap.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/frontend/js/owl.carousel.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/frontend/plugins/shuffle/jquery.shuffle.modernizr.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/vendor/select2/js/select2.full.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/vendor/bootstrap-datepicker/js/bootstrap-datepicker.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/vendor/sweetalert/sweetalert.min.js');?>"></script>
<script src="<?php echo base_url('assets/frontend/plugins/magnific-popup/jquery.magnific-popup.min.js');?>"></script>
<script src="<?php echo base_url('assets/frontend/js/custom.js'); ?>"></script>

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
