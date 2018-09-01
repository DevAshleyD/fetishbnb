<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Customfields Controller
 *
 * This class handles custom_fields module functionality
 *
 * @package     classiebit
 * @author      prodpk
*/

class Customfields extends Admin_Controller {

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();

        $this->load->model('admin/customfields_model');

        // Page Title
        $this->set_title( lang('menu_custom_fields') );
    }

    /**
     * index
     */
    function index()
    {
        if(!$this->acl->get_method_permission($_SESSION['groups_id'], 'customfields', 'p_view'))
        {
            $this->session->set_flashdata('error', sprintf(lang('manage_acl_permission_no'), lang('menu_custom_field').' '.lang('manage_acl_view')));
            redirect($this->uri->segment(1).'/dashboard');
        }


       /* Initialize assets */
        $this->include_index_plugins();
        $data = $this->includes;

        // Table Header
        $data['t_headers']  = array(
                                '#',
                                lang('custom_fields_label'),
                                lang('custom_fields_input_type'),
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

        $table              = 'custom_fields';        
        $columns            = array(
                                "$table.id",
                                "$table.label",
                                "$table.input_type",
                                "$table.date_updated",
                            );
        $columns_order      = array(
                                "#",
                                "$table.label",
                                "$table.input_type",
                                "$table.date_updated",
                            );
        $columns_search     = array(
                                'label',
                                'input_type',
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
            $row[]          = $val->label;
            $row[]          = $val->input_type;
            $row[]          = date('g:iA d/m/y', strtotime($val->date_updated));
            $row[]          = action_buttons('customfields', $val->id, $val->label, lang('menu_custom_field'));
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
        $this->add_js_theme( "pages/custom_fields/form_i18n.js", TRUE );
        $data                       = $this->includes;
        
        // in case of edit
        $id                         = (int) $id;
        if($id)
        {
            $result                 = $this->customfields_model->get_custom_fields_by_id($id);

            if(empty($result))
            {
                $this->session->set_flashdata('error', sprintf(lang('alert_not_found') ,lang('menu_custom_field')));
                redirect($this->uri->segment(1).'/'.$this->uri->segment(2));            
            }

            // hidden field in case of update
            $data['id']             = $result->id; 
            
            $_POST['is_numeric']    = $result->is_numeric;
            $_POST['show_editor']   = $result->show_editor;

            if(! empty($result->options))
            {
                $options        = explode("\n", $result->options);
                foreach ($options as $val)
                {
                    $option                         = explode("|", $val);
                    $_POST['options'][$option[0]]   = $option[1];
                }
            }
        }

        $data['name'] = array(
            'name'      => 'name',
            'id'        => 'name',
            'type'      => 'text',
            'class'     => 'form-control',
            'value'     => $this->form_validation->set_value('name', !empty($result->name) ? $result->name : ''),
        );
        $data['input_type'] = array(
            'name'      => 'input_type',
            'id'        => 'input_type',
            'class'     => 'form-control show-tick',
            'options'   => array(
                                'input'     => 'Input',
                                'textarea'  => 'Textarea',
                                'radio'     => 'Radio',
                                'checkbox'  => 'Checkbox',
                                'dropdown'  => 'Dropdown',
                                'file'      => 'File',
                                'email'     => 'Email',
                            ),
            'selected'  => $this->form_validation->set_value('input_type', !empty($result->input_type) ? $result->input_type : 'input'),
        );
        $data['help_text'] = array(
            'name'      => 'help_text',
            'id'        => 'help_text',
            'type'      => 'text',
            'class'     => 'form-control',
            'value'     => $this->form_validation->set_value('help_text', !empty($result->help_text) ? $result->help_text : ''),
        );
        $data['validation'] = array(
            'name'      => 'validation',
            'id'        => 'validation',
            'class'     => 'form-control show-tick',
            'options'   => array(
                                'trim'      => 'trim',
                                'required'  => 'required',
                            ),
            'selected'  => $this->form_validation->set_value('validation', !empty($result->validation) ? $result->validation : 'trim'),
        );
        $data['label'] = array(
            'name'      => 'label',
            'id'        => 'label',
            'type'      => 'text',
            'class'     => 'form-control',
            'value'     => $this->form_validation->set_value('label', !empty($result->label) ? $result->label : ''),
        );
        $data['value'] = array(
            'name'      => 'value',
            'id'        => 'value',
            'type'      => 'text',
            'class'     => 'form-control',
            'value'     => $this->form_validation->set_value('value', !empty($result->value) ? $result->value : ''),
        );
        
        /* Load Template */
        $content['content']    = $this->load->view('admin/custom_fields/form', $data, TRUE);
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
            if(!$this->acl->get_method_permission($_SESSION['groups_id'], 'customfields', 'p_edit'))
            {
                echo '<p>'.sprintf(lang('manage_acl_permission_no'), lang('manage_acl_edit')).'</p>';exit;
            }

            $id                     = (int) $this->input->post('id');

            $result                 = $this->customfields_model->get_custom_fields_by_id($id);

            if(empty($result))
            {
                $this->session->set_flashdata('message', sprintf(lang('alert_not_found'), lang('menu_custom_field')));
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
            if(!$this->acl->get_method_permission($_SESSION['groups_id'], 'customfields', 'p_add'))
            {
                echo '<p>'.sprintf(lang('manage_acl_permission_no'), lang('manage_acl_add')).'</p>';exit;
            }
        }

        /* Validate form input */
        $this->form_validation
        ->set_rules('name', lang('custom_fields_name'), 'trim|required|max_length[128]|alpha_dash')
        ->set_rules('input_type', lang('custom_fields_input_type'), 'trim|required|in_list[input,textarea,radio,checkbox,dropdown,file,email]')
        ->set_rules('options_value[]', lang('custom_fields_options'), 'trim')
        ->set_rules('options_label[]', lang('custom_fields_options'), 'trim')
        ->set_rules('is_numeric', lang('custom_fields_is_numeric'), 'trim')
        ->set_rules('show_editor', lang('custom_fields_show_editor'), 'trim')
        ->set_rules('help_text', lang('custom_fields_help_text'), 'trim')
        ->set_rules('validation', lang('custom_fields_validation'), 'trim|in_list[trim,required]')
        ->set_rules('label', lang('custom_fields_label'), 'trim|required')
        ->set_rules('value', lang('custom_fields_value'), 'trim');

        if(isset($_POST['input_type']))
            if($_POST['input_type'] === 'dropdown' || $_POST['input_type'] === 'checkbox' || $_POST['input_type'] === 'radio')
                $this->form_validation
                ->set_rules('options_value[]', lang('custom_fields_options'), 'trim|required')
                ->set_rules('options_label[]', lang('custom_fields_options'), 'trim|required');
        
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
        $data['name']               = strtolower($this->input->post('name'));
        $data['input_type']         = $this->input->post('input_type');

        if($data['input_type'] === 'dropdown' || $data['input_type'] === 'checkbox' || $data['input_type'] === 'radio')
        {
            $data['options']        = '';
            $options_value          = $this->input->post('options_value');
            $options_label          = $this->input->post('options_label');

            foreach($options_value as $key => $val)
            {
                $data['options']   .= $val.'|'.$options_label[$key];

                if($key < (count($options_value)-1) ) 
                    $data['options'] .= "\n";
            }
        }
        
        $data['is_numeric']         = $this->input->post('is_numeric') ? $this->input->post('is_numeric') : '0';
        $data['show_editor']        = $this->input->post('show_editor') ? $this->input->post('show_editor') : '0';
        $data['help_text']          = $this->input->post('help_text');
        $data['validation']         = $this->input->post('validation');
        $data['label']              = $this->input->post('label');
        $data['value']              = $this->input->post('value');
        
        $flag                       = $this->customfields_model->save_custom_fields($data, $id);

        if($flag)
        {
            if($id)
                $this->session->set_flashdata('message', sprintf(lang('alert_update_success'), lang('menu_custom_field')));
            else
                $this->session->set_flashdata('message', sprintf(lang('alert_insert_success'), lang('menu_custom_field')));

            echo json_encode(array(
                                    'flag'  => 1, 
                                    'msg'   => $this->session->flashdata('message'),
                                    'type'  => 'success',
                                ));
            exit;
        }
        
        if($id)
            $this->session->set_flashdata('error', sprintf(lang('alert_update_fail'), lang('menu_custom_field')));
        else
            $this->session->set_flashdata('error', sprintf(lang('alert_insert_fail'), lang('menu_custom_field')));

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
    public function delete($id = NULL)
    {
        if(!$this->acl->get_method_permission($_SESSION['groups_id'], 'customfields', 'p_delete'))
        {
            echo '<p>'.sprintf(lang('manage_acl_permission_no'), lang('manage_acl_delete')).'</p>';exit;
        }
       /* Validate form input */
        $this->form_validation->set_rules('id', sprintf(lang('alert_id'), lang('menu_custom_field')), 'required|numeric');

        /* Get Data */
        $id                 = (int) $this->input->post('id');
        $result             = $this->customfields_model->get_custom_fields_by_id($id);

        if(empty($result))
        {
            echo json_encode(array(
                                    'flag'  => 0, 
                                    'msg'   => sprintf(lang('alert_not_found') ,lang('menu_custom_field')),
                                    'type'  => 'fail',
                                ));exit;
        }

        $flag                   = $this->customfields_model->delete_custom_fields($id, $result);
        
        if($flag)
        {
            $this->load->library('file_uploads');
            // Remove image
            if(!empty($result->image))
                $this->file_uploads->remove_file('./upload/custom_fields/images/', $result->image);
            
            echo json_encode(array(
                                    'flag'  => 1, 
                                    'msg'   => sprintf(lang('alert_delete_success'), lang('menu_custom_field')),
                                    'type'  => 'success',
                                ));exit;
        }
        
        echo json_encode(array(
                            'flag'  => 0, 
                            'msg'   => sprintf(lang('alert_delete_fail'), lang('menu_custom_field')),
                            'type'  => 'fail',
                        ));
        exit;
    }

}

/* Customfields controller ends */