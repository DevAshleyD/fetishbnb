<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<!-- Section -->
<div class="page-default">
    <!-- Container -->
    <div class="container">
        <!-- Row -->
        <div class="row">
            <div class="col-md-offset-4 col-md-4 parent-has-overlay">
                <ul class="template-box box-login text-center">
                    <!-- Page Template Logo -->
                    <li class="logo-wrap">
                        <a href="<?php echo site_url() ?>" class="logo">
                            <img class="img-responsive" src="<?php echo base_url('upload/institute/logo.png') ?>">
                        </a>
                        <p class="slogan"><?php echo lang('users_forgot'); ?></p>
                    </li><!-- Page Template Logo -->
                    <!-- Page Template Content -->
                    <li class="template-content">
                        <div class="contact-form">
                            <!-- Form Begins -->
                            <?php echo form_open('auth/reset_password/' . $code, array('class'=>'')); ?>
                            <?php echo form_input($user_id);?>
							<?php echo form_hidden($csrf); ?>

                        	<!-- Field 3 -->
			                <div class="input-email form-group text-left <?php echo form_error('new') ? ' has-error' : ''; ?>">
			                  <?php echo lang('reset_password_new_password_label', 'new', array('class' => '')); ?>
			                  <?php echo form_password(array('name'=>'new', 'id'=>'new', 'class'=>'form-control', 'autocomplete'=>'off')); ?>
			                </div>

			                <!-- Field 3 -->
			                <div class="input-email form-group text-left <?php echo form_error('new_confirm') ? ' has-error' : ''; ?>">
			                  <?php echo lang('reset_password_new_password_confirm_label', 'new_confirm', array('class' => '')); ?>
			                  <?php echo form_password(array('name'=>'new_confirm', 'id'=>'new_confirm', 'class'=>'form-control', 'autocomplete'=>'off')); ?>
			                </div>

                            <!-- Button -->
                            <?php echo form_button(array('type' => 'submit', 'class' => 'btn', 'content' => lang('reset_password_submit_btn'))); ?>
                            <?php echo form_close(); ?> <!-- Form Ends -->
                        </div>  
                    </li><!-- Page Template Content -->
                </ul>
            </div><!-- Column -->
        </div><!-- Row -->
    </div><!-- Container -->    
</div><!-- Page Default -->