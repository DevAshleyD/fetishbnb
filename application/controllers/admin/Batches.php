<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Batches Controller
 *
 * This class handles batches module functionality
 *
 * @package     classiebit
 * @author      prodpk
*/

class Batches extends Admin_Controller {

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();

        $this->load->model('admin/batches_model');

        /* Page Title */
        $this->set_title( lang('menu_batches') );
    }

    /**
     * index
     */
    function index()
    {
        if(!$this->acl->get_method_permission($_SESSION['groups_id'], 'batches', 'p_view'))
        {
            $this->session->set_flashdata('error', sprintf(lang('manage_acl_permission_no'), lang('menu_batch').' '.lang('manage_acl_view')));
            redirect($this->uri->segment(1).'/dashboard');
        }

        /* Initialize assets */
        $this->include_index_plugins();
        $data = $this->includes;

        // Table Header
        $data['t_headers']  = array(
                                '#',
                                lang('common_title'),
                                lang('batches_start_date'),
                                lang('batches_end_date'),
                                lang('batches_start_time'),
                                lang('batches_end_time'),
                                lang('batches_courses'),
                                lang('common_updated'),
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

        $table              = 'batches';        
        $columns            = array(
                                "$table.id",
                                "$table.title",
                                "$table.start_date",
                                "$table.end_date",
                                "$table.start_time",
                                "$table.end_time",
                                "$table.recurring",
                                "$table.courses_id",
                                "$table.status",
                                "$table.date_updated",
                                "(SELECT c.title FROM courses c WHERE c.id = $table.courses_id) course_name",
                            );
        $columns_order      = array(
                                "#",
                                "$table.title",
                                "$table.start_date",
                                "$table.end_date",
                                "$table.start_time",
                                "$table.end_time",
                                "$table.courses_id",
                                "$table.date_updated",
                                "$table.status",
                            );
        $columns_search     = array(
                                'title',
                            );
        $order              = array('date_updated'=>'DESC', 'start_date' => 'ASC', 'start_time'=>'ASC');
        
        $result             = $this->datatables->get_datatables($table, $columns, $columns_order, $columns_search, $order);
        $data               = array();
        $no                 = $_POST['start'];
        
        foreach ($result as $val) 
        {
            $no++;
            $row            = array();
            $row[]          = $no;
            $row[]          = mb_substr($val->title, 0, 30, 'utf-8').($val->recurring ? ' (R)' : '');
            $row[]          = date('d/m/y', strtotime($val->start_date));
            $row[]          = date('d/m/y', strtotime($val->end_date));
            $row[]          = date('g:iA', strtotime($val->start_time));
            $row[]          = date('g:iA', strtotime($val->end_time));
            $row[]          = '<a href="'.site_url('admin/courses/view/').$val->courses_id.'" target="_blank">'.$val->course_name.'</a>';
            $row[]          = date('g:iA d/m/y', strtotime($val->date_updated));
            $row[]          = status_switch($val->status, $val->id);
            $row[]          = action_buttons('batches', $val->id, mb_substr($val->title, 0, 20, 'utf-8'), lang('menu_batch'));

            // For Recurring
// For Recurring
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
                            ), 'admin')
        ->add_js_theme( "pages/batches/form_i18n.js", TRUE );
        $data                       = $this->includes;

        // in case of edit
        $id                         = (int) $id;
        $result                     = (object) (array());
        if($id)
        {
            $result                 = $this->batches_model->get_batches_by_id($id);

            if(empty($result))
            {
                $this->session->set_flashdata('error', sprintf(lang('alert_not_found') ,lang('menu_batch')));
                redirect($this->uri->segment(1).'/'.$this->uri->segment(2));
            }

            // hidden field in case of update
            $data['id']             = $result->id;

            /*Get Tutors*/
            $result_tutors          = $this->batches_model->get_batches_tutors($result->id);
            foreach($result_tutors as $key => $val)
                $result_tutors[$key] = $val->users_id;

            $_POST['tutors']        = $result_tutors;

            // For start time
            $result->start_time     = date("g:i A", strtotime($result->start_time));
            $result->start_time_1   = explode(':', $result->start_time)[0];
            $result->start_time_2   = explode(':', $result->start_time)[1];
            $result->start_time_3   = explode(' ', $result->start_time_2)[1];

            // For End time
            $result->end_time       = date("g:i A", strtotime($result->end_time));
            $result->end_time_1     = explode(':', $result->end_time)[0];
            $result->end_time_2     = explode(':', $result->end_time)[1];
            $result->end_time_3   = explode(' ', $result->end_time_2)[1];

            // For Weekdays
            $_POST['weekdays']  = json_decode($result->weekdays);

            // For Recurring
            $_POST['recurring']     = $result->recurring;
        }

        // get user ids by group tutor = 2
        $tutor_ids                          = array();
        foreach($this->ion_auth->get_users_by_group(2)->result() as $val)
            $tutor_ids[] = $val->user_id;

        // render tutors dropdown
        $tutors                         = $this->batches_model->get_users_dropdown($tutor_ids);
        foreach($tutors as $val)
            $data['tutors'][$val->id] = $val->first_name.' '.$val->last_name.' ('.$val->profession.')';
        
        $data['tutors']   = array(
            'name'          => 'tutors[]',
            'id'            => 'tutors',
            'class'         => 'form-control show-tick text-capitalize',
            'multiple'      => 'multiple',
            'data-live-search'=>"true",
            'options'       => $data['tutors'],
            'selected'      => $this->form_validation->set_value('tutors[]'),
        );

        // render courses dropdown
        $courses                        = $this->batches_model->get_courses_dropdown();
        foreach($courses as $val)
            $data['courses_o'][$val->id] = $val->title.' ('.$val->courses_category_name.')';
        
        $data['courses']   = array(
            'name'          => 'courses',
            'id'            => 'courses',
            'class'         => 'courses form-control show-tick text-capitalize',
            'data-live-search'=>"true",
            'options'       => $data['courses_o'],
            'selected'      => $this->form_validation->set_value('courses', !empty($result->courses_id) ? $result->courses_id : ''),
        );
        $data['fees']= array(
            'name'          => 'fees',
            'id'            => 'fees',
            'type'          => 'number',
            'min'           => '0',
            'class'         => 'form-control',
            'value'         => $this->form_validation->set_value('fees', !empty($result->fees) ? $result->fees : 0),
        );
        $data['capacity']= array(
            'name'          => 'capacity',
            'id'            => 'capacity',
            'type'          => 'number',
            'min'           => '1',
            'class'         => 'form-control',
            'value'         => $this->form_validation->set_value('capacity', !empty($result->capacity) ? $result->capacity : ''),
        );
        // convert_to_mysql_date
        if(empty($result->start_date))
        {
            $data['start_date'] = str_replace('/', '-', date('m/d/Y'));
            $data['end_date']   = str_replace('/', '-', date('m/d/Y'));
        }
        else
        {
            $data['start_date'] = date('m/d/Y', strtotime(str_replace('/', '-', $result->start_date)));
            $data['end_date']   = date('m/d/Y', strtotime(str_replace('/', '-', $result->end_date)));
        }
        $result->start_end_date     = $data['start_date'].' - '.$data['end_date'];
        
        $data['start_end_date']= array(
            'name'          => 'start_end_date',
            'id'            => 'start_end_date',
            'type'          => 'text',
            'class'         => 'form-control',
            'value'         => $this->form_validation->set_value('start_end_date', !empty($result->start_end_date) ? $result->start_end_date : ''),
        );
        $data['start_time_1']= array(
            'name'          => 'start_time_1',
            'id'            => 'start_time_1',
            'class'         => 'form-control show-tick',
            'options'       => array(
                                '01'  => '01', 
                                '02'  => '02', 
                                '03'  => '03', 
                                '04'  => '04', 
                                '05'  => '05', 
                                '06'  => '06', 
                                '07'  => '07', 
                                '08'  => '08',
                                '09'  => '09',
                                '10'  => '10',
                                '11'  => '11',
                                '12'  => '12',
                            ),
            'selected'      => $this->form_validation->set_value('start_time_1', !empty($result->start_time_1) ? $result->start_time_1 : ''),
        );
        $data['start_time_2']= array(
            'name'          => 'start_time_2',
            'id'            => 'start_time_2',
            'class'         => 'form-control show-tick',
            'options'       => array(
                                '00' => '00', 
                                '15' => '15', 
                                '30' => '30', 
                                '45' => '45', 
                            ),
            'selected'      => $this->form_validation->set_value('start_time_2', !empty($result->start_time_2) ? $result->start_time_2 : ''),
        );
        $data['start_time_3']= array(
            'name'          => 'start_time_3',
            'id'            => 'start_time_3',
            'class'         => 'form-control show-tick',
            'options'       => array(
                                'AM' => 'AM', 
                                'PM' => 'PM', 
                            ),
            'selected'      => $this->form_validation->set_value('start_time_3', !empty($result->start_time_3) ? $result->start_time_3 : ''),
        );
        $data['end_time_1']= array(
            'name'          => 'end_time_1',
            'id'            => 'end_time_1',
            'class'         => 'form-control show-tick',
            'options'       => array(
                                '01' => '01', 
                                '02' => '02', 
                                '03' => '03', 
                                '04' => '04', 
                                '05' => '05', 
                                '06' => '06', 
                                '07' => '07', 
                                '08' => '08',
                                '09' => '09',
                                '10' => '10',
                                '11' => '11',
                                '12' => '12',
                            ),
            'selected'      => $this->form_validation->set_value('end_time_1', !empty($result->end_time_1) ? $result->end_time_1 : ''),
        );
        $data['end_time_2']= array(
            'name'          => 'end_time_2',
            'id'            => 'end_time_2',
            'class'         => 'form-control show-tick',
            'options'       => array(
                                '00'  => '00', 
                                '15' => '15', 
                                '30' => '30', 
                                '45' => '45', 
                            ),
            'selected'      => $this->form_validation->set_value('end_time_2', !empty($result->end_time_2) ? $result->end_time_2 : ''),
        );
        $data['end_time_3']= array(
            'name'          => 'end_time_3',
            'id'            => 'end_time_3',
            'class'         => 'form-control show-tick',
            'options'       => array(
                                'AM' => 'AM', 
                                'PM' => 'PM', 
                            ),
            'selected'      => $this->form_validation->set_value('end_time_3', !empty($result->end_time_3) ? $result->end_time_3 : ''),
        );
        $data['weekdays']   = array(
            '0' => lang('batches_weekdays_sun'), 
            '1' => lang('batches_weekdays_mon'),
            '2' => lang('batches_weekdays_tue'), 
            '3' => lang('batches_weekdays_wed'), 
            '4' => lang('batches_weekdays_thu'), 
            '5' => lang('batches_weekdays_fri'), 
            '6' => lang('batches_weekdays_sat'), 
        );

        if(!empty($result->recurring_type)) 
            $_POST['recurring_type']= $result->recurring_type;
        else
            $_POST['recurring_type']= 'every_week';

        $data['recurring_types']   = array(
            'every_week'    => lang('batches_recurring_types_all'),
            'first_week'    => lang('batches_recurring_types_first'), 
            'second_week'   => lang('batches_recurring_types_second'), 
            'third_week'    => lang('batches_recurring_types_third'), 
            'fourth_week'   => lang('batches_recurring_types_fourth'), 
        );

        $data['title'] = array(
            'name'      => 'title',
            'id'        => 'title',
            'type'      => 'text',
            'class'     => 'form-control',
            'value'     => $this->form_validation->set_value('title', !empty($result->title) ? $result->title : ''),
        );
        $data['description'] = array(
            'name'      => 'description',
            'id'        => 'description',
            'type'      => 'textarea',
            'class'     => 'tinymce form-control',
            'value'     => $this->form_validation->set_value('description', !empty($result->description) ? $result->description : ''),
        );
        $data['status'] = array(
            'name'      => 'status',
            'id'        => 'status',
            'class'     => 'form-control',
            'options'   => array('1' => lang('common_status_active'), '0' => lang('common_status_inactive')),
            'selected'  => $this->form_validation->set_value('status', !empty($result->status) ? $result->status : 0),
        );

        /* Load Template */
        $content['content']    = $this->load->view('admin/batches/form', $data, TRUE);
        $this->load->view($this->template, $content);
    }

    /**
     * save
    */
    public function save()
    {
        $id = NULL;

        $result_tutors = array();
        if(! empty($_POST['id']))
        {
            if(!$this->acl->get_method_permission($_SESSION['groups_id'], 'batches', 'p_edit'))
            {
                echo '<p>'.sprintf(lang('manage_acl_permission_no'), lang('manage_acl_edit')).'</p>';exit;
            }

            $id                     = (int) $this->input->post('id');
            $result                 = $this->batches_model->get_batches_by_id($id);

            if(empty($result))
            {
                $this->session->set_flashdata('message', sprintf(lang('alert_not_found'), lang('menu_batch')));
                echo json_encode(array(
                                        'flag'  => 0, 
                                        'msg'   => $this->session->flashdata('message'),
                                        'type'  => 'fail',
                                    ));
                exit;
            }

            /*Get Tutors*/
            $result_tutors       = $this->batches_model->get_batches_tutors($result->id);
            foreach($result_tutors as $key => $val)
                $result_tutors[$key] = $val->users_id;
        }
        else
        {
            if(!$this->acl->get_method_permission($_SESSION['groups_id'], 'batches', 'p_add'))
            {
                echo '<p>'.sprintf(lang('manage_acl_permission_no'), lang('manage_acl_add')).'</p>';exit;
            }
        }

        /* Validate form input */
        $this->form_validation
        ->set_rules('title', lang('common_title'), 'trim|required|alpha_dash_spaces|min_length[3]|max_length[250]')
        ->set_rules('description', lang('common_description'), 'trim')
        ->set_rules('fees', lang('batches_fees'), 'trim|required|is_natural')
        ->set_rules('capacity', lang('batches_capacity'), 'trim|required|is_natural_no_zero')
        ->set_rules('recurring', lang('batches_recurring'), 'trim')
        ->set_rules('start_end_date', lang('batches_start_end_date'), 'trim|required')
        ->set_rules('start_time', lang('batches_start_time'), 'trim|required')
        ->set_rules('end_time', lang('batches_end_time'), 'trim|required')
        ->set_rules('status', lang('common_status'), 'required|in_list[0,1]')
        ->set_rules('courses', lang('batches_courses'), 'trim|required|is_natural_no_zero')
        ->set_rules('tutors[]', lang('batches_tutors'), 'trim|required|is_natural_no_zero');

        // if batch is recurring then weekdays and recurring_type is required
        if(isset($_POST['recurring']))
            if($_POST['recurring'])
            {
                $this->form_validation
                ->set_rules('recurring_type', lang('batches_recurring_types'), 'trim|required|in_list[every_week,first_week,second_week,third_week,fourth_week]');
            }

        if($this->form_validation->run() === FALSE)
        {
            // for fetching specific fields errors in order to view errors on each field seperately
            $error_fields = array();
            foreach($_POST as $key => $val)
                if(form_error($key))
                    $error_fields[] = $key;
            
            echo json_encode(array('flag' => 0, 'msg' => validation_errors(), 'error_fields' => json_encode($error_fields)));
            exit;
        }

        // data to insert in db table
        $data                           = array();
        $data['title']                  = strtolower($this->input->post('title'));
        $data['description']            = $this->input->post('description');
        $data['fees']                   = (int) $this->input->post('fees');
        $data['capacity']               = (int) $this->input->post('capacity');
        $data['recurring']              = $this->input->post('recurring') ? $this->input->post('recurring') : '0';  
        $data['status']                 = $this->input->post('status');  
        $data['courses_id']             = (int) $this->input->post('courses');
        $data['weekdays']               = isset($_POST['weekdays']) 
                                            ? json_encode($this->input->post('weekdays[]'))
                                            : json_encode(array('0'=>'0'));
        $data['start_time']             = date("H:i", strtotime($this->input->post('start_time')));
        $data['end_time']               = date("H:i", strtotime($this->input->post('end_time')));
        // set start & end time for db
        $start_end_date             = explode(' - ', $this->input->post('start_end_date'));
        $data['start_date']         = $start_end_date[0];
        $data['end_date']           = $start_end_date[1];
        // convert_to_mysql_date
        $data['start_date']         = date('Y-m-d', strtotime(str_replace('-', '/', $data['start_date'])));
        $data['end_date']           = date('Y-m-d', strtotime(str_replace('-', '/', $data['end_date'])));    

        // only if event is recurring
        if($data['recurring'])
        {
            $data['weekdays']               = isset($_POST['weekdays']) 
                                                ? json_encode($this->input->post('weekdays[]'))
                                                : json_encode(array('0'=>'0'));
            
            $data['recurring_type']         = $this->input->post('recurring_type');    
        }
        
        $data_2                         = $this->input->post('tutors');

        $flag                           = $this->batches_model->save_batches($data, $data_2, $id, $result_tutors);

        if($flag)
        {
            // add batch notification when new batch inserted
            if(!$id) 
            {
                $notification   = array(
                    'users_id'  => $this->user['id'],
                    'n_type'    => 'batches',
                    'n_content' => 'noti_new_added',
                    'n_url'     => site_url('admin/batches'), 
                );
                $this->notifications_model->save_notifications($notification);    
            }
            
            if($id)
                $this->session->set_flashdata('message', sprintf(lang('alert_update_success'), lang('menu_batch')));
            else
                $this->session->set_flashdata('message', sprintf(lang('alert_insert_success'), lang('menu_batch')));

            echo json_encode(array(
                                    'flag'  => 1, 
                                    'msg'   => $this->session->flashdata('message'),
                                    'type'  => 'success',
                                ));
            exit;
        }
        
        if($id)
            $this->session->set_flashdata('error', sprintf(lang('alert_update_fail'), lang('menu_batch')));
        else
            $this->session->set_flashdata('error', sprintf(lang('alert_insert_fail'), lang('menu_batch')));

        echo json_encode(array(
                                'flag'  => 0, 
                                'msg'   => $this->session->flashdata('message'),
                                'type'  => 'fail',
                            ));
        exit;
    }

    /**
     * view
     */
    public function view($id = NULL)
    {
        if(!$this->acl->get_method_permission($_SESSION['groups_id'], 'batches', 'p_view'))
        {
            $this->session->set_flashdata('error', sprintf(lang('manage_acl_permission_no'), lang('menu_batch').' '.lang('manage_acl_view')));
            redirect($this->uri->segment(1).'/dashboard');
        }

        /* Initialize Assets */
        $data = $this->includes;

        /* Get Data */
        $id         = (int) $id;
        $result     = $this->batches_model->get_batches_by_id($id);

        if(empty($result))
        {
            $this->session->set_flashdata('error', sprintf(lang('alert_not_found'), lang('menu_batch')));
            /* Redirect */
            redirect('admin/batches');            
        }

        // data to insert in db table
        $data['id']                     = $result->id;
        $data['title']                  = $result->title;
        $data['description']            = $result->description;
        $data['fees']                   = $result->fees.' '.$this->settings->default_currency;
        $data['capacity']               = $result->capacity;
        $data['status']                 = $result->status;
        $data['date_added']             = $result->date_added;
        $data['date_updated']           = $result->date_updated;
        $data['status']                 = $result->status;
        $data['courses_title']          = $result->courses_title;
        $data['courses_id']             = $result->courses_id;
        $data['recurring']              = $result->recurring;
        $data['weekdays']   = array(
            '0' => lang('batches_weekdays_sun'), 
            '1' => lang('batches_weekdays_mon'),
            '2' => lang('batches_weekdays_tue'), 
            '3' => lang('batches_weekdays_wed'), 
            '4' => lang('batches_weekdays_thu'), 
            '5' => lang('batches_weekdays_fri'), 
            '6' => lang('batches_weekdays_sat'), 
        );
        $_POST['weekdays']              = json_decode($result->weekdays);
        $data['recurring_types']        = array(
                                            'every_week'    => lang('events_recurring_types_all'),
                                            'first_week'    => lang('events_recurring_types_first'), 
                                            'second_week'   => lang('events_recurring_types_second'), 
                                            'third_week'    => lang('events_recurring_types_third'), 
                                            'fourth_week'   => lang('events_recurring_types_fourth'), 
                                        );
        $_POST['recurring_type']        = $result->recurring_type;
        $data['start_date']             = date("M j Y", strtotime($result->start_date));
        $data['end_date']               = $result->end_date ? date("M j Y", strtotime($result->end_date)) : 'N/A';
        $data['start_time']             = date("g:i A", strtotime($result->start_time));
        $data['end_time']               = date("g:i A", strtotime($result->end_time));
        $data['tutors']                 = $this->batches_model->get_batches_tutors($result->id);

        /* Load Template */
        $content['content']    = $this->load->view('admin/batches/view', $data, TRUE);
        $this->load->view($this->template, $content);
    }

     /**
     * status_update
     */
    public function status_update()
    {
        if(!$this->acl->get_method_permission($_SESSION['groups_id'], 'batches', 'p_edit'))
        {
            echo '<p>'.sprintf(lang('manage_acl_permission_no'), lang('manage_acl_edit')).'</p>';exit;
        }
        /* Validate form input */
        $this->form_validation
        ->set_rules('id', sprintf(lang('alert_id'), lang('menu_batch')), 'required|numeric')
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
            $this->session->set_flashdata('message', sprintf(lang('alert_not_found'), lang('menu_batch')));
            echo json_encode(array(
                                    'flag'  => 0, 
                                    'msg'   => $this->session->flashdata('message'),
                                    'type'  => 'fail',
                                ));
            exit;
        }
        
        $flag                       = $this->batches_model->save_batches($data, array(), $id);

        if($flag)
        {
            echo json_encode(array(
                                    'flag'  => 1, 
                                    'msg'   => sprintf(lang('alert_status_success'), lang('menu_batch')),
                                    'type'  => 'success',
                                ));
            exit;
        }

        echo json_encode(array(
                            'flag'  => 0, 
                            'msg'   => sprintf(lang('alert_status_fail'), lang('menu_batch')),
                            'type'  => 'fail',
                        ));
        exit;
    }

    /**
     * delete
     */
    public function delete($id = NULL)
    {
        if(!$this->acl->get_method_permission($_SESSION['groups_id'], 'batches', 'p_delete'))
        {
            echo '<p>'.sprintf(lang('manage_acl_permission_no'), lang('manage_acl_delete')).'</p>';exit;
        }

        /* Validate form input */
        $this->form_validation->set_rules('id', sprintf(lang('alert_id'), lang('menu_course')), 'required|numeric');

        /* Data */
        $id                     = (int) $this->input->post('id');
        $result                 = $this->batches_model->get_batches_by_id($id);
        
        if(empty($result))
        {
            echo json_encode(array(
                                    'flag'  => 0, 
                                    'msg'   => sprintf(lang('alert_not_found') ,lang('menu_batch')),
                                    'type'  => 'fail',
                                ));exit;
        }

        /*Get Tutors*/
        $result_tutors          = $this->batches_model->get_batches_tutors($result->id);
        
        $flag                   = $this->batches_model->delete_batches($id, $result->title, $result_tutors);

        if($flag)
        {
            echo json_encode(array(
                                'flag'  => 1, 
                                'msg'   => sprintf(lang('alert_delete_success'), lang('menu_batch')),
                                'type'  => 'success',
                            ));exit;
        }
        
        echo json_encode(array(
                            'flag'  => 0, 
                            'msg'   => sprintf(lang('alert_delete_fail'), lang('menu_batch')),
                            'type'  => 'fail',
                        ));exit;
    }


    /*========= Validation callback functions ==================*/

    /**
     * validate start_end_date range
     *
     * @param  string $start_end_date
     * @return boolean
     */
    function _check_start_end_date($start_end_date)
    {
        $start_date = explode(' - ' ,$start_end_date)[0];
        $end_date   = explode(' - ' ,$start_end_date)[1];

        // convert_to_mysql_date
        $start_date = date('Y-m-d', strtotime(str_replace('-', '/', $start_date)));
        $end_date   = date('Y-m-d', strtotime(str_replace('-', '/', $end_date)));
        $today_date = date('Y-m-d');

        if ($start_date < $end_date) // first check for start and end date
        {
            // for checking today's date with start date
            // if ($start_date >= $today_date)
            // {
            //     return TRUE;
            // }

            // $this->form_validation->set_message('_check_start_end_date', sprintf(lang('batches_start_today_date_error'), $start_date, $today_date));
            // return FALSE;
            return TRUE;
        }

        $this->form_validation->set_message('_check_start_end_date', sprintf(lang('batches_start_end_date_error'), $start_date, $end_date));
        return FALSE;
    }

    /**
     * validate weekdays 
     *
     * @param  string $weekdays
     * @return boolean
     */
    /*function _check_weekdays($weekdays)
    {
        if (!empty($weekdays)) // atleast one weekday
            return TRUE;
            
        $this->form_validation->set_message('_check_weekdays', lang('batches_weekdays_error'));
        return FALSE;
    }*/

}

/* Batches controller ends */