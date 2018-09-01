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
                <?php  echo '<a role="button" onclick="ajaxDelete('.$languages->id.', `'.mb_substr($languages->title, 0, 20, 'utf-8').'`, `'.lang('menu_language').'`)" class="btn btn-default btn-circle waves-effect waves-circle waves-float pull-right"><i class="material-icons">delete_forever</i></a>';  ?>
            </div>
            <div class="body table-responsive">
                <table class="table table-striped">
                    <tbody>
                        <tr>
                            <th><?php echo lang('common_title'); ?></th>
                            <td class="text-capitalize"><?php echo $languages->title; ?></td>
                        </tr>
                        <tr>
                            <th><?php echo lang('languages_flag'); ?></th>
                            <td>
                            <?php if(! empty($languages->flag)) { ?>
                                <img src="<?php echo base_url('upload/languages/flags/'.$languages->flag); ?>" class="img-responsive col-sm-2 thumbnail">
                            <?php } else { ?>
                                N/A
                            <?php } ?>
                            </td>
                        </tr>
                        <tr>
                            <th><?php echo lang('common_added'); ?></th>
                            <td><?php echo date("F j, Y g:i A ", strtotime($languages->date_added)) ?></td>
                        </tr>
                        <tr>
                            <th><?php echo lang('common_updated'); ?></th>
                            <td><?php echo date("F j, Y g:i A ", strtotime($languages->date_updated)) ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>