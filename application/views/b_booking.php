<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="page-default bg-grey typo-dark">
	<!-- Container -->
	<div class="container" id="booking-page">

		<div class="row shop-forms">

			<!-- User Login Tab -->
			<div class="col-sm-12">
				<div class="content-box shadow bg-lgrey">
					<input type="hidden" name="logged_in" value="<?php echo $this->session->userdata('logged_in') ? 1 : ''; ?>">
					<?php if ($this->session->userdata('logged_in')) { ?>
					<div class="row">
						<div class="col-md-12">
							<h4><?php echo lang('c_l_logged_in_user') ?></h4>
						</div>
					</div>
					<div class="row">
					  	<div class="col-md-4 col-md-offset-1 image-card">
					     	<div class="picture-container">
					          	<div class="picture">
					              	<img src="<?php echo $this->user['image'] ? base_url('upload/users/images/').$this->user['image'] : base_url('themes/default/img/avatar.jpg'); ?>" class="picture-src img-responsive" id="wizardPicturePreview" title=""/>
					          	</div>
					      	</div>
					  	</div>
					  	<div class="col-md-6">
					  	    <table cellspacing="0" class="shop_table cart">
					  	    	<tr>
					  	    		<td><?php echo lang('users_name') ?></td>
					  	    		<td class="text-capitalize"><?php echo $this->user['first_name'].' '.$this->user['last_name'] ?></td>
					  	    	</tr>
					  	    	<tr>
					  	    		<td><?php echo lang('users_email') ?></td>
					  	    		<td><?php echo $this->user['email']; ?></td>
					  	    	</tr>
					  	    	<tr>
					  	    		<td><?php echo lang('users_mobile') ?></td>
					  	    		<td><?php echo $this->user['mobile']; ?></td>
					  	    	</tr>
					  	    	<tr>
					  	    		<td><?php echo lang('users_address') ?></td>
					  	    		<td class="text-capitalize"><?php echo $this->user['address']; ?></td>
					  	    	</tr>
					  	    </table>
					  	</div>
					</div>
					<?php } else {
				  	// get current uri
					$_SESSION['redirect_url'] = "/" . uri_string();  ?>
					<div class="row">
					<div class="col-md-6">
						<div class="signin-form">
								<?php echo form_open('auth/login', array('class'=>'')); ?>
										<div class="row">
												<div class="form-group <?php echo form_error('identity') ? ' has-error' : ''; ?>">
														<div class="col-md-12">
																<label for="identity">Please login</label>
																<?php echo form_input(array('name'=>'identity', 'id'=>'identity', 'class'=>'form-control', 'placeholder'=>lang('users_email_username'))); ?>
														</div>
												</div>
										</div>
										<div class="row">
												<div class="form-group <?php echo form_error('password') ? ' has-error' : ''; ?>">
														<div class="col-md-12">
																<?php echo form_password(array('name'=>'password', 'id'=>'password', 'class'=>'form-control', 'placeholder'=>lang('users_password'), 'autocomplete'=>'off')); ?>
														</div>
												</div>
										</div>
										<div class="row">
												<div class="col-md-6">
														<span class="remember-box checkbox">
															 <label for="remember">
																<input type="checkbox" id="remember" name="remember" value="1"><?php echo lang('login_remember_label') ?>
															</label>
														</span>
												</div>
												<div class="col-md-6">
													<span class="remember-pass pull-right">
														<a class="pull-right a-hover" href="<?php echo site_url('auth/forgot_password'); ?>"><?php echo lang('users_forgot'); ?></a>
													</span>
												</div>
										</div>
										<div class="row">
												<div class="col-sm-4">
														<!-- Button -->
														<?php echo form_button(array('type' => 'submit', 'class' => 'btn', 'data-loading-text'=>lang('action_loading'), 'content' => lang('users_login'))); ?>
												</div>
										</div>
								<?php echo form_close(); ?> <!-- Form Ends -->
						</div>
				</div>
				<div class="col-md-6">
					<div class="signin-form">
					<?php echo form_open_multipart('auth/register', array('role'=>'form', 'class'=>'', 'id'=>'form_login')); ?>
					<div class="row">
						<div class="form-group <?php echo form_error('email') ? ' has-error' : ''; ?>">
							<label for="email">or register to proceed</label>
										<?php echo form_input(array('name'=>'email', 'value'=>set_value('email', (isset($user['email']) ? $user['email'] : '')), 'class'=>'form-control', 'type'=>'email', 'placeholder'=>lang('users_email'))); ?>
						</div>
					</div>
					<div class="row">
						<div class="form-group <?php echo form_error('username') ? ' has-error' : ''; ?>">
										<?php echo form_input(array('name'=>'username', 'value'=>set_value('username', (isset($user['username']) ? $user['username'] : '')), 'class'=>'form-control','placeholder'=>lang('users_username'))); ?>
						</div>
					</div>
					<div class="row">
						<div class="form-group <?php echo form_error('password') ? ' has-error' : ''; ?>">
								<?php echo form_password(array('name'=>'password', 'value'=>set_value('password', (isset($user['password']) ? $user['password'] : '')), 'class'=>'form-control', 'placeholder'=>lang('users_password'))); ?>
						</div>
					</div>
					<div class="row">
						<div class="form-group <?php echo form_error('password_confirm') ? ' has-error' : ''; ?>">
									<?php echo form_password(array('name'=>'password_confirm', 'value'=>'', 'class'=>'form-control', 'autocomplete'=>'off','placeholder'=>lang('users_password_confirm'))); ?>
						</div>
					</div>
					<div class="row">
	            <div class="col-sm-4 pull-right padding-0">
	                <button type="submit" name="submit_form" id="submit_form" class="btn pull-right"><?php echo lang('users_register'); ?></button>
	            </div>
	        </div>
					<?php echo form_close(); ?>
				</div>
			</div>
			</div>
					<?php } ?>
				</div>
			</div>
			<!-- End User Login Tab -->

			<!-- Select Batch Tab -->
			<?php if ($this->session->userdata('logged_in')) { ?>
			<div class="col-sm-12 margin-top-50">
				<div class="content-box shadow bg-lgrey">
					<input type="hidden" name="courses_id" id="courses_id" value="<?php echo $courses_id; ?>">
                    <input type="hidden" name="batches_id" id="batches_id" value="">
                    <input type="hidden" name="booking_date" id="booking_date" value="">
                    <input type="hidden" name="start_time" id="start_time" value="">
                    <input type="hidden" name="booking_members" id="booking_members" value="">

					<div class="row">
						<div class="col-md-12">
							<h4><?php echo lang('c_l_select_batch') ?></h4>
						</div>
					</div>
					<div class="row">
                    	<div class="col-md-12">
                            <!-- THE CALENDAR -->
                            <div id="calendar"></div>
                        </div>
                    </div>

                    <div class="row batch_details" id="fees_table">
                    	<hr class="lg">
                    	<div class="col-md-6" id="batch_label">
                    		<h4><?php echo lang('c_l_batch_details'); ?></h4>
			                <table cellspacing="0" class="shop_table cart"><tbody></tbody></table>
			                <span id="batch_loader"></span>
			                <br>
                    	</div>
                    	<div class="col-md-6">
                    		<h4><?php echo lang('c_l_batch_tutors'); ?></h4>
			                <table cellspacing="0" class="shop_table cart" id="tutors_table"><tbody></tbody></table>
			                <br>
                    		<h4><?php echo lang('c_l_batch_price'); ?></h4>
                    		<table cellspacing="0" class="shop_table cart">
                    			<tr id="fees_label">
                    				<td><?php echo lang('b_bookings_fees'); ?></td>
                    				<td><p id="fees"></p></td>
                    				<td><span id="net_fees_loader"></span><p class="text-info text-uppercase"></p></td>
                    			</tr>
                    			<tr id="net_fees_label">
                    				<td><?php echo lang('b_bookings_net_fees'); ?></td>
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
                        	<h4><?php echo lang('b_bookings_members'); ?><i class="add_member_button uni-add pointer-elem" title="<?php echo lang('b_bookings_members_add'); ?>"></i></h4>

                            <div class="row">
                                <div class="col-md-offset-1 col-md-11 members">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="<?php echo form_error('fullname') ? ' has-error' : ''; ?>">
                                                <?php echo lang('users_fullname', 'fullname'); ?>
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
                                        <i class="uni-close remove_member pointer-elem" title="<?php echo lang('b_bookings_members_remove'); ?>"></i>
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
					<h4><?php echo lang('c_l_booking_details'); ?></h4>
					<table cellspacing="0" class="cart-totals table table-condensed">
			          	<thead>
				            <tr>
				              <th><?php echo lang('b_bookings_qty'); ?></th>
				              <th><?php echo lang('b_bookings_batch'); ?></th>
				              <th><?php echo lang('b_bookings_duration'); ?></th>
				              <th><?php echo lang('b_bookings_timing'); ?></th>
				              <th><?php echo lang('b_bookings_course'); ?></th>
				              <th><?php echo lang('b_bookings_course_category'); ?></th>
				              <th><?php echo lang('c_l_booking_total_members'); ?></th>
				              <th><?php echo lang('b_bookings_subtotal'); ?></th>
				            </tr>
			            </thead>
			            <tbody>
				            <tr>
				              <td>1</td>
				              <td class="text-capitalize" id="batch_title"></td>
				              <td id="book_duration"></td>
				              <td id="book_timing"></td>
				              <td class="text-capitalize" id="course_title"></td>
				              <td class="text-capitalize" id="course_category_title"></td>
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
					<h4><?php echo lang('c_l_review'); ?></h4>
						<table  cellspacing="0" class="cart-totals">
						<tr class="cart-subtotal">
							<th><?php echo lang('b_bookings_subtotal'); ?>:</th>
							<td class="text-success" id="subtotal2"></td>
						</tr>
						<tr>
							<th id="taxhead"></th>
							<td class="text-info" id="taxval"></td>
						</tr>
						<tr class="total">
							<th><?php echo lang('b_bookings_total') ?>:</th>
							<td class="bookingtotal text-success"></td>
						</tr>
					</table>

					<hr class="lg">

					<h4><?php echo lang('c_l_payment'); ?></h4>

					<?php echo form_open(site_url($this->uri->segment(1).'/'.'payment_method')); ?>
						<div class="row payment-row">
							<div class="col-md-12">
								<span class="remember-box checkbox">
									<label>
										<input type="radio" name="payment_method" checked="" value="paypal"><?php echo lang('b_bookings_payment_type_paypal') ?>
									</label>
								</span>
							</div>
						</div>
						<div class="row payment-row">
							<div class="col-md-12">
								<span class="remember-box checkbox">
									<label>
										<input type="radio" name="payment_method" value="stripe"><?php echo lang('b_bookings_payment_type_stripe') ?>
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
								<input type="submit" value="<?php echo lang('c_l_place_order') ?>" name="" class="btn btn-lg">
							</div>
						</div>
					<?php echo form_close(); ?>

				</div>
			</div>
			<?php } ?>

		</div><!-- Row -->

	</div><!-- Container -->
</div><!-- Page Default -->
