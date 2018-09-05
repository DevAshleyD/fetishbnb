<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="page-default pad-tb-none">

	<!-- Team List Section -->
	<?php if(!empty($hosts)) { foreach($hosts as $key => $host) { ?>
	<div class="<?php echo $key%2 == 1 ? 'bg-grey' : ''; ?> team-list-section typo-dark">
		<!-- Container -->
		<div class="container">
			<div class="row team-list">
				<?php if($key%2 == 1) { ?>
				<!-- Coloumn -->
				<div class="col-md-8">
					<div class="member-detail-wrap">
						<h4 class="member-name"><a href="<?php echo site_url('hosts/').$host->username ?>"><?php echo $host->first_name.' '.$host->last_name; ?></a></h4>
						<span class="position"><?php echo $host->profession ?>
								<?php echo ' ('.lang('users_experience_1') ?> : <?php echo $host->experience > 1 ? $host->experience.' '.lang('users_experience_years') : $host->experience.' '.lang('users_experience_year') ?><?php echo ')'; ?></span>
						<p><?php echo $host->about ?></p>
						<!-- Count Container -->
						<div class="row count-container">
							<!-- Count -->
							<div class="col-sm-4">
								<div class="count-block">
									<h5><?php echo lang('e_l_total_events'); ?></h5>
									<h3 class="count-number" data-count="<?php echo $host->total_events ?>"><span class="counter"></span></h3>
								</div>
							</div><!-- Count -->
						</div><!-- Count Container -->
					</div><!-- Member Detail Wrapper -->
				</div><!-- Member Detail Column -->
				<!-- Member Image Column -->
				<div class="col-md-4 static">
					<div class="member-img-wrap">
						<a href="<?php echo site_url('hosts/').$host->username ?>">
							<img width="400" height="500" alt="<?php echo $host->first_name.' '.$host->last_name ?>" class="img-responsive" src="<?php echo base_url().($host->image ? '/upload/users/images/'.$host->image : 'themes/default/images/teacher/teacher-single-01.jpg') ?>">
						</a>
					</div><!-- Member Image Wrapper -->
				</div><!-- Coloumn -->
				<?php } else { ?>
				<!-- Member Image Column -->
				<div class="col-md-4 static">
					<div class="member-img-wrap">
						<a href="<?php echo site_url('hosts/').$host->username ?>">
							<img width="400" height="500" alt="<?php echo $host->first_name.' '.$host->last_name ?>" class="img-responsive" src="<?php echo base_url().($host->image ? '/upload/users/images/'.$host->image : 'themes/default/images/teacher/teacher-single-01.jpg') ?>">
						</a>
					</div><!-- Member Image Wrapper -->
				</div><!-- Coloumn -->
				<!-- Coloumn -->
				<div class="col-md-8">
					<div class="member-detail-wrap">
						<h4 class="member-name"><a href="<?php echo site_url('hosts/').$host->username ?>"><?php echo $host->first_name.' '.$host->last_name; ?></a></h4>
						<span class="position"><?php echo $host->profession ?>
								<?php echo ' ('.lang('users_experience_1') ?> : <?php echo $host->experience > 1 ? $host->experience.' '.lang('users_experience_years') : $host->experience.' '.lang('users_experience_year') ?><?php echo ')'; ?></span>
						<p><?php echo $host->about ?></p>
						<!-- Count Container -->
						<div class="row count-container">
							<!-- Count -->
							<div class="col-sm-4">
								<div class="count-block">
									<h5><?php echo lang('e_l_total_events'); ?></h5>
									<h3 class="count-number" data-count="<?php echo $host->total_events ?>"><span class="counter"></span></h3>
								</div>
							</div><!-- Count -->
						</div><!-- Count Container -->
					</div><!-- Member Detail Wrapper -->
				</div><!-- Member Detail Column -->
				<?php } ?>
			</div><!-- Row -->
		</div><!-- Container -->
	</div><!-- Team List Section -->
	<?php } } else { ?>
	<div class="col-md-12 text-center">
		<h3><?php echo lang('c_l_no_tutors') ?></h3>
	</div>
	<?php } ?>
</div><!-- Page Default -->
