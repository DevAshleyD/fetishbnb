<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<!-- Section -->
<div class="page-default bg-grey">
    <div class="container">
        <?php echo form_open_multipart('', array('role'=>'form', 'class'=>'form-horizontal', 'id'=>'form_login')); ?>
        <?php if ($this->session->userdata('logged_in')) : ?>
        <div class="card">
        <div class="header"><h2>My Profile</h2><a class="btn pull-right" href="<?php echo site_url('profile/billing');?>">Billing Details</a></div>
        <div class="body table-responsive">
        <div class="row">
            <div class="col-md-12 text-center image-card">
                <div class="picture-container">
                    <div class="picture">
                        <img src="<?php echo $user['image'] ? base_url('upload/users/images/').$user['image'] : base_url('themes/default/images/avatar.jpg'); ?>" class="picture-src img-responsive" id="wizardPicturePreview" title=""/>
                        <input type="file" id="wizard-picture" name="image">
                    </div>
                </div>
            </div>
        </div>
        <br><br>
      <?php endif;?>
        <div class="row">
            <div class="col-md-5">
                <?php if ($this->session->userdata('logged_in')) : ?>
                <div class="form-group <?php echo form_error('username') ? ' has-error' : ''; ?>">
                  <?php echo lang('users_username', 'username', array('class' => 'col-md-4 control-label')); ?>
                  <div class="col-md-8">
                      <?php echo form_input(array('name'=>'username', 'disabled' => TRUE,'value'=>set_value('username', (isset($user['username']) ? $user['username'] : '')), 'class'=>'form-control input-lg')); ?>
                  </div>
                </div>
              <?php endif;?>
                <div class="form-group <?php echo form_error('email') ? ' has-error' : ''; ?>">
                    <?php echo lang('users_email', 'email', array('class' => 'col-md-4 control-label')); ?>
                    <div class="col-md-8">
                        <?php echo form_input(array('name'=>'email', 'value'=>set_value('email', (isset($user['email']) ? $user['email'] : '')), 'class'=>'form-control input-lg', 'type'=>'email')); ?>
                    </div>
                </div>
                <?php if ($this->session->userdata('logged_in')) : ?>
                <div class="form-group <?php echo form_error('mobile') ? ' has-error' : ''; ?>">
                    <?php echo lang('users_mobile', 'mobile', array('class' => 'col-md-4 control-label')); ?>
                    <div class="col-sm-8">
                        <?php echo form_input(array('name'=>'mobile', 'value'=>set_value('mobile', (isset($user['mobile']) ? $user['mobile'] : '')), 'class'=>'form-control input-lg')); ?>
                    </div>
                </div>
                <div class="form-group <?php echo form_error('dob') ? ' has-error' : ''; ?>">
                    <?php echo lang('users_dob', 'dob', array('class' => 'col-md-4 control-label')); ?>
                    <div class="col-md-8">
                        <?php echo form_input(array('name'=>'dob', 'value'=>set_value('dob', (isset($user['dob']) ? $user['dob'] : '')), 'class'=>'form-control input-lg', 'id'=>'dob')); ?>
                    </div>
                </div>
                <?php endif;?>
                <?php if ($this->session->userdata('logged_in')) {
                        if(!$this->ion_auth->is_non_admin()) {
                    ?>
                    <div class="form-group <?php echo form_error('profession') ? ' has-error' : ''; ?>">
                        <?php echo lang('users_profession', 'profession', array('class' => 'col-md-4 control-label')); ?>
                        <div class="col-md-8">
                            <?php echo form_input(array('name'=>'profession', 'value'=>set_value('profession', (isset($user['profession']) ? $user['profession'] : '')), 'class'=>'form-control input-lg')); ?>
                        </div>
                    </div>
                <?php } } ?>
                <div class="form-group <?php echo form_error('password') ? ' has-error' : ''; ?>">
                    <?php echo lang('users_password', 'password', array('class' => 'col-md-4 control-label')); ?>
                    <div class="col-md-8">
                        <?php echo form_password(array('name'=>'password', 'value'=>set_value('password', (isset($user['password']) ? $user['password'] : '')), 'class'=>'form-control input-lg')); ?>
                    </div>
                </div>
                <?php if ($this->session->userdata('logged_in')) : ?>
                <div class="form-group <?php echo form_error('address') ? ' has-error' : ''; ?>">
                    <?php echo lang('users_address', 'address', array('class' => 'col-md-4 control-label')); ?>
                    <div class="col-md-8">
                        <?php echo form_textarea(array('name'=>'address', 'value'=>set_value('address', (isset($user['address']) ? $user['address'] : '')), 'class'=>'form-control input-lg', 'rows'=>3)); ?>
                    </div>
                </div>
              <?php endif;?>
            </div>

            <div class="col-md-5">
                <?php if ($this->session->userdata('logged_in')) : ?>
                <div class="form-group <?php echo form_error('language') ? ' has-error' : ''; ?>">
                    <?php echo lang('users_language', 'language', array('class' => 'col-md-4 control-label')); ?>
                    <div class="col-md-8">
                        <?php echo form_dropdown('language', $this->languages, (isset($user['language']) ? $user['language'] : $this->config->item('language')), 'id="language" class="form-control input-lg"'); ?>
                    </div>
                </div>
                <div class="form-group <?php echo form_error('gender') ? ' has-error' : ''; ?>">
                    <?php echo lang('users_gender', 'gender', array('class' => 'col-md-4 control-label')); ?>
                    <div class="col-md-8">
                        <?php echo form_dropdown('gender', array('male'=>lang('users_gender_male'), 'female'=>lang('users_gender_female'),'other'=>lang('users_gender_other')), (isset($user['gender']) ? $user['gender'] : 'male'), 'id="gender" class="form-control input-lg"'); ?>
                    </div>
                </div>
              <?php endif;?>
                <?php if ($this->session->userdata('logged_in')) {
                        if(!$this->ion_auth->is_non_admin()) {
                    ?>
                    <div class="form-group <?php echo form_error('experience') ? ' has-error' : ''; ?>">
                        <?php echo lang('users_experience', 'experience', array('class' => 'col-md-4 control-label')); ?>
                        <div class="col-md-8">
                            <?php echo form_input(array('name'=>'experience', 'value'=>set_value('experience', (isset($user['experience']) ? $user['experience'] : '')), 'class'=>'form-control input-lg', 'type'=>'number')); ?>
                        </div>
                    </div>
                <?php } } ?>
                <div class="form-group <?php echo form_error('password_confirm') ? ' has-error' : ''; ?>">
                    <?php echo lang('users_password_confirm', 'password_confirm', array('class' => 'col-md-4 control-label')); ?>
                    <div class="col-md-8">
                        <?php echo form_password(array('name'=>'password_confirm', 'value'=>'', 'class'=>'form-control input-lg', 'autocomplete'=>'off')); ?>
                        <?php if ($this->session->userdata('logged_in')) : ?>
                        <span class="help-block"><?php echo lang('users_password_help'); ?></span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
          </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-12 text-center">
                <?php if ($this->session->userdata('logged_in')) : ?>
                    <button type="submit" name="submit_form" id="submit_form" class="btn"><?php echo lang('action_save'); ?></button>
                <?php else : ?>
                    <button type="submit" name="submit_form" id="submit_form" class="btn"><?php echo lang('users_register'); ?></button>
                <?php endif; ?>
            </div>
        </div>
        <br>
      </div>
        <?php echo form_close(); ?>
    </div>
</div>
