<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <!-- Page Heading -->
                <h2 class="text-uppercase p-l-3-em"><?php echo lang('menu_manage_acl'); ?></h2>
                
                <!-- Back Button -->
                <a href="<?php echo site_url($this->uri->segment(1).'/') ?>" class="btn btn-default btn-circle waves-effect waves-circle waves-float pull-left"><i class="material-icons">arrow_back</i></a>
            </div>
            <div class="body">
                <?php echo form_open_multipart(site_url($this->uri->segment(1).'/'.$this->uri->segment(2).'/'.'save'), array('class' => 'form-horizontal', 'id' => 'form-create', 'role'=>"form")); ?>

                    <div class="row clearfix">
                        <div class="col-md-2 form-control-label">
                            <?php echo lang('manage_acl_select_group', 'role', array('class'=>'role')); ?>
                        </div>
                        <div class="col-md-10">
                            <div class="form-group">
                                <div class="form-line">
                                    <?php echo form_dropdown($groups); ?>
                                </div>
                            </div>
                        </div>
                    </div><br><br>

                    <div class="row clearfix">
                        <div class="col-md-2 form-control-label">
                            <?php echo lang('manage_acl_permissions', 'role', array('class'=>'role')); ?>
                        </div>
                        <div class="col-md-10">
                            <div class="row">
                                <div class="col-sm-12"> 
                                    <?php foreach($controllers as $val) { ?>
                                    <div class="row">
                                        
                                        <div class="col-sm-3">
                                            <label><?php echo lang("menu_".$val['name']) ?></label>
                                        </div>

                                        <div class="col-sm-2">
                                            <?php if($val['name'] !== 'settings') { ?>
                                            <input type="checkbox" id="<?php echo $val['name'] ?>_view" name="<?php echo $val['name'] ?>_view" value="1" class="filled-in" <?php echo $group_id == 1 ? 'checked disabled' : ''; ?> <?php echo $group_id == 3 ? 'disabled' : ''; ?> <?php echo !empty($p[$val['name'].'_view']) ? 'checked' : ''; ?>>
                                            <label for="<?php echo $val['name'] ?>_view"><?php echo lang('manage_acl_view'); ?></label>
                                            <?php } ?>
                                        </div>

                                        <div class="col-sm-2">
                                            <?php if($val['name'] !== 'settings') { ?>
                                            <input type="checkbox" id="<?php echo $val['name'] ?>_add" name="<?php echo $val['name'] ?>_add" value="1" class="filled-in" <?php echo $group_id == 1 ? 'checked disabled' : ''; ?> <?php echo $group_id == 3 ? 'disabled' : ''; ?> <?php echo !empty($p[$val['name'].'_add']) ? 'checked' : ''; ?>>
                                            <label for="<?php echo $val['name'] ?>_add"><?php echo lang('manage_acl_add'); ?></label>
                                            <?php } ?>
                                        </div>

                                        <div class="col-sm-2">
                                            <input type="checkbox" id="<?php echo $val['name'] ?>_edit" name="<?php echo $val['name'] ?>_edit" value="1" class="filled-in" <?php echo $group_id == 1 ? 'checked disabled' : ''; ?> <?php echo $group_id == 3 ? 'disabled' : ''; ?> <?php echo !empty($p[$val['name'].'_edit']) ? 'checked' : ''; ?>>
                                            <label for="<?php echo $val['name'] ?>_edit"><?php echo lang('manage_acl_edit'); ?></label>
                                        </div>

                                        <div class="col-sm-2">
                                            <?php if($val['name'] !== 'settings' && $val['name'] !== 'pages' && $val['name'] !== 'users' && $val['name'] !== 'bbookings' && $val['name'] !== 'ebookings') { ?>
                                            <input type="checkbox" id="<?php echo $val['name'] ?>_delete" name="<?php echo $val['name'] ?>_delete" value="1" class="filled-in" <?php echo $group_id == 1 ? 'checked disabled' : ''; ?> <?php echo $group_id == 3 ? 'disabled' : ''; ?> <?php echo !empty($p[$val['name'].'_delete']) ? 'checked' : ''; ?>>
                                            <label for="<?php echo $val['name'] ?>_delete"><?php echo lang('manage_acl_delete'); ?></label>
                                            <?php } ?>
                                        </div>

                                    </div>
                                    <?php } ?>
                                </div>
                            </div>        
                        </div>
                    </div>

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