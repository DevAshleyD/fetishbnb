<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<!-- Widgets -->
<div class="row clearfix">
    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <a href="<?php echo site_url('admin/users') ?>">
            <div class="info-box bg-pink hover-expand-effect pointer-elem">
                <div class="icon">
                    <i class="material-icons">face</i>
                </div>
                <div class="content">
                    <div class="text text-uppercase"><?php echo lang('dashboard_users') ?></div>
                    <div class="number count-to" data-from="0" data-to="<?php echo $total_users ?>" data-speed="1000" data-fresh-interval="20"></div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <a href="<?php echo site_url('admin/ebookings') ?>">
            <div class="info-box bg-cyan hover-expand-effect pointer-elem">
                <div class="icon">
                    <i class="material-icons">monetization_on</i>
                </div>
                <div class="content">
                    <div class="text text-uppercase"><?php echo lang('dashboard_bookings') ?></div>
                    <div class="number count-to" data-from="0" data-to="<?php echo $total_bookings ?>" data-speed="1000" data-fresh-interval="20"></div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <a href="<?php echo site_url('admin/batches') ?>">
            <div class="info-box bg-light-green hover-expand-effect pointer-elem">
                <div class="icon">
                    <i class="material-icons">event_note</i>
                </div>
                <div class="content">
                    <div class="text text-uppercase"><?php echo lang('dashboard_batches') ?></div>
                    <div class="number count-to" data-from="0" data-to="<?php echo $total_batches ?>" data-speed="1000" data-fresh-interval="20"></div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <a href="<?php echo site_url('admin/events') ?>">
            <div class="info-box bg-orange hover-expand-effect pointer-elem">
                <div class="icon">
                    <i class="material-icons">event</i>
                </div>
                <div class="content">
                    <div class="text text-uppercase"><?php echo lang('dashboard_events') ?></div>
                    <div class="number count-to" data-from="0" data-to="<?php echo $total_events ?>" data-speed="1000" data-fresh-interval="20"></div>
                </div>
            </div>
        </a>
    </div>
</div>
<!-- #END# Widgets -->

<!-- Visitors Visits COunt Chart -->
<div class="row clearfix">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="card">
            <div class="header">
                <div class="row clearfix">
                    <div class="col-xs-12 col-sm-6">
                        <h2><?php echo lang('dashboard_visitors_report') ?></h2>
                    </div>
                </div>
            </div>
            <div class="body">
                <div id="chart_visits" style="width: 100%; height: 500px;"></div>
            </div>
        </div>
    </div>
</div>
<!-- #END# Visits count chart -->


<!--  Profit chart -->
<div class="row clearfix">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="card">
            <div class="header">
                <div class="row clearfix">
                    <div class="col-xs-12 col-sm-6">
                        <h2><?php echo lang('dashboard_sales_report') ?></h2>
                    </div>
                </div>
            </div>
            <div class="body">
                <div id="chart_profits" style="width: 100%; height: 500px;"></div>
            </div>
        </div>
    </div>
</div>
<!-- #END# Profit chart -->
                        <!-- <span class="pull-right">
                            <i class="material-icons">trending_up</i>
                        </span> -->

<div class="row clearfix">
    <!-- Task Info -->
    <div class="col-md-6">
        <div class="card">
            <div class="body bg-blue-grey dash-body">
                <div class="m-b--35 font-bold"><?php echo lang('noti_new').' '.lang('notifications') ?></div>
                <ul class="dashboard-stat-list">
                    <?php if(!empty($this->notifications)) { foreach($this->notifications as $key => $val) { ?>
                    <li>
                        <a class="todays-be" href="javascript:read_notification(`<?php echo $val->n_type ?>`, `<?php echo $val->n_url ?>`);"><?php echo ($key+1).'. '.sprintf(lang(''.$val->n_content.''), lang('menu_'.$val->n_type.'')).' <small>('.$val->total.' '.lang('noti_new').')</small>'; ?></a>
                        <span class="pull-right"><b><?php echo time_elapsed_string($val->date_added); ?></b></span>
                    </li>
                    <?php } } else { ?>
                    <li><a href="#" class="text-center"><?php echo lang('noti_no'); ?></a></li>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </div>
    <!-- #END# Task Info -->
    <!-- Latest Social Trends -->
    <div class="col-md-6">
        <div class="card">
            <div class="body bg-blue-grey dash-body">
                <div class="m-b--35 font-bold"><?php echo lang('dashboard_today_b_e') ?></div>
                <ul class="dashboard-stat-list">
                    <?php if(!empty($todays_b_e)) { foreach($todays_b_e as $key => $val) { ?>
                    <li>
                        <a class="todays-be" href="<?php echo site_url($val->url).$val->id; ?>"><?php echo ($key+1).'. '.$val->title.' <small>'.$val->type.'</small>' ?></a>
                        <span class="pull-right"><b><?php echo date("g:iA", strtotime($val->start_time)) .' - '. date("g:iA", strtotime($val->end_time)); ?></b></span>
                    </li>
                    <?php } } else { ?>
                    <li></li>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </div>
    <!-- #END# Latest Social Trends -->
</div>

<div class="row clearfix">
    <!-- Answered Tickets -->
    <div class="col-md-6">
        <div class="card">
            <div class="body bg-indigo dash-body">
                <div class="font-bold m-b--35"><?php echo lang('dashboard_top_batches') ?></div>
                <ul class="dashboard-stat-list">
                    <?php if(!empty($top_batches)) { foreach($top_batches as $key => $val) { ?>
                    <li>
                        <a class="todays-be" href="<?php echo site_url($val->url).$val->id; ?>"><?php echo ($key+1).'. '.$val->title.' <small>'.$val->type.'</small>' ?></a>
                        <span class="pull-right"><b><?php echo $val->total_bookings; ?><?php echo ' '.lang('menu_bookings') ?></b></span>
                    </li>
                    <?php } } else { ?>
                    <li></li>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </div>
    <!-- #END# Answered Tickets -->

    <!-- Answered Tickets -->
    <div class="col-md-6">
        <div class="card">
            <div class="body bg-indigo dash-body">
                <div class="font-bold m-b--35"><?php echo lang('dashboard_top_events') ?></div>
                <ul class="dashboard-stat-list">
                    <?php if(!empty($top_events)) { foreach($top_events as $key => $val) { ?>
                    <li>
                        <a class="todays-be" href="<?php echo site_url($val->url).$val->id; ?>"><?php echo ($key+1).'. '.$val->title.' <small>'.$val->type.'</small>' ?></a>
                        <span class="pull-right"><b><?php echo $val->total_bookings; ?><?php echo ' '.lang('menu_bookings') ?></b></span>
                    </li>
                    <?php } } else { ?>
                    <li></li>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </div>
    <!-- #END# Answered Tickets -->
    
</div>

<script type="text/javascript">
    var totalVisits      = '<?php echo !empty($total_visits) ? json_encode($total_visits) : null; ?>';
    var totalSales       = '<?php echo !empty($total_sales) ? json_encode($total_sales) : null; ?>';
</script>