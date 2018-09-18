<?php defined('BASEPATH') OR exit('No direct script access allowed');

/***
* Email templates
* Author: Rob Reyes
*
****/
?>
<?php defined('BASEPATH') OR exit('No direct script access allowed');

/***
* Email templates
* Author: Rob Reyes
*
****/
?><?php defined('BASEPATH') OR exit('No direct script access allowed');

/***
* Email templates
* Author: Rob Reyes
*
****/
?><?php defined('BASEPATH') OR exit('No direct script access allowed');

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
						<h3><?php echo sprintf(lang('welcome_to')).$username;?></h3>
						<p style="font-size: 12px;"><strong>Your login details:</strong></p>
						<p style="font-size: 12px;"><i>Username: <?php echo $username;?></i><p>
						<p style="font-size: 12px;"><i>Password: <?php echo $password;?></i><p>
						<p style="font-size:17px">Please confirm your email address to make sure that members can contact you.</p>
						<a style=" color: #fff; background: #000;padding: 10px 25px;text-decoration: none; margin-top: 10px;display: inline-block;" href="<?php base_url(). 'auth/activate'.$id.'/'.$activation;?>"><?php echo lang('email_activate_link');?></a>
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
