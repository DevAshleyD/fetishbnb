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
                            <?php echo form_open('auth/forgot_password', array('class'=>'')); ?>
                            	<!-- Field 1 -->
                                <div class="input-email text-left form-group <?php echo form_error('identity') ? ' has-error' : ''; ?>">
                                    <?php echo lang('users_email', 'identity', array('class' => '')); ?>
                                    <?php echo form_input(array('name'=>'identity', 'id'=>'identity', 'value'=>set_value('identity', (isset($user['identity']) ? $user['identity'] : '')), 'class'=>'form-control input-email', 'placeholder'=>lang('users_email'), 'type'=>'email', 'required'=>'required')); ?>
                                </div>
                                <!-- Button -->
                                <?php echo form_button(array('type' => 'submit', 'class' => 'btn', 'content' => lang('action_reset').' '.lang('users_password'))); ?>
                            <?php echo form_close(); ?> <!-- Form Ends -->
                        </div>  
                    </li><!-- Page Template Content -->
                </ul>
            </div><!-- Column -->
        </div><!-- Row -->
    </div><!-- Container -->    
</div><!-- Page Default -->