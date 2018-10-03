<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div id="homeopt-in" style="display:none;">
    <div class="opthead">
      <h4>Subscribe to our newsletter</h4>
    </div>
    <div class="col-sm-4">
      <div class="optimg">
        <img src="<?php echo base_url();?>upload/gallaries/images/1538570322947.png" alt="FetishBNB Logo" />
      </div>
    </div>
    <div class="col-sm-8">
      <div class="optform">
        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec quis risus elementum, aliquet nisl vel, feugiat felis.</p>
        <input name="contact_email" placeholder="Your Email Address" />
        <button name="contact_sub" value="Submit" class="btn">Subscribe</button>
      </div>
    </div>
</div>

<!-- Hero Slider -->
<section class="pad-none cd-hero js-cd-hero js-cd-autoplay">
   <ul class="cd-hero__slider">
     <?php for($i = 1; $i<=3;$i++) { if($this->settings->{'banner_title_'.$i}) { ?>
      <li style="background-image: url('<?php echo base_url('upload/home/').$this->settings->{'banner_image_'.$i} ?>')" class="cd-hero__slide cd-hero__slide--selected js-cd-slide">
         <div class="cd-hero__content cd-hero__content--full-width">
            <h2><?php echo $this->settings->{'banner_title_'.$i} ?></h2>
            <p><?php echo $this->settings->{'banner_description_'.$i} ?></p>
         </div> <!-- .cd-hero__content -->
      </li>
    <?php } } ?>
      <!-- other slides here -->
   </ul> <!-- .cd-hero__slider -->
   <div style="display:none;" class="cd-hero__nav js-cd-nav">
      <nav>
         <span class="cd-hero__marker cd-hero__marker--item-1 js-cd-marker"></span>

         <ul>
           <li class="cd-selected"><a href="#0">Slider</a></li>
           <?php for($i = 2; $i<=3;$i++) { if($this->settings->{'banner_title_'.$i}) {?>
            <li><a href="#<?php echo $i-1;?>">Slider</a></li>
            <!-- other navigation items here -->
          <?php }} ?>
         </ul>
      </nav>
   </div> <!-- .cd-hero__nav -->
</section> <!-- .cd-hero -->
<!-- Hero Slider-->


<!-- Section Search -->
<section class="pad-bottom-none">
    <div class="container">
        <div class="slider-below-wrap typo-light">
            <div class="row">
                <div class="col-md-offset-1 col-md-10">
                    <!-- Search -->
                    <div class="search">
                        <form id="searchForm" class="dark">
                            <div class="input-group">
                                <input type="text" class="form-control search" name="search-categories" id="search-categories" placeholder="<?php echo lang('action_search_home') ?>" required>
                                <span class="input-group-btn">
                                    <button class="btn" type="button"><i class="fa fa-search"></i></button>
                                </span>
                            </div>
                        </form>
                    </div><!-- Search -->
                </div><!-- Column -->
            </div><!-- Slider Below Wrapper -->
        </div><!-- Row -->
    </div><!-- Container -->
</section><!-- Section Search -->

<!-- Section Featured Events -->
<?php if(! empty($f_events)) { ?>
<section class="pad-top-none typo-dark">
    <div class="container">
        <div class="row">
            <!-- Title -->
            <div class="col-sm-12">
                <div class="title-container sm">
                    <div class="title-wrap">
                        <h3 class="title"><?php echo lang('w_featured_events') ?></h3>
                        <span class="separator line-separator"></span>
                    </div>
                </div>
            </div>
            <!-- Title -->
        </div>
        <!-- Row -->
        <div class="row">
            <?php $row = 0; foreach($f_events as $key => $val) {
                $row++;
                if($row%4 == 0) echo '</div><hr class="md"><div class="row">'; ?>
            <!-- Column -->
            <div class="col-md-4">
                <!-- Event Wrapper -->
                <div class="event-wrap">
                    <!-- Event Banner Image -->
                    <a href="<?php echo site_url('events/detail/').str_replace(' ', '+', $val->title) ?>" title="<?php lang('action_view'); ?>" >
                        <div class="event-img-wrap">
                            <img alt="<?php echo $val->title ?>" class="img-responsive" src="<?php echo base_url().($val->images ? '/upload/events/images/'.image_to_thumb(json_decode($val->images)[0]) : 'themes/default/images/course/course-01.jpg') ?>" width="600" height="220">
                            <?php if($val->recurring) { ?>
                            <span class="cat bg-green"><?php echo lang('e_l_repetitive_event'); ?></span>
                            <?php } else { ?>
                            <span class="cat bg-orange"><?php $c_day = strtotime($val->end_date) - strtotime($val->start_date);$c_day = floor($c_day / (60 * 60 * 24)); echo $c_day > 0 ? $c_day : 1; ?><?php echo ' '.lang('e_l_day_event'); ?></span>
                            <?php } ?>
                        </div><!-- Event Banner Image -->
                    </a>
                    <!-- Event Details -->
                    <div class="event-details">
                        <h4><a href="<?php echo site_url('events/detail/').str_replace(' ', '+', $val->title) ?>" title="<?php lang('action_view'); ?>"><?php echo $val->title ?></a></h4>

                        <div class="row">
                            <div class="col-sm-6">
                                <ul class="events-meta">
                                    <li><i class="fa fa-money"></i>
                                        <?php echo lang('e_l_price').' : '; ?>
                                        <?php echo $val->fees ? $val->fees.' '.$this->settings->default_currency : '<strong>'.lang('events_free').'</strong>'; ?></li>
                                    </li>
                                    <li><i class="fa fa-th"></i> <?php echo lang('events_capacity').' : '.$val->capacity ?></li>
                                </ul>
                            </div>
                            <div class="col-sm-6">
                                <ul class="events-meta">
                                  <li>
                                      <i class="fa fa-calendar"></i><?php echo lang('e_bookings_duration').' : '.date('M j, Y', strtotime($val->end_date)); ?>
                                  </li>
                                  <li>
                                      <i class="fa fa-clock-o"></i><?php echo lang('e_bookings_timing').' : '.'<br>'; ?>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo date('g:i A', strtotime($val->start_time)).' - '.date('g:i A', strtotime($val->end_time)); ?>
                                  </li>

                                </ul>
                            </div>
                            <div class="col-sm-6">
                                <ul class="events-meta">
                                </ul>
                            </div>
                            <div class="col-sm-6">
                                <ul class="events-meta">
                                </ul>
                            </div>
                        </div>

                        <?php if( date('Y-m-d') < date( 'Y-m-d', strtotime( str_replace( '-', '/', $val->end_date ) ) ) ) { ?>
                        <a href="<?php echo site_url('ebooking/').str_replace(' ', '+', $val->title) ?>" class="btn"><?php echo lang('action_book_now') ?></a>
                        <?php } else { ?>
                        <a disabled class="btn disabled"><?php echo lang('e_l_event_over') ?></a>
                        <?php } ?>

                    </div>
                </div><!-- Event Wrapper -->
            </div><!-- Column -->
        <?php } ?>

        </div><!-- Row -->
    </div><!-- Container -->
</section><!-- Section Featured Events -->
<?php } ?>


<!-- Section About Institute -->
<section id="about" class="bg-dark typo-light">
    <div class="container">
        <div class="row counter-sm">
            <!-- Title -->
            <div class="col-sm-12">
                <div class="title-container">
                    <div class="title-wrap">
                        <h3 class="title"><?php echo lang('w_about_us') ?></h3>
                        <span class="separator line-separator"></span>
                    </div>
                    <p class="description"><?php echo $this->settings->about_institute; ?></p>
                </div>
            </div>
            <!-- Title -->
            <div class="col-sm-6 col-md-4">
                <!-- Count Block -->
                <div class="count-block dark bg-verydark">
                    <h5><?php echo lang('total_users') ?></h5>
                    <h3 data-count="<?php echo $count_tutors+1234; ?>" class="count-number"><span class="counter"><?php echo $count_batches; ?></span></h3>
                    <i class="uni-business-woman"></i>
                </div><!-- Counter Block -->
            </div><!-- Column -->
            <div class="col-sm-6 col-md-4">
                <!-- Count Block -->
                <div class="count-block dark bg-verydark">
                    <h5><?php echo lang('menu_events'); ?></h5>
                    <h3 data-count="<?php echo $count_events; ?>" class="count-number"><span class="counter"><?php echo $count_events; ?></span></h3>
                    <i class="uni-wine-glass"></i>
                </div><!-- Counter Block -->
            </div><!-- Column -->
            <div class="col-sm-6 col-md-4">
                <!-- Count Block -->
                <div class="count-block dark bg-verydark">
                    <h5><?php echo lang('menu_tutors') ?></h5>
                    <h3 data-count="<?php echo $count_tutors; ?>" class="count-number"><span class="counter"><?php echo $count_tutors; ?></span></h3>
                    <i class="uni-talk-man"></i>
                </div><!-- Counter Block -->
            </div><!-- Column -->
        </div><!-- Row -->
    </div><!-- Container -->
</section><!-- Section About Institute -->

<!-- Section Upcoming Events -->
<?php if(! empty($u_events)) { ?>
<section class="bg-grey typo-dark">
    <div class="container">
        <div class="row">
            <!-- Title -->
            <div class="col-sm-12">
                <div class="title-container typo-dark">
                    <div class="title-wrap">
                        <h3 class="title"><?php echo lang('w_upcoming_events') ?></h3>
                        <span class="separator line-separator"></span>
                    </div>
                </div>
            </div>
            <!-- Title -->
        </div><!-- Row -->

        <!-- Row -->
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <!-- Column -->
                    <?php   foreach($u_events as $key => $val) {  ?>
                    <div class="col-sm-4">
                        <div class="contact-info margin-top-30">
                            <div class="info-icon bg-dark">
                                <?php if($val->category_name == 'workshop') { ?><i class="uni-sound-wave"></i>
                                <?php } else if($val->category_name == 'seminar') { ?><i class="uni-speach-bubbledialog"></i>
                                <?php } else if($val->category_name == 'weekly test') {  ?><i class="uni-letter-open"></i>
                                <?php } else if($val->category_name == 'annual day') { ?><i class="uni-trophy"></i>
                                <?php } else if($val->category_name == 'farewell') { ?><i class="uni-gift-box"></i>
                                <?php } else if($val->category_name == 'freshers day') { ?><i class="uni-rock-androll"></i>
                                <?php } else { ?><i class="uni-megaphone"></i><?php } ?>
                            </div>

                            <div class="event-img-wrap">
                                <img alt="<?php echo $val->title ?>" class="img-responsive" src="<?php echo base_url().($val->images ? '/upload/events/images/'.image_to_thumb(json_decode($val->images)[0]) : 'themes/default/images/course/course-01.jpg') ?>" width="600" height="220">
                                <?php if($val->recurring) { ?>
                                <span class="cat bg-green"><?php echo lang('e_l_repetitive_event'); ?></span>
                                <?php } else { ?>
                                <span class="cat bg-orange"><?php $c_day = strtotime($val->end_date) - strtotime($val->start_date);$c_day = floor($c_day / (60 * 60 * 24)); echo $c_day > 0 ? $c_day : 1; ?><?php echo ' '.lang('e_l_day_event'); ?></span>
                                <?php } ?>
                            </div>

                            <h5 class="title text-capitalize margin-top-30"><a href="<?php echo site_url('events/detail/').str_replace(' ', '+', $val->title) ?>" title="<?php lang('action_view'); ?>"><?php echo $val->title ?></a></h5>
                            <p><i class="fa fa-calendar"></i><?php echo ' '.lang('e_bookings_duration').' : '; ?><?php echo date('M j, y', strtotime($val->start_date)).' - '.date('M j, y', strtotime($val->end_date)); ?></p>
                            <p><i class="fa fa-clock-o"></i><?php echo ' '.lang('e_bookings_timing').' : '; ?><?php echo date('g:i A', strtotime($val->start_time)).' - '.date('g:i A', strtotime($val->end_time)); ?></p>
                        </div><!-- Contact Info -->
                    </div><!-- Column -->
                    <?php } ?>
                </div><!-- Row -->
            </div><!-- Column -->


        </div><!-- Row -->
    </div><!-- Container -->
</section><!-- Section -->
<?php } ?>

<!-- Section Video -->
<?php if($this->settings->intro_video_url) { ?>
<section class="relative typo-light video-bg min-height bg-cover overlay sm" data-background="<?php echo base_url('upload/home/').$this->settings->{'banner_image_1'} ?>" >

    <div class="player" data-property="{videoURL:'<?php echo $this->settings->intro_video_url ?>',containment:'.video-bg',startAt:0, mute:true, autoPlay:true, showControls:false}"></div>
    <div id="video-controls" class="video-controls" data-animation="fadeInRight" data-animation-delay="800">
        <a class="fa fa-pause" href="#"></a>
        <a class="fa fa-volume-down" href="#"></a></div>
    <div class="container">
        <div class="row">
            <div class="col-md-12 static">
                <div class="vmiddle z-index position-none-767">
                    <h2 class="parent-has-overlay margin-none"><?php echo lang('w_take_tour') ?></h2>
                </div>
            </div><!-- Column -->
        </div><!-- Row -->
    </div><!-- Container -->
</section><!-- Section Video -->
<?php } ?>

<!-- Section Teachers -->
<?php if(!empty($tutors)) { ?>
<section id="teacher" class="bg-grey typo-dark">
    <div class="container">
        <div class="row">
            <!-- Title -->
            <div class="col-sm-12">
                <div class="title-container">
                    <div class="title-wrap">
                        <h3 class="title"><?php echo lang('w_our_tutors') ?></h3>
                        <span class="separator line-separator"></span>
                    </div>
                </div>
            </div><!-- Title -->
        </div><!-- Row -->

        <!-- Team Container -->
        <div class="row">

            <!-- Column -->
            <?php foreach($tutors as $key => $tutor) { if($key == 3) break; ?>
            <div class="col-sm-4">
                <!-- Member Wrap -->
                <div class="member-wrap">
                    <!-- Member Image Wrap -->
                    <div class="member-img-wrap">
                        <a href="<?php echo site_url('hosts/').$tutor->username ?>">
                            <img width="600" height="500" alt="<?php echo $tutor->first_name.' '.$tutor->last_name ?>" class="img-responsive" src="<?php echo base_url().($tutor->image ? '/upload/users/images/'.$tutor->image : 'themes/default/images/teacher/teacher-01.jpg') ?>">
                        </a>
                    </div>
                    <!-- Member detail Wrapper -->
                    <div class="member-detail-wrap bg-white">
                        <h4 class="member-name"><a href="<?php echo site_url('hosts/').$tutor->username ?>"><?php echo $tutor->first_name.' '.$tutor->last_name; ?></a></h4>
                        <span><?php echo $tutor->profession ?>
                                <?php echo ' ('.lang('users_experience_1') ?> : <?php echo $tutor->experience > 1 ? $tutor->experience.' '.lang('users_experience_years') : $tutor->experience.' '.lang('users_experience_year') ?><?php echo ')'; ?></span>
                        <p><?php echo $tutor->about ?></p>
                    </div><!-- Member detail Wrapper -->
                </div><!-- Member Wrap -->
            </div><!-- Column -->
            <?php } ?>

        </div><!-- Row -->

        <div class="row m-t-50">
            <div class="col-md-12 text-center">
                <a href="<?php echo site_url('hosts') ?>" class="btn"><?php echo lang('header_view_all') ?></a>
            </div>
        </div>
    </div><!-- Container -->
</section><!-- Section Teachers -->
<?php } ?>

<!-- Section Testimonials -->
<?php if(!empty($testimonials)) { ?>
<section class="relative bg-light typo-light bg-cover overlay" data-background="<?php echo base_url('upload/home/').$this->settings->{'banner_image_2'} ?>">
    <div class="container parent-has-overlay">
        <div class="row rltd-items">
            <!-- Column Begins -->
            <div class="col-sm-12">
                <div class="owl-carousel"
                    data-animatein=""
                    data-animateout=""
                    data-items="2"
                    data-loop="true"
                    data-merge="true"
                    data-nav="false"
                    data-dots="false"
                    data-stagepadding=""
                    data-margin="30"
                    data-mobile="1"
                    data-tablet="1"
                    data-desktopsmall="2"
                    data-desktop="2"
                    data-autoplay="true"
                    data-delay="3000"
                    data-navigation="false">

                    <!-- Item Ends -->
                    <?php foreach($testimonials as $val) { ?>
                    <div class="item">
                        <!-- Blockquote Wrapper -->
                        <div class="quote-wrap dark">
                            <blockquote>
                                <p><?php echo $val->t_feedback; ?></p>
                            </blockquote>
                            <!-- Blockquote Author -->
                            <div class="quote-author">
                                <img width="80" height="80" src="<?php echo base_url('upload/testimonials/images/').image_to_thumb($val->image) ?>" class="img-responsive" alt="thumb">
                                <!-- Blockquote Footer -->
                                <div class="quote-footer">
                                    <h5 class="name text-capitalize"><?php echo $val->t_name ?></h5>
                                    <span class="text-capitalize">/ <?php echo $val->t_type ?></span>
                                </div><!-- Blockquote Footer -->
                            </div><!-- Blockquote Author -->
                        </div><!-- Blockquote Wrapper -->
                    </div><!-- Item Ends -->
                    <?php } ?>

                </div><!-- carousel -->
            </div><!-- Column -->
        </div><!-- Row -->
    </div><!-- Container -->
</section><!-- Section Testimonials -->
<?php } ?>

<!-- Section Contact -->
<section id="contact" class="bg-lgrey">
    <div class="container">
        <div class="row">
            <div class="col-sm-6">
                <div class="contact-info">
                    <div class="info-icon bg-dark">
                        <i class="uni-map2"></i>
                    </div>
                    <h5 class="title"><?php echo lang('contacts_office') ?></h5>
                    <p><?php echo $this->settings->institute_name ?></p>
                    <p><?php echo $this->settings->institute_address ?></p>
                </div><!-- Contact Info -->
            </div><!-- Column -->
            <div class="col-sm-6">
                <div class="contact-info">
                    <div class="info-icon bg-dark">
                        <i class="uni-mail"></i>
                    </div>
                    <h5 class="title"><?php echo lang('menu_contact') ?></h5>
                    <p><a href="mailto:<?php echo $this->settings->site_email ?>"><?php echo $this->settings->site_email ?></a></p>
                    <p><a href="tel:<?php echo $this->settings->institute_phone ?>"><?php echo $this->settings->institute_phone ?></a></p>
                </div><!-- Contact Info -->
            </div>
        </div><!-- Row -->
        <div class="row m-t-50">
            <div class="col-md-12 text-center">
                <a href="<?php echo site_url('contact') ?>" class="btn"><?php echo lang('contacts_get_in_touch') ?></a>
            </div>
        </div>
    </div><!-- Container -->
</section><!-- Section Contact -->
