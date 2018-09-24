	<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>


	<div class="page-default">
		<!-- Container -->
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<div class="card">
						<div class="header">
							<div class="col-md-6">
								<h2>Welcome,  <?php echo $user['username'];?>!</h2>
							</div>
							<div class="col-md-6">
								<h2 class="pull-right">Your current BTC balance is: <?php echo $btc_balance;?> <a style="margin-left: 10px; display:inline-block;" href="#" class="btn">Add Credits</a></h2>
							</div>
							<div class="clear"></div>
						</div>
						<div id="btc_table" class="body">
							<?php echo form_open('charge/charge_btc');?>
							<input type="hidden" name="payer_id" value="<?php echo $this->user['id']?>" />
							<input type="hidden" name="txn_amount" value="<?php echo bcdiv($details['net_fees'],$btc_exchange['rate_float'], 8);?>" />
							<p>Your Booking Details: </p>
							<table width="100%">
								<tr>
									<td>Title</td>
									<td><?php echo $details['event_title'];?></td>
								</tr>
								<tr>
									<td>Event Category</td>
									<td><?php echo $details['event_type_title'];?></td>
								</tr>
								<tr>
									<td>Booking Date</td>
									<td><?php echo date('d, M Y', strtotime($details['booking_date']));?></td>
								</tr>
								<tr>
									<td>Starting Time</td>
									<td><?php echo date('h:i A', strtotime($details['start_time']));?></td>
								</tr>
								<tr>
									<td>Fees</td>
									<td><?php echo $details['fees'].' '.$details['currency'];?></td>
								</tr>
								<tr>
									<td>Net Fees</td>
									<td><?php echo $details['net_fees'].' '.$details['currency'];?> <span style="color: green;"><i><?php echo $details['net_price'].' '.$details['rate'].$details['rate_type'].'VAT';?></i></span></td>
								</tr>
								<tr>
									<td>Members Count</td>
									<td><?php echo $details['count_members'];?></td>
								</tr>
							</table>
							<br>
							<br>
							<p>Members:</p>
							<table width="100%">
							<?php foreach($details['members'] as $member){?>
								<tr>
									<td>Nickname</td>
									<td><?php echo $member['fullname']?></td>
								</tr>
								<tr>
									<td>Email</td>
									<td><?php echo $member['email']?></td>
								</tr>
								<tr>
									<td>Mobile</td>
									<td><?php echo $member['mobile']?></td>
								</tr>
							<?php }; ?>
						</table>
						<br>
						<br>
						<p>Grand Total:</p>
						<table width="100%">
							<tr>
								<td><?php echo $btc_exchange['description'];?></td>
								<td><?php echo $details['net_fees'];?></td>
							</tr>
							<tr>
								<td>BTC</td>
								<td>
								<?php
									$btc = bcdiv($details['net_fees'],$btc_exchange['rate_float'], 10);
									echo $btc;
								?>
							</td>
							</tr>
						</table>
							<div class="clear"></div>
						<div class="col-md-4 pull-right align-right">
							<br>
							<a href="<?php echo site_url('charge/cancel')?>" class="btn">Cancel</a>
							<button class="btn" name="book_proceed" id="booksubmit" value="submit">Submit Payment</button>
							<?php echo form_close();?>
						</div>
						<div class="clear"></div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
