<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?php echo $page_title; ?></title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="<?php echo base_url('themes/core/plugins/bootstrap/css/bootstrap.min.css'); ?>">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo base_url('themes/core/plugins/font-awesome/css/font-awesome.min.css'); ?>">
  
  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>
<body>
<div class="wrapper">
  <div class="container">
    <div class="row">
      <div class="col-sm-1 pull-right">
        <div class="btn-group">
          <span id="print" class="btn btn-default btn-flat"><i class="fa fa-print"></i></span>  
        </div>
      </div>
    </div>
  </div>
  
  <!-- Main content -->
  <section class="container">
    <!-- title row -->
    <div class="row">
      <div class="col-xs-12">
        <h2 class="page-header">
          <i class="fa fa-globe"></i> <?php echo $this->settings->institute_name; ?>
          <small class="pull-right"><?php echo lang('b_bookings_date'); ?>: <?php echo date("F j, Y", strtotime($b_bookings->booking_date)); ?></small>
        </h2>
      </div>
      <!-- /.col -->
    </div>
    <!-- info row -->
    <div class="row invoice-info">
      <div class="col-sm-4 invoice-col">
        <?php echo lang('b_bookings_from'); ?>
        <address>
          <strong><?php echo $this->settings->institute_name; ?></strong><br>
          <?php echo $this->settings->institute_address; ?><br>
          <?php echo lang('users_phone') ?>: <?php echo $this->settings->institute_phone; ?><br>
          <?php echo lang('users_email') ?>: <?php echo $this->settings->site_email; ?>
        </address>
      </div>
      <!-- /.col -->
      <div class="col-sm-4 invoice-col">
        <?php echo lang('b_bookings_to'); ?>
        <address>
          <strong><?php echo $b_bookings->customer_name; ?></strong><br>
          <?php echo $b_bookings->customer_address; ?><br>
          <?php echo lang('users_phone') ?>: <?php echo $b_bookings->customer_mobile; ?><br>
          <?php echo lang('users_email') ?>: <?php echo $b_bookings->customer_email; ?>
        </address>
      </div>
      <!-- /.col -->
      <div class="col-sm-4 invoice-col">
        <b><?php sprintf(lang('alert_id'), lang('menu_booking')) ?> :</b> <?php echo $b_bookings->id ?><br>
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->

    <!-- Table row -->
    <div class="row">
      <div class="col-xs-12 table-responsive">
        <table class="table table-striped">
          <thead>
            <tr>
              <th><?php echo lang('b_bookings_qty'); ?></th>
              <th><?php echo lang('b_bookings_batch'); ?></th>
              <th><?php echo lang('b_bookings_course'); ?></th>
              <th><?php echo lang('b_bookings_course_category'); ?></th>
              <th><?php echo lang('b_bookings_subtotal'); ?></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($members as $key => $val) { ?>
            <tr>
              <td><?php echo $key+1; ?></td>
              <td><?php echo $b_bookings->batch_title; ?></td>
              <td><?php echo $b_bookings->course_title; ?></td>
              <td><?php echo $b_bookings->course_category_title; ?></td>
              <td><?php echo (float) $b_bookings->net_fees>$b_bookings->fees ? $b_bookings->fees/count($members) : $b_bookings->net_fees/count($members); ?><?php echo ' '.$payments->currency ?></td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->

    <div class="row">
      <!-- accepted payments column -->
      <div class="col-xs-6">
        <p class="lead"><?php echo lang('b_bookings_payment_type'); ?>: </p>
        <h4><?php echo $payments->payment_type == 'locally' ? lang('b_bookings_payment_type_locally') : ($payments->payment_type == 'stripe' ? lang('b_bookings_payment_type_stripe') : lang('b_bookings_payment_type_paypal')); ?></h4>
      </div>
      <!-- /.col -->
      <div class="col-xs-6">
        <div class="table-responsive">
          <table class="table">
            <tr>
              <th style="width:50%"><?php echo lang('b_bookings_subtotal'); ?>:</th>
              <td><?php echo (float) $b_bookings->fees; ?><?php echo ' '.$payments->currency ?></td>
            </tr>
            <tr>
              <th><?php echo lang('menu_tax'); ?> <?php echo ' ('.$payments->tax_rate.' '; ?> <?php echo $payments->tax_rate_type == 'percent' ? '%)' : $payments->currency.')'; ?>:</th>
              <td><?php echo $b_bookings->net_fees>$b_bookings->fees ? $b_bookings->net_fees-$b_bookings->fees : $b_bookings->fees-$b_bookings->net_fees; ?><?php echo ' '.$payments->currency ?></td>
            </tr>
            <tr>
              <th><?php echo lang('b_bookings_total') ?>:</th>
              <td><?php echo $b_bookings->net_fees>$b_bookings->fees ? $b_bookings->net_fees : $b_bookings->fees; ?><?php echo ' '.$payments->currency ?></td>
            </tr>
          </table>
        </div>
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->
  </section>
  <!-- /.content -->
</div>
<!-- ./wrapper -->
</body>
</html>
<script type="text/javascript" src="<?php echo base_url('themes/core/plugins/jquery/jquery.min.js'); ?>"></script>
<script type="text/javascript">
  $(function() {
    $('#print').on('click', function() {
      window.print();
    });
  })
</script>