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
                <?php  echo '<a role="button" onclick="ajaxDelete('.$testimonials->id.', `'.mb_substr($testimonials->t_name, 0, 20, 'utf-8').'`, `'.lang('menu_testimonial').'`)" class="btn btn-default btn-circle waves-effect waves-circle waves-float pull-right"><i class="material-icons">delete_forever</i></a>';  ?>
            </div>
            <div class="body table-responsive">
                <table class="table table-striped">
                    <tbody>
                        <tr>
                            <th><?php echo lang('testimonials_name'); ?></th>
                            <td class="text-capitalize"><?php echo $testimonials->t_name; ?></td>
                        </tr>
                        <tr>
                            <th><?php echo lang('testimonials_type'); ?></th>
                            <td class="text-capitalize"><?php echo $testimonials->t_type; ?></td>
                        </tr>
                        <tr>
                            <th><?php echo lang('testimonials_feedback'); ?></th>
                            <td class="text-capitalize"><?php echo $testimonials->t_feedback; ?></td>
                        </tr>
                        <tr>
                            <th><?php echo lang('common_image'); ?></th>
                            <td>
                            <?php if(! empty($testimonials->image)) { ?>
                                <img src="<?php echo base_url('upload/testimonials/images/').$testimonials->image; ?>" class=" col-sm-2 img-circle">
                            <?php } else { ?>
                                N/A
                            <?php } ?>
                            </td>
                        </tr>
                        <tr>
                            <th><?php echo lang('common_updated'); ?></th>
                            <td><?php echo date("F j, Y g:i A ", strtotime($testimonials->date_updated)) ?></td>
                        </tr>
                        <tr>
                            <th><?php echo lang('common_status'); ?></th>
                            <td><?php echo ($testimonials->status) ? '<span class="label label-success">'.lang('common_status_active').'</span>' : '<span class="label label-default">'.lang('common_status_inactive').'</span>'; ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>