<!-- Main Banner Starts -->
<div class="main-banner" style="background: url(<?php echo base_url('uploads/frontend/banners/' . $page_data['banner_image']); ?>) center top;">
    <div class="container px-md-0">
        <h2><span><?php echo $page_data['page_title']; ?></span></h2>
    </div>
</div>

<div class="breadcrumb">
    <div class="container px-md-0">
        <ul class="list-unstyled list-inline">
            <li class="list-inline-item"><a href="<?php echo base_url('home') ?>">Home</a></li>
            <li class="list-inline-item active"><?php echo $page_data['page_title']; ?></li>
        </ul>
    </div>
</div>

        <div class="container px-md-0 main-container">
            <div class="row">
                <div class="col-lg-9 col-md-8 col-sm-12">
                    <div class="news-post-list">
                        <article class="news-post">
                            <div class="text-center">
                                <img src="<?=base_url('uploads/frontend/events/' . $event['image'] )?>" alt="Event Image" class="img-fluid">
                            </div>
                            <div class="inner">
                                <h4>
                                    <a href="#"><?php echo $event['title'] ?></a>
                                </h4>
                                <ul class="list-unstyled list-inline post-meta">
                                    <li class="list-inline-item">
                                        <i class="fa fa-calendar"></i><?php echo _d($event['start_date']); ?> To <?php echo _d($event['end_date']); ?>
                                    </li>
                                    <li class="list-inline-item">
                                        <i class="fa fa-user"></i> 
                                        By <a href="#"><?=get_type_name_by_id('staff', $event['created_by'], 'name')?></a>
                                    </li>
                                    <li class="list-inline-item">
                                        <i class="fa fa-tag"></i>
                                        <?php
                                            if($event['type'] != 'holiday'){
                                                echo get_type_name_by_id('event_types', $event['type']);
                                            }else{
                                                echo translate('holiday'); 
                                            }
                                        ?>
                                    </li>
                                </ul>

                                <?php
                                if (!empty($event['audition'])) {
                                    $auditions = array(
                                        "1" => "everybody",
                                        "2" => "class",
                                        "3" => "section",
                                    ); 
                                    $audition = $auditions[$event['audition']];
                                    $subtxt = "";
                                    if($event['audition'] != 1) {
                                        $array = array();
                                        $selecteds = json_decode($event['selected_list']);
                                        foreach ($selecteds as $selected) {
                                            $array[] = $this->db->get_where($audition , array('id' => $selected))->row()->name;
                                        }
                                        $subtxt = " (" . implode(', ', $array) . ")";
                                    }
                                    echo "<h5>Event For : " . translate($audition) . $subtxt . "</h5>";
                                }
                                ?>
                                <p><?php echo $event['remark'] ?></p>
                            </div>
                        </article>
                    </div>
                </div>

                <div class="col-lg-3 col-md-4 col-sm-12">
                    <h4 class="side-heading1 top">Recent Events</h4>
                    <ul class="list-unstyled recent-comments-list">
                        <?php
                        $url_alias = $cms_setting['url_alias'];
                        $start_date = date('Y-m-d', strtotime("+7 day"));
                        $end_date = date('Y-m-d');
                        $this->db->limit(6);
                        $this->db->where('start_date >=', $end_date);
                        $this->db->where('start_date <=', $start_date);
                        $this->db->where('branch_id', $branchID);
                        $this->db->where('status', 1);
                        $this->db->where('show_web', 1);
                        $this->db->order_by("id", "desc");
                        $q = $this->db->get('event');
                        if ($q->num_rows() > 0) {
                            $result = $q->result_array();
                            foreach ($result as $key => $value) {
                        ?>
                        <li>
                            <p>
                                <a href="<?=base_url('home/event_view/'. $value['id'] . "/" . $url_alias)?>">
                                    <?php echo $value['title'] ?>
                                </a>
                            </p>
                            <span class="date-stamp"><?php echo get_nicetime($value['created_at']) ?></span>
                        </li>
                        <?php } } ?>
                    </ul>
                    </div>
                </div>
            </div>
</div>
<!-- Main Container Ends -->