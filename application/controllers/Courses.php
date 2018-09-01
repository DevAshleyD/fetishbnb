<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Courses Controller
 *
 * This class handles courses listings module functionality
 *
 * @package     classiebit
 * @author      prodpk
*/

class Courses extends Public_Controller {

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

        // get user ids by group tutor = 2
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
	function index($category = NULL)
	{
        /* Initialize assets and title */
        $this
        ->add_plugin_theme(array(
            'vmenu-module/vmenuModule.js',
            'easy-autocomplete/easy-autocomplete.min.css',
            'easy-autocomplete/easy-autocomplete.themes.min.css',
            'easy-autocomplete/jquery.easy-autocomplete.min.js',
        ), 'default')
        ->add_js_theme( "pages/courses_listings/index_i18n.js", TRUE );

        // setup page header data
        $this->set_title(lang('menu_courses'));

        $data = $this->includes;

        $category                   = $category ? str_replace('+', ' ', urldecode($category)) : NULL;

        $courses                    = $this->get_courses_by_category($category);
        
        // set content data
        $content_data['category']   = $category;
        $content_data['courses']    = $courses['courses'];
        $content_data['tutors']     = $courses['tutors'];
        $content_data['p_courses']  = $this->course_model->top_courses_list();

        // load views
        $data['content'] = $this->load->view('courses_listings', $content_data, TRUE);
		$this->load->view($this->template, $data);
	}

    /**
     * detail
    */
    function detail($course_title = NULL)
    {
        /* Initialize assets and title */
        $this
        ->add_plugin_theme(array(
            "owl-carousel/owl.carousel.css",
            "owl-carousel/owl.carousel.min.js",
        ), 'default')
        ->add_js_theme( "pages/courses_listings/detail.js");

        // setup page header data
        $this->set_title(lang('menu_course'));
        $data = $this->includes;

        $course_title                        = $course_title ? str_replace('+', ' ', urldecode($course_title)) : NULL;
        
        if(! $course_title)
            show_404();    
        
        // set content data
        $content_data['course_detail']       = $this->course_model->get_course_detail($course_title);

        if(empty($content_data['course_detail']))
            show_404();
        
        if($content_data['course_detail']->meta_title)
            $this->meta_title                = $content_data['course_detail']->meta_title;
        if($content_data['course_detail']->meta_tags)
            $this->meta_tags                 = $content_data['course_detail']->meta_tags;
        if($content_data['course_detail']->meta_description)
            $this->meta_description          = $content_data['course_detail']->meta_description;
        if($content_data['course_detail']->images)
            $this->meta_image                = base_url('upload/courses/images/').json_decode($content_data['course_detail']->images)[0];
        if($content_data['course_detail']->title)
            $this->meta_url                  = site_url('courses/detail/').str_replace(' ', '+', $content_data['course_detail']->title);

        // related courses
        // render top-level category
        $categories_levels                  = $this->courses_model->get_courses_levels($content_data['course_detail']->course_categories_id);
        $count_level                        = count($categories_levels);
        $top_level_category                 = $categories_levels[$count_level-1]; // the last category will be top category
        $content_data['related_courses']    = $this->get_courses_by_category($top_level_category['title'])['courses'];
        
        if($content_data['course_detail']->total_tutors)
            $content_data['course_tutors'] = $this->course_model->get_courses_tutors($content_data['course_detail']->id);
        
        // load views
        $data['content'] = $this->load->view('courses_detail', $content_data, TRUE);
        $this->load->view($this->template, $data);
    }

    /**
     * search_categories
    */
    function search_categories()
    {
        $this->form_validation->set_rules('phrase', lang('action_search'), 'trim|required|alpha_dash_spaces');

        if($this->form_validation->run() === FALSE)
        {
            echo json_encode(array());exit;
        }

        $search     = $this->input->post('phrase');

        $categories = $this->course_model->get_categories($search);

        if(empty($categories))
        {
            echo json_encode(array());exit;   
        }

        $output = array();
        foreach($categories as $key => $val)
        {
            $output[$key]['text'] = $val->title;
            $output[$key]['link'] = site_url('courses').'/'.str_replace(' ', '+', $val->title);
        }

        echo json_encode($output);exit;
    }    

}

/* Courses controller ends */