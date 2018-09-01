<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Paypal Controller
 *
 * This class handles booking module functionality
 *
 * @package     classiebit
 * @author      prodpk
*/

class Paypal extends Private_Controller {
     
    function  __construct()
    {
        parent::__construct();
        $this->load->library('paypal_lib');
        $this->load->model('admin/bbookings_model');
        $this->load->model('admin/ebookings_model');
    }
     
    function success()
    {
        if(empty($_REQUEST) || empty($_SESSION['bookings']))
            redirect('');

        if(!empty($_REQUEST)) // if request is not empty then check the item number in both request and session
            if($_REQUEST['item_number'] !== $_SESSION['bookings']['temp_id'])
                redirect('');

        $data                                       = array();
        $data['payer_email']                        = $_REQUEST['payer_email'];
        $data['payer_id']                           = $_REQUEST['payer_id'];
        $data['payer_status']                       = $_REQUEST['payer_status'];
        $data['payer_name']                         = $_REQUEST['first_name'].' '.$_REQUEST['last_name'];
        $data['payer_address']                      = $_REQUEST['address_name'].', '.
                                                        $_REQUEST['address_street'].', '.
                                                        $_REQUEST['address_city'].', '.
                                                        $_REQUEST['address_state'].', '.
                                                        $_REQUEST['address_country_code'].', '.
                                                        $_REQUEST['address_zip'].', '.
                                                        $_REQUEST['residence_country'];
        $data['txn_id']                             = $_REQUEST['txn_id'];
        $data['currency']                           = $_REQUEST['mc_currency'];
        $data['protection_eligibility']             = $_REQUEST['protection_eligibility'];
        $data['total_amount']                       = $_REQUEST['payment_gross'];
        $data['payment_status']                     = $_REQUEST['payment_status'];
        $data['payment_type']                       = $_REQUEST['payment_type'];
        $data['item_name']                          = $_REQUEST['item_name'];
        $data['item_number']                        = $_REQUEST['item_number'];
        $data['quantity']                           = $_REQUEST['quantity'];
        $data['txn_type']                           = $_REQUEST['txn_type'];
        $data['payment_date']                       = $_REQUEST['payment_date'];
        $data['business']                           = $_REQUEST['business'];
        $data['notify_version']                     = $_REQUEST['notify_version'];
        $data['verify_sign']                        = $_REQUEST['verify_sign'];

        $_SESSION['bookings']['txn_id']             = $_REQUEST['txn_id'];

        // if event the use event bookings model 
        if(isset($_SESSION['bookings']['is_event']))
            $_SESSION['bookings']['transactions_id']    = $this->ebookings_model->save_transactions($data);
        else
            $_SESSION['bookings']['transactions_id']    = $this->bbookings_model->save_transactions($data);

        $_SESSION['bookings']['payment_gateway']    = 'paypal';

        if(isset($_SESSION['bookings']['is_event']))
            redirect(base_url('ebooking/finish_booking'));
        else
            redirect(base_url('bbooking/finish_booking'));

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
     
    function ipn()
    {
        //paypal return transaction details array
        $paypalInfo    = $this->input->post();

        $paypalURL = $this->paypal_lib->paypal_url;        
        $result    = $this->paypal_lib->curlPost($paypalURL,$paypalInfo);
        
        //check whether the payment is verified
        if(preg_match("/VERIFIED/i",$result))
        {
            //insert the transaction data into the database
            //$this->product->insertTransaction($data);
        }
    }
}