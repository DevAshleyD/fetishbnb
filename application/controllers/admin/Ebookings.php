<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Ebookings Controller
 *
 * This class handles e_bookings module functionality
 *
 * @package     classiebit
 * @author      prodpk
*/

class Ebookings extends Admin_Controller {

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();

        $this->load->model('admin/ebookings_model');
        $this->load->model('admin/events_model');
        $this->load->model('admin/taxes_model');
        $this->load->model('users_model');
        $this->load->model('settings_model');

        /* Page Title */
        $this->set_title( lang('menu_e_bookings') );
    }

    /**
     * index
     */
    function index()
    {
        if(!$this->acl->get_method_permission($_SESSION['groups_id'], 'ebookings', 'p_view'))
        {
            $this->session->set_flashdata('error', sprintf(lang('manage_acl_permission_no'), lang('menu_e_booking').' '.lang('manage_acl_view')));
            redirect($this->uri->segment(1).'/dashboard');
        }

        /* Initialize assets */
        $this->include_index_plugins();
        $data = $this->includes;

        // Table Header
        $data['t_headers']  = array(
                                '#',
                                lang('e_bookings_event'),
                                lang('e_bookings_customer'),
                                lang('e_bookings_booking_date'),
                                lang('common_updated'),
                                lang('common_cancellation'),
                                lang('common_status'),
                                lang('action_action'),
                            );

        // load views
        $content['content'] = $this->load->view('admin/index', $data, TRUE);
        $this->load->view($this->template, $content);
    }

    /**
     * ajax_list
    */
    public function ajax_list()
    {
        $this->load->library('datatables');

        $table              = 'e_bookings';        
        $columns            = array(
                                "$table.id",
                                "$table.event_title",
                                "$table.customer_name",
                                "$table.booking_date",
                                "$table.cancellation",
                                "$table.status",
                                "$table.date_updated",
                            );
        $columns_order      = array(
                                "#",
                                "$table.event_title",
                                "$table.customer_name",
                                "$table.booking_date",
                                "$table.date_updated",
                                "$table.cancellation",
                                "$table.status",
                            );
        $columns_search     = array(
                                'event_title',
                                'customer_name',
                            );
        $order              = array('date_updated' => 'DESC');
        
        $result             = $this->datatables->get_datatables($table, $columns, $columns_order, $columns_search, $order);
        $data               = array();
        $no                 = $_POST['start'];
        
        foreach ($result as $val) 
        {
            $no++;
            $row            = array();
            $row[]          = $no;
            $row[]          = mb_substr($val->event_title, 0, 30, 'utf-8');
            $row[]          = $val->customer_name;
            $row[]          = date('g:iA d/m/y', strtotime($val->booking_date));
            $row[]          = date('g:iA d/m/y', strtotime($val->date_updated));
            $row[]          = $val->cancellation == 1 ? '<span class="label label-info">'.lang('common_cancellation_pending').'</span>' : ($val->cancellation == 2 ? '<span class="label label-warning">'.lang('common_cancellation_approved').'</span>' : ($val->cancellation == 3 ? '<span class="label label-success">'.lang('common_cancellation_refunded') : '<span class="label label-default">'.lang('common_cancellation_disabled')).'</span>');
            $row[]          = status_switch($val->status, $val->id);
            $row[]          = action_buttons('ebookings', $val->id, mb_substr($val->event_title, 0, 20, 'utf-8'), lang('menu_e_booking'));
            $data[]         = $row;
        }
 
        $output             = array(
                                "draw"              => $_POST['draw'],
                                "recordsTotal"      => $this->datatables->count_all(),
                                "recordsFiltered"   => $this->datatables->count_filtered(),
                                "data"              => $data,
                            );
        
        //output to json format
        echo json_encode($output);exit;
    }

    /**
     * form
    */
    public function form($id = NULL)
    {
        /* Initialize assets */
        $this
        ->add_plugin_theme(array(   
                                "tinymce/tinymce.min.js",
                                "daterangepicker/daterangepicker.css",
                                "daterangepicker/moment.min.js",
                                "daterangepicker/daterangepicker.js",
                                "fullcalendar/fullcalendar.min.css",
                                'fullcalendar/fullcalendar.print.min.css" media="print',
                                "fullcalendar/fullcalendar.min.js",
                            ), 'admin')
        ->add_js_theme( "pages/e_bookings/form_i18n.js", TRUE );
        $data                       = $this->includes;

        // in case of edit
        $e_bookings                 = array();
        $id                         = (int) $id;
        if($id)
        {
            $result                 = $this->ebookings_model->get_e_bookings_by_id($id);

            if(empty($result))
            {
                $this->session->set_flashdata('error', sprintf(lang('alert_not_found') ,lang('menu_e_booking')));
                redirect($this->uri->segment(1).'/'.$this->uri->segment(2));            
            }

            // hidden field for e_bookings id in case of update
            $data['id']             = $result->id; 

            // for showing selected event in calendar
            $data['selected_event']                 = $result;
            $data['selected_event']->event_description = '';
            $data['selected_event']->event_weekdays = count(json_decode($result->event_weekdays));

            $result_members         = $this->ebookings_model->get_e_bookings_members($id);
            $data['members']        = $result_members;
            $result_payments        = $this->ebookings_model->get_e_bookings_payments($id);
        }

        // get user ids by group customers = 3
        $cus_ids                          = array();
        foreach($this->ion_auth->get_users_by_group(3)->result() as $val)
            $cus_ids[] = $val->user_id;
        
        // render customers dropdown
        $data['customers']['0']     = '-- '.lang('e_bookings_customer').' --';
        $customers                  = $this->events_model->get_users_dropdown($cus_ids);
        foreach($customers as $val)
            $data['customers'][$val->id] = $val->first_name.' '.$val->last_name.' ('.$val->username.')';
        
        $data['customer']   = array(
            'name'          => 'customers',
            'id'            => 'customers',
            'class'         => 'form-control show-tick',
            'data-live-search'=>"true",
            'options'       => $data['customers'],
            'selected'      => $this->form_validation->set_value('customers', !empty($result->customers_id) ? $result->customers_id : ''),
        );
        
        // render event_types dropdown
        $event_types                = $this->events_model->get_event_types_dropdown();
        $data['event_types_o']['0'] = '-- '.lang('e_bookings_event_type').' --';
        foreach($event_types as $val)
            $data['event_types_o'][$val->id] = $val->title;
        
        $data['event_types']   = array(
            'name'          => 'event_types',
            'id'            => 'event_types',
            'class'         => 'event_types form-control show-tick text-capitalize',
            'data-live-search'=>"true",
            'options'       => $data['event_types_o'],
            'selected'      => $this->form_validation->set_value('event_types', !empty($result->event_types_id) ? $result->event_types_id : ''),
        );
        $data['fees']      = array(
            'name'          => 'fees',
            'id'            => 'fees',
            'type'          => 'number',
            'readonly'      => 'readonly',
            'class'         => 'form-control',
            'value'         => $this->form_validation->set_value('fees', !empty($result->fees) ? $result->fees : 0),
        );
        $data['net_fees']      = array(
            'name'          => 'net_fees',
            'id'            => 'net_fees',
            'type'          => 'number',
            'readonly'      => 'readonly',
            'class'         => 'form-control',
            'value'         => $this->form_validation->set_value('net_fees', !empty($result->net_fees) ? $result->net_fees : 0),
        );
        $data['payment_type']     = array(
            'name'          => 'payment_type',
            'id'            => 'payment_type',
            'class'         => 'form-control show-tick',
            'options'       => array('locally' => lang('e_bookings_payment_type_locally')),
            'selected'      => $this->form_validation->set_value('payment_type', !empty($result_payments->payment_type) ? $result_payments->payment_type : ''),
        );
        $data['payment_status']     = array(
            'name'          => 'payment_status',
            'id'            => 'payment_status',
            'class'         => 'form-control show-tick',
            'options'       => array(
                                    '0' => lang('e_bookings_payment_status_pending'),
                                    '1' => lang('e_bookings_payment_status_successful'),
                                    '2' => lang('e_bookings_payment_status_failed'),
                                ),
            'selected'      => $this->form_validation->set_value('payment_status', !empty($result_payments->payment_status) ? $result_payments->payment_status : 0),
        );
        $data['cancellation']     = array(
            'name'          => 'cancellation',
            'id'            => 'cancellation',
            'class'         => 'form-control show-tick',
            'options'       => array(
                                    '0' => lang('common_cancellation_disabled'),
                                    '1' => lang('common_cancellation_pending'),
                                    '2' => lang('common_cancellation_approved'),
                                    '3' => lang('common_cancellation_refunded'),
                                ),
            'selected'      => $this->form_validation->set_value('cancellation', !empty($result->cancellation) ? $result->cancellation : 0),
        );
        $data['status']     = array(
            'name'          => 'status',
            'id'            => 'status',
            'class'         => 'form-control show-tick',
            'options'       => array('1' => lang('common_status_active'), '0' => lang('common_status_inactive')),
            'selected'      => $this->form_validation->set_value('status', !empty($result->status) ? $result->status : 0),
        );
        // add more fields
        $data['fullname'] = array(
            'name'      => 'fullname[]',
            'type'      => 'text',
            'class'     => 'form-control',
        );
        $data['email'] = array(
            'name'      => 'email[]',
            'type'      => 'email',
            'class'     => 'form-control',
        );
        $data['mobile'] = array(
            'name'      => 'mobile[]',
            'type'      => 'text',
            'class'     => 'form-control',
        );
        
        /* Load Template */
        $content['content']    = $this->load->view('admin/e_bookings/form', $data, TRUE);
        $this->load->view($this->template, $content);
        
    }

    /**
     * save
    */
    public function save()
    {
        if(! empty($_POST['id'])) // if updating booking then only update some fields
        {
            if(!$this->acl->get_method_permission($_SESSION['groups_id'], 'ebookings', 'p_edit'))
            {
                echo '<p>'.sprintf(lang('manage_acl_permission_no'), lang('manage_acl_edit')).'</p>';exit;
            }
            $this->form_validation
            ->set_rules('id', sprintf(lang('alert_id'), lang('menu_e_booking')), 'trim|required|is_natural_no_zero')
            ->set_rules('payment_status', lang('e_bookings_payment_status'), 'trim|required|in_list[0,1,2]')
            ->set_rules('cancellation', lang('common_cancellation'), 'trim|required|in_list[0,1,2,3]')
            ->set_rules('status', lang('common_status'), 'required|in_list[0,1]');

            if($this->form_validation->run() === FALSE)
            {
                // for fetching specific fields errors in order to view errors on each field seperately
                $error_fields = array();
                foreach($_POST as $key => $val)
                    if(form_error($key))
                        $error_fields[] = $key;
                
                echo json_encode(array('flag'=>0, 'msg' => validation_errors(), 'error_fields'=>json_encode($error_fields)));
                exit;
            }

            $id                         = (int) $this->input->post('id');
            
            $data                       = array();
            $data['cancellation']       = $this->input->post('cancellation');
            $data['status']             = $this->input->post('status');

            $payments                   = array();
            $payments['payment_status'] = $this->input->post('payment_status');

            $flag                           = $this->ebookings_model->save_e_bookings($data, array(), $payments, $id);

            if($flag)
            {
                // add cancellation notification when booking cancellation update
                if($data['cancellation'])
                {
                    $notification   = array(
                        'users_id'  => $this->user['id'],
                        'n_type'    => 'e_cancellation',
                        'n_content' => 'noti_cancel_booking',
                        'n_url'     => site_url('admin/ebookings'), 
                    );
                    $this->notifications_model->save_notifications($notification);        
                }

                $this->session->set_flashdata('message', sprintf(lang('alert_update_success'), lang('menu_e_booking')));
                echo json_encode(array(
                                        'flag'  => 1, 
                                        'msg'   => sprintf(lang('alert_update_success'), lang('menu_e_booking')),
                                        'type'  => 'success',
                                    ));
                exit;
            }
            
            $this->session->set_flashdata('error', sprintf(lang('alert_update_fail'), lang('menu_e_booking')));
            echo json_encode(array(
                                    'flag'  => 1, 
                                    'msg'   => sprintf(lang('alert_update_fail'), lang('menu_e_booking')),
                                    'type'  => 'fail',
                                ));
            exit;
        }
        else
        {
            if(!$this->acl->get_method_permission($_SESSION['groups_id'], 'ebookings', 'p_add'))
            {
                echo '<p>'.sprintf(lang('manage_acl_permission_no'), lang('manage_acl_add')).'</p>';exit;
            }
        }

        /* Validate form input */
        $this->form_validation
        ->set_rules('customers', lang('e_bookings_customer'), 'trim|required|is_natural_no_zero')
        ->set_rules('event_types', lang('e_bookings_event_type'), 'trim|required|is_natural_no_zero')
        ->set_rules('events_id', lang('e_bookings_event'), 'trim|required|is_natural_no_zero')
        ->set_rules('booking_date', lang('e_bookings_booking_date'), 'trim|required')
        ->set_rules('start_time', lang('e_bookings_start_time'), 'trim|required')
        ->set_rules('fullname[]', lang('users_fullname'), 'trim|required|alpha_numeric_spaces')
        ->set_rules('email[]', lang('users_email'), 'trim|required|valid_email')
        ->set_rules('mobile[]', lang('users_email'), 'trim|required')
        ->set_rules('fees', lang('e_bookings_fees'), 'trim|required|numeric')
        ->set_rules('net_fees', lang('e_bookings_net_fees'), 'trim|required|numeric')
        ->set_rules('payment_type', lang('e_bookings_payment_type'), 'trim|required|in_list[locally,paypal,stripe]')
        ->set_rules('payment_status', lang('e_bookings_payment_status'), 'trim|required|in_list[0,1,2]')
        ->set_rules('status', lang('common_status'), 'required|in_list[0,1]');

        // check if customer has already booked the event
        if(isset($_POST['customers']) && isset($_POST['events_id']) && isset($_POST['booking_date']) && isset($_POST['start_time'])) 
        {
            if($_POST['customers'] && $_POST['events_id'] && $_POST['booking_date'] && $_POST['start_time'])
            {
                // check availability of event by capacity & pre_booking time
                // check prebooking time from settings (in hour)
                $default_prebook_time = $this->settings_model->get_setting_by_name('default_prebook_time')->value;
                $booking_date         = date('Y-m-d', strtotime(str_replace('-', '/', $this->input->post('booking_date'))));
                $today_date           = date('Y-m-d H:i:s');

                // booking date should not be less than today's date
                if($booking_date < $today_date)
                    $this->form_validation->set_rules('booking_older', 'booking_older', 'required', array('required'=>lang('e_bookings_booking_older_date')));

                // calculate no of hours
                $start_time            = $this->input->post('start_time');
                $time_booking          = strtotime($booking_date.' '.$start_time);
                $time_today            = strtotime($today_date);
                $hours                 = round(abs($time_booking - $time_today)/(60*60));
                
                if($hours < $default_prebook_time)
                    $this->form_validation->set_rules('booking_late', 'booking_late', 'required', array('required'=>sprintf(lang('e_bookings_booking_late'), $default_prebook_time.' Hours')));                           
                
                // check availability
                $total_e_bookings       = $this->ebookings_model->get_total_e_bookings($this->input->post('events_id'), $this->input->post('booking_date'));
                $capacity             = $_SESSION['e_bookings']['capacity'];

                if($total_e_bookings >= $capacity)
                    $this->form_validation->set_rules('booking_full', 'booking_full', 'required', array('required'=>lang('e_bookings_booking_full')));
            }
        }

        if($this->form_validation->run() === FALSE)
        {
            // for fetching specific fields errors in order to view errors on each field seperately
            $error_fields = array();
            foreach($_POST as $key => $val)
            {
                if($key == 'fullname' || $key == 'mobile' || $key == 'email' || $key == 'category') // for input array fields
                    $key .= '[]';
                
                if(form_error($key))
                    $error_fields[] = $key;
            } 
            
            echo json_encode(array('flag'=>0, 'msg' => validation_errors(), 'error_fields'=>json_encode($error_fields)));
            exit;
        }

        
        // data to insert in db table
        // e_bookings table
        $data                           = array();
        // get booking id from settings
        $data['id']                     = $this->settings_model->get_setting_by_name('default_starting_booking_id')->value;
        $data['customers_id']           = $this->input->post('customers');
        $data['events_id']              = $this->input->post('events_id');
        $data['event_types_id']         = $this->input->post('event_types');
        // fetch fees and net fees from session 
        $data['fees']                   = $_SESSION['e_bookings']['fees'];
        $data['net_fees']               = $_SESSION['e_bookings']['net_fees'];
        $data['booking_date']           = $this->input->post('booking_date');
        $data['status']                 = $this->input->post('status');
        
        // fetch event data for static values
        $event                          = array();
        $event                          = $this->events_model->get_events_by_id($data['events_id']);

        // check if event id is not correct
        if(empty($event)) 
        {
            echo json_encode(array('flag'=>0, 'msg' => sprintf(lang('alert_not_found'), lang('e_bookings_event')), 'error_fields'=>json_encode(array(0=>'events_id'))));
            exit;
        }

        $data['event_title']            = $event->title;
        $data['event_description']      = $event->description;
        $data['event_capacity']         = $event->capacity;
        $data['event_weekdays']         = $event->weekdays;
        $data['event_recurring']        = $event->recurring;
        $data['event_start_date']       = $event->recurring ? $data['booking_date'] : $event->start_date;
        $data['event_end_date']         = $event->recurring ? $data['booking_date'] : $event->end_date;;
        $data['event_start_time']       = $event->start_time;
        $data['event_end_time']         = $event->end_time;
        $data['event_type_title']       = $event->event_types_title;
                
        // fetch customer data for static values
        $customer                       = array();
        $customer                       = $this->users_model->get_users_by_id($data['customers_id']);

        // check if event id is not correct
        if(empty($customer)) 
        {
            echo json_encode(array('flag'=>0, 'msg' => sprintf(lang('alert_not_found'), lang('e_bookings_customer')), 'error_fields'=>json_encode(array(0=>'customers'))));
            exit;
        }

        $data['customer_name']          = $customer->first_name.' '.$customer->last_name;
        $data['customer_email']         = $customer->email;
        $data['customer_address']       = $customer->address;
        $data['customer_mobile']        = $customer->mobile;
        
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
            $members[$key]['e_bookings_id']     = $data['id'];
        }

        $taxes                          = $this->taxes_model->get_taxes_by_id($this->settings->default_tax_id);

        // data for e_bookings_payments
        $payments                       = array();
        $payments['e_bookings_id']      = $data['id'];
        $payments['paid_amount']        = 0; 
        $payments['total_amount']       = $_SESSION['e_bookings']['net_fees']; 
        $payments['payment_type']       = $this->input->post('payment_type');
        $payments['payment_status']     = $this->input->post('payment_status');
        $payments['currency']           = get_default_currency();
        $payments['tax_title']          = $taxes->title;
        $payments['tax_rate_type']      = $taxes->rate_type;
        $payments['tax_rate']           = $taxes->rate;
        $payments['tax_net_price']      = $taxes->net_price;
        
        $flag                           = $this->ebookings_model->save_e_bookings($data, $members, $payments);

        if($flag)
        {
            // add batch notification when new batch inserted
            $notification   = array(
                'users_id'  => $this->user['id'],
                'n_type'    => 'ebookings',
                'n_content' => 'noti_new_booking',
                'n_url'     => site_url('admin/ebookings'), 
            );
            $this->notifications_model->save_notifications($notification);    

            $this->session->set_flashdata('message', sprintf(lang('alert_insert_success'), lang('menu_e_booking')));
            echo json_encode(array(
                                    'flag'  => 1, 
                                    'msg'   => sprintf(lang('alert_insert_success'), lang('menu_e_booking')),
                                    'type'  => 'success',
                                ));
            exit;
        }
        
        $this->session->set_flashdata('error', sprintf(lang('alert_insert_fail'), lang('menu_e_booking')));
        echo json_encode(array(
                                'flag'  => 1, 
                                'msg'   => sprintf(lang('alert_insert_fail'), lang('menu_e_booking')),
                                'type'  => 'fail',
                            ));
        exit;
    }

    /**
     * view
     */
    public function view($id = NULL, $print = NULL)
    {
        if(!$this->acl->get_method_permission($_SESSION['groups_id'], 'ebookings', 'p_view'))
        {
            $this->session->set_flashdata('error', sprintf(lang('manage_acl_permission_no'), lang('menu_e_booking').' '.lang('manage_acl_view')));
            redirect($this->uri->segment(1).'/dashboard');
        }
        
        /* Initialize Assets */
        $data                   = $this->includes;

        /* Get Data */
        $id                     = (int) $id;
        $result                 = $this->ebookings_model->get_e_bookings_by_id($id);

        if(empty($result))
        {
            $this->session->set_flashdata('error', sprintf(lang('alert_not_found') ,lang('menu_e_booking')));
            redirect($this->uri->segment(1).'/'.$this->uri->segment(2));            
        }

        $result_members         = $this->ebookings_model->get_e_bookings_members($id);
        $result_payments        = $this->ebookings_model->get_e_bookings_payments($id);

        $data['e_bookings']     = $result;
        $data['members']        = $result_members;
        $data['payments']       = $result_payments;
        
        /* Load Template */
        if($print)
        {
            $this->load->view('admin/e_bookings/view_invoice', $data);
        }
        else
        {
            $content['content']    = $this->load->view('admin/e_bookings/view', $data, TRUE);
            $this->load->view($this->template, $content);
        }
    }


    /**
     * status_update
     */
    public function status_update()
    {
        if(!$this->acl->get_method_permission($_SESSION['groups_id'], 'ebookings', 'p_edit'))
        {
            echo '<p>'.sprintf(lang('manage_acl_permission_no'), lang('manage_acl_edit')).'</p>';exit;
        }
        /* Validate form input */
        $this->form_validation
        ->set_rules('id', sprintf(lang('alert_id'), lang('menu_e_booking')), 'required|numeric')
        ->set_rules('status', lang('common_status'), 'required|in_list[0,1]');

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
        $data['status']             = $this->input->post('status');

        if(empty($id))
        {
            $this->session->set_flashdata('message', sprintf(lang('alert_not_found'), lang('menu_e_booking')));
            echo json_encode(array(
                                    'flag'  => 0, 
                                    'msg'   => $this->session->flashdata('message'),
                                    'type'  => 'fail',
                                ));
            exit;
        }
        
        $flag                           = $this->ebookings_model->save_e_bookings($data, array(), array(), $id);

        if($flag)
        {
            echo json_encode(array(
                                    'flag'  => 1, 
                                    'msg'   => sprintf(lang('alert_update_success'), lang('menu_e_booking')),
                                    'type'  => 'success',
                                ));
            exit;
        }

        echo json_encode(array(
                            'flag'  => 0, 
                            'msg'   => sprintf(lang('alert_update_fail'), lang('menu_e_booking')),
                            'type'  => 'fail',
                        ));
        exit;
    }

    /**
     * get_events
     */
    function get_events()
    {
        /* Validate form input */
        $this->form_validation->set_rules('event_type_id', sprintf(lang('alert_id') ,lang('e_bookings_event_type')), 'required|is_natural_no_zero', array('is_natural_no_zero'=>'<span class="loader text-danger">*'.lang('e_bookings_event_type').'</span>'));

        if($this->form_validation->run() === FALSE)
        {
            echo validation_errors();exit;
        }

        $event_type_id        = $this->input->post('event_type_id');

        /* Data */
        $events               = $this->ebookings_model->get_events($event_type_id);
        
        if(empty($events))
        {
            echo lang('events_empty');exit;
        }

        echo json_encode($events);exit;
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

        // set fees in session
        $_SESSION['e_bookings']['fees']           = $data['fees'];
        $_SESSION['e_bookings']['net_fees']       = $data['net_fees'];
        $_SESSION['e_bookings']['recurring']      = $data['recurring'];
        $_SESSION['e_bookings']['weekdays']       = json_decode($data['weekdays']);
        $_SESSION['e_bookings']['capacity']       = $result->capacity;
        $_SESSION['e_bookings']['count_members']  = $count_members;
        
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
        ->set_rules('booking_date', lang('e_bookings_booking_date'), 'trim|required');

        if($this->form_validation->run() === FALSE)
        {
            echo validation_errors();exit;
        }

        $event_id               = $this->input->post('events_id');
        $booking_date           = $this->input->post('booking_date');
        
        /* Data */
        $result                 = $this->ebookings_model->get_total_e_bookings($event_id, $booking_date);
        
        echo json_encode(array('booked_seats'=>$result));exit;
    }

}

/* Ebookings controller ends */