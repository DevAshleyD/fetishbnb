<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>


<div class="page-default bg-grey team-single">
		<!-- Container -->
	<div class="container">
		<div class="row">
			<!-- Sidebar -->
			<div class="col-md-3">
				<!-- aside -->
				<aside class="sidebar">
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
				</aside><!-- aside -->	
			</div><!-- Column -->
			
			<!-- Page Content -->
			<div class="col-md-9">
				<div class="row team-list">
					<!-- Member Image Column -->
					<div class="col-md-4">
						<img width="400" height="500" alt="<?php echo $tutor->first_name.' '.$tutor->last_name ?>" class="img-responsive" src="<?php echo base_url().($tutor->image ? '/upload/users/images/'.$tutor->image : 'themes/default/images/teacher/teacher-single-01.jpg') ?>">
					</div><!-- Coloumn -->
					<!-- Coloumn -->
					<div class="col-md-8">
						<div class="member-detail-wrap">
							<h4 class="member-name"><?php echo $tutor->first_name.' '.$tutor->last_name; ?></h4>
							<span class="position">
								<?php echo $tutor->profession ?>
								<?php echo ' ('.lang('users_experience_1') ?> : <?php echo $tutor->experience > 1 ? $tutor->experience.' '.lang('users_experience_years') : $tutor->experience.' '.lang('users_experience_year') ?><?php echo ')'; ?>
							</span>
							<div class="share">
								<h5><?php echo lang('users_email') ?> : </h5>
								<ul class="social-icons color round">
									<li class="mail"><a title="<?php echo lang('users_email') ?>" href="mailto:<?php echo $tutor->email ?>" target="_blank"><?php echo $tutor->email ?></a></li>
								</ul><!-- Blog Social Share -->
							</div>
							<div class="share">
								<h5><?php echo lang('users_about') ?> : </h5>
								<blockquote>
								  <p><?php echo $tutor->about ?></p>
								</blockquote>
							</div>
						</div><!-- Member Detail Wrapper -->
					</div><!-- Member Detail Column -->
				</div><!-- Row -->
			</div><!-- Column -->
		</div><!-- Row -->		

		<!-- Tutors Courses -->
		<div class="row rltd-items">
			<div class="col-md-12">
				<!-- Tutors Courses -->
				<?php if(!empty($tutor_Courses)) { ?>
				<h4><?php echo lang('c_l_tutor_Courses'); ?></h4>
				<div class="owl-carousel show-nav-hover dots-dark nav-square dots-square navigation-color" 
				data-animatein="bounceIn" 
				data-animateout="" 
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
					<?php foreach($tutor_Courses as $val) {  ?>
					<div class="item">
						<!-- Related Wrapper -->
						<div class="related-wrap">
							<!-- Related Image Wrapper -->
							<div class="img-wrap">
								<a href="<?php echo site_url('courses/detail/').str_replace(' ', '+', $val->title) ?>" title="<?php lang('action_view'); ?>" >
									<img alt="<?php echo $val->title ?>" class="img-responsive" src="<?php echo base_url().($val->images ? '/upload/Courses/images/'.image_to_thumb(json_decode($val->images)[0]) : 'themes/default/images/course/course-01.jpg') ?>" width="600" height="220">
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
					<?php }  ?> 
				</div><!-- Related Post -->
				<?php } // end if ?>	
			</div>
		</div>
		<br>
		<br>
		<!-- Tutors Events -->
		<div class="row rltd-items">
			<div class="col-md-12">
				<!-- Tutors Events -->
				<?php if(!empty($tutor_events)) { ?>
				<h4><?php echo lang('e_l_tutor_events'); ?></h4>
				<div class="owl-carousel show-nav-hover dots-dark nav-square dots-square navigation-color" 
				data-animatein="bounceIn" 
				data-animateout="" 
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
					<?php foreach($tutor_events as $val) {  ?>
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
					<?php }  ?> 
				</div><!-- Related Post -->
				<?php } // end if ?>	
			</div>
		</div>

	</div><!-- Container -->
</div><!-- Page Default -->