<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Eventtypes Model
 *
 * This model handles event_types module data
 *
 * @package     classiebit
 * @author      prodpk
*/

class Eventtypes_model extends CI_Model {

    /**
     * @vars
     */
    private $table = 'event_types';
    
    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();
    }

    /**
     * get_event_types_by_id
     *
     * @return array
     * 
     **/
    public function get_event_types_by_id($id = FALSE)
    {
        return $this->db->select(array(
                            "$this->table.id",
                            "$this->table.title",
                            "$this->table.image",
                            "$this->table.icon",
                            "$this->table.status",
                            "$this->table.date_added",
                            "$this->table.date_updated",
                        ))
                        ->where(array('id'=>$id))
                        ->get($this->table)
                        ->row();
    }

    /**
     * save_event_types
     *
     * @return array
     * 
     **/
    public function save_event_types($data = array(), $id = FALSE)
    {
        if($id) // update
        {
            $this->db->where(array('id'=>$id))
                     ->update($this->table, $data);
            
            return TRUE;
        }
        else // insert
        {
            $this->db->insert($this->table, $data);
            return $this->db->insert_id();
        }

        return FALSE;
        
    }

    /**
     * count_related_event
     *
     * @return count
     * 
     **/
    public function count_related_event($id = NULL)
    {
        return $this->db->where(array('event_types_id'=>$id))
                        ->count_all_results('events');
    }

    /**
     * delete_event_types
     *
     * @return array
     * 
     **/
    public function delete_event_types($id = NULL, $data = array())
    {
        if($id && !empty($data)) // update
            $this->db->delete($this->table, array('id' => $id, 'title'=>$data->title)); 
        
        if($this->db->affected_rows())
            return TRUE;

        return FALSE;
    }

}

/*Eventtypes model ends*/