<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Ebooking Controller
 *
 * This class handles booking module functionality
 *
 * @package     classiebit
 * @author      prodpk
*/

class Ebooking extends Public_Controller {

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();

        $this->load->model(array(
                            'notifications_model',
                            'event_model',
                            'users_model',
                            'admin/ebookings_model',
                            'admin/events_model',
                            'admin/emailtemplates_model',
                            'admin/taxes_model',
                            'btc_model',
                        ));
    }


    /**
	 * index
     */
	function index($event_title = NULL)
	{
        /* Initialize assets and title */
        $this->add_plugin_theme(array(
                                "daterangepicker/moment.min.js",
                                "datepicker/bootstrap-datepicker.js",
                                "fullcalendar/fullcalendar.min.css",
                                "fullcalendar/fullcalendar.min.js",
                            ), 'default')
             ->add_js_theme( "pages/e_booking/index_i18n.js", TRUE );

        // setup page header data
        $this->set_title(lang('menu_events'));

        $data = $this->includes;

        $event_title                  = $event_title ? str_replace('+', ' ', urldecode($event_title)) : 1;

        $event                        = $this->event_model->get_event_detail($event_title);

        if(empty($event))
            show_404();

        // set events_id and event_category id in session
        $_SESSION['bookings']['events_id']              = $event->id;
        $_SESSION['bookings']['event_title']            = $event->title;
        $_SESSION['bookings']['event_types_id']         = $event->event_types_id;
        $_SESSION['bookings']['event_type_title']   = $this->event_model->get_title_by_id($event->event_types_id, 'event_types')->title;

        // set content data
        $content_data['events_id']                      = $event->id;
        $content_data['event_types_id']                 = $event->event_types_id;

        // for auto selecting event in calendar
        $content_data['selected_event']                 = $event;
        $content_data['selected_event']->weekdays       = $event->weekdays ? count(json_decode($event->weekdays)) : 0;
        $content_data['selected_event']->description    = '';
        $content_data['selected_event']->images         = '';

        // add more fields
        $content_data['fullname'] = array(
            'name'      => 'fullname[]',
            'type'      => 'text',
            'class'     => 'form-control member-input',
        );
        $content_data['email'] = array(
            'name'      => 'email[]',
            'type'      => 'email',
            'class'     => 'form-control member-input',
        );
        $content_data['mobile'] = array(
            'name'      => 'mobile[]',
            'type'      => 'text',
            'class'     => 'form-control member-input',
        );

        // load views
        $data['content'] = $this->load->view('e_booking', $content_data, TRUE);
		$this->load->view($this->template, $data);
	}

    /**
     * get_events
     */
    function get_events($event_id = NULL, $non_json = FALSE)
    {
        if(!$event_id)
        {
            /* Validate form input */
            $this->form_validation->set_rules('event_id', sprintf(lang('alert_id') ,lang('e_bookings_event')), 'required|is_natural_no_zero', array('is_natural_no_zero'=>'<span class="loader text-danger">*'.lang('e_bookings_event').'</span>'));

            if($this->form_validation->run() === FALSE)
            {
                echo validation_errors();exit;
            }

            $event_id        = $this->input->post('event_id');
        }

        /* Data */
        $events         = $this->ebookings_model->get_events($event_id);

        if(empty($events))
        {
            echo lang('events_empty');exit;
        }

        if($non_json)
        {
            return $events;
        }
        else
        {
            echo json_encode($events);exit;
        }
    }

    /**
     * get_net_fees
     */
    function get_net_fees()
    {
        /* Validate form input */
        $this->form_validation
        ->set_rules('events_id', sprintf(lang('alert_id') ,lang('e_bookings_event')), 'required|is_natural_no_zero', array('is_natural_no_zero'=>'<span class="loader text-danger">*'.lang('e_bookings_event').'</span>'))
        ->set_rules('count_members', lang('e_bookings_count_members'), 'required|is_natural_no_zero');

        if($this->form_validation->run() === FALSE)
        {
            echo validation_errors();exit;
        }

        $event_id               = $this->input->post('events_id');
        $count_members          = (int) $this->input->post('count_members');

        /* Data */
        $result                 = $this->ebookings_model->get_net_fees($event_id);

        $data                   = array();
        $data['event_title']    = $result->event_title;
        $data['title']          = $result->title;
        $data['rate_type']      = $result->rate_type == 'percent' ? '%' : get_default_currency();
        $data['rate']           = $result->rate;
        $data['net_price']      = $result->net_price;
        $data['recurring']      = $result->recurring;
        $data['weekdays']       = $result->weekdays;
        $data['count_members']  = $count_members;

        $data['single_fees']    = $result->fees; // will change in case of including tax
        $result->fees          *= $count_members;

        if($result->net_price == 'including') // decrease value from fees
        {
            if($result->rate_type == 'fixed')
            {
                $data['net_fees']       = $result->fees;
                $data['fees']           = $result->fees - $result->rate;
                $data['single_fees']    = (float) $data['fees']/$count_members;
            }
            else // in case of percent
            {
                $data['net_fees']       = $result->fees;
                $data['fees']           = (float) $result->fees - ( ($result->fees*$result->rate)/100 );
                $data['single_fees']    = (float) $data['fees']/$count_members;
            }
        }
        else // in case of excluding increase value of fees
        {
            if($result->rate_type == 'fixed')
            {
                $data['fees']           = $result->fees;
                $data['net_fees']       = $result->fees + $result->rate;
            }
            else // in case of percent
            {
                $data['fees']           = $result->fees;
                $data['net_fees']       = $result->fees + ( ($result->fees*$result->rate)/100 );
            }
        }

        // get event tutors
        $data['tutors']                         = $this->events_model->get_events_tutors($event_id);

        // set fees in session
        $_SESSION['bookings']['event_title']    = $data['event_title'];
        $_SESSION['bookings']['fees']           = $data['fees'];
        $_SESSION['bookings']['net_fees']       = $data['net_fees'];
        $_SESSION['bookings']['recurring']    = $data['recurring'];
        $_SESSION['bookings']['booking_fees']   = $data['net_fees'] > $data['fees'] ? $data['net_fees'] : $data['fees'];
        $_SESSION['bookings']['rate_type']      = $data['rate_type'];
        $_SESSION['bookings']['rate']           = $data['rate'];
        $_SESSION['bookings']['net_price']      = $data['net_price'];
        $_SESSION['bookings']['weekdays']       = json_decode($data['weekdays']);
        $_SESSION['bookings']['capacity']       = $result->capacity;
        $_SESSION['bookings']['count_members']  = $count_members;
        $_SESSION['bookings']['events_id']      = $event_id;
        $_SESSION['bookings']['currency']       = get_default_currency();

        echo json_encode($data);exit;
    }

    /**
     * get_booked_seats
     */
    function get_booked_seats()
    {
        /* Validate form input */
        $this->form_validation
        ->set_rules('events_id', sprintf(lang('alert_id') ,lang('e_bookings_event')), 'trim|required|is_natural_no_zero', array('is_natural_no_zero'=>'<span class="loader text-danger">*'.lang('e_bookings_event').'</span>'))
        ->set_rules('booking_date', lang('e_bookings_booking_date'), 'trim|required')
        ->set_rules('start_time', lang('e_bookings_start_time'), 'trim|required');

        if($this->form_validation->run() === FALSE)
        {
            echo validation_errors();exit;
        }

        $event_id               = $this->input->post('events_id');
        $booking_date           = $this->input->post('booking_date');
        $start_time             = $this->input->post('start_time');

        // set booking date and start time
        $_SESSION['bookings']['booking_date']   = $booking_date;
        $_SESSION['bookings']['start_time']     = $start_time;

        /* Data */
        $result                 = $this->ebookings_model->get_total_e_bookings($event_id, $booking_date);

        echo json_encode(array('booked_seats'=>$result));exit;
    }

    /**
     * initiate_booking
     */
    public function initiate_booking()
    {
        if(! $this->session->userdata('logged_in'))
        {
            echo json_encode(array('flag'=>0, 'msg' => lang('e_l_login_first'), 'error_fields'=>json_encode(array())));
            exit;
        }

        // check availability of event by capacity & pre_booking time
        // check prebooking time from settings (in hour)
        $default_prebook_time = $this->settings->default_prebook_time;
        $booking_date         = date('Y-m-d', strtotime(str_replace('-', '/', $_SESSION['bookings']['booking_date'])));
        $today_date           = date('Y-m-d H:i:s');

        // booking date should not be less than today's date
        if($booking_date < $today_date)
            $this->form_validation->set_rules('booking_older', 'booking_older', 'required', array('required'=>lang('e_bookings_booking_older_date')));

        // calculate no of hours
        $start_time            = $_SESSION['bookings']['start_time'];
        $time_booking          = strtotime($booking_date.' '.$start_time);
        $time_today            = strtotime($today_date);
        $hours                 = round(abs($time_booking - $time_today)/(60*60));

        if($hours < $default_prebook_time)
            $this->form_validation->set_rules('booking_late', 'booking_late', 'required', array('required'=>sprintf(lang('e_bookings_booking_late'), $default_prebook_time.' Hours')));

        // check availability
        $total_bookings       = $this->ebookings_model->get_total_e_bookings($_SESSION['bookings']['events_id'], $_SESSION['bookings']['booking_date']);
        $capacity             = $_SESSION['bookings']['capacity'];

        if($total_bookings >= $capacity)
            $this->form_validation->set_rules('booking_full', 'booking_full', 'required', array('required'=>lang('e_bookings_booking_full')));

        $this->form_validation
        ->set_rules('fullname[]', lang('users_fullname'), 'trim|required|alpha_numeric_spaces')
        ->set_rules('email[]', lang('users_email'), 'trim|required|valid_email')
        ->set_rules('mobile[]', lang('users_mobile'), 'trim|required');

        if($this->form_validation->run() === FALSE)
        {
            // for fetching specific fields errors in order to view errors on each field seperately
            $error_fields = array();
            foreach($_POST as $key => $val)
            {
                if($key == 'fullname' || $key == 'mobile' || $key == 'email') // for input array fields
                    $key .= '[]';

                if(form_error($key))
                    $error_fields[] = $key;
            }

            echo json_encode(array('flag'=>0, 'msg' => validation_errors(), 'error_fields'=>json_encode($error_fields)));
            exit;
        }

        // data for e_bookings_members table
        $members                        = array();
        $fullname                       = $this->input->post('fullname');
        $email                          = $this->input->post('email');
        $mobile                         = $this->input->post('mobile');
        foreach($fullname as $key => $val)
        {
            $members[$key]['fullname']        = $val;
            $members[$key]['email']           = $email[$key];
            $members[$key]['mobile']          = $mobile[$key];
        }

        $_SESSION['bookings']['members']      = $members;
        $_SESSION['bookings']['temp_id']      = time().rand(1,988);
        $_SESSION['bookings']['is_event']     = 1;


        echo json_encode(array('flag'=>1, 'msg' => lang('e_l_booking_initiate_success'), 'type'  => 'success', 'bookings'=>$_SESSION['bookings']));
        exit;
    }

    /**
     * payment_method
     */
    public function payment_method()
    {
        if(empty($_SESSION['bookings']) || empty($this->session->userdata('logged_in')))
        {
            $this->session->set_flashdata('error', lang('e_l_pay_access_denied'));
            redirect(base_url('events'));
        }

        if(! $_SESSION['bookings']['fees']) // in case of free
        {
            $_SESSION['bookings']['txn_id']             = 'FREE';
            $_SESSION['bookings']['transactions_id']    = 'FREE';
            $_SESSION['bookings']['payment_gateway']    = 'FREE';

            $this->finish_booking();
        }
        else
        {
            $payment_method = $this->input->post('payment_method') ? $this->input->post('payment_method') : 'paypal';

            if($payment_method === 'stripe')
                redirect(site_url('ebooking/pay_with_stripe'));
            if($payment_method === 'btc_credit')
                redirect(site_url('ebooking/pay_with_btc'));
            else
                $this->pay_with_paypal();
        }
    }


    /**
     * pay_with_paypal
     */
    public function pay_with_paypal()
    {
        if(empty($_SESSION['bookings']) || empty($this->session->userdata('logged_in')))
        {
            $this->session->set_flashdata('error', lang('e_l_pay_access_denied'));
            redirect(base_url('events'));
        }
        //Set variables for paypal form
        $returnURL = base_url().'paypal/success'; //payment success url
        $cancelURL = base_url().'paypal/cancel'; //payment cancel url
        $notifyURL = base_url().'paypal/ipn'; //ipn url

        $logo = base_url().'themes/default/img/logo.png';

        $this->load->library('paypal_lib');

        $this->paypal_lib->add_field('business', $this->settings->pp_registered_email);
        $this->paypal_lib->add_field('return', $returnURL);
        $this->paypal_lib->add_field('cancel_return', $cancelURL);
        $this->paypal_lib->add_field('notify_url', $notifyURL);
        $this->paypal_lib->add_field('item_name', $_SESSION['bookings']['event_title']);
        $this->paypal_lib->add_field('item_number', $_SESSION['bookings']['temp_id']);
        $this->paypal_lib->add_field('custom', $this->user['id']);
        $this->paypal_lib->add_field('amount', $_SESSION['bookings']['booking_fees']);
        $this->paypal_lib->add_field('currency_code', $_SESSION['bookings']['currency']);
        $this->paypal_lib->add_field($this->security->get_csrf_token_name(), $this->security->get_csrf_hash());
        $this->paypal_lib->paypal_auto_form();
    }

    /**
     * pay_with_stripe
     */
    public function pay_with_stripe()
    {
        if(empty($_SESSION['bookings']) || empty($this->session->userdata('logged_in')))
        {
            $this->session->set_flashdata('error', lang('e_l_pay_access_denied'));
            redirect(base_url('events'));
        }
         // setup page header data
        $this->set_title(lang('e_l_pay_with_stripe'));
        $data = $this->includes;

        // load views
        $data['content'] = $this->load->view('stripe/index', NULL, TRUE);
        $this->load->view($this->template, $data);
    }

    public function pay_with_btc(){
      /*Pay with btc credits*/
      if(empty($_SESSION['bookings']) || empty($this->session->userdata('logged_in')))
      {
          $this->session->set_flashdata('error', lang('e_l_pay_access_denied'));
          redirect(base_url('events'));
      }

      //get user current btc data
      $user_transactions = $this->btc_model->get_user_transaction($this->user['id']);


      // setup page header data
     $this->set_title(lang('e_bookings_payment_type_btc'));
     $data = $this->includes;

     $currency = $_SESSION['bookings']['currency'];

     $api_url = 'http://api.coindesk.com/v1/bpi/currentprice/'.$currency.'json'; //API URL for the BTC conversion

       //  Initiate curl
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_URL,$api_url);
      $result=curl_exec($ch);
      curl_close($ch);

     $btc_data = json_decode($result, true);

     $btc_exchange = $btc_data['bpi'][$currency];

     //content Data
     $content_data = array(
       'user'           =>  $this->user,
       'transactions'   =>  $user_transactions,
       'btc_balance'    =>  $this->users_model->get_user_btc($this->user['id']),
       'details'        =>  $_SESSION['bookings'],
       'btc_exchange'   =>  $btc_exchange,
     );

     // load views
     $data['content'] = $this->load->view('btc/index', $content_data, TRUE);
     $this->load->view($this->template, $data);
    }



    /**
     * finish_booking
    */
    public function finish_booking()
    {
        if(empty($_SESSION['bookings']) || empty($this->session->userdata('logged_in')))
        {
            $this->session->set_flashdata('error', lang('e_l_pay_access_denied'));
            redirect(base_url('events'));
        }

        // data to insert in db table
        // bookings table
        $data                           = array();
        // get booking id from settings
        $data['id']                     = $this->settings->default_starting_booking_id;
        $data['customers_id']           = $this->user['id'];
        $data['events_id']              = $_SESSION['bookings']['events_id'];
        $data['events_id']              = $_SESSION['bookings']['events_id'];
        // fetch fees and net fees from session
        $data['event_types_id']         = $_SESSION['bookings']['event_types_id'];
        $data['fees']                   = $_SESSION['bookings']['fees'];
        $data['net_fees']               = $_SESSION['bookings']['net_fees'];
        $data['booking_date']           = $_SESSION['bookings']['booking_date'];
        $data['status']                 = 1;

        // fetch event data for static values
        $event                          = array();
        $event                          = $this->events_model->get_events_by_id($data['events_id']);

        $data['event_title']            = $event->title;
        $data['event_description']      = $event->description;
        $data['event_capacity']         = $event->capacity;
        $data['event_weekdays']         = $event->weekdays;
        $data['event_recurring']        = $event->recurring;
        $data['event_start_date']       = $event->recurring ? $data['booking_date'] : $event->start_date;
        $data['event_end_date']         = $event->recurring ? $data['booking_date'] : $event->end_date;
        $data['event_start_time']       = $event->start_time;
        $data['event_end_time']         = $event->end_time;
        $data['event_type_title']       = $event->event_types_title;

        // fetch customer data for static values
        $customer                       = array();
        $customer                       = $this->users_model->get_users_by_id($data['customers_id']);

        $data['customer_name']          = $customer->first_name.' '.$customer->last_name;
        $data['customer_email']         = $customer->email;
        $data['customer_address']       = $customer->address;
        $data['customer_mobile']        = $customer->mobile;

        $taxes                          = $this->taxes_model->get_taxes_by_id($this->settings->default_tax_id);

        // data for e_bookings_payments
        $payments                       = array();
        $payments['e_bookings_id']        = $data['id'];
        $payments['paid_amount']        = 0;
        $payments['total_amount']       = $_SESSION['bookings']['net_fees'];
        $payments['payment_type']       = $_SESSION['bookings']['payment_gateway'];
        $payments['transactions_id']    = $_SESSION['bookings']['transactions_id'];
        $payments['payment_status']     = 1;
        $payments['currency']           = $_SESSION['bookings']['currency'];
        $payments['tax_title']          = $taxes->title;
        $payments['tax_rate_type']      = $taxes->rate_type;
        $payments['tax_rate']           = $taxes->rate;
        $payments['tax_net_price']      = $taxes->net_price;

        // data for e_bookings_members table
        $members                        = array();
        foreach($_SESSION['bookings']['members'] as $key => $val)
        {
            $members[$key]['fullname']        = $val['fullname'];
            $members[$key]['email']           = $val['email'];
            $members[$key]['mobile']          = $val['mobile'];
            $members[$key]['e_bookings_id']   = $data['id'];
        }

        $flag                           = $this->ebookings_model->save_e_bookings($data, $members, $payments);

        $mail_content = array(
          'event_title'      =>     $data['event_title'],
          'event_start_date' =>     $data['event_start_date'],
          'event_start_time' =>     $data['event_start_time'],
          'net_fees'         =>     $data['net_fees'],
          'currency'         =>     $_SESSION['bookings']['currency'],
          'payment_method'   =>     $_SESSION['bookings']['payment_gateway'],
          'event_members'    =>     count($_SESSION['bookings']['members']),
        );

        if($_SERVER['HTTP_HOST'] !== 'localhost')
        {
          // send email
              $this->load->library('email');

              // in case of smtp
              if($this->settings->smtp_server && $this->settings->smtp_username && $this->settings->smtp_password && $this->settings->smtp_port)
              {
                  $config['protocol']     = 'smtp';
                  $config['smtp_host']    = $this->settings->smtp_server;
                  $config['smtp_user']    = $this->settings->smtp_username;
                  $config['smtp_pass']    = $this->settings->smtp_password;
                  $config['smtp_port']    = $this->settings->smtp_port;

                  $this->email->initialize($config);
              }

          $message = $this->load->view($this->config->item('email_templates', 'ion_auth').$this->config->item('email_booking_confirm', 'ion_auth'), $mail_content, true);
          $this->email->clear();
          $this->email->from($this->config->item('sender_email', 'ion_auth'), $this->config->item('sender_name', 'ion_auth'));
          $this->email->to($customer->email);
          $this->email->subject($this->config->item('site_title', 'ion_auth') . ' - ' . $this->lang->line('email_book_subject'));
          $this->email->message($message);
          $this->email->send();
        }

        if($flag)
        {
            $notification   = array(
                'users_id'  => 1,
                'n_type'    => 'ebookings',
                'n_content' => 'noti_new_booking',
                'n_url'     => site_url('admin/ebookings'),
            );

            //add credit earned by booking
            $current_earnings = $this->events_model->get_events_by_id($data['events_id'])->event_earned;
            $update_amount = $current_earnings + $data['net_fees'];
            $this->event_model->update_event_credits($data['events_id'], $update_amount);

            $this->notifications_model->save_notifications($notification);
            $this->session->set_flashdata('message', lang('e_l_booking_success'));
            redirect(base_url('ebooking/booking_complete'));
        }
        else
        {
            $this->session->set_flashdata('error', lang('e_l_booking_failed'));
            redirect(base_url('ebooking/booking_complete'));
        }

    }


    /**
     * booking_complete
    */
    public function booking_complete()
    {
        if(empty($_SESSION['bookings']))
            redirect('');

        unset($_SESSION['bookings']);
        unset($_SESSION['redirect_url']);
         // setup page header data
        $this->set_title(lang('e_l_booking_complete'));

        $data = $this->includes;

        // load views
        $data['content'] = $this->load->view('booking_complete', NULL, TRUE);
        $this->load->view($this->template, $data);
    }

}

/* Ebookings controller ends */
