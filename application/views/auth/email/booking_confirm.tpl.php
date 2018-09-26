<?php defined('BASEPATH') OR exit('No direct script access allowed');

/***
* Email templates
* Author: Rob Reyes
*
****/
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<!-- If you delete this meta tag, Half Life 3 will never be released. -->
<meta name="viewport" content="width=device-width" />

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>FetishBNB Email</title>

</head>

<body bgcolor="#ededed" style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; -webkit-font-smoothing:antialiased; -webkit-text-size-adjust:none; width: 100%!important; height: 100%;">
<div style="max-width: 760px;padding: 20px;margin: 0 auto;background: #fff;">
<!-- HEADER -->
<table class="head-wrap" bgcolor="#fff">
	<tr>
		<td></td>
		<td class="header container" >

				<div class="content">
				<table>
					<tr>
						<td><img src="http://18.222.143.177/upload/institute/logo.png" width="150px" height="25px"/></td>
					</tr>
				</table>
				</div>

		</td>
		<td></td>
	</tr>
</table><!-- /HEADER -->


<!-- BODY -->
<table class="body-wrap">
	<tr>
		<td></td>
		<td class="container" bgcolor="#FFFFFF">

			<div class="content">
			<table>
				<tr>
					<td>
						<h3><?php echo sprintf(lang('email_booking_head'));?></h3>
						<p style="font-size: 12px;"><i>Event Name: <?php echo $event_title;?></i><p>
						<p style="font-size: 12px;"><i>Starting Date: <?php echo $event_start_date;?></i><p>
            <p style="font-size: 12px;"><i>Starting Time: <?php echo $event_start_time;?></i><p>
            <p style="font-size: 12px;"><i>Amount Paid: <?php echo $net_fees.' '.$currency;?></i><p>
            <p style="font-size: 12px;"><i>Payment Method: <?php echo ($payment_method == 'btc' ? 'Bitcoin' : $payment_method);?></i><p> 
            <p style="font-size: 12px;"><i>Members Count: <?php echo $event_members;?></i><p>
						<p style="font-size: 17px;">This serves as a confirmation of the booking made on "FetishBNB" website.</p>
									<span class="clear"></span>
								</td>
							</tr>
						</table>

					</td>
				</tr>
			</table>
			</div><!-- /content -->

		</td>
		<td></td>
	</tr>
</table><!-- /BODY -->
</div>
</body>
</html>
