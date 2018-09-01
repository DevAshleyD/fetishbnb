<?php defined('BASEPATH') OR exit('No direct script access allowed'); 
/**
 * Eventtypes Controller
 *
 * This class handles event_types module functionality
 *
 * @package     classiebit
 * @author      prodpk
*/

class Eventtypes extends Admin_Controller {

	/**
     * Constructor
    **/
	function __construct()
	{
	    parent::__construct();
	    $this->load->model('admin/eventtypes_model');

        // Page Title
        $this->set_title( lang('menu_event_types') );
	}


	/**
     * index
     */
    function index()
    {
        if(!$this->acl->get_method_permission($_SESSION['groups_id'], 'eventtypes', 'p_view'))
        {
            $this->session->set_flashdata('error', sprintf(lang('manage_acl_permission_no'), lang('menu_event_type').' '.lang('manage_acl_view')));
            redirect($this->uri->segment(1).'/dashboard');
        }

         /* Initialize assets */
        $this->include_index_plugins();
        $data = $this->includes;

        // Table Header
        $data['t_headers']  = array(
                                '#',
                                lang('common_title'),
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

        $table              = 'event_types';        
        $columns            = array(
                                "$table.id",
                                "$table.title",
                                "$table.status",
                                "$table.date_updated",
                            );
        $columns_order      = array(
                                '#',
                                "$table.title",
                                "$table.date_updated",
                                "$table.status",
                            );
        $columns_search     = array(
                                'title',
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
            $row[]          = $val->title;
            $row[]          = date('g:i A M j Y ', strtotime($val->date_updated));
            $row[]          = status_switch($val->status, $val->id);
            $row[]          = action_buttons('eventtypes', $val->id, mb_substr($val->title, 0, 50, 'utf-8'), lang('menu_event_type'), $val);
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
        $this->add_js_theme( "pages/event_types/form_i18n.js", TRUE );
        $data                       = $this->includes;

        // in case of edit
        $id                         = (int) $id;
        if($id)
        {
            $result                 = $this->eventtypes_model->get_event_types_by_id($id);

            if(empty($result))
            {
                $this->session->set_flashdata('error', sprintf(lang('alert_not_found') ,lang('menu_event_type')));
                redirect($this->uri->segment(1).'/'.$this->uri->segment(2));            
            }

            // hidden field in case of update
            $data['id']             = $result->id;

            // current image & icon
            $data['c_image'] = $result->image;
            $data['c_icon']  = $result->icon;
        }
		
        $data['title']   = array(
            'name'          => 'title',
            'id'            => 'title',
            'type'          => 'text',
            'class'         => 'form-control',
            'value'         => $this->form_validation->set_value('title', !empty($result->title) ? $result->title : ''),
        );
        $data['image'] = array(
            'name'      => 'image',
            'id'        => 'image',
            'type'      => 'file',
            'class'     => 'form-control',
            'accept'    => 'image/*',
        );
        $data['icon'] = array(
            'name'      => 'icon',
            'id'        => 'icon',
            'type'      => 'file',
            'class'     => 'form-control',
            'accept'    => 'image/*',
        );
        $data['status'] = array(
            'name'      => 'status',
            'id'        => 'status',
            'class'     => 'form-control',
            'options'   => array('1' => lang('common_status_active'), '0' => lang('common_status_inactive')),
            'selected'  => $this->form_validation->set_value('status', !empty($result->status) ? $result->status : 0),
        );
        
        /* Load Template */
        $content['content']    = $this->load->view('admin/event_types/form', $data, TRUE);
        $this->load->view($this->template, $content);
    }

    /**
     * save
    */
    public function save()
    {
    	$id = NULL;

        // Unique columns
        $result         = (object) array();
        $result->title  = '';

    	if(! empty($_POST['id']))
    	{
            if(!$this->acl->get_method_permission($_SESSION['groups_id'], 'eventtypes', 'p_edit'))
            {
                echo '<p>'.sprintf(lang('manage_acl_permission_no'), lang('manage_acl_edit')).'</p>';exit;
            }

            $id                     = (int) $this->input->post('id');

            $result                 = $this->eventtypes_model->get_event_types_by_id($id);

            if(empty($result))
            {
                $this->session->set_flashdata('message', sprintf(lang('alert_not_found'), lang('menu_event_type')));
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
            if(!$this->acl->get_method_permission($_SESSION['groups_id'], 'eventtypes', 'p_add'))
            {
                echo '<p>'.sprintf(lang('manage_acl_permission_no'), lang('manage_acl_add')).'</p>';exit;
            }
        }

        /* Validate form input */
        $this->form_validation
        ->set_rules('title', lang('common_title'), 'trim|required|alpha_dash_spaces|min_length[2]|max_length[128]')
        ->set_rules('status', lang('common_status'), 'required|in_list[0,1]');

        // Check duplicacy for unique columns
        if($this->input->post('title') !== $result->title)
            $this->form_validation->set_rules('title', lang('common_title'), 'trim|required|alpha_dash_spaces|alpha_dash_spaces|min_length[2]|max_length[128]|is_unique[event_types.title]');

        if(! empty($_FILES['image']['name'])) // if image 
        {
            $file_image         = array('folder'=>'event_types/images', 'input_file'=>'image');
            // Remove old image
            if($id)
                $this->file_uploads->remove_file('./upload/'.$file_image['folder'].'/', $result->image);
            // update event_types image            
            $filename_image     = $this->file_uploads->upload_file($file_image);
            // through image upload error
            if(!empty($filename_image['error']))
                $this->form_validation->set_rules('image_error', lang('common_image'), 'required', array('required'=>$filename_image['error']));
        }

        if(! empty($_FILES['icon']['name'])) // if icon
        {
            $file_icon         = array('folder'=>'event_types/icons', 'input_file'=>'icon');
            // Remove old icon
            if($id)
                $this->file_uploads->remove_file('./upload/'.$file_icon['folder'].'/', $result->icon);
            // update event_types icon            
            $filename_icon     = $this->file_uploads->upload_file($file_icon);
            // through icon upload error
            if(!empty($filename_icon['error']))
                $this->form_validation->set_rules('icon_error', lang('course_categories_icon'), 'required', array('required'=>$filename_icon['error']));
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
        $data['title'] 				    = strtolower($this->input->post('title'));
        $data['status']             = $this->input->post('status');    

        if(! empty($filename_image) && ! isset($filename_image['error']))
            $data['image']          = $filename_image;

        if(! empty($filename_icon) && ! isset($filename_icon['error']))
            $data['icon']          = $filename_icon;
        
        $flag                           = $this->eventtypes_model->save_event_types($data, $id);

        if($flag)
        {
        	if($id)
            	$this->session->set_flashdata('message', sprintf(lang('alert_update_success'), lang('menu_event_type')));
            else
            	$this->session->set_flashdata('message', sprintf(lang('alert_insert_success'), lang('menu_event_type')));

            echo json_encode(array(
                                    'flag'  => 1, 
                                    'msg'   => $this->session->flashdata('message'),
                                    'type'  => 'success',
                                ));
            exit;
        }
        
        if($id)
        	$this->session->set_flashdata('error', sprintf(lang('alert_update_fail'), lang('menu_event_type')));
        else
        	$this->session->set_flashdata('error', sprintf(lang('alert_insert_fail'), lang('menu_event_type')));

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
        if(!$this->acl->get_method_permission($_SESSION['groups_id'], 'eventtypes', 'p_view'))
        {
            $this->session->set_flashdata('error', sprintf(lang('manage_acl_permission_no'), lang('menu_event_type').' '.lang('manage_acl_view')));
            redirect($this->uri->segment(1).'/dashboard');
        }
        
        /* Initialize Assets */
        $data = $this->includes;

        /* Get Data */
        $id                     = (int) $id;
        $result                 = $this->eventtypes_model->get_event_types_by_id($id);

        if(empty($result))
        {
            $this->session->set_flashdata('error', sprintf(lang('alert_not_found') ,lang('menu_event_type')));
            /* Redirect */
            redirect('admin/eventtypes');            
        }

        $data['event_types']   = $result;

        /* Load Template */
        $content['content']         = $this->load->view('admin/event_types/view', $data, TRUE);
        $this->load->view($this->template, $content);
    }


    /**
     * status_update
     */
    public function status_update()
    {
        if(!$this->acl->get_method_permission($_SESSION['groups_id'], 'eventtypes', 'p_edit'))
        {
            echo '<p>'.sprintf(lang('manage_acl_permission_no'), lang('manage_acl_edit')).'</p>';exit;
        }
        /* Validate form input */
        $this->form_validation
        ->set_rules('id', sprintf(lang('alert_id'), lang('menu_event_type')), 'required|numeric')
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
            $this->session->set_flashdata('message', sprintf(lang('alert_not_found'), lang('menu_event_type')));
            echo json_encode(array(
                                    'flag'  => 0, 
                                    'msg'   => $this->session->flashdata('message'),
                                    'type'  => 'fail',
                                ));
            exit;
        }
        
        $flag                       = $this->eventtypes_model->save_event_types($data, $id);

        if($flag)
        {
            echo json_encode(array(
                                    'flag'  => 1, 
                                    'msg'   => sprintf(lang('alert_status_success'), lang('menu_event_type')),
                                    'type'  => 'success',
                                ));
            exit;
        }

        echo json_encode(array(
                            'flag'  => 0, 
                            'msg'   => sprintf(lang('alert_status_fail'), lang('menu_event_type')),
                            'type'  => 'fail',
                        ));
        exit;
    }


     /**
     * delete
     */
    public function delete()
    {
        if(!$this->acl->get_method_permission($_SESSION['groups_id'], 'eventtypes', 'p_delete'))
        {
            echo '<p>'.sprintf(lang('manage_acl_permission_no'), lang('manage_acl_delete')).'</p>';exit;
        }
        /* Validate form input */
        $this->form_validation->set_rules('id', sprintf(lang('alert_id'), lang('menu_event_type')), 'required|numeric');

        /* Get Data */
        $id                 = (int) $this->input->post('id');
        $result             = $this->eventtypes_model->get_event_types_by_id($id);

        if(empty($result))
        {
            echo json_encode(array(
                                    'flag'  => 0, 
                                    'msg'   => sprintf(lang('alert_not_found') ,lang('menu_event_type')),
                                    'type'  => 'fail',
                                ));exit;
        }

        // check if any related event exists
        $count_related_event      = $this->eventtypes_model->count_related_event($result->id);

        if($count_related_event)
        {
            echo json_encode(array(
                                'flag'  => 0, 
                                'msg'   => sprintf(lang('event_types_delete_alert'), $result->title),
                                'type'  => 'fail',
                            ));exit;
        }
        
        $flag                   = $this->eventtypes_model->delete_event_types($id, $result);

        if($flag)
        {
            // Remove image
            if(!empty($result->image))
                $this->file_uploads->remove_file('./upload/event_types/images/', $result->image);
            // Remove icon
            if(!empty($result->icon))
                $this->file_uploads->remove_file('./upload/event_types/icons/', $result->icon);

            echo json_encode(array(
                                    'flag'  => 1, 
                                    'msg'   => sprintf(lang('alert_delete_success'), lang('menu_event_type')),
                                    'type'  => 'success',
                                ));exit;
        }
        
        echo json_encode(array(
                                    'flag'  => 0, 
                                    'msg'   => sprintf(lang('alert_delete_fail'), lang('menu_event_type')),
                                    'type'  => 'fail',
                                ));
        exit;
    }

}

/* Eventtypes controller ends */