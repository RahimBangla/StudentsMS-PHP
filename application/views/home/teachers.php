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
            <li class="list-inline-item"><a href="<?php echo base_url('home'); ?>">Home</a></li>
            <li class="list-inline-item active"><?php echo $page_data['page_title']; ?></li>
        </ul>
    </div>
</div>
<!-- Breadcrumb Ends -->
<!-- Main Container Starts -->
<div class="container px-md-0 main-container">
    <!-- Doctors Desigination Filters Starts -->
    <ul id="doctors-filter" class="list-unstyled list-inline">
        <li class="list-inline-item"><a href="#" class="active" data-group="all">All Departments</a></li>
        <?php foreach ($departments as $row) { ?>
        <li class="list-inline-item"><a href="#" data-group="<?php echo $row['department_id']; ?>"><?php echo $row['department_name']; ?></a></li>
        <?php } ?>
    </ul>
    <!-- Doctors Desigination Filters Ends -->
    <!-- Doctors Bio List Starts -->
    <ul id="doctors-grid" class="row grid">
        <?php foreach ($doctor_list as $row) { ?>
        <li class="col-lg-3 col-md-6 col-sm-12 doctors-grid" data-groups='["all", "<?php echo $row['department']; ?>"]'>
            <div class="bio-box">
                <div class="profile-img">
                    <div class="dlab-border-left"></div>
                    <div class="dlab-border-right"></div>
                    <div class="dlab-media">
                        <img src="<?php echo get_image_url('staff', $row['photo']); ?>" alt="Doctor" class="img-fluid img-center-sm img-center-xs">
                    </div>
                    <div class="overlay">
                        <div class="overlay-txt">
                            <ul class="list-unstyled list-inline sm-links">
                                <li class="list-inline-item">
                                    <a href="<?php echo $row['facebook_url']; ?>"><i class="fab fa-facebook-f"></i></a>
                                </li>
                                <li class="list-inline-item">
                                    <a href="<?php echo $row['linkedin_url']; ?>"><i class="fab fa-linkedin-in"></i></a>
                                </li>
                                <li class="list-inline-item">
                                    <a href="<?php echo $row['twitter_url']; ?>"><i class="fab fa-twitter"></i></a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="txt-holder txt-overflow">
                    <h5><?php echo $row['name']; ?></h5>
                    <p class="designation"><?php echo $row['department_name']; ?></p>
                </div>
            </div> 
        </li>
        <?php } ?>
    </ul>
    <!-- Doctors List Ends -->
</div>
<!-- Main Container Ends -->