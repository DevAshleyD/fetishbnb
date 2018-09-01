<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Taxes Controller
 *
 * This class handles taxes module functionality
 *
 * @package     classiebit
 * @author      prodpk
*/

class Taxes extends Admin_Controller {

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();

        $this->load->model('admin/taxes_model');

        // Page Title
        $this->set_title( lang('menu_taxes') );
    }

    /**
     * index
     */
    function index()
    {
        if(!$this->acl->get_method_permission($_SESSION['groups_id'], 'taxes', 'p_view'))
        {
            $this->session->set_flashdata('error', sprintf(lang('manage_acl_permission_no'), lang('menu_taxes').' '.lang('manage_acl_view')));
            redirect($this->uri->segment(1).'/dashboard');
        }

        /* Initialize assets */
        $this->include_index_plugins();
        $data = $this->includes;

        // Table Header
        $data['t_headers']  = array(
                                '#',
                                lang('common_title'),
                                lang('taxes_rate_type'),
                                lang('taxes_rate'),
                                lang('taxes_net_price'),
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

        $table              = 'taxes';        
        $columns            = array(
                                "$table.id",
                                "$table.title",
                                "$table.rate_type",
                                "$table.rate",
                                "$table.net_price",
                                "$table.status",
                                "$table.date_updated",
                            );
        $columns_order      = array(
                                "#",
                                "$table.title",
                                "$table.rate_type",
                                "$table.rate",
                                "$table.net_price",
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
            $row[]          = mb_substr($val->title, 0, 30, 'utf-8');
            $row[]          = $val->rate_type === 'percent' ? '<span class="label label-success">'.lang('taxes_rate_type_percent').'</span>' : '<span class="label label-default">'.lang('taxes_rate_type_fixed').'</span>';
            $row[]          = $val->rate;
            $row[]          = $val->net_price == 'including' ? '<span class="label label-primary">'.lang('taxes_net_price_including').'</span>' : '<span class="label label-default">'.lang('taxes_net_price_excluding').'</span>';
            $row[]          = date('g:iA d/m/y', strtotime($val->date_updated));
            $row[]          = status_switch($val->status, $val->id);
            $row[]          = action_buttons('taxes', $val->id, mb_substr($val->title, 0, 20, 'utf-8'), lang('menu_tax'));
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
        $this->add_js_theme( "pages/taxes/form_i18n.js", TRUE );
        $data                       = $this->includes;
        
        $id                         = (int) $id;

        // in case of edit
        if($id)
        {
            $result                 = $this->taxes_model->get_taxes_by_id($id);

            if(empty($result))
            {
                $this->session->set_flashdata('error', sprintf(lang('alert_not_found') ,lang('menu_tax')));
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
        $data['rate_type'] = array(
            'name'      => 'rate_type',
            'id'        => 'rate_type',
            'class'     => 'form-control show-tick',
            'options'   => array('percent' => lang('taxes_rate_type_percent'), 'fixed' => lang('taxes_rate_type_fixed')),
            'selected'  => $this->form_validation->set_value('rate_type', !empty($result->rate_type) ? $result->rate_type : 'percent'),
        );
        $data['rate'] = array(
            'name'      => 'rate',
            'id'        => 'rate',
            'type'      => 'number',
            'class'     => 'form-control',
            'value'     => $this->form_validation->set_value('rate', !empty($result->rate) ? $result->rate : ''),
        );
        $data['net_price'] = array(
            'name'      => 'net_price',
            'id'        => 'net_price',
            'class'     => 'form-control show-tick',
            'options'   => array('including' => lang('taxes_net_price_including'), 'excluding' => lang('taxes_net_price_excluding')),
            'selected'  => $this->form_validation->set_value('net_price', !empty($result->rate_type) ? $result->rate_type : 'including'),
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
        $content['content']    = $this->load->view('admin/taxes/form', $data, TRUE);
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
            if(!$this->acl->get_method_permission($_SESSION['groups_id'], 'taxes', 'p_edit'))
            {
                echo '<p>'.sprintf(lang('manage_acl_permission_no'), lang('manage_acl_edit')).'</p>';exit;
            }
            $id                     = (int) $this->input->post('id');

            $result                 = $this->taxes_model->get_taxes_by_id($id);

            if(empty($result))
            {
                $this->session->set_flashdata('message', sprintf(lang('alert_not_found'), lang('menu_tax')));
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
            if(!$this->acl->get_method_permission($_SESSION['groups_id'], 'taxes', 'p_add'))
            {
                echo '<p>'.sprintf(lang('manage_acl_permission_no'), lang('manage_acl_add')).'</p>';exit;
            }
        }

        /* Validate form input */
        $this->form_validation
        ->set_rules('title', lang('common_title'), 'trim|required')
        ->set_rules('rate_type', lang('taxes_rate_type'), 'trim|required|in_list[percent,fixed]')
        ->set_rules('rate', lang('taxes_rate'), 'trim|required|numeric|max_length[100]')
        ->set_rules('net_price', lang('taxes_net_price'), 'trim|required|in_list[including,excluding]')
        ->set_rules('status', lang('common_status'), 'required|in_list[0,1]');

        // Check duplicacy for unique columns
        if($this->input->post('title') !== $result->title)
            $this->form_validation->set_rules('title', lang('common_title'), 'trim|required|is_unique[taxes.title]');
        
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
        $data['rate_type']          = $this->input->post('rate_type');
        $data['rate']               = $this->input->post('rate');
        $data['net_price']          = $this->input->post('net_price');
        $data['status']             = $this->input->post('status');
        
        $flag                       = $this->taxes_model->save_taxes($data, $id);

        if($flag)
        {
            if($id)
                $this->session->set_flashdata('message', sprintf(lang('alert_update_success'), lang('menu_tax')));
            else
                $this->session->set_flashdata('message', sprintf(lang('alert_insert_success'), lang('menu_tax')));

            echo json_encode(array(
                                    'flag'  => 1, 
                                    'msg'   => $this->session->flashdata('message'),
                                    'type'  => 'success',
                                ));
            exit;
        }
        
        if($id)
            $this->session->set_flashdata('error', sprintf(lang('alert_update_fail'), lang('menu_tax')));
        else
            $this->session->set_flashdata('error', sprintf(lang('alert_insert_fail'), lang('menu_tax')));

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
        if(!$this->acl->get_method_permission($_SESSION['groups_id'], 'taxes', 'p_view'))
        {
            $this->session->set_flashdata('error', sprintf(lang('manage_acl_permission_no'), lang('menu_taxes').' '.lang('manage_acl_view')));
            redirect($this->uri->segment(1).'/dashboard');
        }
        
        /* Initialize assets */
        $data                   = $this->includes;

        /* Get Data */
        $id                     = (int) $id;
        $result                 = $this->taxes_model->get_taxes_by_id($id);

        if(empty($result))
        {
            $this->session->set_flashdata('error', sprintf( lang('alert_not_found'), lang('menu_tax')));
            /* Redirect */
            redirect('admin/taxes');            
        }

        $data['taxes']          = $result;
        
        /* Load Template */
        $content['content']    = $this->load->view('admin/taxes/view', $data, TRUE);
        $this->load->view($this->template, $content);
    }

    /**
     * status_update
     */
    public function status_update()
    {
        if(!$this->acl->get_method_permission($_SESSION['groups_id'], 'taxes', 'p_edit'))
        {
            echo '<p>'.sprintf(lang('manage_acl_permission_no'), lang('manage_acl_edit')).'</p>';exit;
        }
        /* Validate form input */
        $this->form_validation
        ->set_rules('id', sprintf(lang('alert_id'), lang('menu_tax')), 'required|numeric')
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
            $this->session->set_flashdata('message', sprintf(lang('alert_not_found'), lang('menu_tax')));
            echo json_encode(array(
                                    'flag'  => 0, 
                                    'msg'   => $this->session->flashdata('message'),
                                    'type'  => 'fail',
                                ));
            exit;
        }
        
        $flag                       = $this->taxes_model->save_taxes($data, $id);

        if($flag)
        {
            echo json_encode(array(
                                    'flag'  => 1, 
                                    'msg'   => sprintf(lang('alert_status_success'), lang('menu_tax')),
                                    'type'  => 'success',
                                ));
            exit;
        }

        echo json_encode(array(
                            'flag'  => 0, 
                            'msg'   => sprintf(lang('alert_status_fail'), lang('menu_tax')),
                            'type'  => 'fail',
                        ));
        exit;
        
    }

     /**
     * delete
     */
    public function delete($id = NULL)
    {
        if(!$this->acl->get_method_permission($_SESSION['groups_id'], 'taxes', 'p_delete'))
        {
            echo '<p>'.sprintf(lang('manage_acl_permission_no'), lang('manage_acl_delete')).'</p>';exit;
        }
       /* Validate form input */
        $this->form_validation->set_rules('id', sprintf(lang('alert_id'), lang('menu_tax')), 'required|numeric');

        /* Get Data */
        $id                 = (int) $this->input->post('id');
        $result             = $this->taxes_model->get_taxes_by_id($id);

        if(empty($result))
        {
            echo json_encode(array(
                                    'flag'  => 0, 
                                    'msg'   => sprintf(lang('alert_not_found') ,lang('menu_tax')),
                                    'type'  => 'fail',
                                ));exit;
        }

        $flag                   = $this->taxes_model->delete_taxes($id, $result);

        if($flag)
        {
            $this->load->library('file_uploads');
            // Remove image
            if(!empty($result->image))
                $this->file_uploads->remove_file('./upload/taxes/images/', $result->image);
            
            echo json_encode(array(
                                    'flag'  => 1, 
                                    'msg'   => sprintf(lang('alert_delete_success'), lang('menu_tax')),
                                    'type'  => 'success',
                                ));exit;
        }
        
        echo json_encode(array(
                            'flag'  => 0, 
                            'msg'   => sprintf(lang('alert_delete_fail'), lang('menu_tax')),
                            'type'  => 'fail',
                        ));
        exit;   
    }

}

/* Taxes controller ends */