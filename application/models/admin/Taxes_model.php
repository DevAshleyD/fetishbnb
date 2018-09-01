<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Taxes Model
 *
 * This model handles taxes module data
 *
 * @package     classiebit
 * @author      prodpk
*/

class Taxes_model extends CI_Model {

    /**
     * @vars
     */
    private $table = 'taxes';
    
    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();
    }

    /**
     * get_taxes_by_id
     *
     * @return array
     * 
     **/
    public function get_taxes_by_id($id = FALSE)
    {
        return $this->db->select(array(
                            "$this->table.id",
                            "$this->table.title",
                            "$this->table.rate_type",
                            "$this->table.rate",
                            "$this->table.net_price",
                            "$this->table.status",
                            "$this->table.date_added",
                            "$this->table.date_updated",
                        ))
                        ->where(array('id'=>$id))
                        ->get($this->table)
                        ->row();
    }

    /**
     * save_taxes
     *
     * @return array
     * 
     **/
    public function save_taxes($data = array(), $id = FALSE)
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
     * delete_taxes
     *
     * @return array
     * 
     **/
    public function delete_taxes($id = NULL, $data = array())
    {
        if($id && !empty($data)) // update
            $this->db->delete($this->table, array('id' => $id, 'title'=>$data->title)); 
        
        if($this->db->affected_rows())
            return TRUE;

        return FALSE;
    }

}

/*Taxes model ends*/