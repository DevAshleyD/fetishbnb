<?php defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <!-- Page Heading -->
                <h2 class="text-uppercase p-l-3-em"><?php echo lang('action_view'); ?></h2>
                <!-- Back Button -->
                <a href="<?php echo site_url($this->uri->segment(1)) ?>" class="btn btn-default btn-circle waves-effect waves-circle waves-float pull-left"><i class="material-icons">arrow_back</i></a>
            </div>
            <div class="body">
                <?php echo form_open_multipart(site_url($this->uri->segment(1).'/'.$this->uri->segment(2).'/'.'save'), array('class' => 'form-horizontal', 'id' => 'form-create', 'role'=>"form")); ?>
                <div class="row clearfix">
                    <div class="col-md-2 form-control-label">
                        <?php echo lang('common_images', 'images'); ?>
                    </div>
                    <div class="col-md-10">
                        <div class="form-group">
                            <div class="picture-container">
                                <div class="picture-select">
                                    <img src="<?php echo base_url('themes/admin/img/choose_files.png'); ?>" class="img-responsive">
                                    <?php echo form_input($images);?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="gallery">
                                    </div>                                
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row clearfix">
                    <div class="col-lg-offset-2 col-md-offset-2 col-sm-offset-4 col-xs-offset-5">
                        <div class="btn-group">
                            <button type="submit" class="btn bg-<?php echo $this->settings->admin_theme ?> btn-lg waves-effect"><?php echo lang('action_upload') ?></button>
                        </div>
                        <span id="submit_loader"></span>
                    </div>
                </div>
                <?php echo form_close();?>
                <br>
                <hr>
                <br>
                <!-- Image Gallery Plugin -->
                <?php if(! empty($gallaries)) { ?>
                <div id="aniimated-thumbnials" class="list-unstyled row clearfix">
                    <?php foreach($gallaries as $val) { ?>
                    <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                        <a href="<?php echo base_url('upload/gallaries/images/'.$val->image); ?>" data-sub-html="<?php echo lang('menu_gallary') ?>">
                            <img class="img-responsive img-rounded" src="<?php echo base_url('upload/gallaries/images/'.image_to_thumb($val->image)); ?>" >
                        </a>
                        <span class="remove-image" onclick="remove_image('<?php echo $val->id; ?>')"><i class="material-icons">delete_forever</i></span>
                    </div>
                    <?php } ?>
                </div>
                <?php } else { ?>

                <?php } ?>
            </div>
        </div>
    </div>
</div>

