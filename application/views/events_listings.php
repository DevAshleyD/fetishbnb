<?php defined('BASEPATH') OR exit('No direct script access allowed');  ?>

<div class="page-default bg-grey typo-dark">
	<!-- Container -->
	<div class="container">
		<div class="row">
			<!-- Sidebar -->
			<div class="col-md-3">
				<!-- aside -->
				<aside class="sidebar">
					<!-- Widget -->
					<div class="widget no-box">
						<div class="search">
							<div class="input-group">
								<input type="text" class="form-control search" name="search-categories" id="search-categories" placeholder="<?php echo lang('action_search') ?>" required>
								<span class="input-group-btn">
									<button class="btn" type="button"><i class="fa fa-search"></i></button>
								</span>
							</div>
						</div>
					</div><!-- Widget -->

					<!-- Categories Widget -->
					<div class="widget">
						<h5 class="widget-title">
							<?php echo lang('menu_event_types') ?>
							<?php if(!empty($category)) { ?><a href="<?php echo site_url('events') ?>" title="<?php echo lang('action_undo'); ?>"><i class="fa fa-undo"></i></a><?php } ?><br>
							<small><?php echo !empty($category) ? $category : ''; ?></small>
						</h5>
						<ul class="tag-widget">
							<?php echo build_event_types(); ?>
						</ul><!-- Tag Widget -->
					</div><!-- Widget -->
					
					<!-- Tutors Widget -->
					<?php if(!empty($tutors)) { ?>
					<div class="widget">
						<h5 class="widget-title"><?php echo lang('e_l_browse_by_tutors') ?><span></span></h5>
						<ul class="thumbnail-widget thumb-circle">
							<?php  foreach($tutors as $val) { ?>
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

					<!-- Popular Courses -->
					<?php if(!empty($p_events)) { ?>
					<div class="widget">
						<h5 class="widget-title"><?php echo lang('e_l_popular_event') ?><span></span></h5>
						<ul class="thumbnail-widget">
							<?php foreach($p_events as $val) { ?>
							<li>
								<div class="thumb-wrap">
									<img width="66" height="66" alt="<?php echo $val->title ?>" class="img-responsive" src="<?php echo base_url().($val->images ? '/upload/events/images/'.image_to_thumb(json_decode($val->images)[0]) : 'themes/default/images/default/course-thumb-01.jpg') ?>">
								</div>
								<div class="thumb-content"><a href="<?php echo site_url('/').$val->url.str_replace(' ', '+', $val->title) ?>"><?php echo mb_substr($val->title, 0, 30, 'utf-8') ?></a><span><?php echo lang('menu_bookings').': '; ?><?php echo $val->total_bookings ?></span></div>	
							</li>
							<?php } ?>
						</ul><!-- Thumbnail Widget -->
					</div><!-- Widget -->
					<?php } ?>

				</aside><!-- aside -->	
			</div><!-- Column -->
			
			<!-- Page Content -->
			<div class="col-md-9">
				<!-- Course Container -->
				<div class="row">
					<?php if(! empty($events)) {$row = 0; foreach($events as $key => $val) { ?>
					<!-- Column -->
					<div class="col-md-6">
						<!-- Event Wrapper -->
						<div class="event-wrap margin-bottom-30">
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
											<li><i class="fa fa-users"></i> <?php echo lang('e_l_total_tutors').' : '.$val->total_tutors ?></li>
											<li><i class="fa fa-calendar-o"></i> <?php echo lang('e_l_total_bookings').' : '.$val->total_e_bookings ?></li>
										</ul>		
									</div>
									<div class="col-sm-6">
										<ul class="events-meta">
											<li><i class="fa fa-th"></i> <?php echo lang('events_capacity').' : '.$val->capacity ?></li>
											<li><i class="fa fa-money"></i> 
												<?php echo lang('e_l_price').' : '; ?> 
												<?php echo $val->fees ? $val->fees.' '.$this->settings->default_currency : '<strong>'.lang('events_free').'</strong>'; ?></li>
										</ul>		
									</div>
									<div class="col-sm-6">
										<ul class="events-meta">
											<li>
												<i class="fa fa-calendar"></i><?php echo lang('e_bookings_duration').' : '.'<br>'; ?>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo date('M j, y', strtotime($val->start_date)).' - '.date('M j, y', strtotime($val->end_date)); ?>
											</li>
										</ul>		
									</div>
									<div class="col-sm-6">
										<ul class="events-meta">
											<li>
												<i class="fa fa-clock-o"></i><?php echo lang('e_bookings_timing').' : '.'<br>'; ?>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo date('g:i A', strtotime($val->start_time)).' - '.date('g:i A', strtotime($val->end_time)); ?>
											</li>		
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
				<?php } } else { ?>
					<div class="col-md-12 text-center">
						<h3><?php echo lang('e_l_no_events') ?></h3>
						<p><?php echo sprintf(lang('e_l_for_category'), $category); ?></p>
					</div>
				</div><!-- Row -->
				<?php } ?>
				
			</div><!-- Column -->
		</div><!-- Row -->	
	</div><!-- Container -->
</div>