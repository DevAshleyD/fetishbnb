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
							<?php echo lang('batches_category'); ?>
							<?php if(!empty($category)) { ?><a href="<?php echo site_url('courses') ?>" title="<?php echo lang('action_undo'); ?>"><i class="fa fa-undo"></i></a><?php } ?><br>
							<small><?php echo !empty($category) ? $category : ''; ?></small>
						</h5>
						<div class="u-vmenu">
							<?php echo build_filter_levels(0); ?>
						</div>
					</div><!-- Widget -->
					
					<!-- Tutors Widget -->
					<?php if(!empty($tutors)) { ?>
					<div class="widget">
						<h5 class="widget-title"><?php echo lang('c_l_browse_by_tutors') ?><span></span></h5>
						<ul class="thumbnail-widget thumb-circle">
							<?php foreach($tutors as $val) { ?>
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

					<!-- Popular Courses -->
					<?php if(!empty($p_courses)) { ?>
					<div class="widget">
						<h5 class="widget-title"><?php echo lang('c_l_popular_courses') ?><span></span></h5>
						<ul class="thumbnail-widget">
							<?php foreach($p_courses as $val) { ?>
							<li>
								<div class="thumb-wrap">
									<img width="66" height="66" alt="<?php echo $val->title ?>" class="img-responsive" src="<?php echo base_url().($val->images ? '/upload/courses/images/'.image_to_thumb(json_decode($val->images)[0]) : 'themes/default/images/default/course-thumb-01.jpg') ?>">
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
				<div class="row course-container">
					<?php if(! empty($courses)) { foreach($courses as $key => $val) { ?>
					<!-- Column -->
					<div class="col-md-6">
						<!-- Course Wrapper -->
						<div class="course-wrapper">
							<!-- Course Banner Image -->
							<a href="<?php echo site_url('courses/detail/').str_replace(' ', '+', $val->title) ?>" title="<?php lang('action_view'); ?>" >
								<div class="course-banner-wrap">
									<img alt="Course" class="img-responsive" src="<?php echo base_url().($val->images ? '/upload/courses/images/'.image_to_thumb(json_decode($val->images)[0]) : 'themes/default/images/course/course-01.jpg') ?>" width="600" height="220">
									<?php if($val->total_recurring) { ?>
                            		<span class="cat bg-green"><?php echo $val->total_recurring.' '.lang('c_l_repetitive_batch'); ?></span>
									<?php } elseif($val->starting_price) { ?>
									<span class="cat bg-blue"><?php echo lang('c_l_starts_from'); ?> : <?php echo $val->starting_price.' '.$this->settings->default_currency; ?></span>
									<?php } else { ?>
									<span class="cat bg-yellow"><?php echo lang('action_coming_soon'); ?></span>
									<?php } ?>
								</div><!-- Course Banner Image -->
							</a>
							<!-- Course Detail -->
							<div class="course-detail-wrap">
								<!-- Course Teacher Detail -->
								<div class="teacher-wrap">
									<?php if(empty($val->tutor)) { ?>
									<img class="img-responsive" src="<?php echo base_url().'themes/default/images/teacher/thumb-teacher-01.jpg' ?>" width="100" height="100">
									<h5><small><?php echo lang('action_coming_soon'); ?></small></h5>
									<?php } else { ?>
									<a href="<?php echo site_url('tutors/').$val->tutor->username ?>">
										<img alt="<?php echo $val->tutor->first_name.' '.$val->tutor->last_name ?>" class="img-responsive" src="<?php echo base_url().($val->tutor->image ? '/upload/users/images/'.image_to_thumb($val->tutor->image) : 'themes/default/images/teacher/thumb-teacher-01.jpg') ?>" width="100" height="100">
										<small><?php echo lang('users_role_tutor') ?></small>
										<h5><span><?php echo $val->tutor->first_name.' '.$val->tutor->last_name ?></span></h5>
									</a>
									<?php } ?>
									<small><a href="<?php echo site_url('courses/detail/').str_replace(' ', '+', $val->title) ?>" title="<?php lang('action_view'); ?>" ><?php echo $val->total_tutors > 0 ? $val->total_tutors.' '.lang('action_more') : '' ?></a></small>
								</div><!-- Course Teacher Detail -->
								
								<!-- Course Content -->
								<div class="course-content">
									<h4><a href="<?php echo site_url('courses/detail/').str_replace(' ', '+', $val->title) ?>" title="<?php lang('action_view'); ?>" ><?php echo $val->title ?></a></h4>
									<?php if(!$val->total_batches) { ?>
									<a disabled class="btn disabled"><?php echo lang('action_coming_soon') ?></a>
									<?php } else { ?>
									<a href="<?php echo site_url('bbooking/').str_replace(' ', '+', $val->title) ?>" class="btn"><?php echo lang('action_apply_now') ?></a>
									<p><?php echo lang('menu_batches') ?> <?php echo $val->total_batches ? '+'.$val->total_batches : $val->total_batches; ?></p>
									<?php } ?>
								</div><!-- Course Content -->
							</div><!-- Course Detail -->
						</div><!-- Course Wrapper -->
					</div><!-- Column -->		
					<?php } } else { ?>
					<div class="col-md-12 text-center">
						<h3><?php echo lang('c_l_no_courses') ?></h3>
						<p><?php echo sprintf(lang('c_l_for_category'), $category); ?></p>
					</div>
					<?php } ?>
				</div><!-- Row -->
				
			</div><!-- Column -->
		</div><!-- Row -->	
	</div><!-- Container -->
</div>