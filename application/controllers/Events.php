<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Events Controller
 *
 * This class handles events listings module functionality
 *
 * @package     classiebit
 * @author      prodpk
*/

class Events extends Public_Controller {

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();

        $this->load->model(array(
                            'admin/events_model',
                            'admin/eventtypes_model',
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
        
        // get user ids by group tutor = 2
        $tutor_ids                          = array();
        foreach($this->ion_auth->get_users_by_group(2)->result() as $val)
            $tutor_ids[] = $val->user_id;

        $data['tutors']                     = $this->event_model->get_tutors($tutor_ids);

        foreach($data['events'] as $key => $val)
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
            'easy-autocomplete/easy-autocomplete.min.css',
            'easy-autocomplete/easy-autocomplete.themes.min.css',
            'easy-autocomplete/jquery.easy-autocomplete.min.js',
        ), 'default')
        ->add_js_theme( "pages/events_listings/index_i18n.js", TRUE );

        // setup page header data
        $this->set_title(lang('menu_events'));

        $data = $this->includes;

        $category                   = $category ? str_replace('+', ' ', urldecode($category)) : NULL;

        $events                     = $this->get_events_by_category($category);
        
        // set content data
        $content_data['category']   = $category;
        $content_data['events']     = $events['events'];
        $content_data['tutors']     = $events['tutors'];
        $content_data['p_events']   = $this->event_model->top_events_list();

        // load views
        $data['content'] = $this->load->view('events_listings', $content_data, TRUE);
		$this->load->view($this->template, $data);
	}

    /**
     * detail
    */
    function detail($event_title = NULL)
    {
        /* Initialize assets and title */
        $this
        ->add_plugin_theme(array(
            "owl-carousel/owl.carousel.css",
            "owl-carousel/owl.carousel.min.js",
            "theme-lib/counter.js",
            "theme-lib/countdown.js",
        ), 'default')
        ->add_js_theme( "pages/events_listings/detail.js");;

        // setup page header data
        $this->set_title(lang('menu_event'));
        $data = $this->includes;

        $event_title                  = $event_title ? str_replace('+', ' ', urldecode($event_title)) : NULL;
        
        if(! $event_title)
            show_404();    
        
        // set content data
        $content_data['event_detail']       = $this->event_model->get_event_detail($event_title);

        if(empty($content_data['event_detail']))
            show_404();

        if($content_data['event_detail']->meta_title)
            $this->meta_title               = $content_data['event_detail']->meta_title;
        if($content_data['event_detail']->meta_tags)
            $this->meta_tags                = $content_data['event_detail']->meta_tags;
        if($content_data['event_detail']->meta_description)
            $this->meta_description         = $content_data['event_detail']->meta_description;
        if($content_data['event_detail']->images)
            $this->meta_image                = base_url('upload/events/images/').json_decode($content_data['event_detail']->images)[0];
        if($content_data['event_detail']->title)
            $this->meta_url                  = site_url('events/detail/').str_replace(' ', '+', $content_data['event_detail']->title);

        $content_data['related_events']    = $this->get_events_by_category($content_data['event_detail']->category_name)['events'];
        
        if($content_data['event_detail']->total_tutors)
            $content_data['event_tutors'] = $this->event_model->get_events_tutors($content_data['event_detail']->id);

        $content_data['recurring_types']  = array(
                                                'every_week'    => lang('events_recurring_types_all'),
                                                'first_week'    => lang('events_recurring_types_first'), 
                                                'second_week'   => lang('events_recurring_types_second'), 
                                                'third_week'    => lang('events_recurring_types_third'), 
                                                'fourth_week'   => lang('events_recurring_types_fourth'), 
                                            );
        $content_data['weekdays']         = array(
                                                '0' => lang('events_weekdays_sun'), 
                                                '1' => lang('events_weekdays_mon'),
                                                '2' => lang('events_weekdays_tue'), 
                                                '3' => lang('events_weekdays_wed'), 
                                                '4' => lang('events_weekdays_thu'), 
                                                '5' => lang('events_weekdays_fri'), 
                                                '6' => lang('events_weekdays_sat'), 
                                            );
        // load views
        $data['content'] = $this->load->view('events_detail', $content_data, TRUE);
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

        $categories = $this->event_model->get_categories($search);

        if(empty($categories))
        {
            echo json_encode(array());exit;   
        }

        $output = array();
        foreach($categories as $key => $val)
        {
            $output[$key]['text'] = $val->title;
            $output[$key]['link'] = site_url('events').'/'.str_replace(' ', '+', $val->title);
        }

        echo json_encode($output);exit;
    }    

}

/* Events controller ends */