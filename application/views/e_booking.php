<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="page-default bg-grey typo-dark">
	<!-- Container -->
	<div class="container" id="booking-page">

		<div class="row shop-forms">
			<input type="hidden" name="logged_in" value="<?php echo $this->session->userdata('logged_in') ? 1 : ''; ?>">
			<!-- User Login Tab -->
			<!-- End User Login Tab -->

			<!-- Select Batch Tab -->
			<?php if ($this->session->userdata('logged_in')) { ?>
			<div class="col-sm-12 margin-top-50">
				<div class="content-box shadow bg-lgrey">
					<input type="hidden" name="event_types_id" id="event_types_id" value="<?php echo $event_types_id; ?>">
                    <input type="hidden" name="events_id" id="events_id" value="">
                    <input type="hidden" name="booking_date" id="booking_date" value="">
                    <input type="hidden" name="start_time" id="start_time" value="">
                    <input type="hidden" name="booking_members" id="booking_members" value="">


					<div class="row" id="change-event">
						<div class="col-md-12" >
							<button type="button" class="btn"><?php echo lang('e_l_change_event'); ?></button>
						</div>
					</div>
					<div class="row" id="hide-calendar"><br>
                    	<div class="col-md-12" >
							<h4><?php echo lang('e_l_select_event') ?><br>
								<small class="small"><?php echo $selected_event->recurring ? '<sup>*</sup>'.$selected_event->title.lang('e_l_repetitive_event_help') : ''; ?></small>
							</h4>

                            <!-- THE CALENDAR -->
                            <div class="cal" id="calendar"></div>
                        </div>
                    </div>

                    <div class="row event_details" id="fees_table">
                    	<hr class="lg">
                    	<div class="col-md-6" id="event_label">
                    		<h4><?php echo lang('e_l_event_details'); ?></h4>
			                <table cellspacing="0" class="shop_table cart"><tbody></tbody></table>
			                <span id="event_loader"></span>
			                <br>
                    	</div>
                    	<div class="col-md-6">
                    		<h4><?php echo lang('e_l_event_hosts'); ?></h4>
			                <table cellspacing="0" class="shop_table cart" id="tutors_table"><tbody></tbody></table>
			                <br>
                    		<h4><?php echo lang('e_l_event_price'); ?></h4>
                    		<table cellspacing="0" class="shop_table cart">
                    			<tr id="fees_label">
                    				<td><?php echo lang('e_bookings_fees'); ?></td>
                    				<td><p id="fees"></p></td>
                    				<td><span id="net_fees_loader"></span><p class="text-info text-uppercase"></p></td>
                    			</tr>
                    			<tr id="net_fees_label">
                    				<td><?php echo lang('e_bookings_net_fees'); ?></td>
                    				<td><p id="net_fees"></p></td>
                    				<td><p class="text-info text-uppercase"></p></td>
                    			</tr>
                    		</table>
                    	</div>
                    </div>
                    <hr class="lg">
                     <?php echo form_open(site_url($this->uri->segment(1).'/'.'initiate_booking'), array('class' => 'form-horizontal', 'id' => 'form-create', 'role'=>'form')); ?>
                    <div class="row members-toggle">
                        <div class="col-md-12">
                        	<h4><?php echo lang('e_bookings_members'); ?></h4> <a class="add_member_button pointer-elem btn pull-right" title="<?php echo lang('book_act_add'); ?>"><?php echo lang('book_act_add'); ?></a>

                            <div class="row">
                                <div class="col-md-offset-1 col-md-11 members">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="<?php echo form_error('fullname') ? ' has-error' : ''; ?>">
                                                <?php echo lang('users_nickname', 'username'); ?>
                                                <?php echo form_input($fullname); ?>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="<?php echo form_error('email') ? ' has-error' : ''; ?>">
                                                <?php echo lang('users_email', 'email'); ?>
                                                <?php echo form_input($email); ?>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="<?php echo form_error('mobile') ? ' has-error' : ''; ?>">
                                                <?php echo lang('users_mobile', 'mobile'); ?>
                                                <?php echo form_input($mobile); ?>
                                            </div>
                                        </div>
                                        <i class="uni-close remove_member pointer-elem" title="<?php echo lang('e_bookings_members_remove'); ?>"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php echo form_close(); ?>
                    <div class="row">
						<div class="col-md-12 text-right">
							<input type="button" id="continue" value="<?php echo lang('action_continue') ?>" name="continue" class="btn btn-lg">
							<span id="submit_loader"></span>
						</div>
					</div>

				</div>
			</div>
			<!-- End User Login Tab -->

			<!-- Booking Details -->
			<div class="col-sm-12 margin-top-50" id="booking_details">
				<div class="content-box shadow bg-lgrey table-responsive">
					<h4><?php echo lang('e_l_booking_details'); ?></h4>
					<table cellspacing="0" class="cart-totals table table-condensed">
			          	<thead>
				            <tr>
				              <th><?php echo lang('e_bookings_qty'); ?></th>
				              <th><?php echo lang('e_bookings_event'); ?></th>
				              <th><?php echo lang('e_bookings_booking_date'); ?></th>
				              <th><?php echo lang('e_bookings_timing'); ?></th>
				              <th><?php echo lang('e_bookings_event_type'); ?></th>
				              <th><?php echo lang('e_l_booking_total_members'); ?></th>
				              <th><?php echo lang('e_bookings_subtotal'); ?></th>
				            </tr>
			            </thead>
			            <tbody>
				            <tr>
				              <td>1</td>
				              <td class="text-capitalize" id="event_title"></td>
				              <td id="book_duration"></td>
				              <td id="book_timing"></td>
				              <td class="text-capitalize" id="event_type_title"></td>
				              <td id="total_members"></td>
				              <td id="subtotal"></td>
				            </tr>
			          	</tbody>
			        </table>
				</div>
			</div><!-- Shop Form -->

			<!-- Review And Payment -->
			<div class="col-sm-12 margin-top-50" id="review_payment">
				<div class="content-box shadow bg-lgrey">
					<h4><?php echo lang('e_l_review'); ?></h4>
						<table  cellspacing="0" class="cart-totals">
						<tr class="cart-subtotal">
							<th><?php echo lang('e_bookings_subtotal'); ?>:</th>
							<td class="text-success" id="subtotal2"></td>
						</tr>
						<tr>
							<th id="taxhead"></th>
							<td class="text-info" id="taxval"></td>
						</tr>
						<tr class="total">
							<th><?php echo lang('e_bookings_total') ?>:</th>
							<td class="bookingtotal text-success"></td>
						</tr>
					</table>

					<hr class="lg">

					<h4><?php echo lang('e_l_payment'); ?></h4>

					<?php echo form_open(site_url($this->uri->segment(1).'/'.'payment_method')); ?>
						<div class="row payment-row">
							<div class="col-md-12">
								<span class="remember-box checkbox">
									<label>
										<input type="radio" name="payment_method" value="btc_credit" checked="checked"><?php echo lang('e_bookings_payment_type_btc') ?>
									</label>
								</span>
							</div>
						</div>
						<div class="row free-row">
							<div class="col-md-12">
								<h4><?php echo lang('events_free') ?></h4>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12 text-right">
								<input type="submit" value="<?php echo lang('e_l_place_order') ?>" name="" class="btn btn-lg">
							</div>
						</div>
					<?php echo form_close(); ?>

				</div>
			</div>
		<?php }else{ redirect(site_url('auth')); }?>

		</div><!-- Row -->

	</div><!-- Container -->
</div><!-- Page Default -->

<script type="text/javascript">
    var selectedEvent      = '<?php echo !$selected_event->recurring ? json_encode($selected_event) : null; ?>';
</script>
