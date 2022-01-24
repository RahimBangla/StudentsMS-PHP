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
            <h2 class="main-heading2 nomargin-top"><?php echo $gallery['title']; ?></h2>
            <div class="gallery-grid text-center">
                <div class="row">
                <!-- Gallery Image #1 Starts -->
                <?php 
                $elem = $gallery['elements'];
                $elem = json_decode($elem, TRUE);
                foreach ($elem as $key => $row) {
                    $url = "";
                    $class = "";
                    $icon = "";
                    if ($row['type'] == 2) {
                        $class = 'popup-video';
                        $icon = 'fab fa-youtube';
                        $url = $row['video_url'];
                    } else {
                        $class = 'zoom';
                        $icon = 'fa fa-image';
                        $url = $this->gallery_model->get_image_url($row['image']);
                    }
                 ?>
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="hover-content">
                            <img src="<?php echo $this->gallery_model->get_image_url($row['image']); ?>" alt="Gallery Image" class="img-fluid">
                            <div class="overlay">
                                <a href="<?php echo $url; ?>" class="btn btn-1 <?=$class?>"><i class="<?=$icon?>"></i></a>
                            </div>
                        </div>
                    </div>
                <?php } ?>
                </div>                
            </div>
</div>
<!-- Main Container Ends -->
