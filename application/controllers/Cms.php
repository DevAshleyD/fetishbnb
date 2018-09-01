<?php defined('BASEPATH') OR exit('No direct script access allowed'); 

/**
 * Cms Controller
 *
 * This class handles cms module functionality
 *
 * @package     classiebit
 * @author      prodpk
*/

class Cms extends Public_Controller {
	
	/**
     * Constructor
    **/	
	public function __construct()
	{
        parent::__construct();
		$this->load->model(array('admin/pages_model', 'admin/faqs_model'));
	}

	/**
     * index
    **/
	public function index($slug=false)
	{
		$result 				= $this->pages_model->get_pages_by_slug($slug);

        if($result->meta_title)
            $this->meta_title                = $result->meta_title;
        if($result->meta_tags)
            $this->meta_tags                 = $result->meta_tags;
        if($result->meta_description)
            $this->meta_description          = $result->meta_description;
        if($result->image)
            $this->meta_image                = base_url('upload/pages/images/').$result->image;
        if($result->slug)
            $this->meta_url                  = site_url('cms/').$result->slug;
        
        /* Page Title */
        $this
        ->set_title( (!empty($result->title) ? $result->title : '404') );
        
        $data = $this->includes;

        $data['row']			= $result;

        // load views
        $content['content'] = $this->load->view('page', $data, TRUE);
        $this->load->view($this->template, $content);
	}

	/**
     * faq
    **/	
	public function faq()
	{	
		/* Page Title */
        $this
        ->set_title( lang('menu_faq') )
        ->add_js_theme( "pages/pages/index.js");             
        $data = $this->includes;

        $data['faqs'] 		= $this->faqs_model->get_faqs();
        
        // load views
        $content['content'] = $this->load->view('faq', $data, TRUE);
        $this->load->view($this->template, $content);
	}

}	 		

/* Cms controller ends */