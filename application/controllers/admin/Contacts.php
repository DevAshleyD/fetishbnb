<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Contacts Controller
 *
 * This class handles contacts module functionality
 *
 * @package     classiebit
 * @author      prodpk
*/

class Contacts extends Admin_Controller {

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();

        // load the users model
        $this->load->model('contact_model');

        // Page Title
        $this->set_title( lang('menu_contacts') );
    }


   /**
     * index
     */
    function index()
    {
        /* Initialize assets */
        $this->include_index_plugins();
        $this->add_js_theme( "pages/contacts/contact.js");
        $data = $this->includes;

        // Table Header
        $data['t_headers']  = array(
                                '#',
                                lang('contacts_name'),
                                lang('contacts_email'),
                                lang('contacts_title'),
                                lang('common_added'),
                                lang('action_read'),
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

        $table              = 'emails';        
        $columns            = array(
                                "$table.id",
                                "$table.name",
                                "$table.email",
                                "$table.title",
                                "$table.message",
                                "$table.created",
                                "$table.read",
                                "$table.read_by",
                            );
        $columns_order      = array(
                                "#",
                                "$table.name",
                                "$table.email",
                                "$table.title",
                                "$table.created",
                            );
        $columns_search     = array(
                                'name',
                                'email',
                                'title',
                            );
        $order              = array('created' => 'DESC');
        
        $result             = $this->datatables->get_datatables($table, $columns, $columns_order, $columns_search, $order);
        $data               = array();
        $no                 = $_POST['start'];
        
        foreach ($result as $val) 
        {
            $no++;
            $row            = array();
            $row[]          = $no;
            $row[]          = mb_substr($val->name, 0, 30, 'utf-8');
            $row[]          = $val->email;
            $row[]          = mb_substr($val->title, 0, 30, 'utf-8');
            $row[]          = date('g:iA j/m/y ', strtotime($val->created));
            $row[]          = modal_contact($val);
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
     * Marks email message as read
     *
     * @param  int $id
     * @return boolean
    */
    public function read($id)
    {
        if ($id)
        {
            $read = $this->contact_model->read($id, $this->user['id']);

            if ($read)
            {
                $results['success'] = sprintf(lang('alert_update_success'), lang('menu_contact'));
            }
            else
            {
                $results['error'] = sprintf(lang('alert_update_fail'), lang('menu_contact'));
            }
        }
        else
        {
            $results['error'] = sprintf(lang('alert_update_fail'), lang('menu_contact'));
        }

        display_json($results);
        exit;
    }

}

/* Contacts controller ends */