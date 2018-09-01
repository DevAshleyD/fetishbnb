<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="body">
                <!-- Settings Tabs -->
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                    <?php $t_count = 0; foreach($settings as $k => $v) { $t_count++; ?>
                        <li class="<?php echo $k == 'institute' ? 'active' : ''; ?>">
                            <a href="#tab_<?php echo $t_count; ?>" data-toggle="tab" class="text-capitalize">
                                <?php echo str_replace("_", " ", $k); ?>
                            </a>
                        </li>
                    <?php } ?>
                    </ul>
                    <div class="tab-content">
                    <?php $tab_count = 0; foreach($settings as $key => $val) { $tab_count++; ?>    
                        <div class="tab-pane <?php echo $key == 'institute' ? 'active' : ''; ?>" id="tab_<?php echo $tab_count; ?>">
                        <?php echo form_open_multipart('', array('role'=>'form')); ?>
                        <?php foreach ($val as $setting) : ?>

                        <?php // prepare field settings
                        $field_data = array();

                        if ($setting['is_numeric'])
                        {
                            $field_data['type'] = "number";
                            $field_data['step'] = "any";
                        }

                        if ($setting['options'])
                        {
                            $field_options = array();
                            if ($setting['input_type'] == "dropdown")
                            {
                                $field_options[''] = lang('admin input select');
                            }
                            $lines = explode("\n", $setting['options']);
                            foreach ($lines as $line)
                            {
                                $option = explode("|", $line);
                                $field_options[$option[0]] = $option[1];
                            }
                        }

                        switch ($setting['input_size'])
                        {
                            case "small":
                                $col_size = "col-sm-3";
                                break;
                            case "medium":
                                $col_size = "col-sm-6";
                                break;
                            case "large":
                                $col_size = "col-sm-9";
                                break;
                            default:
                                $col_size = "col-sm-6";
                        }

                        if ($setting['input_type'] == 'textarea')
                        {
                            $col_size = "col-sm-12";
                        }
                        ?>

                                <?php // no translations
                                $field_data['name']  = $setting['name'];
                                $field_data['id']    = $setting['name'];
                                $field_data['class'] = "form-control" . (($setting['show_editor']) ? " tinymce" : "") . (($setting['input_type'] == 'dropdown') ? " show-tick" : "");
                                $field_data['value'] = $setting['value'];
                                ?>

                                <div class="row clearfix">
                                    <div class="form-group <?php echo $col_size; ?><?php echo form_error($setting['name']) ? ' has-error' : ''; ?>">
                                        <?php echo form_label($setting['label'], $setting['name'], array('class'=>'control-label')); ?>
                                        <?php if (strpos($setting['validation'], 'required') !== FALSE) : ?>
                                            <span class="required">*</span>
                                        <?php endif; ?>

                                        <?php // render the correct input method
                                        if ($setting['input_type'] == 'input')
                                        {
                                            echo '<div class="form-line">';
                                            echo form_input($field_data);
                                            echo '</div>';
                                        }
                                        elseif ($setting['input_type'] == 'file')
                                        { ?>
                                            <div class="picture-container">
                                                <div class="picture picture-square <?php echo $key == 'institute' ? 'width-72' : 'width-50' ?>">
                                                    <img id="i_<?php echo $field_data['id'] ?>" src="<?php echo base_url('upload/'.$key.'/'.$field_data['value']); ?>" class="img-responsive">
                                                    <?php echo form_upload($field_data);?>
                                                </div>
                                            </div>
                                        <?php }
                                        elseif ($setting['input_type'] == 'email')
                                        {
                                            echo '<div class="form-line">';
                                            echo form_input_custom($field_data, 'email');
                                            echo '</div>';
                                        }
                                        elseif ($setting['input_type'] == 'textarea')
                                        {
                                            echo '<div class="form-line">';
                                            echo form_textarea($field_data);
                                            echo '</div>';
                                        }
                                        elseif ($setting['input_type'] == 'radio')
                                        {
                                            echo '<div class="form-line">';
                                            echo "<br />";
                                            foreach ($field_options as $value=>$label)
                                            {
                                                echo form_radio(array('name'=>$field_data['name'], 'id'=>$field_data['id'] . "-" . $value, 'value'=>$value, 'checked'=>(($value == $field_data['value']) ? 'checked' : FALSE)));
                                                echo $label;
                                            }
                                            echo '</div>';
                                        }
                                        elseif ($setting['input_type'] == 'dropdown')
                                        {   
                                            echo '<div class="form-line">';
                                            echo form_dropdown($setting['name'], $field_options, $field_data['value'], 'id="' . $field_data['id'] . '" class="' . $field_data['class'] . '"');
                                            echo '</div>';
                                        }
                                        elseif ($setting['input_type'] == 'timezones')
                                        {
                                            echo '<div class="form-line">';
                                            echo "<br />";
                                            echo timezone_menu($field_data['value'], 'show-tick');
                                            echo '</div>';
                                        }
                                        elseif ($setting['input_type'] == 'currencies')
                                        {
                                            echo '<div class="form-line">';
                                            echo "<br />";
                                            echo currency_menu($field_data['value'], 'show-tick', $field_data['name']);
                                            echo '</div>';
                                        }
                                        elseif ($setting['input_type'] == 'email_templates')
                                        {
                                            echo '<div class="form-line">';
                                            echo "<br />";
                                            echo email_template_menu($field_data['value'], 'show-tick', $field_data['name']);
                                            echo '</div>';
                                        }
                                        elseif ($setting['input_type'] == 'languages')
                                        {
                                            echo '<div class="form-line">';
                                            echo "<br />";
                                            echo language_menu($field_data['value'], 'show-tick', $field_data['name']);
                                            echo '</div>';
                                        }
                                        elseif ($setting['input_type'] == 'taxes')
                                        {   
                                            echo '<div class="form-line">';
                                            echo "<br />";
                                            echo tax_menu($field_data['value'], 'show-tick', $field_data['name']);
                                            echo '</div>';
                                        }
                                        ?>

                                        <?php if ($setting['help_text']) : ?>
                                            <span class="help-block"><?php echo $setting['help_text']; ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>

                            
                        <?php endforeach; ?>

                        <div class="row">
                            <div class="col-md-3">
                                <div class="btn-group">
                                    <button type="submit" name="submit" class="btn bg-<?php echo $this->settings->admin_theme ?> btn-lg waves-effect text-capitalize"><?php echo lang('action_save').' ('.$key.')'; ?></button>
                                    <a class="btn btn-lg waves-effect" href="<?php echo site_url($this->uri->segment(1).'/'); ?>"><?php echo lang('action_cancel'); ?></a>
                                </div>
                            </div>
                        </div>
                        <?php echo form_close(); ?>
                        
                        </div>
                        <!-- /.tab-pane -->
                    <?php } ?> <!-- End top most foreach loop -->
                    </div>
                <!-- /.tab-content -->
                </div>
                <!-- nav-tabs-custom -->
            </div>
        </div>
    </div>
</div>