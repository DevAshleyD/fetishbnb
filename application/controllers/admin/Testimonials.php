<?php defined('BASEPATH') OR exit('No direct script access allowed'); 
/**
 * Testimonials Controller
 *
 * This class handles testimonials module functionality
 *
 * @package     classiebit
 * @author      prodpk
*/

class Testimonials extends Admin_Controller {

	/**
     * Constructor
    **/
	function __construct()
	{
	    parent::__construct();
	    $this->load->model('admin/testimonials_model');

        

        // Page Title
        $this->set_title( lang('menu_testimonials') );
	}


	/**
     * index
     */
    function index()
    {
        if(!$this->acl->get_method_permission($_SESSION['groups_id'], 'testimonials', 'p_view'))
        {
            $this->session->set_flashdata('error', sprintf(lang('manage_acl_permission_no'), lang('menu_testimonials').' '.lang('manage_acl_view')));
            redirect($this->uri->segment(1).'/dashboard');
        }

        /* Initialize assets */
        $this->include_index_plugins();
        $data = $this->includes;

        // Table Header
        $data['t_headers']  = array(
                                '#',
                                lang('testimonials_name'),
                                lang('testimonials_type'),
                                lang('testimonials_feedback'),
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

        $table              = 'testimonials';        
        $columns            = array(
                                "$table.id",
                                "$table.t_name",
                                "$table.t_type",
                                "$table.t_feedback",
                                "$table.status",
                                "$table.date_updated",
                            );
        $columns_order      = array(
                                "#",
                                "$table.t_name",
                                "$table.t_type",
                                "$table.t_feedback",
                                "$table.date_updated",
                                "$table.status",
                            );
        $columns_search     = array(
                                't_name',
                                't_type',
                            );
        $order              = array('id' => 'DESC');
        
        $result             = $this->datatables->get_datatables($table, $columns, $columns_order, $columns_search, $order);
        $data               = array();
        $no                 = $_POST['start'];
        
        foreach ($result as $val) 
        {
            $no++;
            $row            = array();
            $row[]          = $no;
            $row[]          = $val->t_name;
            $row[]          = $val->t_type;
            $row[]          = mb_substr($val->t_feedback, 0, 30, 'utf-8');
            $row[]          = date('g:iA j/m/y ', strtotime($val->date_updated));
            $row[]          = status_switch($val->status, $val->id);
            $row[]          = action_buttons('testimonials', $val->id, mb_substr($val->t_name, 0, 20, 'utf-8'), lang('menu_testimonial'));
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
        $this->add_js_theme( "pages/testimonials/form_i18n.js", TRUE );
        $data                       = $this->includes;
        
        $id                         = (int) $id;

        // in case of edit
        if($id)
        {
            $result                 = $this->testimonials_model->get_testimonials_by_id($id);

            if(empty($result))
            {
                $this->session->set_flashdata('error', sprintf(lang('alert_not_found') ,lang('menu_testimonial')));
                redirect($this->uri->segment(1).'/'.$this->uri->segment(2));            
            }

            // hidden field in case of update
            $data['id']             = $result->id; 
            
            // current image & icon
        	$data['c_image'] 		= $result->image;
        }
			
		$data['t_name']      = array(
            'name'          => 't_name',
            'id'            => 't_name',
            'type'          => 'text',
            'class'         => 'form-control',
            'value'         => $this->form_validation->set_value('t_name', !empty($result->t_name) ? $result->t_name : ''),
        );
        $data['t_type']      = array(
            'name'          => 't_type',
            'id'            => 't_type',
            'type'          => 'text',
            'class'         => 'form-control',
            'value'         => $this->form_validation->set_value('t_type', !empty($result->t_type) ? $result->t_type : ''),
        );
        $data['t_feedback']  = array(
            'name'          => 't_feedback',
            'id'            => 't_feedback',
            'type'          => 'textarea',
            'class'         => 'form-control',
            'value'         => $this->form_validation->set_value('t_feedback', !empty($result->t_feedback) ? $result->t_feedback : ''),
        );
        $data['image'] = array(
            'name'      => 'image',
            'id'        => 'image',
            'type'      => 'file',
            'class'     => 'form-control',
            'accept'    => 'image/*',
        );
        $data['status']     = array(
            'name'          => 'status',
            'id'            => 'status',
            'class'         => 'form-control show-tick',
            'options'       => array(
                                    '0' => lang('common_status_inactive'),
                                    '1' => lang('common_status_active'),
                                ),
            'selected'      => $this->form_validation->set_value('status', !empty($result->status) ? $result->status : 0),
        );
        
        /* Load Template */
        $content['content']    = $this->load->view('admin/testimonials/form', $data, TRUE);
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
            if(!$this->acl->get_method_permission($_SESSION['groups_id'], 'testimonials', 'p_edit'))
            {
                echo '<p>'.sprintf(lang('manage_acl_permission_no'), lang('manage_acl_edit')).'</p>';exit;
            }
            $id                     = (int) $this->input->post('id');

            $result                 = $this->testimonials_model->get_testimonials_by_id($id);

            if(empty($result))
            {
                $this->session->set_flashdata('message', sprintf(lang('alert_not_found'), lang('menu_testimonial')));
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
            if(!$this->acl->get_method_permission($_SESSION['groups_id'], 'testimonials', 'p_add'))
            {
                echo '<p>'.sprintf(lang('manage_acl_permission_no'), lang('manage_acl_add')).'</p>';exit;
            }
        }

        /* Validate form input */
        $this->form_validation
        ->set_rules('t_name', lang('testimonials_name'), 'trim|required|min_length[2]|max_length[128]|alpha_dash_spaces')
        ->set_rules('t_type', lang('testimonials_type'), 'trim|required|min_length[2]|max_length[64]|alpha_dash_spaces')
        ->set_rules('t_feedback', lang('testimonials_feedback'), 'trim|required|min_length[2]|max_length[256]')
        ->set_rules('status', lang('common_status'), 'required|in_list[0,1]');

        if(! empty($_FILES['image']['name'])) // if image 
        {
            $file_image         = array('folder'=>'testimonials/images', 'input_file'=>'image');
            // Remove old image
            if($id)
            	$this->file_uploads->remove_file('./upload/'.$file_image['folder'].'/', $result->image);
            // update testimonials image            
            $filename_image     = $this->file_uploads->upload_file($file_image);
            // through image upload error
            if(!empty($filename_image['error']))
                $this->form_validation->set_rules('image_error', lang('common_image'), 'required', array('required'=>$filename_image['error']));
        }
        
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
        $data['t_name'] 				= $this->input->post('t_name');
        $data['t_type']					= $this->input->post('t_type');
        $data['t_feedback']				= $this->input->post('t_feedback');
        $data['status']					= $this->input->post('status');

        if(! empty($filename_image) && ! isset($filename_image['error']))
           	$data['image']          = $filename_image;
        
        $flag                           = $this->testimonials_model->save_testimonials($data, $id);

        if($flag)
        {
        	if($id)
            	$this->session->set_flashdata('message', sprintf(lang('alert_update_success'), lang('menu_testimonial')));
            else
            	$this->session->set_flashdata('message', sprintf(lang('alert_insert_success'), lang('menu_testimonial')));

            echo json_encode(array(
                                    'flag'  => 1, 
                                    'msg'   => $this->session->flashdata('message'),
                                    'type'  => 'success',
                                ));
            exit;
        }
        
        if($id)
        	$this->session->set_flashdata('error', sprintf(lang('alert_update_fail'), lang('menu_testimonial')));
        else
        	$this->session->set_flashdata('error', sprintf(lang('alert_insert_fail'), lang('menu_testimonial')));

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
        if(!$this->acl->get_method_permission($_SESSION['groups_id'], 'testimonials', 'p_view'))
        {
            $this->session->set_flashdata('error', sprintf(lang('manage_acl_permission_no'), lang('menu_testimonials').' '.lang('manage_acl_view')));
            redirect($this->uri->segment(1).'/dashboard');
        }
        
        /* Initialize assets */
        $data                   = $this->includes;

        /* Get Data */
        $id                     = (int) $id;
        $result                 = $this->testimonials_model->get_testimonials_by_id($id);

        if(empty($result))
        {
            $this->session->set_flashdata('error', sprintf(lang('alert_not_found') ,lang('menu_testimonial')));
            redirect($this->uri->segment(1).'/'.$this->uri->segment(2));            
        }

        $data['testimonials']       	= $result;
        
        /* Load Template */
        $content['content']    = $this->load->view('admin/testimonials/view', $data, TRUE);
        $this->load->view($this->template, $content);
    }

     /**
     * status_update
     */
    public function status_update()
    {
        if(!$this->acl->get_method_permission($_SESSION['groups_id'], 'testimonials', 'p_edit'))
        {
            echo '<p>'.sprintf(lang('manage_acl_permission_no'), lang('manage_acl_edit')).'</p>';exit;
        }
        /* Validate form input */
        $this->form_validation
        ->set_rules('id', sprintf(lang('alert_id'), lang('menu_testimonial')), 'required|numeric')
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
            $this->session->set_flashdata('message', sprintf(lang('alert_not_found'), lang('menu_testimonial')));
            echo json_encode(array(
                                    'flag'  => 0, 
                                    'msg'   => $this->session->flashdata('message'),
                                    'type'  => 'fail',
                                ));
            exit;
        }
        
        $flag                       = $this->testimonials_model->save_testimonials($data, $id);

        if($flag)
        {
            echo json_encode(array(
                                    'flag'  => 1, 
                                    'msg'   => sprintf(lang('alert_status_success'), lang('menu_testimonial')),
                                    'type'  => 'success',
                                ));
            exit;
        }

        echo json_encode(array(
                            'flag'  => 0, 
                            'msg'   => sprintf(lang('alert_status_fail'), lang('menu_testimonial')),
                            'type'  => 'fail',
                        ));
        exit;
        
    }

    /**
     * delete
     */
    public function delete($id = NULL)
    {
        if(!$this->acl->get_method_permission($_SESSION['groups_id'], 'testimonials', 'p_delete'))
        {
            echo '<p>'.sprintf(lang('manage_acl_permission_no'), lang('manage_acl_delete')).'</p>';exit;
        }
        /* Validate form input */
        $this->form_validation->set_rules('id', sprintf(lang('alert_id'), lang('menu_testimonial')), 'required|numeric');

        /* Get Data */
        $id                 = (int) $this->input->post('id');
        $result             = $this->testimonials_model->get_testimonials_by_id($id);

        if(empty($result))
        {
            echo json_encode(array(
                                    'flag'  => 0, 
                                    'msg'   => sprintf(lang('alert_not_found') ,lang('menu_testimonial')),
                                    'type'  => 'fail',
                                ));exit;
        }

        $flag                   = $this->testimonials_model->delete_testimonials($id, $result);

        if($flag)
        {
            
            // Remove image
            if(!empty($result->image))
                $this->file_uploads->remove_file('./upload/testimonials/images/', $result->image);
            
            echo json_encode(array(
                                    'flag'  => 1, 
                                    'msg'   => sprintf(lang('alert_delete_success'), lang('menu_testimonial')),
                                    'type'  => 'success',
                                ));exit;
        }
        
        echo json_encode(array(
                            'flag'  => 0, 
                            'msg'   => sprintf(lang('alert_delete_fail'), lang('menu_testimonial')),
                            'type'  => 'fail',
                        ));
        exit;   
    }



}

/* Testimonials controller ends */