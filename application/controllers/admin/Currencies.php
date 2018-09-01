<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Currencies Controller
 *
 * This class handles currencies module functionality
 *
 * @package     classiebit
 * @author      prodpk
*/

class Currencies extends Admin_Controller {

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();
        
        $this->load->model('admin/currencies_model');

        // Page Title
        $this->set_title( lang('menu_currencies') );
    }

    /**
     * index
     */
    function index()
    {
        if(!$this->acl->get_method_permission($_SESSION['groups_id'], 'currencies', 'p_view'))
        {
            $this->session->set_flashdata('error', sprintf(lang('manage_acl_permission_no'), lang('menu_currency').' '.lang('manage_acl_view')));
            redirect($this->uri->segment(1).'/dashboard');
        }

        /* Initialize assets */
        $this->include_index_plugins();
        $data = $this->includes;

        // Table Header
        $data['t_headers']  = array(
                                '#',
                                lang('currencies_iso_code'),
                                lang('currencies_symbol'),
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

        $table              = 'currencies';        
        $columns            = array(
                                "$table.iso_code",
                                "$table.symbol",
                                "$table.date_updated",
                                "$table.status",
                            );
        $columns_order      = array(
                                "#",
                                "$table.iso_code",
                                "$table.symbol",
                                "$table.date_updated",
                                "$table.status",
                            );
        $columns_search     = array(
                                'iso_code',
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
            $row[]          = $val->iso_code;
            $row[]          = $val->symbol;
            $row[]          = date('g:iA d/m/y ', strtotime($val->date_updated));
            $row[]          = status_switch($val->status, $val->iso_code);
            $row[]          = action_buttons('currencies', $val->iso_code, $val->iso_code, lang('menu_currency'));
         
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
    public function form($iso_code = NULL)
    {
        /* Initialize assets */
        $this->add_js_theme( "pages/currencies/form_i18n.js", TRUE );
        $data                       = $this->includes;
        
        $iso_code                   = $iso_code;

        // in case of edit
        if($iso_code)
        {
            $result                 = $this->currencies_model->get_currencies_by_id($iso_code);

            if(empty($result))
            {
                $this->session->set_flashdata('error', sprintf(lang('alert_not_found') ,lang('menu_currency')));
                redirect($this->uri->segment(1).'/'.$this->uri->segment(2));            
            }

            // hidden field in case of update
            $data['id']             = $result->iso_code;
        }
        
        $data['iso_code'] = array(
            'name'      => 'iso_code',
            'id'        => 'iso_code',
            'type'      => 'text',
            'class'     => 'form-control',
            'value'     => $this->form_validation->set_value('iso_code', !empty($result->iso_code) ? $result->iso_code : ''),
        );
        $data['symbol'] = array(
            'name'      => 'symbol',
            'id'        => 'symbol',
            'type'      => 'text',
            'class'     => 'form-control',
            'value'     => $this->form_validation->set_value('symbol', !empty($result->symbol) ? $result->symbol : ''),
        );
        $data['unicode']= array(
            'name'          => 'unicode',
            'id'            => 'unicode',
            'type'          => 'text',
            'class'         => 'form-control',
            'value'         => $this->form_validation->set_value('unicode', !empty($result->unicode) ? $result->unicode : ''),
        );
        $data['position'] = array(
            'name'          => 'position',
            'id'            => 'position',
            'class'         => 'form-control show-tick',
            'options'       => array('before' => lang('currencies_position_before'), 'after' => lang('currencies_position_after')),
            'selected'      => $this->form_validation->set_value('position', !empty($result->position) ? $result->position : 0),
        );
        $data['status']     = array(
            'name'          => 'status',
            'id'            => 'status',
            'class'         => 'form-control show-tick',
            'options'       => array('0' => lang('common_status_inactive'),'1' => lang('common_status_active')),
            'selected'      => $this->form_validation->set_value('status', !empty($result->status) ? $result->status : 0),
        );
        
        /* Load Template */
        $content['content']    = $this->load->view('admin/currencies/form', $data, TRUE);
        $this->load->view($this->template, $content);
    }

    /**
     * save
    */
    public function save()
    {
        $iso_code = NULL;

        // Unique columns
        $result             = (object) array();
        $result->iso_code   = '';

        if(! empty($_POST['id']))
        {
            if(!$this->acl->get_method_permission($_SESSION['groups_id'], 'currencies', 'p_edit'))
            {
                echo '<p>'.sprintf(lang('manage_acl_permission_no'), lang('manage_acl_edit')).'</p>';exit;
            }
            $iso_code               = $this->input->post('id');

            $result                 = $this->currencies_model->get_currencies_by_id($iso_code);

            if(empty($result))
            {
                $this->session->set_flashdata('message', sprintf(lang('alert_not_found'), lang('menu_currency')));
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
            if(!$this->acl->get_method_permission($_SESSION['groups_id'], 'currencies', 'p_add'))
            {
                echo '<p>'.sprintf(lang('manage_acl_permission_no'), lang('manage_acl_add')).'</p>';exit;
            }
        }

       /* Validate form input */
        $this->form_validation
        ->set_rules('iso_code', lang('currencies_iso_code'), 'trim|required|max_length[3]')
        ->set_rules('symbol', lang('currencies_symbol'), 'trim|required|max_length[3]')
        ->set_rules('unicode', lang('currencies_unicode'), 'trim|max_length[8]')
        ->set_rules('position', lang('currencies_position'), 'trim|required|in_list[before,after]')
        ->set_rules('status', lang('common_status'), 'required|in_list[0,1]');

        // Check duplicacy for unique columns
        if($this->input->post('iso_code') !== $result->iso_code)
            $this->form_validation->set_rules('iso_code', lang('currencies_iso_code'), 'trim|required|max_length[3]|is_unique[currencies.iso_code]');

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
        $data['iso_code']           = strtoupper($this->input->post('iso_code'));
        $data['symbol']             = $this->input->post('symbol');
        $data['unicode']            = $this->input->post('unicode');
        $data['position']           = $this->input->post('position');
        $data['status']             = $this->input->post('status');
        
        $flag                       = $this->currencies_model->save_currencies($data, $iso_code);

        if($flag)
        {
            if($iso_code)
                $this->session->set_flashdata('message', sprintf(lang('alert_update_success'), lang('menu_currency')));
            else
                $this->session->set_flashdata('message', sprintf(lang('alert_insert_success'), lang('menu_currency')));

            echo json_encode(array(
                                    'flag'  => 1, 
                                    'msg'   => $this->session->flashdata('message'),
                                    'type'  => 'success',
                                ));
            exit;
        }
        
        if($iso_code)
            $this->session->set_flashdata('error', sprintf(lang('alert_update_fail'), lang('menu_currency')));
        else
            $this->session->set_flashdata('error', sprintf(lang('alert_insert_fail'), lang('menu_currency')));

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
    public function view($iso_code)
    {
        if(!$this->acl->get_method_permission($_SESSION['groups_id'], 'currencies', 'p_view'))
        {
            $this->session->set_flashdata('error', sprintf(lang('manage_acl_permission_no'), lang('menu_currency').' '.lang('manage_acl_view')));
            redirect($this->uri->segment(1).'/dashboard');
        }
        
        /* Initialize assets */
        $data                   = $this->includes;

        /* Get Data */
        $iso_code                     = $iso_code;
        $result                 = $this->currencies_model->get_currencies_by_id($iso_code);

        if(empty($result))
        {
            $this->session->set_flashdata('error', sprintf(lang('alert_not_found'), lang('menu_currency')));
            /* Redirect */
            redirect('admin/currencies');            
        }

         // data to insert in db table
        $data['currencies']         = $result;

        /* Load Template */
        $content['content']    = $this->load->view('admin/currencies/view', $data, TRUE);
        $this->load->view($this->template, $content);
    }

     /**
     * status_update
     */
    public function status_update()
    {
        if(!$this->acl->get_method_permission($_SESSION['groups_id'], 'currencies', 'p_edit'))
        {
            echo '<p>'.sprintf(lang('manage_acl_permission_no'), lang('manage_acl_edit')).'</p>';exit;
        }
        /* Validate form input */
        $this->form_validation
        ->set_rules('id', sprintf(lang('alert_id'), lang('menu_currency')), 'trim|required|max_length[3]')
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
        $iso_code                   = $this->input->post('id');
        $data['status']             = $this->input->post('status');

        if(empty($iso_code))
        {
            $this->session->set_flashdata('message', sprintf(lang('alert_not_found'), lang('menu_currency')));
            echo json_encode(array(
                                    'flag'  => 0, 
                                    'msg'   => $this->session->flashdata('message'),
                                    'type'  => 'fail',
                                ));
            exit;
        }

        $flag                       = $this->currencies_model->save_currencies($data, $iso_code);

        if($flag)
        {
            echo json_encode(array(
                                    'flag'  => 1, 
                                    'msg'   => sprintf(lang('alert_status_success'), lang('menu_currency')),
                                    'type'  => 'success',
                                ));
            exit;
        }

        echo json_encode(array(
                            'flag'  => 0, 
                            'msg'   => sprintf(lang('alert_status_fail'), lang('menu_currency')),
                            'type'  => 'fail',
                        ));
        exit;
        
    }

     /**
     * delete
     */
    public function delete($iso_code = NULL)
    {
        if(!$this->acl->get_method_permission($_SESSION['groups_id'], 'currencies', 'p_delete'))
        {
            echo '<p>'.sprintf(lang('manage_acl_permission_no'), lang('manage_acl_delete')).'</p>';exit;
        }
       /* Validate form input */
        $this->form_validation->set_rules('id', sprintf(lang('alert_id'), lang('menu_currency')), 'trim|required|max_length[3]');

        /* Get Data */
        $iso_code           = $this->input->post('id');
        $result             = $this->currencies_model->get_currencies_by_id($iso_code);

        if(empty($result))
        {
            echo json_encode(array(
                                    'flag'  => 0, 
                                    'msg'   => sprintf(lang('alert_not_found') ,lang('menu_currency')),
                                    'type'  => 'fail',
                                ));exit;
        }

        $flag                   = $this->currencies_model->delete_currencies($iso_code, $result);

        if($flag)
        {
            echo json_encode(array(
                                    'flag'  => 1, 
                                    'msg'   => sprintf(lang('alert_delete_success'), lang('menu_currency')),
                                    'type'  => 'success',
                                ));exit;
        }
        
        echo json_encode(array(
                            'flag'  => 0, 
                            'msg'   => sprintf(lang('alert_delete_fail'), lang('menu_currency')),
                            'type'  => 'fail',
                        ));
        exit;   
    }

}

/* Currencies controller ends */