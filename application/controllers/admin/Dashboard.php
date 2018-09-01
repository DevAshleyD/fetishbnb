<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends Admin_Controller {

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();

        $this->load->model(array(
                            'admin/batches_model',
                            'admin/events_model',
                            'users_model',
                            'course_model',
                            'event_model',
                        ));

        $this->load->library('visits_count');
    }


    /**
     * Dashboard
     */
    function index()
    {
        // setup page header data
		$this
            ->add_plugin_theme(array(
                // Jquery CountTo
                'jquery-countto/jquery.countTo.js',
                // Amcharts
                'amcharts/amcharts.js',
                'amcharts/serial.js',
            ), 'admin')
			->add_js_theme( "pages/dashboard_i18n.js", TRUE )
			->set_title( lang('menu_dashboard') );
		
        $data = $this->includes;

        // Breadcrumb
        $data['breadcrumb'][0]     = array('icon'=>'dashboard','route_name'=>lang('menu_dashboard'),'route_path'=>site_url('admin'));

        $data['total_users']       = $this->users_model->count_users();
        $data['total_bookings']    = $this->course_model->count_b_bookings()+$this->event_model->count_e_bookings();
        $data['total_batches']     = $this->course_model->count_batches();
        $data['total_events']      = $this->event_model->count_events();
        $data['total_visits']      = $this->visits_count->get_visitors();
        $data['total_sales']       = $this->course_model->count_total_b_sales()+$this->event_model->count_total_e_sales();
        $data['todays_b_e']        = $this->course_model->todays_batches_list()+$this->event_model->todays_events_list();
        $data['top_batches']       = $this->batches_model->top_batches_list();
        $data['top_events']        = $this->events_model->top_events_list();

        // load views
        $content['content']        = $this->load->view('admin/dashboard', $data, TRUE);
        $this->load->view($this->template, $content);
    }

}
