<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Emailtemplates Controller
 *
 * This class handles email_templates module functionality
 *
 * @package     classiebit
 * @author      prodpk
*/

class Emailtemplates extends Admin_Controller {

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();

        $this->load->model('admin/emailtemplates_model');

        /* Page Title */
        $this->set_title( lang('menu_email_templates') );
    }

    /**
     * index
     */
    function index()
    {
        if(!$this->acl->get_method_permission($_SESSION['groups_id'], 'emailtemplates', 'p_view'))
        {
            $this->session->set_flashdata('error', sprintf(lang('manage_acl_permission_no'), lang('menu_email_template').' '.lang('manage_acl_view')));
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

        $table              = 'email_templates';        
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
            $row[]          = mb_substr($val->title, 0, 50, 'utf-8');
            $row[]          = date('g:iA d/m/y', strtotime($val->date_updated));
            $row[]          = action_buttons('emailtemplates', $val->id, mb_substr($val->title, 0, 20, 'utf-8'), lang('menu_email_template'));
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
                            ), 'admin')
             ->add_js_theme( "pages/email_templates/form_i18n.js", TRUE );
        $data                       = $this->includes;

        // in case of edit
        $id                         = (int) $id;
        if($id)
        {
            $result                 = $this->emailtemplates_model->get_email_templates_by_id($id);

            if(empty($result))
            {
                $this->session->set_flashdata('error', sprintf(lang('alert_not_found') ,lang('menu_email_template')));
                redirect($this->uri->segment(1).'/'.$this->uri->segment(2));
            }

            // hidden field in case of update
            $data['id']             = $result->id; 
        }

        
        $data['title'] = array(
            'name'      => 'title',
            'id'        => 'title',
            'type'      => 'text',
            'class'     => 'form-control',
            'value'     => $this->form_validation->set_value('title', !empty($result->title) ? $result->title : ''),
        );
        $data['subject'] = array(
            'name'      => 'subject',
            'id'        => 'subject',
            'type'      => 'text',
            'class'     => 'form-control',
            'value'     => $this->form_validation->set_value('subject', !empty($result->subject) ? $result->subject : ''),
        );
        $data['message'] = array(
            'name'      => 'message',
            'id'        => 'message',
            'type'      => 'textarea',
            'class'     => 'tinymce form-control',
            'value'     => $this->form_validation->set_value('message', !empty($result->message) ? $result->message : ''),
        );

        /* Load Template */
        $content['content']    = $this->load->view('admin/email_templates/form', $data, TRUE);
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
            if(!$this->acl->get_method_permission($_SESSION['groups_id'], 'emailtemplates', 'p_edit'))
            {
                echo '<p>'.sprintf(lang('manage_acl_permission_no'), lang('manage_acl_edit')).'</p>';exit;
            }
            $id                     = (int) $this->input->post('id');

            $result                 = $this->emailtemplates_model->get_email_templates_by_id($id);

            if(empty($result))
            {
                $this->session->set_flashdata('message', sprintf(lang('alert_not_found'), lang('menu_email_template')));
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
            if(!$this->acl->get_method_permission($_SESSION['groups_id'], 'emailtemplates', 'p_add'))
            {
                echo '<p>'.sprintf(lang('manage_acl_permission_no'), lang('manage_acl_add')).'</p>';exit;
            }
        }

        /* Validate form input */
        $this->form_validation
        ->set_rules('title', lang('common_title'), 'trim|required|min_length[2]|max_length[128]')
        ->set_rules('subject', lang('email_templates_subject'), 'trim|required|max_length[250]')
        ->set_rules('message', lang('email_templates_message'), 'trim|required');

        // Check duplicacy for unique columns
        if($this->input->post('title') !== $result->title)
            $this->form_validation->set_rules('title', lang('common_title'), 'trim|required|min_length[2]|max_length[128]|is_unique[email_templates.title]');
        
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
        $data['subject']            = $this->input->post('subject');
        $data['message']            = $this->input->post('message');
    
        $flag                       = $this->emailtemplates_model->save_email_templates($data, $id);

        if($flag)
        {
            if($id)
                $this->session->set_flashdata('message', sprintf(lang('alert_update_success'), lang('menu_email_template')));
            else
                $this->session->set_flashdata('message', sprintf(lang('alert_insert_success'), lang('menu_email_template')));

            echo json_encode(array(
                                    'flag'  => 1, 
                                    'msg'   => $this->session->flashdata('message'),
                                    'type'  => 'success',
                                ));
            exit;
        }
        
        if($id)
            $this->session->set_flashdata('error', sprintf(lang('alert_update_fail'), lang('menu_email_template')));
        else
            $this->session->set_flashdata('error', sprintf(lang('alert_insert_fail'), lang('menu_email_template')));

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
        if(!$this->acl->get_method_permission($_SESSION['groups_id'], 'emailtemplates', 'p_view'))
        {
            $this->session->set_flashdata('error', sprintf(lang('manage_acl_permission_no'), lang('menu_email_template').' '.lang('manage_acl_view')));
            redirect($this->uri->segment(1).'/dashboard');
        }
        
        /* Initialize Assets */
        $data = $this->includes;

        /* Get Data */
        $id                     = (int) $id;
        $result                 = $this->emailtemplates_model->get_email_templates_by_id($id);

        if(empty($result))
        {
            $this->session->set_flashdata('error', sprintf(lang('alert_not_found'), lang('menu_email_template')));
            /* Redirect */
            redirect('admin/emailtemplates');            
        }

        $data['email_templates']   = $result;
        
        /* Load Template */
        $content['content']    = $this->load->view('admin/email_templates/view', $data, TRUE);
        $this->load->view($this->template, $content);
    }

     /**
     * delete
     */
    public function delete()
    {
        if(!$this->acl->get_method_permission($_SESSION['groups_id'], 'emailtemplates', 'p_delete'))
        {
            echo '<p>'.sprintf(lang('manage_acl_permission_no'), lang('manage_acl_delete')).'</p>';exit;
        }
        /* Validate form input */
        $this->form_validation->set_rules('id', sprintf(lang('alert_id'), lang('menu_email_template')), 'required|numeric');

        /* Get Data */
        $id                 = (int) $this->input->post('id');
        $result             = $this->emailtemplates_model->get_email_templates_by_id($id);

        if(empty($result))
        {
            echo json_encode(array(
                                'flag'  => 0, 
                                'msg'   => sprintf(lang('alert_not_found') ,lang('menu_email_template')),
                                'type'  => 'fail',
                            ));exit;
        }

       $flag                   = $this->emailtemplates_model->delete_email_templates($id, $result);

        if($flag)
        {
            echo json_encode(array(
                                'flag'  => 1, 
                                'msg'   => sprintf(lang('alert_delete_success'), lang('menu_email_template')),
                                'type'  => 'success',
                            ));exit;
        }
        
        echo json_encode(array(
                            'flag'  => 0, 
                            'msg'   => sprintf(lang('alert_delete_fail'), lang('menu_email_template')),
                            'type'  => 'fail',
                        ));
        exit;
    }

}

/* Emailtemplates controller ends */