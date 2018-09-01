<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Welcome Controller
 *
 * This class handles welcome module functionality
 *
 * @package     classiebit
 * @author      prodpk
*/

class Welcome extends Public_Controller {

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();

        $this->load->model(array(
                            'admin/gallaries_model',
                            'admin/testimonials_model',
                            'admin/blogs_model',
                            'course_model',
                            'event_model',
                            'users_model',
                        ));
    }

    private function get_featured_c_e()
    {
        $data['f_courses']          = $this->course_model->get_f_courses();
        $data['f_events']           = $this->event_model->get_f_events();

        $tutor_ids                          = array();
        foreach($this->ion_auth->get_users_by_group(2)->result() as $val)
            $tutor_ids[] = $val->user_id;
        
        $data['tutors']             = $this->course_model->get_tutors($tutor_ids);

        foreach($data['f_courses'] as $key => $val)
            if($val->users_id)
                foreach($data['tutors'] as $v)
                    if($v->id === $val->users_id)
                        $val->tutor = $v;

        return $data;
    }

    /**
	 * Default
     */
	function index()
	{
        /* Initialize assets and title */
        $this
        ->add_plugin_theme(array(
            "owl-carousel/owl.carousel.css",
            "owl-carousel/owl.carousel.min.js",
            "theme-lib/counter.js",
            "theme-lib/isotope.pkgd.min.js",
            "theme-lib/jquery.mb.YTPlayer.min.js",
            // "theme-lib/jquery.stellar.min.js",
            'easy-autocomplete/easy-autocomplete.min.css',
            'easy-autocomplete/easy-autocomplete.themes.min.css',
            'easy-autocomplete/jquery.easy-autocomplete.min.js',
        ), 'default')
        ->add_js_theme( "pages/welcome/index_i18n.js", TRUE );

        // setup page header data
        $this->set_title(sprintf(lang('welcome title'), $this->settings->site_name));

        $data           = $this->includes;

        $c_e_data       = $this->get_featured_c_e();

        // set content data
        $content_data   = array(
            'gallaries'       => $this->gallaries_model->get_gallaries(6),
            'testimonials'    => $this->testimonials_model->get_testimonials(),
            'f_courses'       => $c_e_data['f_courses'],
            'f_events'        => $c_e_data['f_events'],
            'tutors'          => $c_e_data['tutors'],
            'count_courses'   => $this->course_model->count_courses(),
            'count_batches'   => $this->course_model->count_batches(),
            'count_events'    => $this->event_model->count_events(),
            'count_tutors'    => $this->users_model->count_users(),
            'u_events'        => $this->event_model->get_u_events(),
            'blogs'           => $this->blogs_model->get_blogs(3),
        );

        // load views
        $data['content'] = $this->load->view('welcome', $content_data, TRUE);
		$this->load->view($this->template, $data);
	}

}

/* Welcome controller ends */