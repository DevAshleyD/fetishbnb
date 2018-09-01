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
            <p class="slogan"><?php echo lang('users_login_account'); ?></p>
          </li><!-- Page Template Logo -->
          <!-- Page Template Content -->
          <li class="template-content">
            <div class="contact-form">
              <!-- Form Begins -->
              <?php echo form_open('auth/login', array('class'=>'', 'id'=>'form_1')); ?>
              <input type="hidden" name="fb_access_token">
                  <input type="hidden" name="fb_user_id">
                  <input type="hidden" name="fb_email">
                  <input type="hidden" name="fb_fullname">

                <!-- Field 2 -->
                <div class="input-text text-left form-group <?php echo form_error('identity') ? ' has-error' : ''; ?>">
                  <?php echo lang('login_identity_label', 'identity', array('class' => '')); ?>
                  <?php echo form_input(array('name'=>'identity', 'id'=>'identity', 'class'=>'form-control input-name', 'placeholder'=>lang('users_email_username'))); ?>
                </div>
                <!-- Field 3 -->
                <div class="input-email form-group text-left <?php echo form_error('password') ? ' has-error' : ''; ?>">
                  <a class="pull-right" href="<?php echo site_url('auth/forgot_password'); ?>"><?php echo lang('login_forgot_password'); ?></a>
                  <?php echo lang('users_password', 'password', array('class' => '')); ?>
                  <?php echo form_password(array('name'=>'password', 'id'=>'password', 'class'=>'form-control', 'placeholder'=>lang('users_password'), 'autocomplete'=>'off')); ?>
                </div>

                <span class="remember-box checkbox">
                  <label for="remember">
                    <input type="checkbox" id="remember" name="remember" value="1"><?php echo lang('login_remember_label') ?>
                  </label>
                </span>

                <!-- Button -->
                <?php echo form_button(array('type' => 'submit', 'class' => 'btn', 'id'=>'submit_form', 'content' => lang('users_login'))); ?>
                <a class="btn" href="<?php echo base_url('auth/register'); ?>"><?php echo lang('users_register'); ?></a>
              <?php echo form_close(); ?> <!-- Form Ends -->
            </div>  
          </li><!-- Page Template Content -->

          <li class="template-content">
            <hr class="md">
                <?php if($this->settings->fb_app_id && $this->settings->fb_app_secret) { ?>
                <div class="row">
                    <div class="col-md-12 text-center">
                        <a href="<?php echo site_url('auth/f_register') ?>" class="btn loginBtn loginBtn--facebook"><span><?php echo lang('users_login_continue_fb') ?></span></a>
                    <span id="fb_loader"></span>
                    </div>
                </div>
                <?php } ?>
                <br>
                <?php if($this->settings->g_client_id && $this->settings->g_client_secret) { ?>
                <div class="row">
                    <div class="col-md-12 text-center">
                        <a href="<?php echo site_url('auth/g_register') ?>" class="btn loginBtn loginBtn--google"><span><?php echo lang('users_login_continue_g') ?></span></a>
                    <span id="g_loader"></span>
                    </div>
                </div>
                <?php } ?>
          </li> 
        </ul>
      </div><!-- Column -->
    </div><!-- Row -->
  </div><!-- Container -->  
</div><!-- Page Default -->