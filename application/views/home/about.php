<?php $service = $this->db->get_where('front_cms_services', array('branch_id' => $branchID))->row_array(); ?>
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
            <li class="list-inline-item"><a href="<?php echo base_url('home') ?>">Home</a>
            </li>
            <li class="list-inline-item active">
                <?php echo $page_data['page_title']; ?>
            </li>
        </ul>
    </div>
</div>
<!-- Breadcrumb Ends -->
<!-- Main Container Starts -->
<div class="container px-md-0">
    <!-- About Intro Text Starts -->
    <section class="welcome-area about"
        style="background: url(<?php echo base_url('uploads/frontend/about/' . $page_data['about_image']); ?>) no-repeat right bottom;">
        <div class="row">
            <div class="col-lg-6 col-sm-12 about-col">
                <h3 class="main-heading1"><?php echo $page_data['title']; ?></h3>
                <h3 class="main-heading2"><?php echo $page_data['subtitle']; ?></h3>
                <?php echo $page_data['content']; ?>
            </div>
            <div class="col-md-6 col-sm-12 d-none d-md-block"></div>
        </div>
    </section>
    <!-- About Intro Text Ends -->
</div>
<!-- Main Container Ends -->
<!-- About Featured Section Starts -->
<section class="about-featured parallax"
    style="background-image: url(<?php echo base_url('uploads/frontend/about/' . $service['parallax_image']); ?>);">
    <div class="container px-md-0">
        <h3 class="lite"><?php echo $service['title']; ?></h3>
        <h2 class="lite">
            <?php echo $service['subtitle']; ?>
        </h2>
        <ul class="list-unstyled list row">
            <?php
            $services_list = $this->db->where('branch_id', $branchID)->get('front_cms_services_list')->result_array();
            foreach ($services_list as $key => $value) {
            ?>
            <li class="col-lg-4 col-md-6 col-sm-12">
                <i class="<?php echo $value['icon']; ?>"></i>
                <h4><?php echo $value['title']; ?></h4>
                <p><?php echo $value['description']; ?></p>
            </li>
            <?php } ?>
        </ul>
    </div>
</section>
<!-- About Featured Section Ends -->
<!-- Footer Top Bar Starts -->
<section class="footer-top-bar">
    <div class="container px-md-0 clearfix text-center-sm text-center-xs">
        <h3 class="float-left">
            <?php $elements = json_decode($page_data['elements'], true); echo $elements['cta_title']; ?>
        </h3>
        <a href="<?php echo $elements['button_url'] ?>" class="btn btn-black text-uppercase float-right">
            <?php echo $elements['button_text'] ?>
        </a>
    </div>
</section>
<!-- Footer Top Bar Ends -->