<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<!-- Section -->
<section class="page-default bg-grey">
  <div class="container">
    <div class="row">
        <div class="col-md-12">
            <ul class="box-404 parent-has-overlay">
                <!-- Page Template Content -->
                <li class="template-content text-center">
                    <h1><?php echo lang('c_l_payment_cancel_msg'); ?></h1>
                    <p><?php echo empty($this->session->flashdata('message')) ? $this->session->flashdata('error') : $this->session->flashdata('message'); ?></p>
                    <a href="<?php echo site_url('courses'); ?>"><?php echo lang('c_l_go_back_courses'); ?></a>
                </li><!-- Page Template Content -->
            </ul>
        </div>
    </div>
  </div>
</section>