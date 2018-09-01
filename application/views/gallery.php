<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="page-default bg-grey typo-dark">
	<!-- Container -->
	<div class="container">
		<div class="row">
			<div class="col-md-12">

				<?php if(!empty($gallaries)) { ?>
				<!-- Gallery Block -->
                <div class="isotope-grid grid-three-column" data-gutter="20" data-columns="3">
                    <div class="grid-sizer"></div>
                    <!-- Portfolio Item -->
                    <?php foreach($gallaries as $val) { ?>
                    <div class="item all">
                        <!-- Image Wrapper -->
                        <div class="image-wrapper">
                            <!-- IMAGE -->
                            <img src="<?php echo base_url('upload/gallaries/images/').image_to_thumb($val->image) ?>" />
                            <!-- Gallery Btns Wrapper -->
                            <div class="gallery-detail btns-wrapper">
                                <a href="<?php echo base_url('upload/gallaries/images/').$val->image ?>" data-rel="prettyPhoto[portfolio]" class="btn uni-full-screen"></a>
                            </div><!-- Gallery Btns Wrapper -->
                        </div><!-- Image Wrapper -->
                    </div><!-- Portfolio Item -->
                    <?php } ?>
                    
                </div><!-- Gallery Block -->
				<?php } else { ?>
				<h3><?php echo lang('error_no_results'); ?></h3>
				<?php } ?>

			</div><!-- Column -->
		</div><!-- Row -->
	</div><!-- Container -->
</div><!-- Page Default -->