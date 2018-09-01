<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Faqs Model
 *
 * This model handles faqs module data
 *
 * @package     classiebit
 * @author      prodpk
*/

class Faqs_model extends CI_Model {

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
    private $table = 'faqs';

    /**
     * get_faqs
     *
     * @return array
     * 
     **/
    public function get_faqs()
    {
        return $this->db->select(array(
                            "$this->table.id",
                            "$this->table.question",
                            "$this->table.answer",
                            "$this->table.status",
                            "$this->table.date_added",
                            "$this->table.date_updated",
                        ))
                        ->get($this->table)
                        ->result();
        
    }

    /**
     * get_faqs_by_id
     *
     * @return array
     * 
     **/
    public function get_faqs_by_id($id = FALSE)
    {
        return $this->db->select(array(
                            "$this->table.id",
                            "$this->table.question",
                            "$this->table.answer",
                            "$this->table.status",
                            "$this->table.date_added",
                            "$this->table.date_updated",
                        ))
                        ->where(array('id'=>$id))
                        ->get($this->table)
                        ->row();
        
    }

    /**
     * save_faqs
     *
     * @return array
     * 
     **/
    public function save_faqs($data = array(), $id = FALSE)
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
     * delete_faqs
     *
     * @return array
     * 
     **/
    public function delete_faqs($id = NULL, $data = array())
    {
        if($id) // update
            $this->db->delete($this->table, array('id' => $id, 'question' => $data->question)); 
        
        if($this->db->affected_rows())
            return TRUE;

        return FALSE;
    }


}

/* Faqs model ends*/