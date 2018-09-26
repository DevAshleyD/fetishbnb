<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Events Controller
 *
 * This class handles events module functionality
 *
 * @package     classiebit
 * @author      prodpk
*/

class Events extends Admin_Controller {

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();

        $this->load->model('admin/events_model');

        /* Page Title */
        $this->set_title( lang('menu_events') );
    }

    /**
     * index
     */
    function index()
    {
        if(!$this->acl->get_method_permission($_SESSION['groups_id'], 'events', 'p_view'))
        {
            $this->session->set_flashdata('error', sprintf(lang('manage_acl_permission_no'), lang('menu_event').' '.lang('manage_acl_view')));
            redirect($this->uri->segment(1).'/dashboard');
        }

        /* Initialize assets */
        $this->include_index_plugins();
        $data = $this->includes;

        // Table Header
        $data['t_headers']  = array(
                                '#',
                                lang('common_title'),
                                lang('events_start_date'),
                                lang('events_end_date'),
                                lang('events_start_time'),
                                lang('events_end_time'),
                                lang('events_type'),
                                lang('common_updated'),
                                lang('common_featured'),
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

        $table              = 'events';
        $columns            = array(
                                "$table.id",
                                "$table.title",
                                "$table.start_date",
                                "$table.end_date",
                                "$table.start_time",
                                "$table.end_time",
                                "$table.recurring",
                                "$table.event_types_id",
                                "$table.featured",
                                "$table.status",
                                "$table.date_updated",
                                "(SELECT et.title FROM event_types et WHERE et.id = $table.event_types_id) event_type_name",
                            );
        $columns_order      = array(
                                "#",
                                "$table.title",
                                "$table.start_date",
                                "$table.end_date",
                                "$table.start_time",
                                "$table.end_time",
                                "$table.event_types_id",
                                "$table.date_updated",
                                "$table.featured",
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
            $row[]          = $val->event_type_name;
            $row[]          = date('g:iA d/m/y', strtotime($val->date_updated));
            $row[]          = featured_switch($val->featured, $val->id);
            $row[]          = status_switch($val->status, $val->id);
            $row[]          = action_buttons('events', $val->id, mb_substr($val->title, 0, 20, 'utf-8'), lang('menu_event'));
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
        ->add_js_theme( "pages/events/form_i18n.js", TRUE );
        $data                       = $this->includes;

        // in case of edit
        $id                         = (int) $id;
        $result                     = (object) (array());
        if($id)
        {
            $result                 = $this->events_model->get_events_by_id($id);

            if(empty($result))
            {
                $this->session->set_flashdata('error', sprintf(lang('alert_not_found') ,lang('menu_event')));
                redirect($this->uri->segment(1).'/'.$this->uri->segment(2));
            }

            // hidden field in case of update
            $data['id']             = $result->id;

            // current images
            $data['c_images']   = json_decode($result->images);

            /*Get Tutors*/
            $result_tutors          = $this->events_model->get_events_tutors($result->id);
            foreach($result_tutors as $key => $val)
                $result_tutors[$key] = $val->users_id;

            $_POST['tutors']  = $result_tutors;

            // For start time
            $result->start_time     = date("g:i A", strtotime($result->start_time));
            $result->start_time_1   = explode(':', $result->start_time)[0];
            $result->start_time_2   = explode(':', $result->start_time)[1];
            $result->start_time_3   = explode(' ', $result->start_time_2)[1];

            // For End time
            $result->end_time       = date("g:i A", strtotime($result->end_time));
            $result->end_time_1     = explode(':', $result->end_time)[0];
            $result->end_time_2     = explode(':', $result->end_time)[1];
            $result->end_time_3     = explode(' ', $result->end_time_2)[1];

            // For Weekdays
            $_POST['weekdays']      = json_decode($result->weekdays);

            // For Recurring
            $_POST['recurring']     = $result->recurring;
        }

        // get user ids by group tutor = 2
        $tutor_ids                          = array();
        foreach($this->ion_auth->get_users_by_group(2)->result() as $val)
            $tutor_ids[] = $val->user_id;

        // render tutors dropdown
        $tutors                         = $this->events_model->get_users_dropdown($tutor_ids);
        foreach($tutors as $val)
            $data['tutors_o'][$val->id] = $val->first_name.' '.$val->last_name.' ('.$val->profession.')';

        $data['tutors']   = array(
            'name'          => 'tutors[]',
            'id'            => 'tutors',
            'class'         => 'form-control show-tick text-capitalize',
            'multiple'      => 'multiple',
            'data-live-search'=>"true",
            'options'       => $data['tutors_o'],
            'selected'      => $this->form_validation->set_value('tutors[]'),
        );

        // render event_types dropdown
        $event_types                        = $this->events_model->get_event_types_dropdown();
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
            '0' => lang('events_weekdays_sun'),
            '1' => lang('events_weekdays_mon'),
            '2' => lang('events_weekdays_tue'),
            '3' => lang('events_weekdays_wed'),
            '4' => lang('events_weekdays_thu'),
            '5' => lang('events_weekdays_fri'),
            '6' => lang('events_weekdays_sat'),
        );
        if(!empty($result->recurring_type))
            $_POST['recurring_type']= $result->recurring_type;
        else
            $_POST['recurring_type']= 'every_week';

        $data['recurring_types']   = array(
            'every_week'    => lang('events_recurring_types_all'),
            'first_week'    => lang('events_recurring_types_first'),
            'second_week'   => lang('events_recurring_types_second'),
            'third_week'    => lang('events_recurring_types_third'),
            'fourth_week'   => lang('events_recurring_types_fourth'),
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
        $data['images']     = array(
            'name'          => 'images[]',
            'id'            => 'images',
            'type'          => 'file',
            'multiple'      => 'multiple',
            'class'         => 'form-control',
            'accept'        => 'image/*',
        );
        $data['meta_title']= array(
            'name'          => 'meta_title',
            'id'            => 'meta_title',
            'type'          => 'text',
            'class'         => 'form-control',
            'value'         => $this->form_validation->set_value('meta_title', !empty($result->meta_title) ? $result->meta_title : ''),
        );
        $data['meta_tags']= array(
            'name'          => 'meta_tags',
            'id'            => 'meta_tags',
            'type'          => 'text',
            'class'         => 'form-control',
            'value'         => $this->form_validation->set_value('meta_tags', !empty($result->meta_tags) ? $result->meta_tags : ''),
        );
        $data['meta_description']= array(
            'name'          => 'meta_description',
            'id'            => 'meta_description',
            'type'          => 'textarea',
            'class'         => 'form-control',
            'value'         => $this->form_validation->set_value('meta_description', !empty($result->meta_description) ? $result->meta_description : ''),
        );
        $data['featured'] = array(
            'name'      => 'featured',
            'id'        => 'featured',
            'class'     => 'form-control',
            'options'   => array('1' => lang('common_featured_enabled'), '0' => lang('common_featured_disabled')),
            'selected'  => $this->form_validation->set_value('featured', !empty($result->featured) ? $result->featured : 0),
        );
        $data['status'] = array(
            'name'      => 'status',
            'id'        => 'status',
            'class'     => 'form-control',
            'options'   => array('1' => lang('common_status_active'), '0' => lang('common_status_inactive')),
            'selected'  => $this->form_validation->set_value('status', !empty($result->status) ? $result->status : 0),
        );

        /* Load Template */
        $content['content']    = $this->load->view('admin/events/form', $data, TRUE);
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
            if(!$this->acl->get_method_permission($_SESSION['groups_id'], 'events', 'p_edit'))
            {
                echo '<p>'.sprintf(lang('manage_acl_permission_no'), lang('manage_acl_edit')).'</p>';exit;
            }

            $id                     = (int) $this->input->post('id');
            $result                 = $this->events_model->get_events_by_id($id);

            if(empty($result))
            {
                $this->session->set_flashdata('message', sprintf(lang('alert_not_found'), lang('menu_event')));
                echo json_encode(array(
                                        'flag'  => 0,
                                        'msg'   => $this->session->flashdata('message'),
                                        'type'  => 'fail',
                                    ));
                exit;
            }

            /*Get Tutors*/
            $result_tutors       = $this->events_model->get_events_tutors($result->id);
            foreach($result_tutors as $key => $val)
                $result_tutors[$key] = $val->users_id;
        }
        else
        {
            if(!$this->acl->get_method_permission($_SESSION['groups_id'], 'events', 'p_add'))
            {
                echo '<p>'.sprintf(lang('manage_acl_permission_no'), lang('manage_acl_add')).'</p>';exit;
            }
        }

        /* Validate form input */
        $this->form_validation
        ->set_rules('title', lang('common_title'), 'trim|required|alpha_dash_spaces|min_length[3]|max_length[250]')
        ->set_rules('description', lang('common_description'), 'trim')
        ->set_rules('fees', lang('events_fees'), 'trim|required|is_natural')
        ->set_rules('capacity', lang('events_capacity'), 'trim|required|is_natural_no_zero')
        ->set_rules('recurring', lang('events_recurring'), 'trim')
        ->set_rules('start_end_date', lang('events_start_end_date'), 'trim|required')
        ->set_rules('start_time', lang('events_start_time'), 'trim|required')
        ->set_rules('end_time', lang('events_end_time'), 'trim|required')
        ->set_rules('meta_title', lang('common_meta_title'), 'trim|max_length[128]')
        ->set_rules('meta_tags', lang('common_meta_tags'), 'trim|max_length[256]')
        ->set_rules('meta_description', lang('common_meta_description'), 'trim')
        ->set_rules('featured', lang('common_featured'), 'required|in_list[0,1]')
        ->set_rules('status', lang('common_status'), 'required|in_list[0,1]')
        ->set_rules('event_types', lang('events_type'), 'trim|required|is_natural_no_zero')
        ->set_rules('tutors[]', lang('events_tutors'), 'trim|required|is_natural_no_zero');

        // if event is recurring then weekdays and recurring_type is required
        if(isset($_POST['recurring']))
            if($_POST['recurring'])
            {
                $this->form_validation
                ->set_rules('recurring_type', lang('events_recurring_types'), 'trim|required|in_list[every_week,first_week,second_week,third_week,fourth_week]');
            }

        // update events image
        if(! empty($_FILES['images']['name'][0])) // if image
        {
            $files         = array('folder'=>'events/images', 'input_file'=>'images');

            // Remove old image
            if($id)
                if(!empty($result->images))
                    $this->file_uploads->remove_files('./upload/'.$files['folder'].'/', json_decode($result->images));

            // update events image
            $filenames     = $this->file_uploads->upload_files($files);
            // through image upload error
            if(!empty($filenames['error']))
                $this->form_validation->set_rules('image_error', lang('common_images'), 'required', array('required'=>$filenames['error']));
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
        $data['meta_title']             = $this->input->post('meta_title');
        $data['meta_tags']              = $this->input->post('meta_title');
        $data['meta_description']       = $this->input->post('meta_description');
        $data['featured']               = $this->input->post('featured');
        $data['status']                 = $this->input->post('status');
        $data['event_types_id']         = (int) $this->input->post('event_types');
        $data['start_time']             = date("H:i", strtotime($this->input->post('start_time')));
        $data['end_time']               = date("H:i", strtotime($this->input->post('end_time')));
        // set start & end time for db
        $start_end_date                 = explode(' - ', $this->input->post('start_end_date'));
        $data['start_date']             = $start_end_date[0];
        $data['end_date']               = $start_end_date[1];
        // convert_to_mysql_date
        $data['start_date']             = date('Y-m-d', strtotime(str_replace('-', '/', $data['start_date'])));
        $data['end_date']               = date('Y-m-d', strtotime(str_replace('-', '/', $data['end_date'])));
        $data['created_by']             = $this->user['id'];

        if(!empty($filenames) && !isset($filenames['error']))
            $data['images']             = json_encode($filenames);

        // only if event is recurring
        if($data['recurring'])
        {
            $data['weekdays']               = isset($_POST['weekdays'])
                                                ? json_encode($this->input->post('weekdays[]'))
                                                : json_encode(array('0'=>'0'));

            $data['recurring_type']         = $this->input->post('recurring_type');
        }

        $data_2                         = $this->input->post('tutors');

        $flag                           = $this->events_model->save_events($data, $data_2, $id, $result_tutors);

        if($flag)
        {
            // add batch notification when new batch inserted
            if(!$id)
            {
                $notification   = array(
                    'users_id'  => $this->user['id'],
                    'n_type'    => 'events',
                    'n_content' => 'noti_new_added',
                    'n_url'     => site_url('admin/events'),
                );
                $this->notifications_model->save_notifications($notification);
            }

            if($id)
                $this->session->set_flashdata('message', sprintf(lang('alert_update_success'), lang('menu_event')));
            else
                $this->session->set_flashdata('message', sprintf(lang('alert_insert_success'), lang('menu_event')));

            echo json_encode(array(
                                    'flag'  => 1,
                                    'msg'   => $this->session->flashdata('message'),
                                    'type'  => 'success',
                                ));
            exit;
        }

        if($id)
            $this->session->set_flashdata('error', sprintf(lang('alert_update_fail'), lang('menu_event')));
        else
            $this->session->set_flashdata('error', sprintf(lang('alert_insert_fail'), lang('menu_event')));

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
        if(!$this->acl->get_method_permission($_SESSION['groups_id'], 'events', 'p_view'))
        {
            $this->session->set_flashdata('error', sprintf(lang('manage_acl_permission_no'), lang('menu_event').' '.lang('manage_acl_view')));
            redirect($this->uri->segment(1).'/dashboard');
        }

        /* Initialize Assets */
        $data = $this->includes;

        /* Get Data */
        $id         = (int) $id;
        $result     = $this->events_model->get_events_by_id($id);

        if(empty($result))
        {
            $this->session->set_flashdata('error', sprintf(lang('alert_not_found'), lang('menu_event')));
            /* Redirect */
            redirect('admin/events');
        }

        // data to insert in db table
        $data['id']                     = $result->id;
        $data['title']                  = $result->title;
        $data['description']            = $result->description;
        $data['fees']                   = $result->fees.' '.$this->settings->default_currency;
        $data['capacity']               = $result->capacity;
        $data['meta_title']             = $result->meta_title;
        $data['meta_tags']              = $result->meta_tags;
        $data['meta_description']       = $result->meta_description;
        $data['images']                 = $result->images;
        $data['status']                 = $result->status;
        $data['date_added']             = $result->date_added;
        $data['date_updated']           = $result->date_updated;
        $data['featured']               = $result->featured;
        $data['status']                 = $result->status;
        $data['event_types_title']      = $result->event_types_title;
        $data['event_types_id']         = $result->event_types_id;
        $data['recurring']              = $result->recurring;
        $data['weekdays']               = array(
                                            '0' => lang('events_weekdays_sun'),
                                            '1' => lang('events_weekdays_mon'),
                                            '2' => lang('events_weekdays_tue'),
                                            '3' => lang('events_weekdays_wed'),
                                            '4' => lang('events_weekdays_thu'),
                                            '5' => lang('events_weekdays_fri'),
                                            '6' => lang('events_weekdays_sat'),
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
        $data['start_date']             = $result->start_date ? date("M j Y", strtotime($result->start_date)) : lang('events_recurring');
        $data['end_date']               = $result->end_date ? date("M j Y", strtotime($result->end_date)) : 'N/A';
        $data['start_time']             = date("g:i A", strtotime($result->start_time));
        $data['end_time']               = date("g:i A", strtotime($result->end_time));
        $data['tutors']                 = $this->events_model->get_events_tutors($result->id);

        /* Load Template */
        $content['content']    = $this->load->view('admin/events/view', $data, TRUE);
        $this->load->view($this->template, $content);
    }

     /**
     * status_update
     */
    public function status_update()
    {
        if(!$this->acl->get_method_permission($_SESSION['groups_id'], 'events', 'p_edit'))
        {
            echo '<p>'.sprintf(lang('manage_acl_permission_no'), lang('manage_acl_edit')).'</p>';exit;
        }
        /* Validate form input */
        $this->form_validation
        ->set_rules('id', sprintf(lang('alert_id'), lang('menu_event')), 'required|numeric')
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
            $this->session->set_flashdata('message', sprintf(lang('alert_not_found'), lang('menu_event')));
            echo json_encode(array(
                                    'flag'  => 0,
                                    'msg'   => $this->session->flashdata('message'),
                                    'type'  => 'fail',
                                ));
            exit;
        }

        $flag                       = $this->events_model->save_events($data, array(), $id);

        if($flag)
        {
            echo json_encode(array(
                                    'flag'  => 1,
                                    'msg'   => sprintf(lang('alert_status_success'), lang('menu_event')),
                                    'type'  => 'success',
                                ));
            exit;
        }

        echo json_encode(array(
                            'flag'  => 0,
                            'msg'   => sprintf(lang('alert_status_fail'), lang('menu_event')),
                            'type'  => 'fail',
                        ));
        exit;
    }

    /**
     * featured_update
     */
    public function featured_update()
    {
        if(!$this->acl->get_method_permission($_SESSION['groups_id'], 'events', 'p_edit'))
        {
            echo '<p>'.sprintf(lang('manage_acl_permission_no'), lang('manage_acl_edit')).'</p>';exit;
        }
        /* Validate form input */
        $this->form_validation
        ->set_rules('id', sprintf(lang('alert_id'), lang('menu_event')), 'required|numeric')
        ->set_rules('featured', lang('common_featured'), 'required|in_list[0,1]');

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
        $data['featured']             = $this->input->post('featured');

        if(empty($id))
        {
            $this->session->set_flashdata('message', sprintf(lang('alert_not_found'), lang('menu_event')));
            echo json_encode(array(
                                    'flag'  => 0,
                                    'msg'   => $this->session->flashdata('message'),
                                    'type'  => 'fail',
                                ));
            exit;
        }

        $flag                       = $this->events_model->save_events($data, array(), $id);

        if($flag)
        {
            echo json_encode(array(
                                    'flag'  => 1,
                                    'msg'   => $data['featured'] ? sprintf(lang('alert_featured_added'), lang('menu_event')) : sprintf(lang('alert_featured_removed'), lang('menu_event')),
                                    'type'  => 'success',
                                ));
            exit;
        }

        echo json_encode(array(
                            'flag'  => 0,
                            'msg'   => sprintf(lang('alert_featured_fail'), lang('menu_event')),
                            'type'  => 'fail',
                        ));
        exit;
    }

    /**
     * delete
     */
    public function delete($id = NULL)
    {
        if(!$this->acl->get_method_permission($_SESSION['groups_id'], 'events', 'p_delete'))
        {
            echo '<p>'.sprintf(lang('manage_acl_permission_no'), lang('manage_acl_delete')).'</p>';exit;
        }
         /* Validate form input */
        $this->form_validation->set_rules('id', sprintf(lang('alert_id'), lang('menu_course')), 'required|numeric');

        /* Data */
        $id                     = (int) $this->input->post('id');
        $result                 = $this->events_model->get_events_by_id($id);

        if(empty($result))
        {
            echo json_encode(array(
                                    'flag'  => 0,
                                    'msg'   => sprintf(lang('alert_not_found') ,lang('menu_event')),
                                    'type'  => 'fail',
                                ));exit;
        }

        /*Get Tutors*/
        $result_tutors          = $this->events_model->get_events_tutors($result->id);

        $flag                   = $this->events_model->delete_events($id, $result->title, $result_tutors);

        if($flag)
        {
            // Remove events images
            if(!empty($result->images))
                $this->file_uploads->remove_files('./upload/events/', json_decode($result->images));

            echo json_encode(array(
                                'flag'  => 1,
                                'msg'   => sprintf(lang('alert_delete_success'), lang('menu_event')),
                                'type'  => 'success',
                            ));exit;
        }

        echo json_encode(array(
                            'flag'  => 0,
                            'msg'   => sprintf(lang('alert_delete_fail'), lang('menu_event')),
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

            // $this->form_validation->set_message('_check_start_end_date', sprintf(lang('events_start_today_date_error'), $start_date, $today_date));
            // return FALSE;
            return TRUE;
        }

        $this->form_validation->set_message('_check_start_end_date', sprintf(lang('events_start_end_date_error'), $start_date, $end_date));
        return FALSE;
    }

    /**
     * validate weekdays
     *
     * @param  string $weekdays
     * @return boolean
     */
    // function _check_weekdays($weekdays)
    // {
    //     echo "<pre>";print_r($weekdays);exit;
    //     if (!empty($weekdays)) // atleast one weekday
    //         return TRUE;

    //     $this->form_validation->set_message('_check_weekdays', lang('events_weekdays_error'));
    //     return FALSE;
    // }

}

/* Events controller ends */
