<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Hosts extends Public_Controller {

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();

        $this->load->model(array(
                            'admin/courses_model',
                            'admin/categories_model',
                            'course_model',
                            'event_model',
                            'users_model',
                        ));
    }


    private function get_events_by_category($category = NULL)
    {
        $category_id                         = NULL;
        if($category)
        {
            $category_id                    = $this->event_model->get_event_types_id($category);

            if(empty($category_id))
                show_404();

            $category_id                    = $category_id->id;
        }

        $data['events']                     = $this->event_model->get_events($category_id);

        // get user ids by group host = 4
        $tutor_ids                          = array();
        foreach($this->ion_auth->get_users_by_group(2)->result() as $val)
            $tutor_ids[] = $val->user_id;

        $data['hosts']                     = $this->event_model->get_tutors($tutor_ids);

        foreach($data['events'] as $key => $val)
            if($val->users_id)
                foreach($data['hosts'] as $v)
                    if($v->id === $val->users_id)
                        $val->tutor = $v;


        return $data;
    }

    /**
     * index
    */
    function index()
    {
        // setup page header data
        $this
        ->add_plugin_theme(array(
            "theme-lib/counter.js",
        ), 'default')
        ->set_title(lang('batches_tutors'));
        $data           = $this->includes;

        $events                    = $this->get_events_by_category();

        // set content data
        $content_data['events']    = $events['events'];
        $content_data['hosts']     = $events['hosts'];

        // load views
        $data['content'] = $this->load->view('hosts', $content_data, TRUE);
        $this->load->view($this->template, $data);
    }

    /**
     * tutor
    */
    function host($username = NULL)
    {
        /* Initialize assets and title */
        $this
        ->add_plugin_theme(array(
            "owl-carousel/owl.carousel.css",
            "owl-carousel/owl.carousel.min.js",
        ), 'default');

        // setup page header data
        $this->set_title(lang('users_role_tutor'));

        $data           = $this->includes;

        $username       = $username ? urldecode($username) : NULL;

        if(! $username)
            show_404();

        $events                    = $this->get_events_by_category();

        // set content data
        $content_data['courses']    = $events['courses'];
        $content_data['tutors']     = $events['tutors'];
        $content_data['tutor']      = $this->course_model->get_courses_tutor($username);

        if(empty($content_data['tutor']))
            show_404();

        $content_data['hosts_ecount']   = $this->event_model->get_tutor_events($content_data['host']->id);

        // load views
        $data['content'] = $this->load->view('hosts', $content_data, TRUE);
        $this->load->view($this->template, $data);
    }

}

/* Tutors controller ends */
