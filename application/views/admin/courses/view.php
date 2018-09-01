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
                <?php  echo '<a role="button" onclick="ajaxDelete('.$courses->id.', `'.mb_substr($courses->title, 0, 20, 'utf-8').'`, `'.lang('menu_course').'`)" class="btn btn-default btn-circle waves-effect waves-circle waves-float pull-right"><i class="material-icons">delete_forever</i></a>';  ?>
            </div>
            <div class="body table-responsive">
                <table class="table table-striped">
                    <tbody>
                        <tr>
                            <th><?php echo lang('common_title'); ?></th>
                            <td class="text-capitalize"><?php echo $courses->title; ?></td>
                        </tr>
                        <tr>
                            <th><?php echo lang('courses_category'); ?></th>
                            <td class="text-capitalize"><?php echo $category; ?></td>
                        </tr>
                        <tr>
                            <th><?php echo lang('common_description'); ?></th>
                            <td class="text-capitalize"><?php echo $courses->description ? $courses->description : 'N/A'; ?></td>
                        </tr>
                        <tr>
                            <th><?php echo lang('common_images'); ?></th>
                            <td>
                            <?php if(! empty($courses->images)) { 
                                    foreach(json_decode($courses->images) as $image) {
                            ?>
                                <img src="<?php echo base_url('upload/courses/images/'.image_to_thumb($image)); ?>" class="img-responsive col-sm-2 thumbnail">
                            <?php } } else { ?>
                                N/A
                            <?php } ?>
                            </td>
                        </tr>
                        <tr>
                            <th><?php echo lang('common_meta_title'); ?></th>
                            <td><?php echo $courses->meta_title ? $courses->meta_title : 'N/A'; ?></td>
                        </tr>
                        <tr>
                            <th><?php echo lang('common_meta_tags'); ?></th>
                            <td><?php echo $courses->meta_tags ? $courses->meta_tags : 'N/A'; ?></td>
                        </tr>
                        <tr>
                            <th><?php echo lang('common_meta_description'); ?></th>
                            <td><?php echo $courses->meta_description ? $courses->meta_description : 'N/A'; ?></td>
                        </tr>
                        <tr>
                            <th><?php echo lang('common_added'); ?></th>
                            <td><?php echo date("F j, Y g:i A ", strtotime($courses->date_added)) ?></td>
                        </tr>
                        <tr>
                            <th><?php echo lang('common_updated'); ?></th>
                            <td><?php echo date("F j, Y g:i A ", strtotime($courses->date_updated)) ?></td>
                        </tr>
                        <tr>
                            <th><?php echo lang('common_featured'); ?></th>
                            <td><?php echo ($courses->featured) ? '<span class="label label-success">'.lang('common_featured_enabled').'</span>' : '<span class="label label-default">'.lang('common_featured_disabled').'</span>'; ?></td>
                        </tr>
                        <tr>
                            <th><?php echo lang('common_status'); ?></th>
                            <td><?php echo ($courses->status) ? '<span class="label label-success">'.lang('common_status_active').'</span>' : '<span class="label label-default">'.lang('common_status_inactive').'</span>'; ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>