<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Bbooking Controller
 *
 * This class handles booking module functionality
 *
 * @package     classiebit
 * @author      prodpk
*/

class Bbooking extends Public_Controller {

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();

        $this->load->model(array(
                            'course_model',
                            'notifications_model',
                            'users_model',
                            'admin/bbookings_model',
                            'admin/batches_model',
                            'admin/emailtemplates_model',
                            'admin/taxes_model',
                        ));
    }


    /**
	 * index
     */
	function index($courses_title = NULL)
	{
        /* Initialize assets and title */
        $this->add_plugin_theme(array(  
                                "daterangepicker/moment.min.js",
                                "datepicker/bootstrap-datepicker.js", 
                                "fullcalendar/fullcalendar.min.css",
                                "fullcalendar/fullcalendar.min.js",
                            ), 'default')
             ->add_js_theme( "pages/b_booking/index_i18n.js", TRUE );

        // setup page header data
        $this->set_title(lang('menu_courses'));

        $data = $this->includes;

        $courses_title                  = $courses_title ? str_replace('+', ' ', urldecode($courses_title)) : 1;

        $courses_id                    = $this->course_model->get_course_id_by_title($courses_title);
        
        if(empty($courses_id))
            show_404();

        // set courses_id and course_category id in session
        $_SESSION['bookings']['courses_id']             = $courses_id->id;
        $_SESSION['bookings']['course_title']            = $this->course_model->get_title_by_id($courses_id->id, 'courses')->title;
        $_SESSION['bookings']['course_categories_id']    = $courses_id->course_categories_id;
        $_SESSION['bookings']['course_category_title']   = $this->course_model->get_title_by_id($courses_id->course_categories_id, 'course_categories')->title;
        
        // set content data
        $content_data['courses_id']                     = $courses_id->id;
        $content_data['course_categories_id']            = $courses_id->course_categories_id;

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
        $data['content'] = $this->load->view('b_booking', $content_data, TRUE);
		$this->load->view($this->template, $data);
	}

    /**
     * get_batches
     */
    function get_batches()
    {
        /* Validate form input */
        $this->form_validation->set_rules('course_id', sprintf(lang('alert_id') ,lang('b_bookings_course')), 'required|is_natural_no_zero', array('is_natural_no_zero'=>'<span class="loader text-danger">*'.lang('b_bookings_course').'</span>'));

        if($this->form_validation->run() === FALSE)
        {
            echo validation_errors();exit;
        }

        $course_id        = $this->input->post('course_id');

        /* Data */
        $batches         = $this->bbookings_model->get_batches($course_id);
        
        if(empty($batches))
        {
            echo lang('batches_empty');exit;
        }

        echo json_encode($batches);exit;
    }

    /**
     * get_net_fees
     */
    function get_net_fees()
    {
        /* Validate form input */
        $this->form_validation
        ->set_rules('batches_id', sprintf(lang('alert_id') ,lang('b_bookings_batch')), 'required|is_natural_no_zero', array('is_natural_no_zero'=>'<span class="loader text-danger">*'.lang('b_bookings_batch').'</span>'))
        ->set_rules('count_members', lang('b_bookings_count_members'), 'required|is_natural_no_zero');

        if($this->form_validation->run() === FALSE)
        {
            echo validation_errors();exit;
        }

        $batch_id               = $this->input->post('batches_id');
        $count_members          = (int) $this->input->post('count_members');
        
        /* Data */
        $result                 = $this->bbookings_model->get_net_fees($batch_id);

        $data                   = array();
        $data['batch_title']    = $result->batch_title;
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

        // get batch tutors
        $data['tutors']                         = $this->batches_model->get_batches_tutors($batch_id);

        // set fees in session
        $_SESSION['bookings']['batch_title']    = $data['batch_title'];
        $_SESSION['bookings']['fees']           = $data['fees'];
        $_SESSION['bookings']['net_fees']       = $data['net_fees'];
        $_SESSION['bookings']['recurring']      = $data['recurring'];
        $_SESSION['bookings']['booking_fees']   = $data['net_fees'] > $data['fees'] ? $data['net_fees'] : $data['fees'];
        $_SESSION['bookings']['rate_type']      = $data['rate_type'];
        $_SESSION['bookings']['rate']           = $data['rate'];
        $_SESSION['bookings']['net_price']      = $data['net_price'];
        $_SESSION['bookings']['weekdays']       = json_decode($data['weekdays']);
        $_SESSION['bookings']['capacity']       = $result->capacity;
        $_SESSION['bookings']['count_members']  = $count_members;
        $_SESSION['bookings']['batches_id']     = $batch_id;
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
        ->set_rules('batches_id', sprintf(lang('alert_id') ,lang('b_bookings_batch')), 'trim|required|is_natural_no_zero', array('is_natural_no_zero'=>'<span class="loader text-danger">*'.lang('b_bookings_batch').'</span>'))
        ->set_rules('booking_date', lang('b_bookings_booking_date'), 'trim|required')
        ->set_rules('start_time', lang('b_bookings_start_time'), 'trim|required');

        if($this->form_validation->run() === FALSE)
        {
            echo validation_errors();exit;
        }

        $batch_id               = $this->input->post('batches_id');
        $booking_date           = $this->input->post('booking_date');
        $start_time             = $this->input->post('start_time');

        // set booking date and start time
        $_SESSION['bookings']['booking_date']   = $booking_date;
        $_SESSION['bookings']['start_time']     = $start_time;
        
        /* Data */
        $result                 = $this->bbookings_model->get_total_b_bookings($batch_id, $booking_date);
        
        echo json_encode(array('booked_seats'=>$result));exit;
    }   

    /**
     * initiate_booking
     */
    public function initiate_booking()
    {
        if(! $this->session->userdata('logged_in'))
        {
            echo json_encode(array('flag'=>0, 'msg' => lang('c_l_login_first'), 'error_fields'=>json_encode(array())));
            exit;
        }

        // check availability of batch by capacity & pre_booking time
        // check prebooking time from settings (in hour)
        $default_prebook_time = $this->settings->default_prebook_time;
        $booking_date         = date('Y-m-d', strtotime(str_replace('-', '/', $_SESSION['bookings']['booking_date'])));
        $today_date           = date('Y-m-d H:i:s');

        // booking date should not be less than today's date
        if($booking_date < $today_date)
            $this->form_validation->set_rules('booking_older', 'booking_older', 'required', array('required'=>lang('b_bookings_booking_older_date')));

        // calculate no of hours
        $start_time            = $_SESSION['bookings']['start_time'];
        $time_booking          = strtotime($booking_date.' '.$start_time);
        $time_today            = strtotime($today_date);
        $hours                 = round(abs($time_booking - $time_today)/(60*60));
        
        if($hours < $default_prebook_time)
            $this->form_validation->set_rules('booking_late', 'booking_late', 'required', array('required'=>sprintf(lang('b_bookings_booking_late'), $default_prebook_time.' Hours')));                           
        
        // check availability
        $total_bookings       = $this->bbookings_model->get_total_b_bookings($_SESSION['bookings']['batches_id'], $_SESSION['bookings']['booking_date']);
        $capacity             = $_SESSION['bookings']['capacity'];

        if($total_bookings >= $capacity)
            $this->form_validation->set_rules('booking_full', 'booking_full', 'required', array('required'=>lang('b_bookings_booking_full')));       

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

        // data for b_bookings_members table
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

        echo json_encode(array('flag'=>1, 'msg' => lang('c_l_booking_initiate_success'), 'type'  => 'success', 'bookings'=>$_SESSION['bookings']));
        exit;
    }

    /**
     * payment_method
     */
    public function payment_method()    
    {
        if(empty($_SESSION['bookings']) || empty($this->session->userdata('logged_in')))
        {
            $this->session->set_flashdata('error', lang('c_l_pay_access_denied'));
            redirect(base_url('courses'));
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
                redirect(site_url('bbooking/pay_with_stripe'));
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
            $this->session->set_flashdata('error', lang('c_l_pay_access_denied'));
            redirect(base_url('courses'));
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
        $this->paypal_lib->add_field('item_name', $_SESSION['bookings']['batch_title']);
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
            $this->session->set_flashdata('error', lang('c_l_pay_access_denied'));
            redirect(base_url('courses'));
        }
         // setup page header data
        $this->set_title(lang('c_l_pay_with_stripe'));
        $data = $this->includes;

        // load views
        $data['content'] = $this->load->view('stripe/index', NULL, TRUE);
        $this->load->view($this->template, $data);
    }


    /**
     * finish_booking
    */
    public function finish_booking()
    {
        if(empty($_SESSION['bookings']) || empty($this->session->userdata('logged_in')))
        {
            $this->session->set_flashdata('error', lang('c_l_pay_access_denied'));
            redirect(base_url('courses'));
        }

        // data to insert in db table
        // bookings table
        $data                           = array();
        // get booking id from settings
        $data['id']                     = $this->settings->default_starting_booking_id;
        $data['customers_id']           = $this->user['id'];
        $data['batches_id']             = $_SESSION['bookings']['batches_id'];
        $data['courses_id']             = $_SESSION['bookings']['courses_id'];
        // fetch fees and net fees from session 
        $data['course_categories_id']    = $_SESSION['bookings']['course_categories_id'];
        $data['fees']                   = $_SESSION['bookings']['fees'];
        $data['net_fees']               = $_SESSION['bookings']['net_fees'];
        $data['booking_date']           = $_SESSION['bookings']['booking_date'];
        $data['status']                 = 1;
        
        // fetch batch data for static values
        $batch                          = array();
        $batch                          = $this->batches_model->get_batches_by_id($data['batches_id']);

        $data['batch_title']            = $batch->title;
        $data['batch_description']      = $batch->description;
        $data['batch_capacity']         = $batch->capacity;
        $data['batch_weekdays']         = $batch->weekdays;
        $data['batch_recurring']        = $batch->recurring;
        $data['batch_start_date']       = $batch->recurring ? $data['booking_date'] : $batch->start_date;
        $data['batch_end_date']         = $batch->recurring ? $data['booking_date'] : $batch->end_date;
        $data['batch_start_time']       = $batch->start_time;
        $data['batch_end_time']         = $batch->end_time;
        $data['course_title']            = $batch->courses_title;
        $data['course_category_title']   = $batch->course_categories_title;
                
        // fetch customer data for static values
        $customer                       = array();
        $customer                       = $this->users_model->get_users_by_id($data['customers_id']);

        $data['customer_name']          = $customer->first_name.' '.$customer->last_name;
        $data['customer_email']         = $customer->email;
        $data['customer_address']       = $customer->address;
        $data['customer_mobile']        = $customer->mobile;
        
        $taxes                          = $this->taxes_model->get_taxes_by_id($this->settings->default_tax_id);

        // data for b_bookings_payments
        $payments                       = array();
        $payments['b_bookings_id']        = $data['id'];
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

        // data for b_bookings_members table
        $members                        = array();
        foreach($_SESSION['bookings']['members'] as $key => $val)
        {
            $members[$key]['fullname']        = $val['fullname'];
            $members[$key]['email']           = $val['email'];
            $members[$key]['mobile']          = $val['mobile'];
            $members[$key]['b_bookings_id']     = $data['id'];
        }
        
        $flag                           = $this->bbookings_model->save_b_bookings($data, $members, $payments);

        if($_SERVER['HTTP_HOST'] !== 'localhost') 
        {
            $this->load->library('make_mail');
            $email      = $this->emailtemplates_model->get_email_templates_by_id($this->settings->default_b_booking_email_template);

            $message    = str_replace('(t_user_name)', ucwords($this->user['first_name'].' '.$this->user['last_name']), $email->message);
            $message    = str_replace('(t_be_name)', '#'.$_SESSION['bookings']['temp_id'].' - '.$_SESSION['bookings']['batch_title'], $message);
            $message    = str_replace('(t_txn_id)', '#'.$_SESSION['bookings']['txn_id'], $message);
            $message    = str_replace('(t_total_amount)', $_SESSION['bookings']['booking_fees'].' '.$_SESSION['bookings']['currency'], $message);
            
            $this->make_mail->send($this->user['email'], $email->subject, $message);
        }
        
        if($flag)
        {
            $notification   = array(
                'users_id'  => 1,
                'n_type'    => 'bbookings',
                'n_content' => 'noti_new_booking',
                'n_url'     => site_url('admin/bbookings'), 
            );
            $this->notifications_model->save_notifications($notification);

            $this->session->set_flashdata('message', lang('c_l_booking_success'));
            redirect(base_url('bbooking/booking_complete'));
        }
        else
        {
            $this->session->set_flashdata('error', lang('c_l_booking_failed'));
            redirect(base_url('bbooking/booking_complete'));
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
        $this->set_title(lang('c_l_booking_complete'));

        $data = $this->includes;

        // load views
        $data['content'] = $this->load->view('booking_complete', NULL, TRUE);
        $this->load->view($this->template, $data);
    }

}

/* Bbookings controller ends */