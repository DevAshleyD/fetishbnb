<?php defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div class="row clearfix index-page">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <!-- Page Heading -->
                <h2 class="text-uppercase p-l-3-em"><?php echo lang('action_view'); ?></h2>
                
                <!-- Back Button -->
                <a href="<?php echo site_url($this->uri->segment(1).'/'.$this->uri->segment(2)) ?>" class="btn btn-default btn-circle waves-effect waves-circle waves-float pull-left"><i class="material-icons">arrow_back</i></a>

                <!-- Delete Button -->
                <?php  echo '<a role="button" onclick="ajaxDelete('.$id.', `'.mb_substr($title, 0, 20, 'utf-8').'`, `'.lang('menu_event').'`)" class="btn btn-default btn-circle waves-effect waves-circle waves-float pull-right"><i class="material-icons">delete_forever</i></a>';  ?>
            </div>
            <div class="body table-responsive">
                <table class="table table-striped">
                    <tbody>
                        <tr>
                            <th><?php echo lang('common_title'); ?></th>
                            <td class="text-capitalize"><?php echo $title; ?></td>
                        </tr>
                        <tr>
                            <th><?php echo lang('events_type'); ?></th>
                            <td class="text-capitalize"><?php echo $event_types_title; ?></td>
                        </tr>
                        <tr>
                            <th><?php echo lang('common_description'); ?></th>
                            <td class="text-capitalize"><?php echo $description ? $description : 'N/A'; ?></td>
                        </tr>
                        <tr>
                            <th><?php echo lang('events_fees'); ?></th>
                            <td class="text-capitalize"><?php echo $fees; ?></td>
                        </tr>
                        <tr>
                            <th><?php echo lang('events_capacity'); ?></th>
                            <td class="text-capitalize"><?php echo $capacity; ?></td>
                        </tr>
                        <tr>
                            <th><?php echo lang('events_start_date'); ?></th>
                            <td class="text-capitalize"><?php echo $start_date; ?></td>
                        </tr>
                        <tr>
                            <th><?php echo lang('events_end_date'); ?></th>
                            <td class="text-capitalize"><?php echo $end_date; ?></td>
                        </tr>
                        <tr>
                            <th><?php echo lang('events_start_time'); ?></th>
                            <td class="text-capitalize"><?php echo $start_time; ?></td>
                        </tr>
                        <tr>
                            <th><?php echo lang('events_end_time'); ?></th>
                            <td class="text-capitalize"><?php echo $end_time; ?></td>
                        </tr>
                        <tr>
                            <th><?php echo lang('events_recurring'); ?></th>
                            <td>
                                <?php echo $recurring ? lang('action_yes') : lang('action_no'); ?>
                            </td>
                        </tr>
                        <tr>
                            <th><?php echo lang('events_weekdays'); ?></th>
                            <td class="text-capitalize">
                                <?php foreach($weekdays as $key => $val) { ?>
                                <input type="checkbox" id="w_<?php echo $key; ?>" name="weekdays[]" value="<?php echo $key; ?>" class="filled-in" <?php echo isset($_POST['weekdays']) ? (in_array($key, $_POST['weekdays']) ? 'checked' : '') :'' ; ?> disabled>
                                <label for="w_<?php echo $key; ?>" class="recurring"><?php echo $val; ?></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <?php } ?>
                            </td>
                        </tr>
                        <tr>
                            <th><?php echo lang('events_recurring_types'); ?></th>
                            <td>
                                <?php foreach($recurring_types as $key => $val) { ?>
                                    <input type="radio" id="r_<?php echo $key; ?>" name="recurring_type" value="<?php echo $key; ?>" class="filled-in" <?php echo isset($_POST['recurring_type']) ? ($key == $_POST['recurring_type'] ? 'checked' : '') :'' ; ?> disabled>
                                    <label for="r_<?php echo $key; ?>" class="recurring_type"><?php echo $val; ?></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <?php } ?>
                            </td>
                        </tr>
                        <tr>
                            <th><?php echo lang('events_tutors'); ?></th>
                            <td class="text-capitalize">
                                <?php foreach($tutors as $val) { 
                                    echo '<span class="label label-info">'.$val->first_name.' '.$val->last_name.'</span> ';
                                } ?>
                            </td>
                        </tr>
                        <tr>
                            <th><?php echo lang('common_images'); ?></th>
                            <td>
                            <?php if(! empty($images)) { 
                                    foreach(json_decode($images) as $image) {
                            ?>
                                <img src="<?php echo base_url('upload/events/images/'.image_to_thumb($image)); ?>" class="img-responsive col-sm-2 thumbnail">
                            <?php } } else { ?>
                                N/A
                            <?php } ?>
                            </td>
                        </tr>
                        <tr>
                            <th><?php echo lang('common_meta_title'); ?></th>
                            <td class="text-capitalize"><?php echo $meta_title ? $meta_title : 'N/A'; ?></td>
                        </tr>
                        <tr>
                            <th><?php echo lang('common_meta_tags'); ?></th>
                            <td class="text-capitalize"><?php echo $meta_tags ? $meta_tags : 'N/A'; ?></td>
                        </tr>
                        <tr>
                            <th><?php echo lang('common_meta_description'); ?></th>
                            <td class="text-capitalize"><?php echo $meta_description ? $meta_description : 'N/A'; ?></td>
                        </tr>
                        <tr>
                            <th><?php echo lang('common_added'); ?></th>
                            <td><?php echo date("F j, Y g:i A ", strtotime($date_added)) ?></td>
                        </tr>
                        <tr>
                            <th><?php echo lang('common_updated'); ?></th>
                            <td><?php echo date("F j, Y g:i A ", strtotime($date_updated)) ?></td>
                        </tr>
                        <tr>
                            <th><?php echo lang('common_featured'); ?></th>
                            <td><?php echo ($featured) ? '<span class="label label-success">'.lang('common_featured_enabled').'</span>' : '<span class="label label-default">'.lang('common_featured_disabled').'</span>'; ?></td>
                        </tr>
                        <tr>
                            <th><?php echo lang('common_status'); ?></th>
                            <td><?php echo ($status) ? '<span class="label label-success">'.lang('common_status_active').'</span>' : '<span class="label label-default">'.lang('common_status_inactive').'</span>'; ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>