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
                <?php  echo '<a role="button" onclick="ajaxDelete(`'.$currencies->iso_code.'`, `'.$currencies->iso_code.'`, `'.lang('menu_currency').'`)" class="btn btn-default btn-circle waves-effect waves-circle waves-float pull-right"><i class="material-icons">delete_forever</i></a>';  ?>
            </div>
            <div class="body table-responsive">
                <table class="table table-striped">
                    <tbody>
                        <tr>
                            <th><?php echo lang('currencies_iso_code'); ?></th>
                            <td class="text-capitalize"><?php echo $currencies->iso_code; ?></td>
                        </tr>
                        <tr>
                            <th><?php echo lang('currencies_symbol'); ?></th>
                            <td class="text-capitalize"><?php echo $currencies->symbol; ?></td>
                        </tr>
                        <tr>
                            <th><?php echo lang('currencies_unicode'); ?></th>
                            <td class="text-capitalize"><?php echo $currencies->unicode ? $currencies->unicode : 'N/A'; ?></td>
                        </tr>
                        <tr>
                            <th><?php echo lang('currencies_position'); ?></th>
                            <td><?php echo ($currencies->position == 'before') ? '<span class="label label-success">'.lang('currencies_position_before').'</span>' : '<span class="label label-default">'.lang('currencies_position_after').'</span>'; ?></td>
                        </tr>
                        <tr>
                            <th><?php echo lang('common_updated'); ?></th>
                            <td><?php echo date("F j, Y g:i A ", strtotime($currencies->date_updated)) ?></td>
                        </tr>
                        <tr>
                            <th><?php echo lang('common_status'); ?></th>
                            <td><?php echo ($currencies->status) ? '<span class="label label-success">'.lang('common_status_active').'</span>' : '<span class="label label-default">'.lang('common_status_inactive').'</span>'; ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>