<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Pages Model
 *
 * This model handles pages module data
 *
 * @package     classiebit
 * @author      prodpk
*/

class Pages_model extends CI_Model {

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
    private $table = 'pages';

    /**
     * get_pages
     *
     * @return array
     * 
     **/
    public function get_pages_by_id($id = FALSE)
    {
        return $this->db->select(array(
                            "$this->table.id",
                            "$this->table.title",
                            "$this->table.slug",
                            "$this->table.content",
                            "$this->table.image",
                            "$this->table.meta_title",
                            "$this->table.meta_tags",
                            "$this->table.meta_description",
                            "$this->table.status",
                            "$this->table.date_added",
                            "$this->table.date_updated",
                        ))
                        ->where(array('id'=>$id))
                        ->get($this->table)
                        ->row();
        
    }

    /**
     * save_pages
     *
     * @return array
     * 
     **/
    public function save_pages($data = array(), $id = FALSE)
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
     * get_slug
     *
     * @return array
     * 
     **/
    function get_slug_by_title($title = NULL)
    {
		return $this->db->like('slug', $title) 
		 				->get($this->table)
		 				->row();
	}

	/**
     * get_pages_by_slug
     *
     * @return array
     * 
    **/
	function get_pages_by_slug($slug)
	{
		return $this->db->where(array('slug'=>$slug))
						->get($this->table)
						->row();
	}

    /**
     * get_pages
     *
     * @return array
     * 
    **/
    function get_pages()
    {
        return $this->db->not_like('status', 2)
                        ->get($this->table)
                        ->result();
    }
    
}

/* Pages model ends*/