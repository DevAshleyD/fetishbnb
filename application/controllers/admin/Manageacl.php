<?php defined('BASEPATH') OR exit('No direct script access allowed'); 
/**
 * Manageacl Controller
 *
 * This class handles acl module functionality
 *
 * @package     classiebit
 * @author      prodpk
*/

class Manageacl extends Admin_Controller {
    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();

        $this->load->model('admin/manageacl_model');

         // Page Title
        $this->set_title(lang('menu_manage_acl'));
    }

    /**
     * index
     */
    function index($group = NULL)
    {   
        // users can only edit non-admins
        if(!$this->ion_auth->is_admin())
        {
            $this->session->set_flashdata('error', lang('users_only_admin_can'));
            redirect($this->uri->segment(1).'/dashboard');
        }   

        /* Initialize assets */
        $this->add_js_theme( "pages/manage_acl/index_i18n.js", TRUE);
        $data           = $this->includes;

        $group               = (int) $group;
        if(!empty($group))
            $result_group    = $this->ion_auth->group($group)->row_array();

        $data['controllers'] = $this->manageacl_model->get_controllers();
        $data['group_id']    = !empty($result_group) ? $result_group['id'] : 3;
        
        $groups             = $this->ion_auth->groups()->result_array();
        foreach($groups as $val)
            $data['group'][$val['id']] = $val['name'];
        
        $data['groups']     = array(
            'name'          => 'groups',
            'id'            => 'groups',
            'class'         => 'form-control show-tick text-capitalize',
            'options'       => $data['group'],
            'selected'      => $this->form_validation->set_value('groups', !empty($result_group) ? $result_group['id'] : 3),
        );

        $data['permissions'] = $this->manageacl_model->get_group_permissions((!empty($result_group) ? $result_group['id'] : 3));

        foreach($data['permissions'] as $val)
        {
            $k = $this->get_array_key($data['controllers'], 'id', $val['controllers_id']);
            
            $data['p'][''.$data['controllers'][$k]['name'].'_view']       = $val['p_view'];
            $data['p'][''.$data['controllers'][$k]['name'].'_add']       = $val['p_add'];
            $data['p'][''.$data['controllers'][$k]['name'].'_edit']      = $val['p_edit'];
            $data['p'][''.$data['controllers'][$k]['name'].'_delete']    = $val['p_delete'];
        }

        // load views
        $content['content'] = $this->load->view('admin/manage_acl/index', $data, TRUE);
        $this->load->view($this->template, $content);
    }

    private function get_array_key($array, $field, $value)
    {
        foreach($array as $key => $val)
          if ( $val[$field] === $value )
             return $key;

       return false;
    }

    /**
     * save
    */
    public function save()
    {
        // users can only edit non-admins
        if(!$this->ion_auth->is_admin())
        {
            echo '<p>'.lang('users_only_admin_can').'</p>';exit;
        }   

        /* Validate form input */
        $this->form_validation
        ->set_rules('groups', lang('manage_acl_select_group'), 'trim|required|is_natural_no_zero');

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

        $group_id                       = (int) $this->input->post('groups');
        if($group_id === 1 || $group_id === 3)
        {
            $this->session->set_flashdata('message', sprintf(lang('alert_update_success'), lang('manage_acl_permissions')));
            echo json_encode(array(
                                    'flag'  => 1, 
                                    'msg'   => $this->session->flashdata('message'),
                                    'type'  => 'success',
                                ));
            exit;
        }

        $data                               = array();
        $controllers                        = $this->manageacl_model->get_controllers();
        foreach($controllers as $key => $val)
        {
            $data[$key]['controllers_id']   = $val['id'];
            $data[$key]['groups_id']        = $group_id;
            $data[$key]['p_view']            = isset($_POST[''.$val['name'].'_view']) ? 1 : 0;
            $data[$key]['p_add']            = isset($_POST[''.$val['name'].'_add']) ? 1 : 0;
            $data[$key]['p_edit']           = isset($_POST[''.$val['name'].'_edit']) ? 1 : 0;
            $data[$key]['p_delete']         = isset($_POST[''.$val['name'].'_delete']) ? 1 : 0;
        }

        $flag                               = $this->manageacl_model->save_permissions($data, $group_id);

        $this->session->set_flashdata('message', sprintf(lang('alert_update_success'), lang('manage_acl_permissions')));
        echo json_encode(array(
                                'flag'  => 1, 
                                'msg'   => $this->session->flashdata('message'),
                                'type'  => 'success',
                            ));
        exit;
    }


}

/* Manageacl controller ends */