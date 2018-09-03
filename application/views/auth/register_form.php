<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<!-- Section -->
<div class="page-default bg-grey">
    <div class="container">
      <div class="col-md-8 col-centered">
        <div class="signin-form">
        <?php echo form_open_multipart('auth/register', array('role'=>'form', 'class'=>'', 'id'=>'form_login')); ?>
        <div class="row">
          <div class="form-group <?php echo form_error('email') ? ' has-error' : ''; ?>">
            <label for="email">Register</label>
                  <?php echo form_input(array('name'=>'email', 'value'=>set_value('email', (isset($user['email']) ? $user['email'] : '')), 'class'=>'form-control', 'type'=>'email', 'placeholder'=>lang('users_email'))); ?>
          </div>
        </div>
        <div class="row">
          <div class="form-group <?php echo form_error('username') ? ' has-error' : ''; ?>">
                  <?php echo form_input(array('name'=>'username', 'value'=>set_value('username', (isset($user['username']) ? $user['username'] : '')), 'class'=>'form-control','placeholder'=>lang('users_username'))); ?>
          </div>
        </div>
        <div class="row">
          <div class="form-group <?php echo form_error('password') ? ' has-error' : ''; ?>">
              <?php echo form_password(array('name'=>'password', 'value'=>set_value('password', (isset($user['password']) ? $user['password'] : '')), 'class'=>'form-control', 'placeholder'=>lang('users_password'))); ?>
          </div>
        </div>
        <div class="row">
          <div class="form-group <?php echo form_error('password_confirm') ? ' has-error' : ''; ?>">
                <?php echo form_password(array('name'=>'password_confirm', 'value'=>'', 'class'=>'form-control', 'autocomplete'=>'off','placeholder'=>lang('users_password_confirm'))); ?>
          </div>
        </div>
        <div class="row">
            <div class="col-sm-6 col-centered text-centered">
                <button type="submit" name="submit_form" id="submit_form" class="btn"><?php echo lang('users_register'); ?></button>
            </div>
        </div>
        <?php echo form_close(); ?>
      </div>
    </div>
    </div>
</div>
