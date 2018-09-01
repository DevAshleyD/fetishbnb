<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="page-default bg-grey typo-dark">
	<!-- Container -->
	<div class="container">
		<ul class="row">
			<!-- Blog Column -->
			<?php if(!empty($blogs)) { foreach($blogs as $val) { ?>
			<li class="col-xs-12 blog-large-wrap">
				<div class="blog-wrap">
					<div class="blog-img-wrap">
						<img src="<?php echo base_url('upload/blogs/images/').$val->image ?>" class="img-responsive" alt="Blog">
						<h6 class="post-type">&nbsp;<i class="fa fa-image"></i>&nbsp;</h6>
					</div>
					<!-- Blog Detail Wrapper -->
					<div class="blog-details">
						<h4><a href="<?php echo site_url('blogs/').$val->slug ?>"><?php echo $val->title ?></a></h4>
						<ul class="blog-meta">
							<li class="text-capitalize">
								<img width="36" src="<?php echo $val->user_image ? base_url('upload/users/images/').image_to_thumb($val->user_image) : base_url('themes/default/images/avatar.jpg'); ?>" class="img-circle" />
								
								&nbsp;&nbsp;<?php echo $val->first_name.' '.$val->last_name; ?>
							</li>
							<li><i class="fa fa-calendar-o"></i> <?php echo date("g:i A F j, Y", strtotime($val->date_updated)); ?></li>
						</ul><!-- Blog Meta Details -->
						
						<!-- Blog Description -->
						<a class="btn" href="<?php echo site_url('blogs/').$val->slug ?>"><?php echo lang('blogs_read') ?></a>
					</div><!-- Blog Detail Wrapper -->
				</div><!-- Blog Wrapper -->
				<!-- Divider -->
				<hr class="lg">
			</li><!-- Column -->
			<?php } } else { ?>
			<li><h3><?php echo lang('error_no_results'); ?></h3></li>
			<?php } ?>
		</ul><!-- Row -->
	</div><!-- Container -->
</div><!-- Page Default -->