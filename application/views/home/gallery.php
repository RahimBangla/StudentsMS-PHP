<div class="main-banner" style="background: url(<?php echo base_url('uploads/frontend/banners/' . $page_data['banner_image']); ?>) center top;">
    <div class="container px-md-0">
        <h2><span><?php echo $page_data['page_title']; ?></span></h2>
    </div>
</div>
<div class="breadcrumb">
    <div class="container px-md-0">
        <ul class="list-unstyled list-inline">
            <li class="list-inline-item"><a href="<?php echo base_url('home'); ?>">Home</a></li>
            <li class="list-inline-item active"><?php echo $page_data['page_title']; ?></li>
        </ul>
    </div>
</div>

<div class="container gallery-album px-md-0 main-container">
    <ul id="doctors-filter" class="list-unstyled list-inline">
        <li class="list-inline-item"><a href="#" class="active" data-group="all">All</a></li>
        <?php foreach ($category as $row) { ?>
        <li class="list-inline-item"><a href="#" data-group="<?php echo $row['category_id']; ?>"><?php echo $row['category_name']; ?></a></li>
        <?php } ?>
    </ul>
    <ul id="doctors-grid" class="row grid">
        <?php foreach ($galleryList as $row) { ?>
        <li class="doctors-grid col-lg-4 col-md-6" data-groups='["all", "<?php echo $row['category_id']; ?>"]'>
            <div class="bio-box">
                <div class="gallery-album-item">
                    <div class="gallery-album-img">
                        <img src="<?php echo $this->gallery_model->get_image_url($row['thumb_image']); ?>" alt="Image">
                    </div>
                    <div class="gallery-album-title">
                        <h3><?php echo $row['title'] ?></h3>
                        <a class="btn" href="<?php echo base_url('home/gallery_view/' . $row['alias']); ?>"><i class="fas fa-photo-video"></i></a>
                    </div>
                    <div class="gallery-album-meta">
                        <p>By<a href=""><?php echo $row['staff_name'] ?></a></p>
                    </div>
                    <div class="gallery-album-text">
                        <p><?php echo $row['description']; ?></p>
                    </div>
                </div>
            </div>
        </li>
        <?php } ?>
    </ul>
</div>
