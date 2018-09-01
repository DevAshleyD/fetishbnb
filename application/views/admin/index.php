<?php defined('BASEPATH') OR exit('No direct script access allowed');
/*Common Table View*/
?>

<div class="row clearfix index-page">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <!-- Page Heading -->
                <h2 class="text-uppercase p-l-3-em"><?php echo lang('action_listing'); ?></h2>
                
                <!-- Back Button -->
                <a href="<?php echo site_url('admin') ?>" class="btn btn-default btn-circle waves-effect waves-circle waves-float pull-left"><i class="material-icons">arrow_back</i></a>

                <!-- Add Button -->
                <?php if($this->uri->segment(2) !== 'contacts') { ?>
                <a href="<?php echo site_url().'admin/'.$this->uri->segment(2).'/form'; ?>" class="btn bg-<?php echo $this->settings->admin_theme ?> btn-circle waves-effect waves-circle waves-float pull-right">
                <i class="material-icons">add</i></a>
                <?php } ?>
            </div>
            <div class="body table-responsive">
                <table id="table" class="table table-striped table-hover dataTable">
                    <thead>
                        <tr>
                            <?php foreach($t_headers as $val) { echo '<th>'.$val.'</th>'; } ?>
                        </tr>
                    </thead>
                    <tbody class="text-capitalize">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>