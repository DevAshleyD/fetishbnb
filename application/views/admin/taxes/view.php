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
                <?php  echo '<a role="button" onclick="ajaxDelete('.$taxes->id.', `'.mb_substr($taxes->title, 0, 20, 'utf-8').'`, `'.lang('menu_tax').'`)" class="btn btn-default btn-circle waves-effect waves-circle waves-float pull-right"><i class="material-icons">delete_forever</i></a>';  ?>
            </div>
            <div class="body table-responsive">
                <table class="table table-striped">
                    <tbody>
                        <tr>
                            <th><?php echo lang('common_title'); ?></th>
                            <td class="text-capitalize"><?php echo $taxes->title; ?></td>
                        </tr>
                        <tr>
                            <th><?php echo lang('taxes_rate_type'); ?></th>
                            <td><?php echo ($taxes->rate_type == 'percent') ? '<span class="label label-success">'.lang('taxes_rate_type_percent').'</span>' : '<span class="label label-default">'.lang('taxes_rate_type_fixed').'</span>'; ?></td>
                        </tr>
                        <tr>
                            <th><?php echo lang('taxes_rate'); ?></th>
                            <td class="text-capitalize"><?php echo $taxes->rate; ?></td>
                        </tr>
                        <tr>
                            <th><?php echo lang('taxes_net_price'); ?></th>
                            <td><?php echo ($taxes->net_price == 'including') ? '<span class="label label-success">'.lang('taxes_net_price_including').'</span>' : '<span class="label label-default">'.lang('taxes_net_price_excluding').'</span>'; ?></td>
                        </tr>

                        <tr>
                            <th><?php echo lang('common_added'); ?></th>
                            <td><?php echo date("F j, Y g:i A ", strtotime($taxes->date_added)) ?></td>
                        </tr>
                        <tr>
                            <th><?php echo lang('common_updated'); ?></th>
                            <td><?php echo date("F j, Y g:i A ", strtotime($taxes->date_updated)) ?></td>
                        </tr>
                        <tr>
                            <th><?php echo lang('common_status'); ?></th>
                            <td><?php echo ($taxes->status) ? '<span class="label label-success">'.lang('common_status_active').'</span>' : '<span class="label label-default">'.lang('common_status_inactive').'</span>'; ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>