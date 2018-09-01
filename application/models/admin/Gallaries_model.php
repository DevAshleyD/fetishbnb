<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Gallaries Model
 *
 * This model handles gallaries module data
 *
 * @package     classiebit
 * @author      prodpk
*/

class Gallaries_model extends CI_Model {

	/**
     * Constructor
     */
	function __construct()
    {
        parent::__construct();
    }

    /**
     * @vars
     */
    private $table = 'gallaries';

    /**
     * get_gallaries
     *
     * @return array
     * 
     **/
    public function get_gallaries($limit = NULL)
    {
        $this->db->select(array(
                        "$this->table.id",
                        "$this->table.image",
                        "$this->table.date_added",
                    ));

        if($limit)
            $this->db->limit($limit);
                        
        return $this->db->get($this->table)
                        ->result();
        
    }

    /**
     * get_gallaries_by_id
     *
     * @return array
     * 
     **/
    public function get_gallaries_by_id($id = FALSE)
    {
        return $this->db->select(array(
                            "$this->table.id",
                            "$this->table.image",
                            "$this->table.date_added",
                        ))
                        ->where(array('id'=>$id))
                        ->get($this->table)
                        ->row();
        
    }

    /**
     * save_gallaries
     *
     * @return array
     * 
     **/
    public function save_gallaries($data = array())
    {
        $this->db->insert_batch($this->table, $data);
        return $this->db->insert_id();
    }

    /**
     * delete_gallaries
     *
     * @return array
     * 
     **/
    public function delete_gallaries($id = NULL, $data = array())
    {
        if($id && !empty($data)) // update
            $this->db->delete($this->table, array('id' => $id, 'image'=>$data->image)); 
        
        if($this->db->affected_rows())
            return TRUE;

        return FALSE;
    }
    
}

/* Gallaries model ends*/