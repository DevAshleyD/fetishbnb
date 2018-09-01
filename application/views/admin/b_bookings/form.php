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

                <!-- Invoice Button -->
                <?php if(!empty($id)) { echo '<a href="'.site_url($this->uri->segment(1).'/'.$this->uri->segment(2).'/view/'.$id.'/invoice').'" class="btn btn-default btn-circle waves-effect waves-circle waves-float pull-right" target="_blank"><i class="material-icons">description</i></a>';; } ?>
            </div>
            <div class="body">
                <?php echo form_open_multipart(site_url($this->uri->segment(1).'/'.$this->uri->segment(2).'/'.'save'), array('class' => 'form-horizontal', 'id' => 'form-create', 'role'=>"form")); ?>

                    <?php if(! empty($id)) { ?> <!-- in case of update b_bookings -->
                        <input type="hidden" name="id" value="<?php echo $id; ?>">
                    <?php } ?>
                    <input type="hidden" name="batches_id" id="batches_id">
                    <input type="hidden" name="booking_date" id="booking_date">
                    <input type="hidden" name="start_time" id="start_time">

                    <div class="row clearfix" id="customers_label">
                        <div class="col-md-2 form-control-label">
                            <?php echo lang('b_bookings_customer', 'customers', array('class'=>'customers')); ?>
                        </div>
                        <div class="col-md-10">
                            <div class="form-group">
                                <div class="form-line">
                                    <?php echo form_dropdown($customer); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix" id="category_label">
                        <div class="col-md-2 form-control-label">
                            <?php echo lang('b_bookings_course_category', 'category', array('class'=>'category')); ?>
                        </div>
                        <div class="col-md-10">
                            <div class="form-group">
                                <div class="form-line" id="show_sub_categories">
                                    <?php echo form_dropdown($category); ?>
                                    <?php if(!empty($id)) foreach($category_l as $c_l) { echo form_dropdown($c_l); } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix" id="courses_label">
                        <div class="col-md-2 form-control-label">
                            <?php echo lang('b_bookings_course', 'courses', array('class'=>'courses')); ?>
                        </div>
                        <div class="col-md-10">
                            <div class="form-group">
                                <div class="form-line">
                                    <?php echo form_dropdown($courses); ?>
                                </div>
                                <div class="help-info"><span id="courses_loader"></span></div>
                            </div>
                        </div>
                    </div>

                    <div class="row clearfix" id="batch_label">
                        <div class="col-md-2 form-control-label">
                            <?php echo lang('b_bookings_batch_selected', 'selected_batch', array('class'=>'selected_batch')); ?>
                        </div>
                        <div class="col-md-8">
                            <h4></h4>
                            <table class="table table-condensed"></table>
                        </div>
                        <div class="col-md-2">
                            <span id="batch_loader"></span>
                        </div>
                    </div>

                    <div class="row clearfix" id="batch_calendar">
                        <div class="col-md-2 form-control-label">
                            <?php echo lang('b_bookings_batch_select', 'select_batch', array('class'=>'select_batch')); ?>
                        </div>
                        <div class="col-md-10">
                            <!-- THE CALENDAR -->
                            <div id="calendar"></div>
                        </div>
                    </div>

                    <div class="row clearfix members-toggle">
                        <div class="col-md-2 form-control-label">
                            <?php echo lang('b_bookings_members', 'members', array('class' => 'col-sm-2 control-label')); ?>
                        </div>
                        <div class="col-sm-10">
                            <div class="row">
                                <div class="col-sm-3">
                                    <button type="button" class="btn btn-default waves-effect add_member_button"><i class="material-icons">add</i><?php echo lang('b_bookings_members_add'); ?></button>
                                </div>
                                <br><br>
                            </div>
                            <div class="row">
                                <div class="col-sm-12 members"> 
                                <?php if(empty($members)) { ?>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <?php echo lang('users_fullname', 'fullname', array('class'=>'fullname')); ?>
                                                <div class="form-line"><?php echo form_input($fullname); ?></div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <?php echo lang('users_email', 'email', array('class'=>'email')); ?>
                                                <div class="form-line"><?php echo form_input($email); ?></div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <?php echo lang('users_mobile', 'mobile', array('class'=>'mobile')); ?>
                                                <div class="form-line"><?php echo form_input($mobile); ?></div>
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-default btn-circle waves-effect waves-circle waves-float"><i class="material-icons">remove</i></button>                                           
                                    </div>
                                <?php } else { 
                                            foreach($members as $val) { ?>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <?php echo lang('users_fullname', 'fullname', array('fullname')); ?>
                                                <div class="form-line"><?php echo form_input($fullname, $val->fullname); ?></div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <?php echo lang('users_email', 'email', array('email')); ?>
                                                <div class="form-line"><?php echo form_input($email, $val->email); ?></div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <?php echo lang('users_mobile', 'mobile', array('mobile')); ?>
                                                <div class="form-line"><?php echo form_input($mobile, $val->mobile); ?></div>
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-default btn-circle waves-effect waves-circle waves-float"><i class="material-icons">remove</i></button>                                           
                                    </div>
                                            <?php } ?><!--  end foreach -->
                                <?php } ?> <!-- end if -->
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row clearfix">
                        <div class="col-md-6" id="fees_label">
                            <div class="row clearfix">
                                <div class="col-md-4 form-control-label">
                                <?php echo lang('b_bookings_fees', 'fees', array('class'=>'fees')); ?>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <?php echo form_input($fees);?>
                                        </div>
                                        <div class="help-info">
                                            <span id="net_fees_loader"></span>
                                            <p class="text-info text-uppercase"></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6" id="net_fees_label">
                            <div class="row clearfix">
                                <div class="col-md-4 form-control-label">
                                    <?php echo lang('b_bookings_net_fees', 'net_fees', array('class'=>'net_fees')); ?>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <?php echo form_input($net_fees);?>
                                        </div>
                                        <div class="help-info text-info text-uppercase"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row clearfix">
                        <div class="col-md-6">
                            <div class="row clearfix">
                                <div class="col-md-4 form-control-label">
                                <?php echo lang('b_bookings_payment_type', 'payment_type', array('class'=>'payment_type')); ?>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <?php echo form_dropdown($payment_type);?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row clearfix">
                                <div class="col-md-4 form-control-label">
                                    <?php echo lang('b_bookings_payment_status', 'payment_status', array('class'=>'payment_status')); ?>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <?php echo form_dropdown($payment_status);?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                   
                    <div class="row clearfix">
                        <div class="col-md-2 form-control-label">
                            <?php echo lang('common_status', 'status', array('class'=>'status')); ?>
                        </div>
                        <div class="col-md-10">
                            <div class="form-group">
                                <div class="form-line">
                                    <?php echo form_dropdown($status);?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php if(!empty($id)) { ?>                    
                    <div class="row clearfix">
                        <div class="col-md-2 form-control-label">
                            <?php echo lang('common_cancellation', 'cancellation', array('class'=>'cancellation')); ?>
                        </div>
                        <div class="col-md-10">
                            <div class="form-group">
                                <div class="form-line">
                                    <?php echo form_dropdown($cancellation);?>
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
<script type="text/javascript">
    var selectedBatch      = '<?php echo !empty($selected_batch) ? json_encode($selected_batch) : null; ?>';
</script>