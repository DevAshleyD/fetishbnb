<?php defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div class="row clearfix blogs-view">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <!-- Page Heading -->
                <h2 class="text-uppercase p-l-3-em"><?php echo $blogs->title ?><small><?php echo $blogs->slug ?></small></h2>
                
                <!-- Back Button -->
                <a href="<?php echo site_url($this->uri->segment(1).'/'.$this->uri->segment(2)) ?>" class="btn btn-default btn-circle waves-effect waves-circle waves-float pull-left"><i class="material-icons">arrow_back</i></a>
            </div>
            <div class="body">
                <?php if (!empty ($blogs->image)) { ?>
                <img src="<?php echo site_url('upload/blogs/images/'.$blogs->image) ?>" class="img-responsive img-rounded">
                <?php } ?>
                
                <?php echo $blogs->content; ?>
            </div>
        </div>
    </div>
</div>