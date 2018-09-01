<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<!-- Section -->
<section class="page-default bg-grey">
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <?php if (!empty ($faqs)) { $i= 0;  ?>  
        <div class="title-container sm">
          <div class="title-wrap">
            <h4 class="title"><?php echo lang('faqs_asked');?></h4>
            <span class="separator line-separator"></span>
          </div>              
        </div>
        <!-- Tab -->
        <div class="panel-group accordion" id="accordion" role="tablist" aria-multiselectable="true">
            
            <?php foreach ($faqs as $faq) { $i++; ?>
            <div class="panel panel-default">
              <div class="panel-heading">
                  <h4 class="panel-title">
                      <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse_<?php echo $faq->id ?>">
                     <?php echo ucwords ($faq->question); ?>
                      </a>
                  </h4>
              </div>
              <div id="collapse_<?php echo $faq->id ?>" class="panel-collapse collapse <?php echo ($i == 1) ? 'in' : '' ?>">
                  <div class="panel-body">
                     <?php echo ($faq->answer); ?>
                  </div>
              </div>
            </div>
            <?php } ?>

        </div><!-- Tab -->
        <?php } else { ?>

        <ul class="box-404 parent-has-overlay">
          <!-- Page Template Content -->
          <li class="template-content text-center">
            <h1>Oops,</h1>
            <p><?php echo lang('faqs_no') ?></p>
            <a href="<?php echo site_url() ?>" class="btn"><?php echo lang('action_home'); ?></a>
          </li><!-- Page Template Content -->
        </ul>

        <?php } ?>  

      </div><!-- Column -->
    </div><!-- Row -->

    <!-- Disqus Divider -->
    <?php if($this->settings->disqus_short_name) { ?>
    <hr class="md">
    <div class="row">
      <div class="col-md-12">
        <!-- Post Comments -->
        <div id="post-comment"  class="post-block post-comments clearfix">
          <h4><?php echo lang('common_discussion') ?></h4>
          <div id="disqus_thread"></div>
        </div><!-- Post Comments -->
      </div>
    </div>
    <?php } ?>

  </div><!-- Container -->
</section><!-- Section -->