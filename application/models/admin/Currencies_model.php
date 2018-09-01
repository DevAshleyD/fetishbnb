<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Currencies Model
 *
 * This model handles currencies module data
 *
 * @package     classiebit
 * @author      prodpk
*/

class Currencies_model extends CI_Model {

    /**
     * @vars
     */
    private $table = 'currencies';
    
    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();
    }

    /**
     * get_currencies_by_id
     *
     * @return array
     * 
     **/
    public function get_currencies_by_id($id = FALSE)
    {
        return $this->db->select(array(
                            "$this->table.iso_code",
                            "$this->table.symbol",
                            "$this->table.unicode",
                            "$this->table.position",
                            "$this->table.status",
                            "$this->table.date_updated",
                        ))
                        ->where(array('iso_code'=>$id))
                        ->get($this->table)
                        ->row();
    }

    /**
     * save_currencies
     *
     * @return array
     * 
     **/
    public function save_currencies($data = array(), $id = FALSE)
    {
        if($id) // update
        {
            $this->db->where(array('iso_code'=>$id))
                     ->update($this->table, $data);
            return $id;
        }
        else // insert
        {
            $this->db->insert($this->table, $data);

            if($this->db->affected_rows() == TRUE)
                return TRUE;
        }

        return FALSE;
    }

    /**
     * delete_currencies
     *
     * @return array
     * 
     **/
    public function delete_currencies($id = NULL, $data = array())
    {
        if($id && !empty($data)) // update
            $this->db->delete($this->table, array('iso_code' => $id)); 
        
        if($this->db->affected_rows())
            return TRUE;

        return FALSE;
    }

}

/*Currencies model ends*/