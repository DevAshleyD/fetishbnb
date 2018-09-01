<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Menus Model
 *
 * This model handles menus module data
 *
 * @package     classiebit
 * @author      prodpk
*/

class Menus_model extends CI_Model {
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
    private $table = 'menus';

	/**
     * get_menus
     */
	function get_menus()
	{
		return $this->db->get($this->table)
						->result();
	}
		
	/**
     * get_menus_by_id
     */
	function get_menus_by_id($id = NULL)
	{
		return $this->db->where(array('id'=>$id))
						->get($this->table)
						->row();
	}
	
	/**
     * save_menus
     */
	function save_menus($data = array())
	{
		$this->db->where(array('id'=>$data['id']))
				 ->update('menus', $data);

		return TRUE;
	}
	
	/**
     * get_slug
     */
	function get_slug($slug = NULL)
	{
		return $this->db->like('slug', $slug)
						->get($this->table)
 						->row();
	}

	
}

/* Menus Model Ends */