<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Myevents Controller
 *
 * This class handles profile module functionality
 *
 * @package     classiebit
 * @author      prodpk
*/


class Myevents extends Private_Controller {

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();

        // load the users model
        $this->load->model(array(
                            'users_model',
                            'notifications_model',
                            'admin/ebookings_model',
                          ));
    }


    /**
     * index
     */
    function index()
    {
        $this->set_title( lang('menu_my_events'));
        $data = $this->includes;

        $content_data['my_events'] = $this->ebookings_model->get_my_events($this->user['id']);

        // load views
        $data['content'] = $this->load->view('my_events', $content_data, TRUE);
        $this->load->view($this->template, $data);
    }

    /**
     * view_invoice
     */
    public function view_invoice($id = NULL)
    {
        /* Get Data */
        $id                     = (int) $id;
        $result                 = $this->ebookings_model->get_e_bookings_by_id($id, $this->user['id']);

        if(empty($result))
        {
            $this->session->set_flashdata('error', sprintf(lang('alert_not_found') ,lang('menu_booking')));
            redirect(site_url('myevents'));            
        }

        $result_members         = $this->ebookings_model->get_e_bookings_members($id);
        $result_payments        = $this->ebookings_model->get_e_bookings_payments($id);

        $data['e_bookings']     = $result;
        $data['members']        = $result_members;
        $data['payments']       = $result_payments;
        
        $this->load->view('admin/e_bookings/view_invoice', $data);
    }

    /**
   * cancel_booking
     */
  function cancel_booking()
  {
        /* Validate form input */
        $this->form_validation
        ->set_rules('id', sprintf(lang('alert_id'), lang('menu_booking')), 'required|is_natural_no_zero');

        if($this->form_validation->run() === FALSE)
        {
            echo json_encode(array(
                                'flag'  => 0, 
                                'msg'   => validation_errors(), 
                                'type'  => 'fail',
                            ));exit;
        }

        // data to insert in db table
        $data                       = array();
        $id                         = (int) $this->input->post('id');
        
        if(empty($id))
        {
            echo json_encode(array(
                                    'flag'  => 0, 
                                    'msg'   => $this->session->flashdata('message'),
                                    'type'  => 'fail',
                                ));
            exit;
        }

        $result                = $this->ebookings_model->get_user_e_bookings($id, $this->user['id']);

        if(empty($result))
        {
            echo json_encode(array(
                                    'flag'  => 0, 
                                    'msg'   => $this->session->flashdata('message'),
                                    'type'  => 'fail',
                                ));
            exit;
        }

        // check availability of event by capacity & pre_booking time
        // check prebooking time from settings (in hour)
        $booking_date         = date('Y-m-d', strtotime(str_replace('-', '/', $result->booking_date)));
        $today_date           = date('Y-m-d H:i:s');

        // booking date should not be less than today's date
        if($booking_date < $today_date)
            $this->form_validation->set_rules('booking_older', 'booking_older', 'required', array('required'=>lang('e_bookings_booking_older_date')));

        // calculate no of hours
        $start_time            = $result->event_start_time;
        $time_booking          = strtotime($result->booking_date.' '.$start_time);
        $time_today            = strtotime($today_date);
        $hours                 = round(abs($time_booking - $time_today)/(60*60));
        
        if($hours < $this->settings->default_precancel_time)
        {
            echo json_encode(array(
                                    'flag'  => 0, 
                                    'msg'   => sprintf(lang('e_bookings_cancel_late'), $this->settings->default_precancel_time.' Hours'),
                                    'type'  => 'fail',
                                ));
            exit;
        }

        if($result->cancellation)
        {
            $this->session->set_flashdata('message', sprintf(lang('alert_cancellation_already'), lang('menu_booking')));
            echo json_encode(array(
                                'flag'  => 1, 
                                'msg'   => sprintf(lang('alert_cancellation_already'), lang('menu_booking')),
                                'type'  => 'success',
                            ));
            exit;
        }

        $data                   = array('cancellation'=>1);
        
        $flag                   = $this->ebookings_model->cancel_e_bookings($id, $this->user['id'], $data);

        if($flag)
        {
            if($flag == 'already')
            {
                $this->session->set_flashdata('message', sprintf(lang('alert_cancellation_already'), lang('menu_booking')));
                echo json_encode(array(
                                    'flag'  => 1, 
                                    'msg'   => sprintf(lang('alert_cancellation_already'), lang('menu_booking')),
                                    'type'  => 'success',
                                ));
            }
            else
            {
                $notification   = array(
                    'users_id'  => 1,
                    'n_type'    => 'e_cancellation',
                    'n_content' => 'noti_cancel_booking',
                    'n_url'     => site_url('admin/ebookings'), 
                );
                $this->notifications_model->save_notifications($notification);        

                $this->session->set_flashdata('message', sprintf(lang('alert_cancellation_success'), lang('menu_booking')));
                echo json_encode(array(
                                    'flag'  => 1, 
                                    'msg'   => sprintf(lang('alert_cancellation_success'), lang('menu_booking')),
                                    'type'  => 'success',
                                ));
            }

            exit;
        }

        echo json_encode(array(
                            'flag'  => 0, 
                            'msg'   => lang('alert_cancel_fail_1'),
                            'type'  => 'fail',
                        ));
        exit;

        
  }
    

}

/*End Myevents*/