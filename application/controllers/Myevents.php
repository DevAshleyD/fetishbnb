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
                            'event_model',
                            'admin/events_model',

                          ));
    }


    /**
     * index
     */
    function index()
    {
      // setup page header data
      $this
      ->add_plugin_theme(array(
          "jquery-datatable/datatables.min.css",
          "jquery-datatable/datatables.min.js",
          "font-awesome/css/font-awesome.min.css",
      ), 'default');

        $this->set_title( lang('menu_my_events'));
        $data = $this->includes;



        $content_data['my_events'] = $this->ebookings_model->get_my_events($this->user['id']);

        // load views
        $data['content'] = $this->load->view('my_events', $content_data, TRUE);
        $this->load->view($this->template, $data);
    }

    public function fget_hosts_events(){
      $id = $_GET['uid'];
      $host_events  = $this->event_model->get_tutor_events($id);


      $arr = '';
      foreach ($host_events as $event) {
        $e_detail     = $this->event_model->get_event_detail($event->title);
        $eventurl     = str_replace(' ','+', $e_detail->title);

        $arr[] =  array(
          $e_detail->title,
          $e_detail->fees,
          $e_detail->start_date,
          $e_detail->end_date,
          $e_detail->start_time,
          $e_detail->end_time,
          '<a href="'.base_url().'events/detail/'.$eventurl.'"><i class="material-icons">visibility</i></a>
           <a href="'.base_url().'myevents/edit/'.$e_detail->id.'"><i class="material-icons">edit</i></a>
           <a href="#"><i class="material-icons">delete_forever</i></a>',
        );
      }

      header('Content-Type: application/json');
      echo '{"data":'. json_encode( $arr ).'}';
    }

    public function edit_myevent($id = NULL){

      $this->set_title( lang('menu_my_events'));

      /* Initialize assets */
      $this
      ->add_plugin_theme(array(
                              "tinymce/tinymce.min.js",
                              "daterangepicker/daterangepicker.css",
                              "daterangepicker/moment.min.js",
                              "daterangepicker/daterangepicker.js",
                              "node-waves/waves.min.js",
                              "node-waves/waves.min.css",
                          ), 'admin')
      ->add_js_theme(array("pages/events/form_i18n.js","admin.js"), TRUE );

      $this
      ->add_plugin_theme(array(
          "customs/admin-styles.css",
          "customs/materialize.css",
      ), 'default');
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
      $content['content']    = $this->load->view('myevent_form', $data, TRUE);
      $this->load->view($this->template, $content);
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
