<?php defined('BASEPATH') OR exit('No direct script access allowed'); 
/**
 * Faqs Controller
 *
 * This class handles faqs module functionality
 *
 * @package     classiebit
 * @author      prodpk
*/

class Faqs extends Admin_Controller {

	/**
     * Constructor
    **/
	function __construct()
	{
	    parent::__construct();
	    $this->load->model('admin/faqs_model');

        // Page Title
        $this->set_title( lang('menu_faqs') );
	}


	/**
     * index
     */
    function index()
    {
        if(!$this->acl->get_method_permission($_SESSION['groups_id'], 'faqs', 'p_view'))
        {
            $this->session->set_flashdata('error', sprintf(lang('manage_acl_permission_no'), lang('menu_faq').' '.lang('manage_acl_view')));
            redirect($this->uri->segment(1).'/dashboard');
        }

         /* Initialize assets */
        $this->include_index_plugins();
        $data = $this->includes;

        // Table Header
        $data['t_headers']  = array(
                                '#',
                                lang('faqs_question'),
                                lang('common_updated'),
                                lang('common_status'),
                                lang('action_action'),
                            );

        // load views
        $content['content'] = $this->load->view('admin/index', $data, TRUE);
        $this->load->view($this->template, $content);
    }

    /**
     * ajax_list
    */
    public function ajax_list()
    {
        $this->load->library('datatables');

        $table              = 'faqs';        
        $columns            = array(
                                "$table.id",
                                "$table.question",
                                "$table.answer",
                                "$table.status",
                                "$table.date_updated",
                            );
        $columns_order      = array(
                                '#',
                                "$table.question",
                                "$table.date_updated",
                                "$table.status",
                            );
        $columns_search     = array(
                                'question',
                            );
        $order              = array('date_updated' => 'DESC');
        
        $result             = $this->datatables->get_datatables($table, $columns, $columns_order, $columns_search, $order);
        $data               = array();
        $no                 = $_POST['start'];
        
        foreach ($result as $val) 
        {
            $no++;
            $row            = array();
            $row[]          = $no;
            $row[]          = $val->question;
            $row[]          = date('g:i A M j Y ', strtotime($val->date_updated));
            $row[]          = status_switch($val->status, $val->id);
            $row[]          = action_buttons('faqs', $val->id, mb_substr($val->question, 0, 50, 'utf-8'), lang('menu_faq'), $val);
            $data[]         = $row;
        }
 
        $output             = array(
                                "draw"              => $_POST['draw'],
                                "recordsTotal"      => $this->datatables->count_all(),
                                "recordsFiltered"   => $this->datatables->count_filtered(),
                                "data"              => $data,
                            );
        
        //output to json format
        echo json_encode($output);exit;
    }


    /**
     * form
    */
    public function form($id = NULL)
    {
         /* Initialize assets */
        $this->add_plugin_theme(array(   
                                "tinymce/tinymce.min.js",
                            ), 'admin');
        $this->add_js_theme( "pages/faqs/form_i18n.js", TRUE );
        $data                       = $this->includes;

        // in case of edit
        $id                         = (int) $id;
        if($id)
        {
            $result                 = $this->faqs_model->get_faqs_by_id($id);

            if(empty($result))
            {
                $this->session->set_flashdata('error', sprintf(lang('alert_not_found') ,lang('menu_faq')));
                redirect($this->uri->segment(1).'/'.$this->uri->segment(2));            
            }

            // hidden field in case of update
            $data['id']             = $result->id;
        }
		
        $data['question']   = array(
            'name'          => 'question',
            'id'            => 'question',
            'type'          => 'text',
            'class'         => 'form-control',
            'value'         => $this->form_validation->set_value('question', !empty($result->question) ? $result->question : ''),
        );
        $data['answer']     = array(
            'name'          => 'answer',
            'id'            => 'answer',
            'type'          => 'textarea',
            'class'         => 'tinymce form-control',
            'value'         => $this->form_validation->set_value('answer', !empty($result->answer) ? $result->answer : ''),
        );
        $data['status']     = array(
            'name'          => 'status',
            'id'            => 'status',
            'class'         => 'form-control show-tick',
            'options'       => array('1' => lang('common_status_active'), '0' => lang('common_status_inactive')),
            'selected'      => $this->form_validation->set_value('status'),
        );
        
        /* Load Template */
        $content['content']    = $this->load->view('admin/faqs/form', $data, TRUE);
        $this->load->view($this->template, $content);
    }

    /**
     * save
    */
    public function save()
    {
    	$id = NULL;
    	if(! empty($_POST['id']))
    	{
            if(!$this->acl->get_method_permission($_SESSION['groups_id'], 'faqs', 'p_edit'))
            {
                echo '<p>'.sprintf(lang('manage_acl_permission_no'), lang('manage_acl_edit')).'</p>';exit;
            }

            $id                     = (int) $this->input->post('id');

            $result                 = $this->faqs_model->get_faqs_by_id($id);

            if(empty($result))
            {
                $this->session->set_flashdata('message', sprintf(lang('alert_not_found'), lang('menu_faq')));
		        echo json_encode(array(
		                                'flag'  => 0, 
		                                'msg'   => $this->session->flashdata('message'),
		                                'type'  => 'fail',
		                            ));
		        exit;
            }
    	}
        else
        {
            if(!$this->acl->get_method_permission($_SESSION['groups_id'], 'faqs', 'p_add'))
            {
                echo '<p>'.sprintf(lang('manage_acl_permission_no'), lang('manage_acl_add')).'</p>';exit;
            }
        }

        /* Validate form input */
        $this->form_validation
        ->set_rules('question', lang('faqs_question'), 'trim|required|max_length[256]')
        ->set_rules('answer', lang('faqs_answer'), 'trim|required')
        ->set_rules('status', lang('common_status'), 'required|in_list[0,1]');

        if($this->form_validation->run() === FALSE)
        {
            // for fetching specific fields errors in order to view errors on each field seperately
            $error_fields = array();
            foreach($_POST as $key => $val)
                if(form_error($key))
                    $error_fields[] = $key;
            
            echo json_encode(array('flag' => 0, 'msg' => validation_errors(), 'error_fields' => json_encode($error_fields)));
            exit;
        }

        $data                           = array();
        $data['question'] 				= $this->input->post('question');
        $data['answer']					= $this->input->post('answer');
        $data['status']					= $this->input->post('status');

        $flag                           = $this->faqs_model->save_faqs($data, $id);

        if($flag)
        {
        	if($id)
            	$this->session->set_flashdata('message', sprintf(lang('alert_update_success'), lang('menu_faq')));
            else
            	$this->session->set_flashdata('message', sprintf(lang('alert_insert_success'), lang('menu_faq')));

            echo json_encode(array(
                                    'flag'  => 1, 
                                    'msg'   => $this->session->flashdata('message'),
                                    'type'  => 'success',
                                ));
            exit;
        }
        
        if($id)
        	$this->session->set_flashdata('error', sprintf(lang('alert_update_fail'), lang('menu_faq')));
        else
        	$this->session->set_flashdata('error', sprintf(lang('alert_insert_fail'), lang('menu_faq')));

        echo json_encode(array(
                                'flag'  => 0, 
                                'msg'   => $this->session->flashdata('message'),
                                'type'  => 'fail',
                            ));
        exit;
    }

    /**
     * view
     */
    public function view($id = NULL)
    {

        if(!$this->acl->get_method_permission($_SESSION['groups_id'], 'faqs', 'p_view'))
        {
            $this->session->set_flashdata('error', sprintf(lang('manage_acl_permission_no'), lang('menu_faq').' '.lang('manage_acl_view')));
            redirect($this->uri->segment(1).'/dashboard');
        }
        
        /* Initialize assets */
        $data                   = $this->includes;

        /* Get Data */
        $id                     = (int) $id;
        $result                 = $this->faqs_model->get_faqs_by_id($id);

        if(empty($result))
        {
            $this->session->set_flashdata('error', sprintf(lang('alert_not_found') ,lang('menu_faq')));
            redirect($this->uri->segment(1).'/'.$this->uri->segment(2));            
        }

        $data['faqs']       	= $result;
        
        /* Load Template */
        $content['content']    = $this->load->view('admin/faqs/view', $data, TRUE);
        $this->load->view($this->template, $content);
    }

    /**
     * status_update
     */
    public function status_update()
    {
        if(!$this->acl->get_method_permission($_SESSION['groups_id'], 'faqs', 'p_edit'))
        {
            echo '<p>'.sprintf(lang('manage_acl_permission_no'), lang('manage_acl_edit')).'</p>';exit;
        }
        /* Validate form input */
        $this->form_validation
        ->set_rules('id', sprintf(lang('alert_id'), lang('menu_faq')), 'required|numeric')
        ->set_rules('status', lang('common_status'), 'required|in_list[0,1]');

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
        $data['status']             = $this->input->post('status');

        if(empty($id))
        {
            $this->session->set_flashdata('message', sprintf(lang('alert_not_found'), lang('menu_faq')));
            echo json_encode(array(
                                    'flag'  => 0, 
                                    'msg'   => $this->session->flashdata('message'),
                                    'type'  => 'fail',
                                ));
            exit;
        }
        
        $flag                       = $this->faqs_model->save_faqs($data, $id);

        if($flag)
        {
            echo json_encode(array(
                                    'flag'  => 1, 
                                    'msg'   => sprintf(lang('alert_status_success'), lang('menu_faq')),
                                    'type'  => 'success',
                                ));
            exit;
        }

        echo json_encode(array(
                            'flag'  => 0, 
                            'msg'   => sprintf(lang('alert_status_fail'), lang('menu_faq')),
                            'type'  => 'fail',
                        ));
        exit;
        
    }

    /**
     * delete
    */
    public function delete($id = NULL)
    {   
        if(!$this->acl->get_method_permission($_SESSION['groups_id'], 'faqs', 'p_delete'))
        {
            echo '<p>'.sprintf(lang('manage_acl_permission_no'), lang('manage_acl_delete')).'</p>';exit;
        }
        /* Validate form input */
        $this->form_validation->set_rules('id', sprintf(lang('alert_id'), lang('menu_faq')), 'required|numeric');

        /* Get Data */
        $id                 = (int) $this->input->post('id');
        $result             = $this->faqs_model->get_faqs_by_id($id);

        if(empty($result))
        {
            echo json_encode(array(
                                    'flag'  => 0, 
                                    'msg'   => sprintf(lang('alert_not_found') ,lang('menu_faq')),
                                    'type'  => 'fail',
                                ));exit;
        }

        $flag                   = $this->faqs_model->delete_faqs($id, $result);

        if($flag)
        {
            echo json_encode(array(
                                    'flag'  => 1, 
                                    'msg'   => sprintf(lang('alert_delete_success'), lang('menu_faq')),
                                    'type'  => 'success',
                                ));exit;
        }
        
        echo json_encode(array(
                            'flag'  => 0, 
                            'msg'   => sprintf(lang('alert_delete_fail'), lang('menu_faq')),
                            'type'  => 'fail',
                        ));
        exit; 
    }

}

/* Faqs controller ends */