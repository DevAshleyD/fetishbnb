<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Library Make Mail
 *
 * This class handles mail related functionality
 *
 * @package     DA
 * @author      DK
**/

class Make_mail {
	
	public function send($recipient = NULL, $subject = NULL, $message = NULL, $attached_file = NULL)
	{	
		$CI 			=& get_instance();
		
		if(empty($CI->settings->smtp_server) || empty($CI->settings->smtp_username) || empty($CI->settings->smtp_password) || empty($CI->settings->smtp_port) || empty($CI->settings->sender_email))
		{
			return TRUE;
		}
					
		$config = array(
					'smtp_host' => $CI->settings->smtp_server,
					'smtp_port' => $CI->settings->smtp_port,
					'smtp_user' => $CI->settings->smtp_username,
					'smtp_pass' => $CI->settings->smtp_password,
					'crlf' 		=> "\r\n",    							
					'protocol'	=> 'smtp',
				);
		
		// Send email 
		$config['useragent'] 	= $CI->settings->site_name;
		$config['mailtype'] 	= "html";
		$config['newline'] 		= "\r\n";
		$config['charset'] 		= 'utf-8';
		$config['wordwrap'] 	= TRUE;
		
		$CI->load->library('email', $config);

		$CI->email->from($CI->settings->sender_email, $CI->settings->site_name);
		$CI->email->to($recipient);

		$CI->email->subject($subject);
		$CI->email->message($message);
		
		if(isset($attached_file))
		    $CI->email->attach($attached_file);
		    
		$CI->email->send();
	}
}

/*End Make Mail*/