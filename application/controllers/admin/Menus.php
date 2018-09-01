<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Menus Controller
 *
 * This class handles menus module functionality
 *
 * @package     classiebit
 * @author      prodpk
*/

class Menus extends Admin_Controller {

	/**
     * Constructor
    **/
	function __construct()
	{
		parent::__construct();
		$this->load->helper('my_func_helper');
		$this->load->model('admin/menus_model');
		$this->load->model('admin/pages_model');

        // Page Title
        $this->set_title( lang('menu_menus') );
	}
	
	/**
     * index
    **/
	function index($id=1)
	{
		if(!$this->acl->get_method_permission($_SESSION['groups_id'], 'pages', 'p_view'))
        {
            $this->session->set_flashdata('error', sprintf(lang('manage_acl_permission_no'), lang('menu_menus').' '.lang('manage_acl_view')));
            redirect($this->uri->segment(1).'/dashboard');
        }

		/* Initialize assets and title */
        $this
        ->add_plugin_theme(array(
            "jquery-ui/jquery-ui.min.css",
            "jquery-ui/jquery-ui.min.js",
            "netstedSortableAlpha/jquery.mjs.nestedSortable.css",
            "netstedSortableAlpha/jquery.mjs.nestedSortable.js",
        ), 'admin')
        ->add_js_theme(array("pages/menus/index.js"));
        $data = $this->includes;

        $id   = 1;
		$menu = $this->menus_model->get_menus_by_id($id);
		
		if($this->input->server('REQUEST_METHOD') === 'POST')
		{
            //validate form input
			$this->form_validation->set_rules('title',  lang('menu_menu').' '.lang('common_title'), 'required|trim');
			
			if ($this->form_validation->run() === TRUE)
			{
				$save = array('id'			=> $id,
							  'title'		=> $this->input->post('title'),
							  'slug'		=> url_title($this->input->post('title'), 'dash', TRUE),
							  'content'		=> $this->input->post('content'),
						);
				
				if ($this->menus_model->save_menus($save))
					$this->session->set_flashdata('message', sprintf(lang('alert_update_success'), lang('menu_menu')));

				redirect('admin/menus', 'refresh');
			}
		}
		
		$data['menus'] 			  = $this->menus_model->get_menus();
		$data['main'] 		      = $menu;
		$data['highestmenu'] 	  = 0;
		if(!empty($data['main']->content))
        {
			$mn_array 			= (array)json_decode($data['main']->content,true);
			foreach($mn_array as $mn)
				if($mn['id']>$data['highestmenu'])
					$data['highestmenu'] = $mn['id'];
		}
		
		$data['id'] 			= $id;
		$data['pages'] 			= $this->pages_model->get_pages();
		
		// load views
        $content['content'] 	= $this->load->view('admin/menus/index', $data, TRUE);
        $this->load->view($this->template, $content);
	}
	
	/**
     * ajax_save
    */
	function ajax_save()
	{
		if(!$this->acl->get_method_permission($_SESSION['groups_id'], 'pages', 'p_edit'))
        {
            echo '<p>'.sprintf(lang('manage_acl_permission_no'), lang('manage_acl_edit')).'</p>';exit;
        }
		$return = array();
		
		if($this->input->server('REQUEST_METHOD') === 'POST')
		{
			$id = 1; 
			$this->form_validation
			->set_rules('title', lang('menu_menu').' '.lang('common_title'), 'required|trim');
			
			if ($this->form_validation->run() === TRUE)
			{
				$save   = array(
                                'id'=>$id,
                                'title'=>$this->input->post('title'),
                                'content'=>json_encode($this->input->post('content')),
                            );
				
				if(empty($id))
				    $save['slug'] = url_title($this->input->post('title'), 'dash', TRUE);
				
				if ($this->menus_model->save_menus($save))
                {
					$return['status']     = 'Success';
					$return['result']     = sprintf(lang('alert_update_success'), lang('menu_menu'));
					
				}
			}
            else
            {
				$return['status'] = 'error';
				$return['result'] = validation_errors();
			}
		}
		
		echo json_encode($return);exit;
	}
	

}

/*End Menus Controller*/