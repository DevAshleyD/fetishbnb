<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Charge (Stripe) Controller
 *
 * This class handles stripe payment api functionality
 *
 * @package     classeventie
 * @author      prodpk
*/

class Charge extends Private_Controller {

	function index ()
	{
		if(empty($_POST) || empty($_SESSION['bookings']))
            redirect('');

        if(empty($_POST['stripeToken']) || empty($_POST['stripeTokenType']) || empty($_POST['stripeEmail']))
            redirect('');

		$this->load->model('admin/bbookings_model');
		$this->load->model('admin/ebookings_model');

		try
		{
			$charge = \Stripe\Charge::create(array(
				"source"  		=> $_POST['stripeToken'],
				"amount" 		=> $_SESSION['bookings']['booking_fees'] * 100,
				"currency" 		=> $_SESSION['bookings']['currency'],
				"description" 	=> "Stripe payment for ".$_SESSION['bookings']['booking_fees'].' '.$_SESSION['bookings']['currency']
			));
		}
		catch(\Stripe\Error\Card $e)
		{
			redirect(base_url('charge/cancel'));
		}
		catch (\Stripe\Error\RateLimit $e)
		{
			redirect(base_url('charge/cancel'));
		}
		catch (\Stripe\Error\InvalidRequest $e)
		{
			redirect(base_url('charge/cancel'));
		}
		catch (\Stripe\Error\Authentication $e)
		{
			redirect(base_url('charge/cancel'));
		}
		catch (\Stripe\Error\ApiConnection $e)
		{
			redirect(base_url('charge/cancel'));
		}
		catch (\Stripe\Error\Base $e)
		{
			redirect(base_url('charge/cancel'));
		}
		catch (Exception $e)
		{
			redirect(base_url('charge/cancel'));
		}

		$response 									= $charge->__toArray(true);


				$data                                       = array();
        $data['payer_email']                        = $response['source']['name'];
        $data['payer_id']                           = $response['id'];
        $data['payer_status']                       = $response['paid'];
        $data['payer_name']                         = $response['customer'];
        $data['payer_address']                      = $response['source']['address_line1'].', '.
                                                        $response['source']['address_line2'].', '.
                                                        $response['source']['address_city'].', '.
                                                        $response['source']['address_state'].', '.
                                                        $response['source']['address_country'].', '.
                                                        $response['source']['address_zip'].', '.
                                                        $response['source']['country'];
        $data['txn_id']                             = $response['balance_transaction'];
        $data['currency']                           = $response['currency'];
        $data['protection_eligibility']             = $response['dispute'];
        $data['total_amount']                       = $response['amount'] / 100;
        $data['payment_status']                     = $response['status'];
        $data['payment_type']                       = $response['source']['object'].' '.$response['source']['brand'].' '.$response['source']['funding'];
        $data['item_name']                          = $_SESSION['bookings']['batch_title'];
        $data['item_number']                        = $_SESSION['bookings']['temp_id'];
        $data['quantity']                           = 1;
        $data['txn_type']                           = $response['object'];
        $data['payment_date']                       = date('Y-m-d H:i:s');
        $data['business']                           = 'stripe';
        $data['verify_sign']                        = $response['source']['fingerprint'];

        $_SESSION['bookings']['txn_id']             = $response['balance_transaction'];

        // if event the use event bookings model
        if(isset($_SESSION['bookings']['is_event']))
            $_SESSION['bookings']['transactions_id']    = $this->ebookings_model->save_transactions($data);
        else
            $_SESSION['bookings']['transactions_id']    = $this->bbookings_model->save_transactions($data);

        $_SESSION['bookings']['payment_gateway']    = 'stripe';

        if(isset($_SESSION['bookings']['is_event']))
        	redirect(site_url('ebooking/finish_booking'));
        else
        	redirect(site_url('bbooking/finish_booking'));

	}

	function charge_btc()
	{
		if(empty($_POST) || empty($_SESSION['bookings']))
		{
			redirect('');
		}

					$this->load->model('admin/bbookings_model');
					$this->load->model('admin/ebookings_model');
					$this->load->model('users_model');
					$this->load->model('btc_model');
					$this->load->model('billing_model');

					$payer_billing = $this->billing_model->get_user_billing($this->input->post('payer_id'), TRUE);

			//transaction variables
			$data                                       = array();
			$data['payer_email']                        = $this->user['email'];
			$data['payer_id']                           = $this->input->post('payer_id');
			$data['payer_status']                       = 'Verified';
			$data['payer_name']                         = $payer_billing['billing_name'].' '.$payer_billing['billing_lastname'];
			$data['payer_address']                      = $payer_billing['billing_address_1'].', '.
																										$payer_billing['billing_address_2'].', '.
																										$payer_billing['billing_city'].', '.
																										$payer_billing['billing_state'].', '.
																										$payer_billing['billing_country'].', '.
																										$payer_billing['billing_zip'];
			$data['txn_id']                             = substr( str_shuffle( str_repeat( 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789', 10 ) ), 0, 10 );
			$data['currency']                           = 'BTC'; 
			$data['protection_eligibility']             = '';
			$data['total_amount']                       = $this->input->post('txn_amount');
			$data['payment_status']                     = 'Completed';
			$data['payment_type']                       = 'btc';
			$data['item_name']                          = $_SESSION['bookings']['event_title'];
			$data['item_number']                        = $_SESSION['bookings']['temp_id'];
			$data['quantity']                           = 1;
			$data['txn_type']                           = 'Web BTC';
			$data['payment_date']                       = date('Y-m-d H:i:s');
			$data['business']                           = '';
			$data['verify_sign']                        = '';

			$_SESSION['bookings']['txn_id']             = '';

			$btc_balance = $this->users_model->get_user_btc($this->input->post('payer_id'));

			if($btc_balance < $this->input->post('txn_amount'))
			{
				$this->session->set_flashdata('error', lang('btc_balance_insufficient'));
				redirect(site_url('ebooking/pay_with_btc'));
			}
			else
			{
				//deduct BTC balance from user
				$balance = $btc_balance - $data['total_amount'];
				$this->users_model->save_users(array('btc_balance'=>$balance),$data['payer_id']);

				//register transaction to BTC transactions
				$btc_data = array(
					'event_id'		=> 	$_SESSION['bookings']['events_id'],
					'user_id'			=>	$data['payer_id'],
					'date'				=> 	date('Y-m-d H:i:s'),
					'amount'			=>  $data['total_amount'],
				);

				$this->btc_model->add_btc_transaction($btc_data);

				// if event the use event bookings model
				if(isset($_SESSION['bookings']['is_event']))
				$_SESSION['bookings']['transactions_id']    = $this->ebookings_model->save_transactions($data);
				$_SESSION['bookings']['payment_gateway']    = 'btc';

				if(isset($_SESSION['bookings']['is_event']))
					redirect(site_url('ebooking/finish_booking'));
			}
	}


	function cancel()
    {
        // setup page header data
        $this->set_title(lang('c_l_payment_cancel'));

        $data = $this->includes;

        unset($_SESSION['bookings']);
        unset($_SESSION['redirect_url']);

        // load views
        $data['content'] = $this->load->view('payment/cancel', NULL, TRUE);
        $this->load->view($this->template, $data);
    }
}

?>
