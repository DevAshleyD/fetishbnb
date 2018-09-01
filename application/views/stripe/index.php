<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>


<div class="page-default">
	<!-- Container -->
	<div class="container">
		<div class="row">
			<div class="col-md-12">

				<div class="col-md-offset-4 col-md-4 parent-has-overlay">
					<ul class="template-box box-login text-center">
						<!-- Page Template Content -->
						<li class="template-content">
							<h4 class="text-primary"><?php echo lang('c_l_pay_with_stripe').' : '.$_SESSION['bookings']['booking_fees'].' '.$_SESSION['bookings']['currency']; ?></h4>
							<small><a class="text-info" href="<?php echo site_url('charge/cancel'); ?>"><?php echo lang('c_l_cancel_payment'); ?></a></small>
							<div class="contact-form">
								<?php echo form_open('charge') ?>
								<script
									src="https://checkout.stripe.com/checkout.js" class="stripe-button"
									data-key="<?php echo $this->settings->s_publishable_key; ?>"
									data-name="<?php echo $this->settings->site_name; ?>"
									data-description="Stripe payment for <?php echo $_SESSION['bookings']['booking_fees'].' '.$_SESSION['bookings']['currency']; ?>"
									data-currency="<?php echo $_SESSION['bookings']['currency']; ?>"
									data-amount="<?php echo $_SESSION['bookings']['booking_fees']*100; ?>">
								</script>		
								<?php echo form_close(); ?>
							</div>	
						</li><!-- Page Template Content -->
					</ul>
				</div><!-- Column -->
			</div>
		</div>
	</div>
</div>