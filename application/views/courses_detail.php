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
							<?php if($course_detail->images) { ?> 
							<div class="owl-carousel dots-inline" 
							<?php echo count(json_decode($course_detail->images))>1 ? 'data-loop="true"' : ''; ?> 
							data-animatein="pulse" 
							data-animateout="" 
							data-items="1" 
							data-margin="" 
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
								<?php foreach(json_decode($course_detail->images) as $key => $val) { ?>
								<div class="item <?php echo $key == 0 ? 'active' : ''; ?>">
									<img class="img-responsive" src="<?php echo base_url('/upload/courses/images/').$val; ?>" alt="<?php echo $course_detail->title; ?>">
								</div>
								<?php } ?>
							</div>
							<?php } else { ?>
							<img class="img-responsive carousel-inner" src="<?php echo base_url('themes/default/images/course/course-01.jpg'); ?>" alt="<?php echo $course_detail->title; ?>">
							<?php } ?>
						</div>
						
						<!-- Course Detail -->
						<div class="col-sm-12">
							<div class="course-detail">
								<!-- Course Content -->
								<div class="course-meta margin-top-30">
									<a href="<?php echo site_url('courses/').str_replace(' ', '+', $course_detail->category_name); ?>"><span class="cat"><?php echo $course_detail->category_name; ?></span></a>
									<div class="pull-right">
										<?php if($course_detail->total_recurring) { ?>
			                            <span class="cat bg-green"><?php echo $course_detail->total_recurring.' '.lang('c_l_repetitive_batch'); ?></span>
			                            <?php } elseif($course_detail->starting_price) { ?>
			                            <span class="cat bg-blue"><?php echo lang('c_l_starts_from'); ?> : <?php echo $course_detail->starting_price.' '.$this->settings->default_currency; ?></span>
			                            <?php } else { ?>
			                            <span class="cat bg-yellow"><?php echo lang('action_coming_soon'); ?></span>
			                            <?php } ?>
									</div>
									<h4><?php echo $course_detail->title; ?></h4>
									<?php if($course_detail->total_batches) { ?>
									<ul class="course-meta-icons">
										<li>
											<i class="fa fa-money"></i><span><?php echo lang('c_l_price'); ?></span>
											<h5><?php echo lang('c_l_starts_from').' '.$course_detail->starting_price.' '.$this->settings->default_currency; ?></h5>
										</li>
										<li>
											<i class="fa fa-institution"></i><span><?php echo lang('menu_batches') ?></span>
											<h5><?php echo $course_detail->total_batches ?></h5>
										</li>
										<li><i class="fa fa-users"></i><span><?php echo lang('batches_tutors') ?></span><h5><?php echo $course_detail->total_tutors ?></h5></li>
										<li><i class="fa fa-user-plus"></i><span><?php echo lang('c_l_customers') ?></span><h5><?php echo $course_detail->total_b_bookings ?></h5></li>
										<li>
											<i class="fa fa-clock-o"></i><span><?php echo date('Y-m-d') < date('Y-m-d', strtotime(str_replace('-', '/', $course_detail->starting_date))) ? lang('c_l_starting_from') : lang('c_l_started_on') ?></span>
											<h5><?php echo date('F j, Y', strtotime($course_detail->starting_date)); ?></h5>
										</li>
										
										<li><?php echo strtotime(date('Y-m-d')) < strtotime($course_detail->starting_date) ? 
										'<i class="fa fa-circle-o"></i><span>'.lang('common_status').'</span><h5>'.get_date_difference(date('Y-m-d'), $course_detail->starting_date).' '.lang('batches_time_remain').'</h5>' 
										: '<i class="fa fa-circle status-green"></i><span>'.lang('common_status').'</span><h5>'.lang('action_running').'</h5>' ?>
										</li>
									</ul>
									<a href="<?php echo site_url('bbooking/').str_replace(' ', '+', $course_detail->title) ?>" class="btn"><?php echo lang('action_apply_now') ?></a>
									<?php } else { ?>
									<a disabled class="btn disabled"><?php echo lang('action_coming_soon') ?></a>
									<?php } ?>
								</div>
							</div><!-- Course Detail -->
						</div><!-- Column -->	
					</div><!-- Course Wrapper -->
			
					<div class="row course-full-detail">
						<div class="col-sm-12">
							<h4><?php echo lang('c_l_course_description'); ?></h4>
							<?php echo $course_detail->description ?>
						</div><!-- Column -->
					</div><!-- row -->
				</div><!-- Column -->
				<!-- Sidebar -->
				<div class="col-md-3">
					<!-- aside -->
					<aside class="sidebar">
						<!-- Widget -->
						<?php if(!empty($course_tutors)) { ?>
						<div class="widget">
							<h5 class="widget-title"><?php echo lang('batches_tutors') ?><span></span></h5>
							<ul class="thumbnail-widget thumb-circle">
								<?php foreach($course_tutors as $val) { ?>
								<li>
									<div class="thumb-wrap">
										<a href="<?php echo site_url('tutors/').$val->username ?>">
											<img width="66" height="66" alt="<?php echo $val->first_name.' '.$val->last_name ?>" class="img-responsive" src="<?php echo base_url().($val->image ? '/upload/users/images/'.image_to_thumb($val->image) : 'themes/default/images/teacher/thumb-teacher-01.jpg') ?>">
										</a>
									</div>
									<div class="thumb-content">
										<a href="<?php echo site_url('tutors/').$val->username ?>"><?php echo $val->first_name.' '.$val->last_name ?></a>
										<span><?php echo lang('c_l_total_batches').' : '.$val->total_batches ?></span>
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
			<hr class="lg hidden-767">

			<!-- Related Courses -->
			<div class="row rltd-items">
				<div class="col-md-12">
					<!-- Related Courses -->
					<?php if(!empty($related_courses)) { ?>
					<h4><?php echo lang('c_d_related_courses'); ?></h4>
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
					data-navigation="false"
				>
						<?php foreach($related_courses as $val) { if($val->id !== $course_detail->id) { ?>
						<div class="item">
							<!-- Related Wrapper -->
							<div class="related-wrap">
								<!-- Related Image Wrapper -->
								<div class="img-wrap">
									<a href="<?php echo site_url('courses/detail/').str_replace(' ', '+', $val->title) ?>" title="<?php lang('action_view'); ?>" >
										<img alt="<?php echo $val->title ?>" class="img-responsive" src="<?php echo base_url().($val->images ? '/upload/courses/images/'.image_to_thumb(json_decode($val->images)[0]) : 'themes/default/images/course/course-01.jpg') ?>" width="600" height="220">
									</a>
								</div>
								<!-- Related Content Wrapper -->
								<div class="related-content">
									<a href="<?php echo site_url('courses/detail/').str_replace(' ', '+', $val->title) ?>" title="<?php lang('action_view'); ?>" ><i class="fa fa-location-arrow"></i></a>
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

			<!-- Disqus Divider -->
			<?php if($this->settings->disqus_short_name) { ?>
			<hr class="md">
			<!-- Discussion -->
			<div class="row">
				<div class="col-md-12">
					<!-- Post Comments -->
					 <div id="post-comment"  class="post-block post-comments clearfix">
						<h4><?php echo lang('common_discussion') ?></h4>
						<div id="disqus_thread"></div>
					</div><!-- Post Comments -->
				</div>
			</div>
			<?php } ?>

		</div><!-- Container -->
	</div><!-- Page Default -->
</div><!-- Page Main -->