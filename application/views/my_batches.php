<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<!-- Section -->
<div class="page-default bg-grey">
    <div class="container">
    	<div class="row">
    		<?php if(!empty($my_batches)) { ?>
    		<div class="col-md-12 table-responsive">
    			<table class="table table-striped table-hover table-hover">
    				<thead>
	    				<tr class="my-be-tr">
	    					<th><?php echo lang('b_bookings_course'); ?></th>
	    					<th><?php echo lang('b_bookings_batch').' '.lang('common_title'); ?></th>
	    					<th><?php echo lang('batches_start_end'); ?></th>
	    					<th><?php echo lang('b_bookings_timing'); ?></th>
	    					<th><?php echo lang('b_bookings_payment_paid'); ?></th>
	    					<th><?php echo lang('c_l_payment'); ?></th>
	    					<th><?php echo lang('b_bookings_txn_id'); ?></th>
	    					<th><?php echo lang('b_bookings_booking'); ?></th>
	    				</tr>
    				</thead>
    				<tbody>
    					<?php foreach($my_batches as $val) { ?>
    					<tr class="my-be-tr">
    						<td class="text-capitalize"><a href="<?php echo site_url('courses/').str_replace(' ', '+', $val->course_title) ?>"><?php echo $val->course_title ?></a></td>
    						<td class="text-capitalize"><?php echo $val->batch_title ?></td>
    						<td><?php echo date("F j, Y", strtotime($val->batch_start_date)).' - '.date("F j, Y", strtotime($val->batch_end_date)) ?></td>
    						<td><?php echo date("g:i A", strtotime($val->batch_start_time)).' - '.date("g:i A", strtotime($val->batch_end_time)) ?></td>
    						<td><?php echo $val->total_amount.' '.$val->currency ?></td>
    						<td><?php echo $val->payment_type == 'locally' ? lang('b_bookings_payment_type_lly') : ($val->payment_type == 'stripe' ? lang('b_bookings_payment_type_ipe') : lang('b_bookings_payment_type_pal')); ?> (<?php echo $val->payment_status == 1 ? lang('b_bookings_payment_status_successful') : ($val->payment_status == 2 ? lang('b_bookings_payment_status_failed') : lang('b_bookings_payment_status_pending')); ?>)</td>
    						<td><?php echo $val->txn_id ?></td>
    						<td><?php echo date("F j, Y", strtotime($val->date_added)) ?></td>
                            <td>
                                <a href="<?php echo site_url("mybatches/view_invoice/".$val->id); ?>" target="_blank" class="btn btn-info btn-mini"><i class="fa fa-file-text"></i><?php echo lang('action_view_invoice'); ?></a>
                                <?php if(date('Y-m-d', strtotime(str_replace('-', '/', $val->booking_date))) > date('Y-m-d H:i:s')) { ?>
                                <div class="btn-group">
                                <?php if($val->cancellation) { echo $val->cancellation == 1 ? '<span class="label label-info">'.lang('common_cancellation_pending').'</span>' : ($val->cancellation == 2 ? '<span class="label label-warning">'.lang('common_cancellation_approved').'</span>' : ($val->cancellation == 3 ? '<span class="label label-success">'.lang('common_cancellation_refunded') : '<span class="label label-default">'.lang('common_cancellation_disabled')).'</span>'); } else { ?>
                                    <a role="button" class="btn btn-danger btn-mini" onclick="bookingCancel(`<?php echo $val->id ?>`, `<?php echo $val->batch_title ?>`, `<?php echo lang('menu_b_booking') ?>`)"><i class="fa fa-times"></i> <?php echo lang('common_cancellation') ?></a>
                                    <?php } ?>
                                </div>
                                <?php } else { ?> 
                                <span class="label label-default"><?php echo lang('common_ended'); ?></span>
                                <?php } ?>
                            </td>
    					</tr>
    					<?php } ?>
    				</tbody>
    			</table>
    		</div>
    		<?php } else { ?>
    		<div class="col-md-12 text-center">
				<h3><?php echo lang('b_bookings_no') ?></h3>
				<p><a href="<?php echo site_url('') ?>"><?php echo lang('action_home'); ?></a></p>
			</div>
    		<?php } ?>
    	</div>
    </div>
</div>