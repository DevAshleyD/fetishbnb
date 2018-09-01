<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="page-default bg-grey typo-dark dynamic-page">
	<!-- Container -->
	<div class="container">
		<?php if (!empty ($row)) { ?>
		<ul class="row">
			<!-- Blog Column -->
			<li class="col-xs-12 blog-large-wrap">
				<div class="blog-wrap">
					<div class="blog-img-wrap">
						<?php if (!empty ($row->image))  {?>
						<img src="<?php echo base_url('upload/pages/images/'.image_to_large($row->image)) ?>" class="img-responsive" />
						<?php } else { ?>
						<img width="1920" height="700" src="<?php echo base_url('themes/default/') ?>/images/blog/blog-large-01.jpg" class="img-responsive">
						<?php } ?>
						<h6 class="post-type">&nbsp;<i class="fa fa-image"></i>&nbsp;</h6>
					</div>
						
					<!-- Blog Detail Wrapper -->
					<div class="blog-details">
						<h4><?php echo $row->title ?></h4>
						<ul class="blog-meta">
							<li><i class="fa fa-user"></i> John Mathew</li>
							<li><i class="fa fa-calendar-o"></i> 02 . 12 . 2016</li>
							<li><i class="fa fa-comments"></i> 22</li>
							<li><i class="fa fa-heart"></i> 0</li>
							<li><i class="fa fa-folder"></i> Chemical, Maths, Conference</li>
						</ul><!-- Blog Meta Details -->
						
						<!-- Blog Description -->
						<?php echo  $row->content; ?>
					</div><!-- Blog Detail Wrapper -->
				</div><!-- Blog Wrapper -->
			</li><!-- Column -->
		</ul><!-- Row -->
		<?php } else { ?>
		<div class="row">
			<div class="col-sm-12">
				<ul class="box-404 parent-has-overlay">
					<!-- Page Template Content -->
					<li class="template-content text-center">
						<h1>Oops,</h1>
						<p><?php echo lang('action_404') ?></p>
						<a href="<?php echo site_url() ?>" class="btn"><?php echo lang('action_home'); ?></a>
					</li><!-- Page Template Content -->
				</ul>
			</div>
		</div>
		<?php } ?>
	</div><!-- Container -->
</div><!-- Page Default -->