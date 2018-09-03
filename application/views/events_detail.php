<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<!-- Page Main -->
<div role="main" class="main">
	<div class="page-default bg-grey typo-dark">
		<!-- Container -->
		<div class="container">
			<div class="row">
				<!-- Page Content -->
				<div class="col-md-9">
					<!-- Course Wrapper -->
					<div class="row course-single">
						<!-- Course Banner Image -->
						<div class="col-sm-12">
							<?php if($event_detail->images) { ?>
							<div class="owl-carousel dots-inline"
							<?php echo count(json_decode($event_detail->images))>1 ? 'data-loop="true"' : ''; ?>
							data-animatein="pulse"
							data-animateout=""
							data-items="1" data-margin=""
							data-merge="true"
							data-nav="false"
							data-dots="false"
							data-stagepadding=""
							data-mobile="1"
							data-tablet="1"
							data-desktopsmall="1"
							data-desktop="1"
							data-autoplay="true"
							data-delay="3000"
							data-navigation="false">
								<!-- Items -->
								<?php foreach(json_decode($event_detail->images) as $key => $val) { ?>
								<div class="item <?php echo $key == 0 ? 'active' : ''; ?>">
									<img class="img-responsive" src="<?php echo base_url('/upload/events/images/').$val; ?>" alt="<?php echo $event_detail->title; ?>">
								</div>
								<?php } ?>
							</div>
							<?php } else { ?>
							<img class="img-responsive carousel-inner" src="<?php echo base_url('themes/default/images/course/course-01.jpg'); ?>" alt="<?php echo $event_detail->title; ?>">
							<?php } ?>
						</div>
					</div>
					<div class="row  course-single margin-top-30">
						<!-- Course Detail -->
						<div class="col-sm-12">
							<div class="course-detail">
								<!-- Course Content -->
								<div class="course-meta">
									<a href="<?php echo site_url('events/').str_replace(' ', '+', $event_detail->category_name); ?>"><span class="cat"><?php echo $event_detail->category_name; ?></span></a>
									<div class="rating"><span>☆</span><span>☆</span><span>☆</span><span>☆</span><span>☆</span></div>
									<h4><?php echo $event_detail->title; ?></h4>
									<?php if($event_detail->total_tutors) { ?>
									<ul class="course-meta-icons">
										<li>
											<i class="fa fa-money"></i><span><?php echo lang('e_l_price'); ?></span>
											<h5>
												<?php echo lang('e_l_price').' : '; ?>
												<?php echo $event_detail->fees ? $event_detail->fees.' '.$this->settings->default_currency : '<strong>'.lang('events_free').'</strong>'; ?></li>
											</h5>
										</li>
										<li><i class="fa fa-users"></i><span><?php echo lang('events_tutors') ?></span><h5><?php echo $event_detail->total_tutors ?></h5>
										</li>
										<?php if($event_detail->recurring) { ?>
										<li>
											<i class="fa fa-refresh"></i><span><?php echo lang('e_l_event_repetitive') ?></span>
											<h5>
											<?php foreach($recurring_types as $key => $val) {
													if($event_detail->recurring_type == $key)
			                                    		echo lang('events_recurring_types').' '.$val;
											?>
			                                <?php } ?>
			                                </h5>
										</li>
										<li>
											<i class="fa fa-calendar-check-o"></i><span><?php echo lang('e_l_event_days'); ?></span>
											<h5>
											<?php foreach($weekdays as $key => $val) {
													if(in_array($key, json_decode($event_detail->weekdays)))
														echo ' | '.$val;
											?>
			                                <?php } echo ' | '; ?>
											</h5>
										</li>
									    <?php } ?>
										<li>
											<i class="fa fa-calendar"></i><span><?php echo lang('e_bookings_duration'); ?></span>
											<h5><?php echo date('M j, Y', strtotime($event_detail->start_date)).' - '.date('M j, Y', strtotime($event_detail->end_date)); ?></h5>
										</li>
										<li>
											<i class="fa fa-clock-o"></i><span><?php echo lang('e_bookings_timing'); ?></span>
											<h5><?php echo date('g:i A', strtotime($event_detail->start_time)).' - '.date('g:i A', strtotime($event_detail->end_time)); ?></h5>
										</li>
									</ul>
									<?php } else { ?>
									<a disabled class="btn disabled"><?php echo lang('action_coming_soon') ?></a>
									<?php } ?>
								</div>
							</div><!-- Course Detail -->
						</div><!-- Column -->
					</div><!-- Course Wrapper -->
					<?php if($event_detail->total_tutors) { ?>
					<!-- Divider -->
					<hr class="md">
					<!-- If event is not started -->
					<?php if( strtotime(date('Y-m-d')) < strtotime($event_detail->end_date) ) { ?>
					<div class="row">
						<!-- Event Count -->
						<div class="<?php echo $event_detail->recurring ? 'col-md-4' : 'col-md-6' ?>">
							<!-- Count -->
							<div class="count-block">
								<h5><?php echo lang('e_l_booking_available_tickets') ?></h5>
								<h3 class="count-number" data-count="<?php echo $event_detail->capacity - $event_detail->total_e_bookings ?>"><span class="counter"></span> / <?php echo $event_detail->capacity ?></h3>
							</div><!-- Count -->
						</div>
						<div class="<?php echo $event_detail->recurring ? 'col-md-4' : 'col-md-6' ?>">
							<!-- Count -->
							<div class="count-block">
								<h5><?php echo lang('e_l_booking_joined_members') ?></h5>
								<h3 class="count-number" data-count="<?php echo $event_detail->total_e_bookings ?>"><span class="counter"></span></h3>
							</div><!-- Count -->
						</div><!-- Column -->

						<!-- If event is recurring -->
						<?php if($event_detail->recurring) { ?>
						<div class="col-md-4">
							<!-- Count -->
							<div class="count-block">
								<h5><?php echo lang('e_l_book_seats') ?></h5>
								<a href="<?php echo site_url('ebooking/').str_replace(' ', '+', $event_detail->title) ?>" class="btn"><?php echo lang('action_book_now') ?></a>
								<br>
							</div>
						</div>
						<?php } ?>

						<!-- If event is recurring -->
						<?php if(!$event_detail->recurring) { ?>
						<div class="col-md-6">
							<h4 class="title-sm"><?php echo lang('e_l_starts_in') ?> : </h4>
							<div id="daycounter-2" class="daycounter clearfix" data-counter="down" data-year="<?php echo date('Y', strtotime($event_detail->start_date)) ?>" data-month="<?php echo date('m', strtotime($event_detail->start_date)) ?>" data-date="<?php echo date('d', strtotime($event_detail->start_date)) ?>"></div>
						</div><!-- Column -->
						<div class="col-md-6">
							<!-- Count -->
							<div class="count-block m-t-80">
								<h5><?php echo lang('e_l_book_seats') ?></h5>
								<a href="<?php echo site_url('ebooking/').str_replace(' ', '+', $event_detail->title) ?>" class="btn"><?php echo lang('action_book_now') ?></a>
								<br>
							</div>
						</div>
						<?php } ?>
					</div>
					<?php } else { ?>
					<div class="row">
						<div class="col-md-12 text-center">
							<div class="count-block">
								<h4 class="title-sm"><?php echo lang('e_l_event_over') ?></h4>
							</div>
						</div><!-- Column -->
					</div>
					<?php } ?>
					<?php } ?>
					<!-- Divider -->
					<hr class="md">
					<div class="row">
						<div class="col-sm-12">
							<h4><?php echo lang('e_l_event_description'); ?></h4>
							<?php echo $event_detail->description ?>
						</div><!-- Column -->
					</div><!-- row -->
				</div><!-- Column -->
				<!-- Sidebar -->
				<div class="col-md-3">
					<!-- aside -->
					<aside class="sidebar">
						<!-- Widget -->
						<?php if(!empty($event_tutors)) { ?>
						<div class="widget">
							<h5 class="widget-title">Hosts<span></span></h5>
							<ul class="thumbnail-widget thumb-circle">
								<?php foreach($event_tutors as $val) { ?>
								<li>
									<div class="thumb-wrap">
										<a href="<?php echo site_url('tutors/').$val->username ?>">
											<img width="66" height="66" alt="<?php echo $val->first_name.' '.$val->last_name ?>" class="img-responsive" src="<?php echo base_url().($val->image ? '/upload/users/images/'.image_to_thumb($val->image) : 'themes/default/images/teacher/thumb-teacher-01.jpg') ?>">
										</a>
									</div>
									<div class="thumb-content">
										<a href="<?php echo site_url('tutors/').$val->username ?>"><?php echo $val->first_name.' '.$val->last_name ?></a>
										<span><?php echo lang('e_l_total_events').' : '.$val->total_events ?></span>
									</div>
								</li>
								<?php } ?>
							</ul>
						</div><!-- Widget -->
						<?php } ?>
					</aside><!-- aside -->
				</div><!-- Column -->
			</div><!-- Row -->

			<!-- Divider -->
			<hr class="md">

			<!-- Related Classes -->
			<div class="row rltd-items">
				<div class="col-md-12">
					<!-- Related Classes -->
					<?php if(!empty($related_events)) { ?>
					<h4><?php echo lang('e_d_related_events'); ?></h4>
					<div class="owl-carousel show-nav-hover dots-dark nav-square dots-square navigation-color"
					data-animatein="zoomIn"
					data-animateout="slideOutDown"
					data-items="3"
					data-margin="30"
					data-loop="true"
					data-merge="true"
					data-nav="true"
					data-dots="false"
					data-stagepadding=""
					data-mobile="1"
					data-tablet="2"
					data-desktopsmall="3"
					data-desktop="3"
					data-autoplay="true"
					data-delay="3000"
					data-navigation="false">
						<?php foreach($related_events as $val) { if($val->id !== $event_detail->id) { ?>
						<div class="item">
							<!-- Related Wrapper -->
							<div class="related-wrap">
								<!-- Related Image Wrapper -->
								<div class="img-wrap">
									<a href="<?php echo site_url('events/detail/').str_replace(' ', '+', $val->title) ?>" title="<?php lang('action_view'); ?>" >
										<img alt="<?php echo $val->title ?>" class="img-responsive" src="<?php echo base_url().($val->images ? '/upload/events/images/'.image_to_thumb(json_decode($val->images)[0]) : 'themes/default/images/course/course-01.jpg') ?>" width="600" height="220">
									</a>
								</div>
								<!-- Related Content Wrapper -->
								<div class="related-content">
									<a href="<?php echo site_url('events/detail/').str_replace(' ', '+', $val->title) ?>" title="<?php lang('action_view'); ?>" ><i class="fa fa-location-arrow"></i></a>
									<span><?php echo $val->category_name ?></span>
									<h5 class="title"><?php echo $val->title ?></h5>
								</div><!-- Related Content Wrapper -->
							</div><!-- Related Wrapper -->
						</div><!-- Item -->
						<?php } }  ?>
					</div><!-- Related Post -->
					<?php } // end if ?>
				</div>
			</div>
		</div><!-- Container -->
	</div><!-- Page Default -->
</div><!-- Page Main -->
