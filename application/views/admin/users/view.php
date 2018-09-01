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
                <?php  echo '<a role="button" onclick="ajaxDelete('.$users->id.', `'.mb_substr($users->first_name.' '.$users->last_name, 0, 30, 'utf-8').'`, `'.lang('menu_user').'`)" class="btn btn-default btn-circle waves-effect waves-circle waves-float pull-right"><i class="material-icons">delete_forever</i></a>';  ?>
            </div>
            <div class="body table-responsive">
                <table class="table table-striped">
                    <tbody>
                        <tr>
                                <th><?php echo lang('users_first_name'); ?></th>
                                <td class="text-capitalize"><?php echo $users->first_name; ?></td>
                            </tr>
                            <tr>
                                <th><?php echo lang('users_last_name'); ?></th>
                                <td class="text-capitalize"><?php echo $users->last_name; ?></td>
                            </tr>
                            <tr>
                                <th><?php echo lang('users_username'); ?></th>
                                <td class="text-capitalize"><?php echo $users->username; ?></td>
                            </tr>
                            <tr>
                                <th><?php echo lang('users_email'); ?></th>
                                <td class="text-capitalize"><?php echo $users->email; ?></td>
                            </tr>
                            <tr>
                                <th><?php echo lang('common_image'); ?></th>
                                <td>
                                <?php if(! empty($users->image)) { ?>
                                    <img src="<?php echo base_url('upload/users/images/'.$users->image); ?>" class="img-responsive col-sm-2 thumbnail">
                                <?php } else { ?>
                                    N/A
                                <?php } ?>
                                </td>
                            </tr>
                            <tr>
                                <th><?php echo lang('users_language'); ?></th>
                                <td><?php echo $users->language; ?></td>
                            </tr>
                            <tr>
                                <th><?php echo lang('users_mobile'); ?></th>
                                <td><?php echo $users->mobile; ?></td>
                            </tr>
                            <tr>
                                <th><?php echo lang('users_profession'); ?></th>
                                <td><?php echo $users->profession; ?></td>
                            </tr>
                            <tr>
                                <th><?php echo lang('users_experience'); ?></th>
                                <td><?php echo $users->experience; ?></td>
                            </tr>
                            <tr>
                                <th><?php echo lang('users_about'); ?></th>
                                <td><?php echo $users->about; ?></td>
                            </tr>
                            <tr>
                                <th><?php echo lang('users_address'); ?></th>
                                <td><?php echo $users->address; ?></td>
                            </tr>
                            <tr>
                                <th><?php echo lang('users_dob'); ?></th>
                                <td><?php echo date('g:i A M j Y ', strtotime($users->dob)); ?></td>
                            </tr> 
                            <tr>
                                <th><?php echo lang('users_gender'); ?></th>
                                <td><?php echo $users->gender == 'male' ? lang('users_gender_male') : ($users->gender == 'female' ? lang('users_gender_female') : lang('users_gender_other')); ?></td>
                            </tr>
                            <tr>
                                <th><?php echo lang('create_group_validation_name_label'); ?></th>
                                <td><?php echo $users->group_name; ?></td>
                            </tr>
                            <tr>
                                <th><?php echo lang('common_status'); ?></th>
                                <td><?php echo ($users->active) ? '<span class="label label-success">'.lang('common_status_active').'</span>' : '<span class="label label-default">'.lang('common_status_inactive').'</span>'; ?></td>
                            </tr>
                            <tr>
                                <th><?php echo lang('common_added'); ?></th>
                                <td><?php echo date("F j, Y g:i A ", strtotime($users->date_added)) ?></td>
                            </tr>
                            <tr>
                                <th><?php echo lang('common_updated'); ?></th>
                                <td><?php echo date("F j, Y g:i A ", strtotime($users->date_updated)) ?></td>
                            </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>