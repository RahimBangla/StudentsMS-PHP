<!-- Main Slider Section Starts -->
<section class="main-slider">
    <div class="container-fluid">
        <ul class="main-slider-carousel owl-carousel owl-theme slide-nav">
            <?php
			foreach ($sliders as $key => $value) {
				$elements = json_decode($value['elements'], true);
				?>
            <li class="slider-wrapper">
                <div class="image" style="background-image: url(<?php echo base_url('uploads/frontend/slider/' . $elements['image']) ?>)" ></div>
                <div class="slider-caption <?php echo $elements['position'];  ?>">
                    <div class="container">
                        <div class="wrap-caption">
                            <h1><?php echo $value['title']; ?></h1>
                            <div class="text center"><?php echo $value['description']; ?></div>
                            <div class="link-btn">
                                <a href="<?php echo $elements['button_url1']; ?>" class="btn">
                                    <?php echo $elements['button_text1']; ?>
                                </a>
                                <a href="<?php echo $elements['button_url2']; ?>" class="btn btn1">
                                    <?php echo $elements['button_text2']; ?>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="slide-overlay"></div>
            </li>
            <?php } ?>
        </ul>
    </div>
</section>
<div class="container px-md-0 main-container">
    <!-- Features Section Starts -->
    <div class="notification-boxes row">
        <?php
		foreach ($features as $key => $value) {
			$elements = json_decode($value['elements'], true);
			?>
        <div class="col-lg-3 col-md-6 col-sm-12">
            <div class="box hover-border-outer hover-border">
                <div class="icon"><i class="<?php echo $elements['icon']; ?>"></i></div>
                <h4><?php echo $value['title']; ?></h4>
                <p><?php echo $value['description']; ?></p>
                <a href="<?php echo $elements['button_url']; ?>" class="btn btn-transparent">
                    <?php echo $elements['button_text']; ?>
                </a>
            </div>
        </div>
        <?php } ?>
    </div>
    <?php
        if (!empty($wellcome)) {
        $elements = json_decode($wellcome[ 'elements' ], true);
        ?>
    <!-- Welcome Section Starts -->
    <section class="welcome-area">
        <div class="row">
            <div class="col-md-6 col-sm-12">
                <h2 class="main-heading1 lite"><?php echo $wellcome['title']; ?></h2>
                <div class="sec-title style-two mb-tt">
                    <h2 class="main-heading2"><?php echo $wellcome['subtitle']; ?></h2>
                    <span class="decor"><span class="inner"></span></span>
                </div>
                <?php echo nl2br($wellcome['description']); ?>
            </div>
            <div class="col-md-6 col-sm-12">
                <div class="wel-img">
                    <img src="<?php echo base_url('uploads/frontend/home_page/' . $elements['image']); ?>" alt="image" class="img-fluid">
                </div>
            </div>
        </div>
    </section>
    <?php } ?>
</div>

<!-- Teachers Section Starts -->
<?php
    if (!empty($teachers)) {
    $elements = json_decode($teachers[ 'elements' ], true);
    ?>
<section class="featured-doctors" style="background-image: url(<?php echo base_url('uploads/frontend/home_page/' . $elements['image']); ?>);">
    <div class="container px-md-0">
        <div class="sec-title text-center">
            <h2><?php echo $teachers['title'] ?></h2>
            <p><?php echo nl2br($teachers['description']); ?></p>
            <span class="decor"><span class="inner"></span></span>
        </div>
        <div class="row">
            <?php
			$doctor_list = $this->home_model->get_teacher_list($elements['teacher_start'], $branchID);
			foreach ($doctor_list as $row) {
                ?>
            <div class="col-lg-3 col-sm-6">
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
            </div>
            <?php } ?>
        </div>
    </div>
</section>
<?php }
    if (!empty($testimonial)) {
    ?>
<!-- Testimonial Section Starts -->
<section class="testimonial-wrapper" >
    <div class="container px-md-0">
        <div class="sec-title text-center">
            <h2><?php echo $testimonial['title'] ?></h2>
            <p><?php echo nl2br($testimonial['description']); ?></p>
            <span class="decor"><span class="inner"></span></span>
        </div>
        <div class="testimonial-carousel owl-carousel owl-theme">
        <?php
        $this->db->where('branch_id', $branchID);
        $testimonials = $this->db->get('front_cms_testimonial')->result_array();
        foreach ($testimonials as $value) {
            ?>
            <div class="single-testimonial-style">
                <div class="inner-content">
                    <div class="review-box">
                        <ul>
                        <?php 
                        for ($i=1; $i < 6; $i++) {
                            if ($i <= $value['rank']) {
                                echo '<li><i class="fas fa-star"></i></li>';
                            }else{
                                echo '<li><i class="far fa-star"></i></li>';
                            }
                        }
                        ?>
                        </ul>
                    </div>
                    <div class="text-box">
                        <p><?php echo nl2br($value['description']); ?></p>
                    </div>
                    <div class="client-info">
                        <div class="image">
                            <img src="<?php echo $this->testimonial_model->get_image_url($value['image']); ?>" alt="Awesome Image">
                        </div>
                        <div class="title">
                            <h3><?php echo $value['name']; ?></h3>
                            <span><?php echo $value['surname']; ?></span>
                        </div>
                    </div>
                </div> 
            </div>
        <?php } ?>      
        </div>
    </div>
</section>
<?php } 
    if (!empty($statistics)) {
    $statisticsElem = json_decode($statistics['elements'], true);
    ?>
<!-- Statistics Section Starts -->
<section class="counters-wrapper" style="background-image: url(<?php echo base_url('uploads/frontend/home_page/' . $statisticsElem['image']); ?>);" >
    <div class="container px-md-0">
        <div class="sec-title text-center">
            <h2><?php echo $statistics['title'] ?></h2>
            <p><?php echo nl2br($statistics['description']); ?></p>
            <span class="decor"><span class="inner"></span></span>
        </div>
        <div class="row">
            <!-- widget count item -->
            <?php for ($i=1; $i < 5; $i++) { ?>
            <div class="col-lg-3 col-sm-6 col-xs-6 text-center">
                <div class="counters-item">
                    <i class="<?php echo $statisticsElem['widget_icon_' . $i] ?>"></i>
                    <div>
                        <span class="counter" data-count="<?php echo $this->home_model->getStatisticsCounter($statisticsElem['type_' . $i], $branchID); ?>">0</span>
                    </div>
                    <h3><?php echo $statisticsElem['widget_title_' . $i]; ?></h3>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>
</section>
<?php }
    if (!empty($services) || !empty($cta_box)) {
        ?>
<!-- Services Section Starts -->      
<div class="" style="background-image: url(<?php echo base_url('assets/frontend/images/14.png') ?>); padding: 60px 0;">
    <div class="container px-md-0">
    <?php if (!empty($services)) { ?>
        <section class="medical-services">
            <div class="sec-title text-center">
                <h2><?php echo $services['title']; ?></h2>
                <p><?php echo nl2br($services['description']); ?></p>
                <span class="decor"><span class="inner"></span></span>
            </div>
            <ul class="list-unstyled row text-center">
                <?php
                $this->db->where('branch_id', $branchID);
				$services_list = $this->db->get('front_cms_services_list')->result_array();
			    foreach ($services_list as $key => $value) {
			    	?>
                <li class="col-lg-2 col-sm-4">
                    <div class="icon">
                        <div class="i-hover"><i class="<?php echo $value['icon']; ?>"></i></div>
                    </div>
                    <h5><?php echo $value['title']; ?></h5>
                    <p><?php $string = $value['description']; echo (strlen($string) > 30) ? substr($string, 0, 30) . '...' : $string; ?></p>
                </li>
                <?php } ?>
            </ul>
        </section>
    <?php } 
		if (!empty($cta_box)) {
        $elements = json_decode($cta_box[ 'elements' ], true);
		?>
        <div class="book-appointment-box">
            <div class="row">
                <div class="col-lg-8 col-md-12 text-center text-lg-left">
                    <h4><?php echo $cta_box['title']; ?></h4>
                    <h3><div class="inner-box"><i class="fa fa-phone"></i></div> <?php echo $elements['mobile_no']; ?></h3>
                </div>
                <div class="col-lg-4 col-md-12 text-center text-lg-left">
                    <a href="<?php echo $elements['button_url']; ?>" class="btn btn-main btn-1 text-uppercase"><?php echo $elements['button_text']; ?></a>
                </div>
            </div>
        </div>
    <?php } ?>
    </div>
</div>
<?php } ?>

