<?php defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <!-- Page Heading -->
                <h2 class="text-uppercase p-l-3-em"><?php echo !empty($id) ? lang('action_edit') : lang('action_create'); ?></h2>
                
                <!-- Back Button -->
                <a href="<?php echo site_url($this->uri->segment(1).'/'.$this->uri->segment(2)) ?>" class="btn btn-default btn-circle waves-effect waves-circle waves-float pull-left"><i class="material-icons">arrow_back</i></a>

                <!-- Delete Button -->
                <?php if(!empty($id)) { echo '<a role="button" onclick="ajaxDelete('.$id.', ``, `'.lang('menu_user').'`)" class="btn btn-default btn-circle waves-effect waves-circle waves-float pull-right"><i class="material-icons">delete_forever</i></a>'; } ?>
            </div>
            <div class="body">
                <?php echo form_open_multipart(site_url($this->uri->segment(1).'/'.$this->uri->segment(2).'/'.'save'), array('class' => 'form-horizontal', 'id' => 'form-create', 'role'=>"form")); ?>

                    <?php if(! empty($id)) { ?> <!-- in case of update users -->
                        <input type="hidden" name="id" value="<?php echo $id; ?>">
                    <?php } ?>

                    <div class="row clearfix">
                        <div class="col-md-6">
                            <div class="row clearfix">
                                <div class="col-md-4 form-control-label">
                                    <?php echo lang('users_first_name', 'first_name', array('class'=>'first_name')); ?>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <?php echo form_input($first_name);?>
                                        </div>
                                    </div>
                                </div>
                            </div>        
                        </div>
                        <div class="col-md-6">
                            <div class="row clearfix">
                                <div class="col-md-4 form-control-label">
                                    <?php echo lang('users_last_name', 'last_name', array('class'=>'last_name')); ?>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <?php echo form_input($last_name);?>
                                        </div>
                                    </div>
                                </div>
                            </div>        
                        </div>
                    </div>
                    
                    <div class="row clearfix">
                        <div class="col-md-6">
                            <div class="row clearfix">
                                <div class="col-md-4 form-control-label">
                                    <?php echo lang('users_username', 'username', array('class'=>'username')); ?>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <?php echo form_input($username);?>
                                        </div>
                                    </div>
                                </div>
                            </div>        
                        </div>
                        <div class="col-md-6">
                            <div class="row clearfix">
                                <div class="col-md-4 form-control-label">
                                    <?php echo lang('users_email', 'email', array('class'=>'email')); ?>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <?php echo form_input($email);?>
                                        </div>
                                    </div>
                                </div>
                            </div>        
                        </div>
                    </div>
                    
                    <div class="row clearfix">
                        <div class="col-md-6">
                            <div class="row clearfix">
                                <div class="col-md-4 form-control-label">
                                    <?php echo lang('users_mobile', 'mobile', array('class'=>'mobile')); ?>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <?php echo form_input($mobile);?>
                                        </div>
                                    </div>
                                </div>
                            </div>        
                        </div>
                        <div class="col-md-6">
                            <div class="row clearfix">
                                <div class="col-md-4 form-control-label">
                                    <?php echo lang('users_profession', 'profession', array('class'=>'profession')); ?>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <?php echo form_input($profession);?>
                                        </div>
                                    </div>
                                </div>
                            </div>        
                        </div>
                    </div>
                    
                    <div class="row clearfix">
                        <div class="col-md-6">
                            <div class="row clearfix">
                                <div class="col-md-4 form-control-label">
                                    <?php echo lang('users_experience', 'experience', array('class'=>'experience')); ?>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <?php echo form_input($experience);?>
                                        </div>
                                    </div>
                                </div>
                            </div>        
                        </div>
                        <div class="col-md-6">
                            <div class="row clearfix">
                                <div class="col-md-4 form-control-label">
                                    <?php echo lang('users_dob', 'dob', array('class'=>'dob')); ?>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <?php echo form_input($dob);?>
                                        </div>
                                    </div>
                                </div>
                            </div>        
                        </div>
                    </div>
                    
                    <div class="row clearfix">
                        <div class="col-md-6">
                            <div class="row clearfix">
                                <div class="col-md-4 form-control-label">
                                    <?php echo lang('users_gender', 'gender', array('class'=>'gender')); ?>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <?php echo form_dropdown($gender);?>
                                        </div>
                                    </div>
                                </div>
                            </div>                                    
                        </div>
                        <div class="col-md-6">
                            <div class="row clearfix">
                                <div class="col-md-4 form-control-label">
                                    <?php echo lang('users_address', 'address', array('class'=>'address')); ?>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <?php echo form_textarea($address);?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row clearfix">
                        <div class="col-md-12">
                            <div class="row clearfix">
                                <div class="col-md-2 form-control-label">
                                    <?php echo lang('users_about', 'about', array('class'=>'about')); ?>
                                </div>
                                <div class="col-md-10">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <?php echo form_textarea($about);?>
                                        </div>
                                    </div>
                                </div>
                            </div>        
                        </div>
                    </div>
                    
                    <div class="row clearfix">
                        <div class="col-md-6">
                            <div class="row clearfix">
                                <div class="col-md-4 form-control-label">
                                    <?php echo lang('users_password', 'password', array('class'=>'password')); ?>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <?php echo form_password($password);?>
                                        </div>
                                    </div>
                                </div>
                            </div>        
                        </div>
                        <div class="col-md-6">
                            <div class="row clearfix">
                                <div class="col-md-4 form-control-label">
                                    <?php echo lang('users_password_confirm', 'password_confirm', array('class'=>'password_confirm')); ?>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <?php echo form_password($password_confirm);?>
                                        </div>
                                    </div>
                                </div>
                            </div> 
                        </div>
                    </div>
                    
                    <div class="row clearfix">
                        <div class="col-md-6">
                            <div class="row clearfix">
                                <div class="col-md-4 form-control-label">
                                    <?php echo lang('common_image', 'image'); ?>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <div class="picture-container">
                                            <div class="picture">
                                                <?php if(! empty($c_image)) { ?>
                                                    <img id="c_image" src="<?php echo base_url('upload/users/images/'.image_to_thumb($c_image)); ?>" class="img-responsive">
                                                <?php } else { ?>
                                                    <img id="c_image" src="<?php echo base_url('themes/admin/img/avatar2.png'); ?>" class="img-responsive">
                                                <?php } ?>
                                                <?php echo form_input($image);?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>       
                        </div>
                        <div class="col-md-6">
                            <div class="row clearfix">
                                <div class="col-md-4 form-control-label">
                                    <?php echo lang('users_language', 'language', array('class'=>'language')); ?>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <?php echo form_dropdown($language);?>
                                        </div>
                                    </div>
                                </div>
                            </div> 
                        </div>
                    </div>
                    
                    <?php if ($this->ion_auth->is_admin() && !empty($id)) { ?>
                    <div class="row clearfix">
                        <div class="col-md-6">
                            <div class="row clearfix">
                                <div class="col-md-4 form-control-label">
                                    <?php echo lang('edit_user_groups_heading', 'role', array('class'=>'role')); ?>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <?php echo form_dropdown($groups); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row clearfix">
                                <div class="col-md-4 form-control-label">
                                    <?php echo lang('common_status', 'active', array('class'=>'active')); ?>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <?php echo form_dropdown($status);?>
                                        </div>
                                    </div>
                                </div>
                            </div>         
                        </div>
                    </div>
                    <?php } ?>

                    <div class="row clearfix">
                        <div class="col-lg-offset-2 col-md-offset-2 col-sm-offset-4 col-xs-offset-5">
                            <div class="btn-group">
                                <button type="submit" class="btn bg-<?php echo $this->settings->admin_theme ?> btn-lg waves-effect"><?php echo lang('action_submit') ?></button>
                            </div>
                            <span id="submit_loader"></span>
                        </div>
                    </div>

                <?php echo form_close();?>
            </div>
        </div>
    </div>
</div>