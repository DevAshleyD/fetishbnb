<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="page-default bg-grey typo-dark dynamic-page">
	<!-- Container -->
	<div class="container">
		<div class="row">
			<!-- Blog Column -->
			<div class="col-xs-12 blog-large-wrap">
				<div class="blog-wrap">
					<div class="blog-img-wrap">
						<img src="<?php echo base_url('upload/blogs/images/').$blogs->image ?>" class="img-responsive" alt="Blog">
					</div>
					<!-- Blog Detail Wrapper -->
					<div class="blog-details">
						<h4><?php echo $blogs->title ?></h4>
						<ul class="blog-meta">
							<li class="text-capitalize">
								<img width="36" src="<?php echo $blogs->user_image ? base_url('upload/users/images/').image_to_thumb($blogs->user_image) : base_url('themes/default/images/avatar.jpg'); ?>" class="img-circle" />
								
								&nbsp;&nbsp;<?php echo $blogs->first_name.' '.$blogs->last_name; ?>
							</li>
							<li><i class="fa fa-calendar-o"></i> <?php echo date("g:i A F j, Y", strtotime($blogs->date_updated)); ?></li>
						</ul><!-- Blog Meta -->
						
						<!-- Blog Description -->
						<div class="row m-t-50">
							<div class="col-md-12">
								<?php echo $blogs->content; ?>	
							</div>
						</div>
					
					</div><!-- Blog Detail Wrapper -->
				</div><!-- Blog Wrapper -->
				
				<!-- Blog Share Post -->
				<div class="share">
					<h5 class="m-t-50">Share Post : </h5>
					<div class="fb-share-button" data-href="<?php echo $this->meta_url ?>" data-layout="button_count" data-size="large"></div>
				</div><!-- Blog Share Post -->
				
				<!-- Post Comments -->
				<?php if($this->settings->disqus_short_name) { ?>
				<div id="post-comment"  class="post-block post-comments clearfix">
					<h4><?php echo lang('common_discussion') ?></h4>
					<div id="disqus_thread"></div>
				</div><!-- Post Comments -->
				<?php } ?>
				
			</div><!-- Column -->
		</div><!-- Row -->
	</div><!-- Container -->
</div><!-- Page Default -->