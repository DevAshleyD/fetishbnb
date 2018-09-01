<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<!-- Section -->
<section class="bg-lgrey">  
    <div class="container">
        <div class="row">
            <div class="col-sm-offset-1 col-sm-4">
                <!-- Title -->
                <div class="title-container sm text-left">
                    <div class="title-wrap">
                        <h5 class="title"><?php echo lang('menu_contact') ?></h5>
                        <span class="separator line-separator"></span>
                    </div>
                </div><!-- Title -->
                <div class="contact-info">
                    <div class="info-icon bg-dark">
                        <i class="uni-map2"></i>
                    </div>
                    <h5 class="title"><?php echo lang('contacts_office') ?></h5>
                    <p><?php echo $this->settings->institute_name ?></p>
                    <p><?php echo $this->settings->institute_address ?></p>
                </div><!-- Contact Info -->
                
                <div class="contact-info margin-top-30">
                    <div class="info-icon bg-dark">
                        <i class="uni-mail"></i>
                    </div>
                    <h5 class="title"><?php echo lang('menu_contact') ?></h5>
                    <p><a href="mailto:<?php echo $this->settings->site_email ?>"><?php echo $this->settings->site_email ?></a></p>
                    <p><a href="tel:<?php echo $this->settings->institute_phone ?>"><?php echo $this->settings->institute_phone ?></a></p>
                </div><!-- Contact Info -->
                
            </div><!-- Column -->
            
            <div class="col-sm-6">
                <!-- Title -->
                <div class="title-container sm text-left">
                    <div class="title-wrap">
                        <h5 class="title"><?php echo lang('contacts_get_in_touch') ?></h5>
                        <span class="separator line-separator"></span>
                    </div>
                </div><!-- Title -->
                <div class="contact-info">
                    <div class="info-icon bg-dark">
                        <i class="uni-fountain-pen"></i>
                    </div>
                    <?php echo form_open('', array('role'=>'form', 'id'=>'form-create')); ?>
                    <div class="input-text form-group<?php echo form_error('name') ? ' has-error' : ''; ?>">
                        <?php echo form_label(lang('contacts_name'), 'name', array('class'=>'control-label')); ?>
                        <span class="required">*</span>
                        <?php echo form_input(array('name'=>'name', 'value'=>set_value('name'), 'class'=>'form-control input-name')); ?>
                    </div>
                    <div class="input-email form-group <?php echo form_error('email') ? ' has-error' : ''; ?>">
                        <?php echo form_label(lang('contacts_email'), 'email', array('class'=>'control-label')); ?>
                        <span class="required">*</span>
                        <?php echo form_input(array('name'=>'email', 'value'=>set_value('email'), 'class'=>'form-control input-email')); ?>
                    </div>
                    <div class="input-text form-group <?php echo form_error('title') ? ' has-error' : ''; ?>">
                        <?php echo form_label(lang('common_title'), 'title', array('class'=>'control-label')); ?>
                        <span class="required">*</span>
                        <?php echo form_input(array('name'=>'title', 'value'=>set_value('title'), 'class'=>'form-control input-text ')); ?>
                    </div>
                    <div class="textarea-message form-group <?php echo form_error('message') ? ' has-error' : ''; ?>">
                        <?php echo form_label(lang('contacts_message'), 'message', array('class'=>'control-label')); ?>
                        <span class="required">*</span>
                        <?php echo form_textarea(array('name'=>'message', 'value'=>set_value('message'), 'class'=>'form-control textarea-message')); ?>
                    </div>
                    <div class="form-group textarea-message <?php echo form_error('captcha') ? ' has-error' : ''; ?>">
                        <?php echo form_label(lang('contacts_captcha'), 'captcha', array('class'=>'control-label')); ?>
                        <br />
                        <?php echo $captcha_image; ?>
                        <?php echo form_input(array('name'=>'captcha', 'id'=>'captcha', 'value'=>"", 'class'=>'form-control textarea-message')); ?>
                    </div>
                    <button type="submit" name="submit" class="btn"><i class="fa fa-send"></i> <?php echo lang('contacts_send_message') ?></button>
                    <span id="submit-loader"></span>
                    <?php echo form_close(); ?>
                </div><!-- Contact Info -->
            </div><!-- Column -->
        </div><!-- Row -->
    </div><!-- Container -->    
</section><!-- Section -->  

<!-- Map -->
<?php if($this->settings->g_map_key) { ?>
<div class="full-screen map-canvas" 
    style="" 
    data-zoom="12" 
    data-lat="<?php echo $this->settings->g_map_lat ?>" 
    data-lng="<?php echo $this->settings->g_map_lng ?>" 
    data-title="<?php echo $this->settings->institute_name ?>" 
    data-type="roadmap" 
    data-hue="#2196F3" 
    data-content="<?php echo $this->settings->site_name ?>&lt;br&gt; Contact: <?php echo $this->settings->institute_phone ?>&lt;br&gt; <?php echo $this->settings->site_email ?>">
</div><!-- Map -->
<?php } ?>