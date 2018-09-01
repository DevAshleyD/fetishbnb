<?php defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!-- Section -->
<div class="page-default bg-grey">
    <div class="container">
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="body">
                        <?php echo form_open_multipart(site_url($this->uri->segment(1).'/'.$this->uri->segment(2).'/'.'save'), array('class' => 'form-horizontal', 'id' => 'form-create', 'role'=>"form")); ?>

                            <?php if(! empty($id)) { ?> <!-- in case of update blogs -->
                                <input type="hidden" name="id" value="<?php echo $id; ?>">
                            <?php } ?>
                            
                            <div class="row clearfix">
                                <div class="col-md-2 form-control-label">
                                    <?php echo lang('common_title', 'title', array('class'=>'title')); ?>
                                </div>
                                <div class="col-md-10">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <?php echo form_input($title);?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row clearfix">
                                <div class="col-md-2 form-control-label">
                                    <?php echo lang('blogs_slug', 'slug', array('class'=>'slug')); ?>
                                </div>
                                <div class="col-md-10">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <?php echo form_input($slug);?>
                                        </div>
                                        <div class="help-block"><?php echo lang('common_auto_readonly') ?></div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row clearfix">
                                <div class="col-md-2 form-control-label">
                                    <?php echo lang('blogs_content', 'content', array('class'=>'content')); ?>
                                </div>
                                <div class="col-md-10">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <?php echo form_textarea($content); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row clearfix">
                                <div class="col-md-2 form-control-label">
                                    <?php echo lang('common_image', 'image'); ?>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <div class="picture-container">
                                            <div class="picture-square width-50">
                                                <?php if(! empty($c_image)) { ?>
                                                    <img id="c_image" src="<?php echo base_url('upload/blogs/images/'.image_to_thumb($c_image)); ?>" class="img-responsive">
                                                <?php } else { ?>
                                                    <img id="c_image" src="<?php echo base_url('themes/admin/img/img_place.png'); ?>" class="img-responsive">
                                                <?php } ?>
                                                <?php echo form_input($image);?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row clearfix">
                                <div class="col-md-2 form-control-label">
                                    <?php echo lang('common_meta_title', 'meta_title', array('class'=>'meta_title')); ?>
                                </div>
                                <div class="col-md-10">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <?php echo form_input($meta_title);?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row clearfix">
                                <div class="col-md-2 form-control-label">
                                    <?php echo lang('common_meta_tags', 'meta_tags', array('class'=>'meta_tags')); ?>
                                </div>
                                <div class="col-md-10">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <?php echo form_input($meta_tags);?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row clearfix">
                                <div class="col-md-2 form-control-label">
                                    <?php echo lang('common_meta_description', 'meta_description', array('class'=>'meta_description')); ?>
                                </div>
                                <div class="col-md-10">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <?php echo form_textarea($meta_description);?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <?php if(!empty($id)) { ?>
                            <div class="row clearfix">
                                <div class="col-md-2 form-control-label">
                                    <?php echo lang('common_status', 'status', array('class'=>'status')); ?>
                                </div>
                                <div class="col-md-10">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <?php echo $status; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php } ?>

                            <div class="row clearfix">
                                <div class="col-lg-offset-2 col-md-offset-2 col-sm-offset-4 col-xs-offset-5">
                                    <div class="btn-group">
                                        <button type="submit" class="btn btn-lg"><?php echo lang('action_submit') ?></button>
                                        <!-- Back Button -->
                                        <a href="<?php echo site_url('myblogs') ?>" class="btn btn-mute btn-lg"><?php echo lang('action_cancel') ?></a>
                                    </div>
                                    <span id="submit_loader"></span>
                                </div>
                            </div>

                        <?php echo form_close();?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>