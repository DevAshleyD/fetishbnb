<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Tutors Controller
 *
 * This class handles courses listings module functionality
 *
 * @package     classiebit
 * @author      prodpk
*/

class Tutors extends Public_Controller {

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


    private function get_courses_by_category($category = NULL)
    {
        $categories                         = array();
        if($category)
        {
            $category_id                    = $this->course_model->get_course_categories_id($category);

            if(empty($category_id))
                show_404();

            $categories                     = $this->categories_model->get_course_categories_levels_status_update($category_id->id);
            // insert top-level category id at first
            array_unshift($categories, array('id'=>$category_id->id));

            foreach($categories as $key => $val)
                $categories[$key] = $val['id'];    
        }
        
        $data['courses']                    = $this->course_model->get_courses($categories);

        $tutor_ids                          = array();
        foreach($this->ion_auth->get_users_by_group(2)->result() as $val)
            $tutor_ids[] = $val->user_id;

        $data['tutors']                     = $this->course_model->get_tutors($tutor_ids);

        foreach($data['courses'] as $key => $val)
            if($val->users_id)
                foreach($data['tutors'] as $v)
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

        $courses                    = $this->get_courses_by_category();
        
        // set content data
        $content_data['courses']    = $courses['courses'];
        $content_data['tutors']     = $courses['tutors'];
        
        // load views
        $data['content'] = $this->load->view('tutors', $content_data, TRUE);
        $this->load->view($this->template, $data);
    }

    /**
     * tutor
    */
    function tutor($username = NULL)
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
        
        $courses                    = $this->get_courses_by_category();
        
        // set content data
        $content_data['courses']    = $courses['courses'];
        $content_data['tutors']     = $courses['tutors'];
        $content_data['tutor']      = $this->course_model->get_courses_tutor($username);
        
        if(empty($content_data['tutor']))
            show_404();
        
        $content_data['tutor_courses']  = $this->course_model->get_tutor_courses($content_data['tutor']->id);
        $content_data['tutor_events']   = $this->event_model->get_tutor_events($content_data['tutor']->id);
        
        // load views
        $data['content'] = $this->load->view('tutor', $content_data, TRUE);
        $this->load->view($this->template, $data);
    }

}

/* Tutors controller ends */