<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Languages Controller
 *
 * This class handles languages module functionality
 *
 * @package     classiebit
 * @author      prodpk
*/

class Languages extends Admin_Controller {

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();

        $this->load->model('admin/languages_model');

        // Page Title
        $this->set_title( lang('menu_languages') );
    }

    /**
     * index
     */
    function index()
    {
        if(!$this->acl->get_method_permission($_SESSION['groups_id'], 'languages', 'p_view'))
        {
            $this->session->set_flashdata('error', sprintf(lang('manage_acl_permission_no'), lang('menu_language').' '.lang('manage_acl_view')));
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

        $table              = 'languages';        
        $columns            = array(
                                "$table.id",
                                "$table.title",
                                "$table.date_updated",
                            );
        $columns_order      = array(
                                "#",
                                "$table.title",
                                "$table.date_updated",
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
            $row[]          = mb_substr($val->title, 0, 30, 'utf-8');
            $row[]          = date('g:iA d/m/y ', strtotime($val->date_updated));
            $row[]          = action_buttons('languages', $val->id, mb_substr($val->title, 0, 30, 'utf-8'), lang('menu_language'));
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
        $this->add_js_theme( "pages/languages/form_i18n.js", TRUE );
        $data                       = $this->includes;
        
        // in case of edit
        $id                         = (int) $id;
        if($id)
        {
            $result                 = $this->languages_model->get_languages_by_id($id);

            if(empty($result))
            {
                $this->session->set_flashdata('error', sprintf(lang('alert_not_found') ,lang('menu_language')));
                redirect($this->uri->segment(1).'/'.$this->uri->segment(2));            
            }

            // hidden field in case of update
            $data['id']             = $result->id; 
            
            // current image & icon
            $data['c_image']        = $result->flag;
        }
            
        $data['title']      = array(
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
        $data['core_lang'] = array(
            'name'      => 'core_lang',
            'id'        => 'core_lang',
            'type'      => 'file',
            'class'     => 'form-control',
        );
        $data['form_validation_lang'] = array(
            'name'      => 'form_validation_lang',
            'id'        => 'form_validation_lang',
            'type'      => 'file',
            'class'     => 'form-control',
        );
        
        /* Load Template */
        $content['content']    = $this->load->view('admin/languages/form', $data, TRUE);
        $this->load->view($this->template, $content);
    }

    /**
     * save
    */
    public function save()
    {
        $id             = NULL;

        // Unique columns
        $result         = (object) array();
        $result->title  = '';

        if(! empty($_POST['id']))
        {
            if(!$this->acl->get_method_permission($_SESSION['groups_id'], 'languages', 'p_edit'))
            {
                echo '<p>'.sprintf(lang('manage_acl_permission_no'), lang('manage_acl_edit')).'</p>';exit;
            }
            $id                     = (int) $this->input->post('id');

            $result                 = $this->languages_model->get_languages_by_id($id);

            if(empty($result))
            {
                $this->session->set_flashdata('message', sprintf(lang('alert_not_found'), lang('menu_language')));
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
            if(!$this->acl->get_method_permission($_SESSION['groups_id'], 'languages', 'p_add'))
            {
                echo '<p>'.sprintf(lang('manage_acl_permission_no'), lang('manage_acl_add')).'</p>';exit;
            }
        }

        /* Validate form input */
        $this->form_validation
        ->set_rules('title', lang('common_title'), 'trim|required|alpha|min_length[2]|max_length[128]');

        // Check duplicacy for unique columns
        if($this->input->post('title') !== $result->title)
            $this->form_validation->set_rules('title', lang('common_title'), 'trim|required|min_length[2]|max_length[128]|is_unique[languages.title]');

        
        if(! empty($_FILES['image']['name'])) // if image 
        {
            $file_image         = array('folder'=>'languages/flags', 'input_file'=>'image');
            // Remove old image
            if($id)
                $this->file_uploads->remove_file('./upload/'.$file_image['folder'].'/', $result->image);
            // update languages image            
            $filename_image     = $this->file_uploads->upload_file($file_image);
            // through image upload error
            if(!empty($filename_image['error']))
                $this->form_validation->set_rules('image_error', lang('languages_flag'), 'required', array('required'=>$filename_image['error']));
        }
        else
        {
            $this->form_validation->set_rules('image_error', lang('languages_flag'), 'required');   
        }

        if(! empty($_FILES['core_lang']['name'])) // if image 
        {
            $file_core_lang         = array('folder'=>APPPATH.'language/'.$this->input->post('title'), 'input_file'=>'core_lang', 'extension'=>'.php');
            $filename_core_lang     = $this->file_uploads->upload_file_custom_url($file_core_lang);
            // through image upload error
            if(!empty($filename_core_lang['error']))
                $this->form_validation->set_rules('image_error_core', lang('languages_core_lang'), 'required', array('required'=>$filename_core_lang['error']));
        }        
        else
        {
            $this->form_validation->set_rules('image_error_core', lang('languages_core_lang'), 'required');   
        }

        if(! empty($_FILES['form_validation_lang']['name'])) // if image 
        {
            $file_form_validation_lang         = array('folder'=>APPPATH.'language/'.$this->input->post('title'), 'input_file'=>'form_validation_lang', 'extension'=>'.php');
            $filename_form_validation_lang     = $this->file_uploads->upload_file_custom_url($file_form_validation_lang);
            // through image upload error
            if(!empty($filename_form_validation_lang['error']))
                $this->form_validation->set_rules('image_error_valid', lang('languages_form_validation_lang'), 'required', array('required'=>$filename_form_validation_lang['error']));
        }
        else
        {
            $this->form_validation->set_rules('image_error_valid', lang('languages_form_validation_lang'), 'required');   
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

        // data to insert in db table
        $data                       = array();
        $data['title']              = strtolower($this->input->post('title'));
        
        if(! empty($filename_image) && ! isset($filename_image['error']))
            $data['flag']           = $filename_image;
        
        $flag                           = $this->languages_model->save_languages($data, $id);

        if($flag)
        {
            if($id)
                $this->session->set_flashdata('message', sprintf(lang('alert_update_success'), lang('menu_language')));
            else
                $this->session->set_flashdata('message', sprintf(lang('alert_insert_success'), lang('menu_language')));

            echo json_encode(array(
                                    'flag'  => 1, 
                                    'msg'   => $this->session->flashdata('message'),
                                    'type'  => 'success',
                                ));
            exit;
        }
        
        if($id)
            $this->session->set_flashdata('error', sprintf(lang('alert_update_fail'), lang('menu_language')));
        else
            $this->session->set_flashdata('error', sprintf(lang('alert_insert_fail'), lang('menu_language')));

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
    public function view($id)
    {
        if(!$this->acl->get_method_permission($_SESSION['groups_id'], 'languages', 'p_view'))
        {
            $this->session->set_flashdata('error', sprintf(lang('manage_acl_permission_no'), lang('menu_language').' '.lang('manage_acl_view')));
            redirect($this->uri->segment(1).'/dashboard');
        }
        
        /* Initialize assets */
        $data                   = $this->includes;

        /* Get Data */
        $id                     = (int) $id;
        $result                 = $this->languages_model->get_languages_by_id($id);

        if(empty($result))
        {
            $this->session->set_flashdata('error', sprintf( lang('alert_not_found'), lang('menu_language')));
            /* Redirect */
            redirect('admin/languages');            
        }

        $data['languages']     = $result;
        
        /* Load Template */
        $content['content']    = $this->load->view('admin/languages/view', $data, TRUE);
        $this->load->view($this->template, $content);
    }

     /**
     * delete
     */
    public function delete($id = NULL)
    {
        if(!$this->acl->get_method_permission($_SESSION['groups_id'], 'languages', 'p_delete'))
        {
            echo '<p>'.sprintf(lang('manage_acl_permission_no'), lang('manage_acl_delete')).'</p>';exit;
        }
        /* Validate form input */
        $this->form_validation->set_rules('id', sprintf(lang('alert_id'), lang('menu_language')), 'required|numeric');

        /* Get Data */
        $id                 = (int) $this->input->post('id');
        $result             = $this->languages_model->get_languages_by_id($id);

        if(empty($result))
        {
           echo json_encode(array(
                                    'flag'  => 0, 
                                    'msg'   => sprintf(lang('alert_not_found') ,lang('menu_language')),
                                    'type'  => 'fail',
                                ));exit;  
        }

        $flag                   = $this->languages_model->delete_languages($id, $result);

        if($flag)
        {
            // Remove flag & files
            
            $this->file_uploads->remove_file('./upload/languages/flags/', $result->flag);
            $this->file_uploads->remove_dir(APPPATH.'language/'.$result->title);
            
            echo json_encode(array(
                                'flag'  => 1, 
                                'msg'   => sprintf(lang('alert_delete_success'), lang('menu_language')),
                                'type'  => 'success',
                            ));exit;
        }
        
        echo json_encode(array(
                            'flag'  => 0, 
                            'msg'   => sprintf(lang('alert_delete_fail'), lang('menu_language')),
                            'type'  => 'fail',
                        ));
        exit;
    }

    public function download_lang($file_name = NULL) 
    {   
        $this->load->helper('download');

       if ($file_name) 
       {
            if($file_name == 'core')
                $file_name  = 'core_lang';
            else 
                $file_name  = 'form_validation_lang';

            $path       = base_url().'languages/';
            $mime       = 'application/force-download';
            $ext        = '.txt';
            
            header('Pragma: public');   // required
            header('Expires: 0');       // no cache
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Cache-Control: private',false);
            header('Content-Type: '.$mime);
            header('Content-Disposition: attachment; filename="'.$file_name.'.php"');
            header('Content-Transfer-Encoding: binary');
            
            header('Connection: close');
            readfile($path.$file_name.$ext);       // push it out
            exit();
       }
    }

}

/* Languages controller ends */