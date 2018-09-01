<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Customfields Model
 *
 * This model handles custom_fields module data
 *
 * @package     classiebit
 * @author      prodpk
*/

class Customfields_model extends CI_Model {

    /**
     * @vars
     */
    private $table = 'custom_fields';
    
    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();
    }

    /**
     * get_custom_fields_by_id
     *
     * @return array
     * 
     **/
    public function get_custom_fields_by_id($id = FALSE)
    {
        return $this->db->select(array(
                            "$this->table.id",
                            "$this->table.name",
                            "$this->table.input_type",
                            "$this->table.options",
                            "$this->table.is_numeric",
                            "$this->table.show_editor",
                            "$this->table.help_text",
                            "$this->table.validation",
                            "$this->table.label",
                            "$this->table.value",
                            "$this->table.date_updated",
                        ))
                        ->where(array('id'=>$id))
                        ->get($this->table)
                        ->row();
    }

    /**
     * save_custom_fields
     *
     * @return array
     * 
     **/
    public function save_custom_fields($data = array(), $id = FALSE)
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
     * delete_custom_fields
     *
     * @return array
     * 
     **/
    public function delete_custom_fields($id = NULL, $data = array())
    {
        if($id && !empty($data)) // update
            $this->db->delete($this->table, array('id' => $id, 'name'=>$data->name)); 
        
        if($this->db->affected_rows())
            return TRUE;

        return FALSE;
    }

}

/*Customfields model ends*/