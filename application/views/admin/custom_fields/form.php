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
                <?php if(!empty($id)) { echo '<a role="button" onclick="ajaxDelete('.$id.', ``, `'.lang('menu_custom_field').'`)" class="btn btn-default btn-circle waves-effect waves-circle waves-float pull-right"><i class="material-icons">delete_forever</i></a>'; } ?>
            </div>
            <div class="body">
                <?php echo form_open_multipart(site_url($this->uri->segment(1).'/'.$this->uri->segment(2).'/'.'save'), array('class' => 'form-horizontal', 'id' => 'form-create', 'role'=>"form")); ?>

                    <?php if(! empty($id)) { ?> <!-- in case of update customfields -->
                        <input type="hidden" name="id" value="<?php echo $id; ?>">
                    <?php } ?>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="row clearfix">
                                <div class="col-md-4 form-control-label">
                                    <?php echo lang('customfields_label', 'c_label', array('class'=>'')); ?>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <?php echo form_input($label);?>
                                        </div>
                                    </div>
                                </div>
                            </div>        
                        </div>
                        <div class="col-md-6">
                            <div class="row clearfix">
                                <div class="col-md-4 form-control-label">
                                    <?php echo lang('customfields_name', 'name', array('class'=>'name')); ?>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <?php echo form_input($name);?>
                                        </div>
                                    </div>
                                </div>
                            </div>        
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="row clearfix">
                                <div class="col-md-4 form-control-label">
                                    <?php echo lang('customfields_input_type', 'input_type', array('class'=>'input_type')); ?>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <?php echo form_dropdown($input_type);?>
                                        </div>
                                    </div>
                                </div>
                            </div>        
                        </div>
                        <div class="col-md-6">
                            <div class="row clearfix">
                                <div class="col-md-4 form-control-label">
                                    <?php echo lang('customfields_validation', 'validation', array('class'=>'validation')); ?>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <?php echo form_dropdown($validation);?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row clearfix options-toggle">
                        <div class="col-md-2 form-control-label">
                            <?php echo lang('customfields_options', 'options', array('class' => '')); ?>
                        </div>
                        <div class="col-md-10">
                            <div class="row">
                                <div class="col-md-3">
                                    <button type="button" class="btn btn-default waves-effect add_field_button"><i class="material-icons">add</i></button>
                                </div>
                                <br><br>
                            </div>
                            <div class="row">
                                <div class="col-md-12 options"> 
                                <?php if(!isset($_POST['options'])) { ?>
                                    <div class="row">
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <?php echo lang('customfields_options_value', 'options_value', array('class'=>'options_value')); ?>
                                                <div class="form-line">
                                                    <input type="text" class="form-control" name="options_value[]" value=""/>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <?php echo lang('customfields_options_label', 'options_label', array('class'=>'options_label')); ?>
                                                <div class="form-line">
                                                    <input type="text" class="form-control" name="options_label[]" value=""/>
                                                </div>
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-default waves-effect waves-float remove_field"><i class="material-icons">remove</i></button>                                           
                                    </div>
                                <?php } else { 
                                            foreach($_POST['options'] as $key => $val) { ?>
                                    <div class="row">
                                         <div class="col-md-5">
                                            <div class="form-group">
                                                <?php echo lang('customfields_options_value', 'options_value', array('class'=>'options_value')); ?>
                                                <div class="form-line">
                                                    <input type="text" class="form-control" name="options_value[]" value="<?php echo $key ?>" placeholder="value"/>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <?php echo lang('customfields_options_label', 'options_label', array('class'=>'options_label')); ?>
                                                <div class="form-line">
                                                    <input type="text" class="form-control" name="options_label[]" value="<?php echo $val ?>" placeholder="label"/>
                                                </div>
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-default waves-effect waves-float remove_field"><i class="material-icons">remove</i></button>                                           
                                    </div>
                                            <?php } ?><!--  end foreach -->
                                <?php } ?> <!-- end if -->
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row clearfix">
                        <div class="col-md-2 form-control-label">
                            
                        </div>
                        <div class="col-md-2">
                            <input type="checkbox" id="is_numeric" name="is_numeric" value="1" class="filled-in" <?php echo isset($_POST['is_numeric']) ? ($_POST['is_numeric'] ? 'checked' : '') :'' ; ?>>
                            <?php echo lang('customfields_is_numeric', 'is_numeric', array('class'=>'is_numeric')); ?>
                        </div>
                        <div class="col-md-2">
                            <input type="checkbox" id="show_editor" name="show_editor" value="1" class="filled-in" <<?php echo isset($_POST['show_editor']) ? ($_POST['show_editor'] ? 'checked' : '') :'' ; ?>>
                            <?php echo lang('customfields_show_editor', 'show_editor', array('class'=>'show_editor')); ?>
                        </div>        
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="row clearfix">
                                <div class="col-md-4 form-control-label">
                                    <?php echo lang('customfields_value', 'value', array('class'=>'value')); ?>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <?php echo form_input($value);?>
                                        </div>
                                    </div>
                                </div>
                            </div>        
                        </div>
                        <div class="col-md-6">
                            <div class="row clearfix">
                                <div class="col-md-4 form-control-label">
                                    <?php echo lang('customfields_help_text', 'help_text', array('class'=>'help_text')); ?>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <?php echo form_input($help_text);?>
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
    var options_count = '<?php echo isset($_POST['options']) ? count($_POST['options']) : '1'; ?>';
</script>