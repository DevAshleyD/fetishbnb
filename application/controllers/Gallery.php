<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Gallery Controller
 *
 * This class handles gallery module functionality
 *
 * @package     classiebit
 * @author      prodpk
*/

class Gallery extends Public_Controller {

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();

        $this->load->model(array(
                            'admin/gallaries_model',
                        ));
    }

    /**
	 * Default
     */
	function index()
	{
        /* Initialize assets and title */
        $this
        ->add_plugin_theme(array(
            "theme-lib/isotope.pkgd.min.js",
        ), 'default')
        ->set_title(lang('menu_gallary'));
        $data           = $this->includes;

        // set content data
        $content_data   = array(
            'gallaries'       => $this->gallaries_model->get_gallaries(),
        );



        // load views
        $data['content'] = $this->load->view('gallery', $content_data, TRUE);
		$this->load->view($this->template, $data);
	}

}

/* Gallery controller ends */