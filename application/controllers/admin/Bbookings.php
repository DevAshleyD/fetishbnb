<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Bbookings Controller
 *
 * This class handles batches bookings module functionality
 *
 * @package     classiebit
 * @author      prodpk
*/

class Bbookings extends Admin_Controller {

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();

        $this->load->model('admin/bbookings_model');
        $this->load->model('admin/batches_model');
        $this->load->model('admin/courses_model');
        $this->load->model('admin/taxes_model');
        $this->load->model('users_model');
        $this->load->model('settings_model');

        /* Page Title */
        $this->set_title( lang('menu_b_bookings') );
    }

    /**
     * index
     */
    function index()
    {

        if(!$this->acl->get_method_permission($_SESSION['groups_id'], 'bbookings', 'p_view'))
        {
            $this->session->set_flashdata('error', sprintf(lang('manage_acl_permission_no'), lang('menu_b_bookings').' '.lang('manage_acl_view')));
            redirect($this->uri->segment(1).'/dashboard');
        }

        /* Initialize assets */
        $this->include_index_plugins();
        $data = $this->includes;

        // Table Header
        $data['t_headers']  = array(
                                '#',
                                lang('b_bookings_batch'),
                                lang('b_bookings_customer'),
                                lang('b_bookings_booking_date'),
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

        $table              = 'b_bookings';        
        $columns            = array(
                                "$table.id",
                                "$table.batch_title",
                                "$table.customer_name",
                                "$table.booking_date",
                                "$table.cancellation",
                                "$table.status",
                                "$table.date_updated",
                            );
        $columns_order      = array(
                                "#",
                                "$table.batch_title",
                                "$table.customer_name",
                                "$table.booking_date",
                                "$table.date_updated",
                                "$table.cancellation",
                                "$table.status",
                            );
        $columns_search     = array(
                                'batch_title',
                                'customer_name',
                            );
        $order              = array('date_updated' => 'DESC');
        $order              = array('date_updated'=>'DESC', 'booking_date' => 'ASC');
        
        $result             = $this->datatables->get_datatables($table, $columns, $columns_order, $columns_search, $order);
        $data               = array();
        $no                 = $_POST['start'];
        
        foreach ($result as $val) 
        {
            $no++;
            $row            = array();
            $row[]          = $no;
            $row[]          = mb_substr($val->batch_title, 0, 30, 'utf-8');
            $row[]          = $val->customer_name;
            $row[]          = date('g:iA d/m/y', strtotime($val->booking_date));
            $row[]          = date('g:iA d/m/y', strtotime($val->date_updated));
            $row[]          = $val->cancellation == 1 ? '<span class="label label-info">'.lang('common_cancellation_pending').'</span>' : ($val->cancellation == 2 ? '<span class="label label-warning">'.lang('common_cancellation_approved').'</span>' : ($val->cancellation == 3 ? '<span class="label label-success">'.lang('common_cancellation_refunded') : '<span class="label label-default">'.lang('common_cancellation_disabled')).'</span>');
            $row[]          = status_switch($val->status, $val->id);
            $row[]          = action_buttons('bbookings', $val->id, mb_substr($val->batch_title, 0, 20, 'utf-8'), lang('menu_booking'));
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
        ->add_js_theme( "pages/b_bookings/form_i18n.js", TRUE );

        $data                       = $this->includes;

        // in case of edit
        $b_bookings                   = array();
        $id                         = (int) $id;
        if($id)
        {
            $result                 = $this->bbookings_model->get_b_bookings_by_id($id);

            if(empty($result))
            {
                $this->session->set_flashdata('error', sprintf(lang('alert_not_found') ,lang('menu_booking')));
                redirect($this->uri->segment(1).'/'.$this->uri->segment(2));            
            }

            // hidden field for b_bookings id in case of update
            $data['id']             = $result->id; 

            // for showing selected batch in calendar
            $data['selected_batch'] = $result;
            $data['selected_batch']->batch_description = '';
            $data['selected_batch']->batch_weekdays = count(json_decode($result->batch_weekdays));

            $result_members         = $this->bbookings_model->get_b_bookings_members($id);
            $data['members']        = $result_members;
            $result_payments        = $this->bbookings_model->get_b_bookings_payments($id);
        }

        // get user ids by group customers = 3
        $cus_ids                          = array();
        foreach($this->ion_auth->get_users_by_group(3)->result() as $val)
            $cus_ids[] = $val->user_id;
        
        // render customers dropdown
        $data['customers']['0']     = '-- '.lang('b_bookings_customer').' --';
        $customers                  = $this->batches_model->get_users_dropdown($cus_ids);
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

        // render category dropdown
        $categories                     = $this->bbookings_model->get_course_categories_dropdown();
        $data['categories']['0']        = '-- '.lang('b_bookings_course_category').' --';

        foreach($categories as $val)
            $data['categories'][$val->id] = $val->title;
        
        // for course dropdown, in case of edit
        $data['course']              = array();
        $data['course']['0']         = '-- '.lang('b_bookings_course').' --';
        $top_level_category['id']   = '';

        if(! empty($result->course_categories_id))
        {
            // render category levels for selected category
            $categories_levels              = $this->courses_model->get_courses_levels($result->course_categories_id);
            $count_level                    = count($categories_levels);
            $top_level_category             = $categories_levels[$count_level-1]; // the last category will be top category
            
            // populate subcategory dropdown
            $data['category_l']     = array();
            if(count($categories_levels) > 0) 
            {
                for($i = (count($categories_levels) - 2); $i >= 0; $i--)
                {
                    $data['category_l'][$i] = array(
                        'name'  => 'category[]',
                        'options'=> array($categories_levels[$i]['id'] => $categories_levels[$i]['title']),
                        'class' => 'form-control show-tick parent',
                        'data-live-search'=>"true",
                        'selected' => $this->form_validation->set_value('category[]', $categories_levels[$i]['id']),
                    );
                }    
            }

            // inside this if cz its linked dropdown with category dropdown
            $courses            = $this->bbookings_model->get_courses_dropdown($result->course_categories_id);
            
            $data['course']['0'] = '-- '.lang('b_bookings_course').' --';
            foreach($courses as $val)
                $data['course'][$val->id] = $val->title;
        }
        
        $data['category']   = array(
            'name'          => 'category[]',
            'id'            => 'category',
            'class'         => 'category form-control show-tick text-capitalize parent',
            'data-live-search'=>"true",
            'options'       => $data['categories'],
            'selected'      => $this->form_validation->set_value('category[]', $top_level_category['id']),
        );
        $data['courses']   = array(
            'name'          => 'courses',
            'id'            => 'courses',
            'class'         => 'form-control show-tick text-capitalize',
            'data-live-search'=>"true",
            'options'       => $data['course'],
            'selected'      => $this->form_validation->set_value('courses', !empty($result->courses_id) ? $result->courses_id : ''),
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
            'options'       => array('locally' => lang('b_bookings_payment_type_locally')),
            'selected'      => $this->form_validation->set_value('payment_type', !empty($result_payments->payment_type) ? $result_payments->payment_type : ''),
        );
        $data['payment_status']     = array(
            'name'          => 'payment_status',
            'id'            => 'payment_status',
            'class'         => 'form-control show-tick',
            'options'       => array(
                                    '0' => lang('b_bookings_payment_status_pending'),
                                    '1' => lang('b_bookings_payment_status_successful'),
                                    '2' => lang('b_bookings_payment_status_failed'),
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
        $content['content']    = $this->load->view('admin/b_bookings/form', $data, TRUE);
        $this->load->view($this->template, $content);
        
    }

    /**
     * save
    */
    public function save()
    {
        if(! empty($_POST['id'])) // if updating booking then only update some fields
        {
            if(!$this->acl->get_method_permission($_SESSION['groups_id'], 'bbookings', 'p_edit'))
            {
                echo '<p>'.sprintf(lang('manage_acl_permission_no'), lang('manage_acl_edit')).'</p>';exit;
            }

            $this->form_validation
            ->set_rules('id', sprintf(lang('alert_id'), lang('menu_booking')), 'trim|required|is_natural_no_zero')
            ->set_rules('payment_status', lang('b_bookings_payment_status'), 'trim|required|in_list[0,1,2]')
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

            $flag                           = $this->bbookings_model->save_b_bookings($data, array(), $payments, $id);

            if($flag)
            {
                // add cancellation notification when booking cancellation update
                if($data['cancellation'])
                {
                    $notification   = array(
                        'users_id'  => $this->user['id'],
                        'n_type'    => 'b_cancellation',
                        'n_content' => 'noti_cancel_booking',
                        'n_url'     => site_url('admin/bbookings'), 
                    );
                    $this->notifications_model->save_notifications($notification);        
                }
                
                $this->session->set_flashdata('message', sprintf(lang('alert_update_success'), lang('menu_booking')));
                echo json_encode(array(
                                        'flag'  => 1, 
                                        'msg'   => sprintf(lang('alert_update_success'), lang('menu_booking')),
                                        'type'  => 'success',
                                    ));
                exit;
            }
            
            $this->session->set_flashdata('error', sprintf(lang('alert_update_fail'), lang('menu_booking')));
            echo json_encode(array(
                                    'flag'  => 1, 
                                    'msg'   => sprintf(lang('alert_update_fail'), lang('menu_booking')),
                                    'type'  => 'fail',
                                ));
            exit;
        }
        else
        {
            if(!$this->acl->get_method_permission($_SESSION['groups_id'], 'bbookings', 'p_add'))
            {
                echo '<p>'.sprintf(lang('manage_acl_permission_no'), lang('manage_acl_add')).'</p>';exit;
            }
        }

        /* Validate form input */
        $this->form_validation
        ->set_rules('customers', lang('b_bookings_customer'), 'trim|required|is_natural_no_zero')
        ->set_rules('category[]', lang('b_bookings_course_cateogry'), 'trim|required|is_natural_no_zero')
        ->set_rules('courses', lang('b_bookings_course'), 'trim|required|is_natural_no_zero')
        ->set_rules('batches_id', lang('b_bookings_batch'), 'trim|required|is_natural_no_zero')
        ->set_rules('booking_date', lang('b_bookings_booking_date'), 'trim|required')
        ->set_rules('start_time', lang('b_bookings_start_time'), 'trim|required')
        ->set_rules('fullname[]', lang('users_fullname'), 'trim|required|alpha_numeric_spaces')
        ->set_rules('email[]', lang('users_email'), 'trim|required|valid_email')
        ->set_rules('mobile[]', lang('users_email'), 'trim|required')
        ->set_rules('fees', lang('b_bookings_fees'), 'trim|required|numeric')
        ->set_rules('net_fees', lang('b_bookings_net_fees'), 'trim|required|numeric')
        ->set_rules('payment_type', lang('b_bookings_payment_type'), 'trim|required|in_list[locally,paypal,stripe]')
        ->set_rules('payment_status', lang('b_bookings_payment_status'), 'trim|required|in_list[0,1,2]')
        ->set_rules('status', lang('common_status'), 'required|in_list[0,1]');

        // check if customer has already booked the batch
        if(isset($_POST['customers']) && isset($_POST['batches_id']) && isset($_POST['booking_date']) && isset($_POST['start_time'])) 
        {
            if($_POST['customers'] && $_POST['batches_id'] && $_POST['booking_date'] && $_POST['start_time'])
            {

                // check availability of batch by capacity & pre_booking time
                // check prebooking time from settings (in hour)
                $default_prebook_time = $this->settings_model->get_setting_by_name('default_prebook_time')->value;
                $booking_date         = date('Y-m-d', strtotime(str_replace('-', '/', $this->input->post('booking_date'))));
                $today_date           = date('Y-m-d H:i:s');

                // booking date should not be less than today's date
                if($booking_date < $today_date)
                    $this->form_validation->set_rules('booking_older', 'booking_older', 'required', array('required'=>lang('b_bookings_booking_older_date')));

                // calculate no of hours
                $start_time            = $this->input->post('start_time');
                $time_booking          = strtotime($booking_date.' '.$start_time);
                $time_today            = strtotime($today_date);
                $hours                 = round(abs($time_booking - $time_today)/(60*60));
                
                if($hours < $default_prebook_time)
                    $this->form_validation->set_rules('booking_late', 'booking_late', 'required', array('required'=>sprintf(lang('b_bookings_booking_late'), $default_prebook_time.' Hours')));                           
                
                // check availability
                $total_b_bookings       = $this->bbookings_model->get_total_b_bookings($this->input->post('batches_id'), $this->input->post('booking_date'));
                $capacity             = $_SESSION['b_bookings']['capacity'];

                if($total_b_bookings >= $capacity)
                    $this->form_validation->set_rules('booking_full', 'booking_full', 'required', array('required'=>lang('b_bookings_booking_full')));       
                
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
        // b_bookings table
        $data                           = array();
        // get booking id from settings
        $data['id']                     = $this->settings_model->get_setting_by_name('default_starting_booking_id')->value;
        $data['customers_id']           = $this->input->post('customers');
        $data['batches_id']             = $this->input->post('batches_id');
        $data['courses_id']             = $this->input->post('courses');
        // fetch fees and net fees from session 
        // pick the last selected category
        $data['course_categories_id']    = count($this->input->post('category'))-1;
        $data['course_categories_id']    = $this->input->post('category')[$data['course_categories_id']];

        $data['fees']                   = $_SESSION['b_bookings']['fees'];
        $data['net_fees']               = $_SESSION['b_bookings']['net_fees'];
        $data['booking_date']           = $this->input->post('booking_date');
        $data['status']                 = $this->input->post('status');
        
        // fetch batch data for static values
        $batch                          = array();
        $batch                          = $this->batches_model->get_batches_by_id($data['batches_id']);

        // check if batch id is not correct
        if(empty($batch)) 
        {
            echo json_encode(array('flag'=>0, 'msg' => sprintf(lang('alert_not_found'), lang('b_bookings_batch')), 'error_fields'=>json_encode(array(0=>'batches_id'))));
            exit;
        }

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

        // check if batch id is not correct
        if(empty($customer)) 
        {
            echo json_encode(array('flag'=>0, 'msg' => sprintf(lang('alert_not_found'), lang('b_bookings_customer')), 'error_fields'=>json_encode(array(0=>'customers'))));
            exit;
        }

        $data['customer_name']          = $customer->first_name.' '.$customer->last_name;
        $data['customer_email']         = $customer->email;
        $data['customer_address']       = $customer->address;
        $data['customer_mobile']        = $customer->mobile;
        
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
            $members[$key]['b_bookings_id']     = $data['id'];
        }

        $taxes                          = $this->taxes_model->get_taxes_by_id($this->settings->default_tax_id);

        // data for b_bookings_payments
        $payments                       = array();
        $payments['b_bookings_id']        = $data['id'];
        $payments['paid_amount']        = 0; 
        $payments['total_amount']       = $_SESSION['b_bookings']['net_fees']; 
        $payments['payment_type']       = $this->input->post('payment_type');
        $payments['payment_status']     = $this->input->post('payment_status');
        $payments['currency']           = get_default_currency();
        $payments['tax_title']          = $taxes->title;
        $payments['tax_rate_type']      = $taxes->rate_type;
        $payments['tax_rate']           = $taxes->rate;
        $payments['tax_net_price']      = $taxes->net_price;
        
        $flag                           = $this->bbookings_model->save_b_bookings($data, $members, $payments);

        if($flag)
        {
            // add bookings notification when new batch inserted
            $notification   = array(
                'users_id'  => $this->user['id'],
                'n_type'    => 'bbookings',
                'n_content' => 'noti_new_booking',
                'n_url'     => site_url('admin/bbookings'), 
            );
            $this->notifications_model->save_notifications($notification);    
            
            $this->session->set_flashdata('message', sprintf(lang('alert_insert_success'), lang('menu_booking')));
            echo json_encode(array(
                                    'flag'  => 1, 
                                    'msg'   => sprintf(lang('alert_insert_success'), lang('menu_booking')),
                                    'type'  => 'success',
                                ));
            exit;
        }
        
        $this->session->set_flashdata('error', sprintf(lang('alert_insert_fail'), lang('menu_booking')));
        echo json_encode(array(
                                'flag'  => 1, 
                                'msg'   => sprintf(lang('alert_insert_fail'), lang('menu_booking')),
                                'type'  => 'fail',
                            ));
        exit;
    }

    /**
     * view
     */
    public function view($id = NULL, $print = NULL)
    {

        if(!$this->acl->get_method_permission($_SESSION['groups_id'], 'bbookings', 'p_view'))
        {
            $this->session->set_flashdata('error', sprintf(lang('manage_acl_permission_no'), lang('menu_b_bookings').' '.lang('manage_acl_view')));
            redirect($this->uri->segment(1).'/dashboard');
        }
        
        /* Initialize Assets */
        $data                   = $this->includes;

        /* Get Data */
        $id                     = (int) $id;
        $result                 = $this->bbookings_model->get_b_bookings_by_id($id);

        if(empty($result))
        {
            $this->session->set_flashdata('error', sprintf(lang('alert_not_found') ,lang('menu_booking')));
            redirect($this->uri->segment(1).'/'.$this->uri->segment(2));            
        }

        $result_members         = $this->bbookings_model->get_b_bookings_members($id);
        $result_payments        = $this->bbookings_model->get_b_bookings_payments($id);

        $data['b_bookings']       = $result;
        $data['members']        = $result_members;
        $data['payments']       = $result_payments;
        
        /* Load Template */
        if($print)
        {
            $this->load->view('admin/b_bookings/view_invoice', $data);
        }
        else
        {
            $content['content']    = $this->load->view('admin/b_bookings/view', $data, TRUE);
            $this->load->view($this->template, $content);
        }
    }


    /**
     * status_update
     */
    public function status_update()
    {
        if(!$this->acl->get_method_permission($_SESSION['groups_id'], 'bbookings', 'p_edit'))
        {
            echo '<p>'.sprintf(lang('manage_acl_permission_no'), lang('manage_acl_edit')).'</p>';exit;
        }

        /* Validate form input */
        $this->form_validation
        ->set_rules('id', sprintf(lang('alert_id'), lang('menu_booking')), 'required|numeric')
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
            $this->session->set_flashdata('message', sprintf(lang('alert_not_found'), lang('menu_booking')));
            echo json_encode(array(
                                    'flag'  => 0, 
                                    'msg'   => $this->session->flashdata('message'),
                                    'type'  => 'fail',
                                ));
            exit;
        }
        
        $flag                           = $this->bbookings_model->save_b_bookings($data, array(), array(), $id);

        if($flag)
        {
            echo json_encode(array(
                                    'flag'  => 1, 
                                    'msg'   => sprintf(lang('alert_update_success'), lang('menu_booking')),
                                    'type'  => 'success',
                                ));
            exit;
        }

        echo json_encode(array(
                            'flag'  => 0, 
                            'msg'   => sprintf(lang('alert_update_fail'), lang('menu_booking')),
                            'type'  => 'fail',
                        ));
        exit;
    }


    /**
     * get_course_categories_levels
     */
    public function get_course_categories_levels()
    {
        /* Validate form input */
        $this->form_validation->set_rules('category_id', sprintf(lang('alert_id') ,lang('courses_sub_category')), 'required', array('required'=>'<span class="loader text-danger">*'.lang('courses_select_category').'</span>'));

        if($this->form_validation->run() === FALSE)
        {
            echo validation_errors();exit;
        }

        $category_id        = $this->input->post('category_id');

        /* Data */
        $course_levels       = $this->bbookings_model->get_course_categories_levels($category_id);

        if(empty($course_levels))
        {
            echo FALSE;exit;
        }

        $gen_html           = '<select name="category[]" class="form-control parent">
                                <option value="" selected="selected">-- '.lang('courses_sub_category').' --</option>';

        foreach($course_levels as $val)
        {
            $gen_html .= '<option value="'.$val->id.'">'.$val->title.'</option>';
        }

        $gen_html          .= '</select>';
        
        echo $gen_html;exit;
    }


    /**
     * get_courses
     */
    function get_courses()
    {
        /* Validate form input */
        $this->form_validation->set_rules('category_id', sprintf(lang('alert_id') ,lang('b_bookings_course_category')), 'required|is_natural_no_zero', array('is_natural_no_zero'=>'<span class="loader text-danger">*'.lang('b_bookings_course_category').'</span>'));

        if($this->form_validation->run() === FALSE)
        {
            echo validation_errors();exit;
        }

        $category_id        = $this->input->post('category_id');

        /* Data */
        $courses            = $this->bbookings_model->get_courses_dropdown($category_id);

        if(empty($courses))
        {
            echo lang('courses_empty');exit;
        }

        $categories = array();
        $categories[0] = '-- '.lang('b_bookings_course').' --';
        foreach($courses as $val)
            $categories[$val->id] = $val->title;


        echo json_encode($categories);exit;
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
        $_SESSION['b_bookings']['fees']           = $data['fees'];
        $_SESSION['b_bookings']['net_fees']       = $data['net_fees'];
        $_SESSION['b_bookings']['recurring']      = $data['recurring'];
        $_SESSION['b_bookings']['weekdays']       = json_decode($data['weekdays']);
        $_SESSION['b_bookings']['capacity']       = $result->capacity;
        $_SESSION['b_bookings']['count_members']  = $count_members;
        
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
        ->set_rules('booking_date', lang('b_bookings_booking_date'), 'trim|required');

        if($this->form_validation->run() === FALSE)
        {
            echo validation_errors();exit;
        }

        $batch_id               = $this->input->post('batches_id');
        $booking_date           = $this->input->post('booking_date');
        
        /* Data */
        $result                 = $this->bbookings_model->get_total_b_bookings($batch_id, $booking_date);
        
        echo json_encode(array('booked_seats'=>$result));exit;
    }

}

/* Bbookings controller ends */