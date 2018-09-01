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
                <?php if(!empty($id)) { echo '<a role="button" onclick="ajaxDelete('.$id.', ``, `'.lang('menu_event').'`)" class="btn btn-default btn-circle waves-effect waves-circle waves-float pull-right"><i class="material-icons">delete_forever</i></a>'; } ?>
            </div>
            <div class="body">
                <?php echo form_open_multipart(site_url($this->uri->segment(1).'/'.$this->uri->segment(2).'/'.'save'), array('class' => 'form-horizontal', 'id' => 'form-create', 'role'=>"form")); ?>

                    <?php if(! empty($id)) { ?> <!-- in case of update events -->
                        <input type="hidden" name="id" value="<?php echo $id; ?>">
                    <?php } ?>

                    <input hidden="" name="start_time" id="start_time" value="">
                    <input hidden="" name="end_time" id="end_time" value="">
                    
                    <div class="row clearfix">
                        <div class="col-md-2 form-control-label">
                            <?php echo lang('common_title', 'title', array('class'=>'title')); ?>
                        </div>
                        <div class="col-md-10">
                            <div class="form-group">
                                <div class="form-line">
                                    <?php echo form_input($title);?>
                                </div>
                                <div class="help-info"><?php echo lang('events_title_eg') ?></div>
                            </div>
                        </div>
                    </div>    
                    <div class="row clearfix">
                        <div class="col-md-2 form-control-label">
                            <?php echo lang('events_type', 'event_types', array('class'=>'event_types')); ?>
                        </div>
                        <div class="col-md-10">
                            <div class="form-group">
                                <div class="form-line">
                                    <?php echo form_dropdown($event_types); ?>
                                </div>
                                <div class="help-info"><?php echo lang('events_type_eg') ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-md-2 form-control-label">
                            <?php echo lang('events_tutors', 'tutors', array('class'=>'tutors')); ?>
                        </div>
                        <div class="col-md-10">
                            <div class="form-group">
                                <div class="form-line">
                                    <?php echo form_multiselect($tutors); ?>
                                </div>
                                <div class="help-info"><?php echo lang('events_tutors_eg') ?></div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row clearfix">
                        <div class="col-md-6">
                            <div class="row clearfix">
                                <div class="col-md-4 form-control-label">
                                <?php echo lang('events_fees', 'fees', array('class'=>'fees')); ?>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <?php echo form_input($fees);?>
                                        </div>
                                        <div class="help-info"><?php echo lang('events_fees_eg').' ('.$this->settings->default_currency.')'; ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row clearfix">
                                <div class="col-md-4 form-control-label">
                                    <?php echo lang('events_capacity', 'capacity', array('class'=>'capacity')); ?>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <?php echo form_input($capacity);?>
                                        </div>
                                        <div class="help-info"><?php echo lang('events_capacity_eg') ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row clearfix">
                        <div class="col-md-6">
                            <div class="row clearfix">
                                <div class="col-md-4 form-control-label">
                                    <?php echo lang('events_start_end_date', 'start_end_date', array('class'=>'start_end_date')); ?>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <div class="form-line input-group date-range-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                            <?php echo form_input($start_end_date);?>
                                        </div>
                                    </div>
                                </div>        
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row clearfix">
                                <div class="col-md-4 form-control-label">
                                    <?php echo lang('menu_event', 'events_type', array('class'=>'events_type')); ?>
                                </div>
                                <div class="col-md-8">
                                    <input type="checkbox" id="recurring" name="recurring" value="1" class="filled-in" <?php echo isset($_POST['recurring']) ? ($_POST['recurring'] ? 'checked' : '') :'' ; ?>>
                                    <?php echo lang('events_recurring', 'recurring', array('class'=>'recurring')); ?>
                                    <div class="help-block"><?php echo lang('events_recurring_eg') ?>&nbsp;&nbsp;<i class="material-icons pointer-elem help-recursive">help</i></div>
                                </div>        
                            </div>
                        </div>
                    </div>

                    <div class="row clearfix" id="weekdays_select">
                        <div class="col-md-2 form-control-label">
                            <?php echo lang('events_weekdays', 'weekdays', array('class' => 'weekdays')); ?>
                        </div>
                        <div class="col-md-10">
                            <?php foreach($weekdays as $key => $val) { ?>
                                <input type="checkbox" id="w_<?php echo $key; ?>" name="weekdays[]" value="<?php echo $key; ?>" class="filled-in" <?php echo isset($_POST['weekdays']) ? (in_array($key, $_POST['weekdays']) ? 'checked' : '') :'' ; ?>>
                                <label for="w_<?php echo $key; ?>" class="recurring"><?php echo $val; ?></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <?php } ?>
                            <div class="help-block"><?php echo lang('events_weekdays_eg') ?></div>
                        </div>        
                    </div>

                    <div class="row clearfix" id="recurring_type_select">
                        <div class="col-md-2 form-control-label">
                            <?php echo lang('events_recurring_types', 'recurring_type', array('class' => 'recurring_type')); ?>
                        </div>
                        <div class="col-md-10">
                            <?php foreach($recurring_types as $key => $val) { ?>
                                <input type="radio" id="r_<?php echo $key; ?>" name="recurring_type" value="<?php echo $key; ?>" class="filled-in" <?php echo isset($_POST['recurring_type']) ? ($key == $_POST['recurring_type'] ? 'checked' : '') :'' ; ?>>
                                <label for="r_<?php echo $key; ?>" class="recurring_type"><?php echo $val; ?></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <?php } ?>
                            <div class="help-block"><?php echo lang('events_recurring_types_ie') ?></div>
                        </div>        
                    </div>
                    

                    <div class="row clearfix">
                        <div class="col-md-6">
                            <div class="col-md-offset-2 input-group"> 
                                <?php echo lang('events_start_time', 'start_time_1', array('class' => 'col-md-2 start_time_1')); ?>
                                <div class="col-sm-3 input-join"><?php echo form_dropdown($start_time_1);?></div>
                                <div class="col-sm-3 input-join"><?php echo form_dropdown($start_time_2);?></div>
                                <div class="col-sm-3 input-join"><?php echo form_dropdown($start_time_3);?></div>
                            </div>  
                        </div>
                        <div class="col-md-6">
                            <div class="col-md-offset-1 input-group"> 
                                <?php echo lang('events_end_time', 'end_time_1', array('class' => 'col-md-1 start_time_1')); ?>
                                <div class="col-sm-3 input-join"><?php echo form_dropdown($end_time_1);?></div>
                                <div class="col-sm-3 input-join"><?php echo form_dropdown($end_time_2);?></div>
                                <div class="col-sm-3 input-join"><?php echo form_dropdown($end_time_3);?></div>
                            </div>
                        </div>
                    </div>

                    <div class="row clearfix">
                        <div class="col-md-2 form-control-label">
                            <?php echo lang('common_images', 'images'); ?>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <div class="picture-container">
                                    <div class="picture-select">
                                        <img src="<?php echo base_url('themes/admin/img/choose_files.png'); ?>" class="img-responsive">
                                        <?php echo form_input($images);?>
                                    </div>
                                </div>
                                <?php if(! empty($c_images)) { ?>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="gallery">
                                                <?php foreach($c_images as $val) { ?> 
                                                    <img src="<?php echo base_url('upload/events/images/'.image_to_thumb($val)); ?>" class="col-sm-2 img-responsive thumbnail">            
                                                <?php } ?>
                                            </div>                                
                                        </div>
                                    </div>
                                <?php } else { ?> 
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="gallery">
                                            </div>                                
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>

                    <div class="row clearfix">
                        <div class="col-md-2 form-control-label">
                            <?php echo lang('common_description', 'description', array('class'=>'description')); ?>
                        </div>
                        <div class="col-md-10">
                            <div class="form-group">
                                <div class="form-line">
                                    <?php echo form_textarea($description); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-md-2 form-control-label">
                            <?php echo lang('common_meta_title', 'meta_title', array('class'=>'meta_title')); ?>
                        </div>
                        <div class="col-md-10">
                            <div class="form-group">
                                <div class="form-line">
                                    <?php echo form_input($meta_title);?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-md-2 form-control-label">
                            <?php echo lang('common_meta_tags', 'meta_tags', array('class'=>'meta_tags')); ?>
                        </div>
                        <div class="col-md-10">
                            <div class="form-group">
                                <div class="form-line">
                                    <?php echo form_input($meta_tags);?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-md-2 form-control-label">
                            <?php echo lang('common_meta_description', 'meta_description', array('class'=>'meta_description')); ?>
                        </div>
                        <div class="col-md-10">
                            <div class="form-group">
                                <div class="form-line">
                                    <?php echo form_textarea($meta_description);?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-4 form-control-label">
                                    <?php echo lang('common_status', 'status', array('class'=>'status')); ?>
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
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-4 form-control-label">
                                    <?php echo lang('common_featured', 'featured', array('class'=>'featured')); ?>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <?php echo form_dropdown($featured);?>
                                        </div>
                                    </div>
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
<script type="text/javascript">
    var tutors      = <?php echo isset($_POST['tutors']) ? json_encode($_POST['tutors']) : json_encode(array()) ?>;
</script>