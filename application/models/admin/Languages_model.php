<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Languages Model
 *
 * This model handles languages module data
 *
 * @package     classiebit
 * @author      prodpk
*/

class Languages_model extends CI_Model {

    /**
     * @vars
     */
    private $table = 'languages';
    
    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();
    }

    
    /**
     * get_languages_by_id
     *
     * @return array
     * 
     **/
    public function get_languages_by_id($id = FALSE)
    {
        return $this->db->select(array(
                            "$this->table.id",
                            "$this->table.title",
                            "$this->table.flag",
                            "$this->table.date_added",
                            "$this->table.date_updated",
                        ))
                        ->where(array('id'=>$id))
                        ->get($this->table)
                        ->row();
    }

    /**
     * save_languages
     *
     * @return array
     * 
     **/
    public function save_languages($data = array(), $id = FALSE)
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
     * delete_languages
     *
     * @return array
     * 
     **/
    public function delete_languages($id = NULL, $data = array())
    {
        if($id && !empty($data)) // update
            $this->db->delete($this->table, array('id' => $id, 'title'=>$data->title)); 
        
        if($this->db->affected_rows())
            return TRUE;

        return FALSE;
    }

}

/*Languages model ends*/