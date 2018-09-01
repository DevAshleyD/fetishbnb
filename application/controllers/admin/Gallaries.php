<?php defined('BASEPATH') OR exit('No direct script access allowed'); 
/**
 * Gallaries Controller
 *
 * This class handles gallaries module functionality
 *
 * @package     classiebit
 * @author      prodpk
*/

class Gallaries extends Admin_Controller {

	/**
     * Constructor
    **/
	public function __construct()
	{
	    parent::__construct();
	    $this->load->model('admin/gallaries_model');

        

        // Page Title
        $this->set_title( lang('menu_gallaries') );
	}


	/**
     * index
     */
    public function index()
    {
        if(!$this->acl->get_method_permission($_SESSION['groups_id'], 'gallaries', 'p_view'))
        {
            $this->session->set_flashdata('error', sprintf(lang('manage_acl_permission_no'), lang('menu_gallaries').' '.lang('manage_acl_view')));
            redirect($this->uri->segment(1).'/dashboard');
        }

        /* Initialize assets and title */
        $this
        ->add_plugin_theme(array(
            'light-gallery/css/lightgallery.css',
            'light-gallery/js/lightgallery-all.js',
        ), 'admin')
        ->add_js_theme("pages/gallaries/index_i18n.js", TRUE);
        $data = $this->includes;

        $data['gallaries']  = $this->gallaries_model->get_gallaries();

        $data['images']     = array(
            'name'          => 'images[]',
            'id'            => 'images',
            'type'          => 'file',
            'multiple'      =>'multiple',
            'class'         => 'form-control',
            'accept'        => 'image/*',
        );

        // load views
        $content['content'] = $this->load->view('admin/gallaries/index', $data, TRUE);
        $this->load->view($this->template, $content);
    }


    /**
     * save
    */
    public function save()
    {
        if(!$this->acl->get_method_permission($_SESSION['groups_id'], 'gallaries', 'p_add'))
        {
            echo '<p>'.sprintf(lang('manage_acl_permission_no'), lang('manage_acl_add')).'</p>';exit;
        }

        $files               = array('folder'=>'gallaries/images', 'input_file'=>'images');
        $filenames           = $this->file_uploads->upload_files($files);
        
        // through image upload error
        if(!empty($filenames['error']))
        {
            $this->form_validation->set_rules('image_error', lang('common_images'), 'required', array('required'=>$filenames['error']));

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
        }
        
        $data                           = array();
        foreach($filenames as $val)
            $data[]['image']            = $val;
        
        $flag                           = $this->gallaries_model->save_gallaries($data);

        if($flag)
        {
        	$this->session->set_flashdata('message', sprintf(lang('alert_insert_success'), lang('common_images')));

            echo json_encode(array(
                                    'flag'  => 1, 
                                    'msg'   => $this->session->flashdata('message'),
                                    'type'  => 'success',
                                ));
            exit;
        }
        
        $this->session->set_flashdata('error', sprintf(lang('alert_insert_fail'), lang('common_images')));

        echo json_encode(array(
                                'flag'  => 0, 
                                'msg'   => $this->session->flashdata('message'),
                                'type'  => 'fail',
                            ));
        exit;
    }

    /**
     * delete
     */
    public function delete()
    {
        if(!$this->acl->get_method_permission($_SESSION['groups_id'], 'gallaries', 'p_delete'))
        {
            echo '<p>'.sprintf(lang('manage_acl_permission_no'), lang('manage_acl_add')).'</p>';exit;
        }
        /* Validate form input */
        $this->form_validation->set_rules('id', sprintf(lang('alert_id'), lang('common_images')), 'required|numeric');

        /* Get Data */
        $id                 = (int) $this->input->post('id');
        $result             = $this->gallaries_model->get_gallaries_by_id($id);

        if(empty($result))
        {
            $this->session->set_flashdata('message', sprintf(lang('alert_not_found'), lang('common_image')));

            echo '<p>'.$this->session->flashdata('message').'</p>';
            exit;
        }

        if ($this->form_validation->run() === TRUE)
        {
            $flag                   = $this->gallaries_model->delete_gallaries($id, $result);

            if($flag)
            {
                
                // Remove image
                $this->file_uploads->remove_file('./upload/gallaries/images/', $result->image);
                
                $this->session->set_flashdata('message', sprintf(lang('alert_delete_success'), lang('common_image')));
                echo json_encode(array(
                                'flag'  => 1, 
                                'msg'   => $this->session->flashdata('message'),
                                'type'  => 'success',
                            ));   
                exit;

            }
            
            echo json_encode(array(
                                'flag'  => 0, 
                                'msg'   => sprintf(lang('alert_delete_fail'), lang('common_image')),
                                'type'  => 'fail',
                            ));
        }
        
        exit;
    }
}

/* Gallaries controller ends */