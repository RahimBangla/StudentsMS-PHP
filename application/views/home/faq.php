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
    <!-- Content Starts -->
    <h3 class="main-heading2 mt-0"><?php echo $page_data['title']; ?></h3>
    <?php echo $page_data['description']; ?>
    <!-- Content Ends -->
    <!-- FAQ's Accordions Starts -->
    <div class="accordion" id="accordion-faqs">
        <?php 
            $faq_list = $this->db->where('branch_id', $branchID)->get('front_cms_faq_list')->result_array();
            foreach ($faq_list as $key => $value) {
            ?>
        <div class="card">
            <!-- Card Header Starts -->
            <div class="card-header" id="faq<?php echo $key; ?>">
                <h5 class="card-title">
                    <a data-toggle="collapse" data-target="#collapse<?php echo $key; ?>" aria-expanded="false" aria-controls="collapse<?php echo $key; ?>"><?php echo $value['title'] ?></a>
                </h5>
            </div>
            <div id="collapse<?php echo $key; ?>" class="collapse" aria-labelledby="faq<?php echo $key; ?>" data-parent="#accordion-faqs">
                <div class="card-body">
                    <?php echo $value['description']; ?>
                </div>
            </div>
        </div>
        <?php } ?>
    </div>
    <!-- Accordion #5 Ends -->
</div>
<!-- Main Container Ends -->