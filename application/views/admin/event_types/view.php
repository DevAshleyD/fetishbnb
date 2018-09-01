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
                <?php  echo '<a role="button" onclick="ajaxDelete('.$event_types->id.', `'.mb_substr($event_types->title, 0, 20, 'utf-8').'`, `'.lang('menu_course_category').'`)" class="btn btn-default btn-circle waves-effect waves-circle waves-float pull-right"><i class="material-icons">delete_forever</i></a>';  ?>
            </div>
            <div class="body table-responsive">
                <table class="table table-striped">
                    <tbody>
                        <tr>
                            <th><?php echo lang('common_title'); ?></th>
                            <td class="text-capitalize"><?php echo $event_types->title; ?></td>
                        </tr>
                        <tr>
                            <th><?php echo lang('common_image'); ?></th>
                            <td>
                            <?php if(! empty($event_types->image)) { ?>
                                <img src="<?php echo base_url('upload/event_types/images/'.$event_types->image); ?>" class="img-responsive col-sm-2 img-rounded">
                            <?php } else { ?>
                                N/A
                            <?php } ?>
                            </td>
                        </tr>
                        <tr>
                            <th><?php echo lang('course_categories_icon'); ?></th>
                            <td>
                            <?php if(! empty($event_types->icon)) { ?>
                                <img src="<?php echo base_url('upload/event_types/icons/'.$event_types->icon); ?>" class="img-responsive col-sm-1 img-rounded">
                            <?php } else { ?>
                                N/A
                            <?php } ?>
                            </td>
                        </tr>
                        <tr>
                            <th><?php echo lang('common_added'); ?></th>
                            <td><?php echo date("F j, Y g:i A ", strtotime($event_types->date_added)) ?></td>
                        </tr>
                        <tr>
                            <th><?php echo lang('common_updated'); ?></th>
                            <td><?php echo date("F j, Y g:i A ", strtotime($event_types->date_updated)) ?></td>
                        </tr>
                        <tr>
                            <th><?php echo lang('common_status'); ?></th>
                            <td><?php echo ($event_types->status) ? '<span class="label label-success">'.lang('common_status_active').'</span>' : '<span class="label label-default">'.lang('common_status_inactive').'</span>'; ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>