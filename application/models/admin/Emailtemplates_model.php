<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Emailtemplates Model
 *
 * This model handles email_templates module data
 *
 * @package     classiebit
 * @author      prodpk
*/

class Emailtemplates_model extends CI_Model {

    /**
     * @vars
     */
    private $table = 'email_templates';
    
    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();
    }

    
    /**
     * get_email_templates_by_id
     *
     * @return array
     * 
     **/
    public function get_email_templates_by_id($id = FALSE)
    {
        return $this->db->select(array(
                            "$this->table.id",
                            "$this->table.title",
                            "$this->table.subject",
                            "$this->table.message",
                            "$this->table.date_added",
                            "$this->table.date_updated",
                        ))
                        ->where(array('id'=>$id))
                        ->get($this->table)
                        ->row();
    }

    /**
     * save_email_templates
     *
     * @return array
     * 
     **/
    public function save_email_templates($data = array(), $id = FALSE)
    {
        if($id) // update
        {
            $this->db->where(array('id'=>$id))
                     ->update($this->table, $data);
            return $id;
        }
        else // insert
        {
            $this->db->insert($this->table, $data);
            return $this->db->insert_id();
        }
        
    }

    /**
     * delete_email_templates
     *
     * @return array
     * 
     **/
    public function delete_email_templates($id = NULL, $data = array())
    {
        if($id && !empty($data)) // update
            $this->db->delete($this->table, array('id' => $id, 'title'=>$data->title)); 
        
        if($this->db->affected_rows())
            return TRUE;

        return FALSE;
    }

}

/*Emailtemplates model ends*/