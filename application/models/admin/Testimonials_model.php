<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Testimonials Model
 *
 * This model handles testimonials module data
 *
 * @package     classiebit
 * @author      prodpk
*/

class Testimonials_model extends CI_Model {

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
    private $table = 'testimonials';

    /**
     * get_testimonials
     *
     * @return array
     * 
     **/
    public function get_testimonials()
    {
        return $this->db->select(array(
                            "$this->table.id",
                            "$this->table.t_name",
                            "$this->table.t_type",
                            "$this->table.t_feedback",
                            "$this->table.image",
                            "$this->table.status",
                            "$this->table.date_added",
                            "$this->table.date_updated",
                        ))
                        ->get($this->table)
                        ->result();
        
    }

    /**
     * get_testimonials_by_id
     *
     * @return array
     * 
     **/
    public function get_testimonials_by_id($id = FALSE)
    {
        return $this->db->select(array(
                            "$this->table.id",
                            "$this->table.t_name",
                            "$this->table.t_type",
                            "$this->table.t_feedback",
                            "$this->table.image",
                            "$this->table.status",
                            "$this->table.date_added",
                            "$this->table.date_updated",
                        ))
                        ->where(array('id'=>$id))
                        ->get($this->table)
                        ->row();
        
    }

    /**
     * save_testimonials
     *
     * @return array
     * 
     **/
    public function save_testimonials($data = array(), $id = FALSE)
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
     * delete_testimonials
     *
     * @return array
     * 
     **/
    public function delete_testimonials($id = NULL, $data = array())
    {
        if($id && !empty($data)) // update
            $this->db->delete($this->table, array('id' => $id, 't_name'=>$data->t_name)); 
        
        if($this->db->affected_rows())
            return TRUE;

        return FALSE;
    }
    
}

/* Testimonials model ends*/