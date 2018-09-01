<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Blogs Model
 *
 * This model handles blogs module data
 *
 * @package     classiebit
 * @author      prodpk
*/

class Blogs_model extends CI_Model {

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
    private $table = 'blogs';

    /**
     * get_blogs
     *
     * @return array
     * 
     **/
    public function get_blogs_by_id($id = FALSE)
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
     * save_blogs
     *
     * @return array
     * 
     **/
    public function save_blogs($data = array(), $id = FALSE)
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
     * get_blogs_by_slug
     *
     * @return array
     * 
    **/
    function get_blogs_by_slug($slug)
    {
        return $this->db->select(array(
                            "$this->table.id",
                            "$this->table.title",
                            "$this->table.slug",
                            "$this->table.image",
                            "$this->table.content",
                            "$this->table.meta_title",
                            "$this->table.meta_tags",
                            "$this->table.meta_description",
                            "$this->table.date_updated",
                            "users.username",
                            "users.first_name",
                            "users.last_name",
                            "users.image user_image",
                        ))
                        ->join('users', "users.id = $this->table.users_id", 'left')
                        ->where(array("$this->table.status"=>1, 'slug'=>$slug))
                        ->get($this->table)
                        ->row();
    }

    
    /**
     * get_blogs_by_users_id
     *
     * @return array
     * 
    **/
    function get_blogs_by_users_id($users_id = NULL)
    {
        return $this->db->select(array(
                            "$this->table.id",
                            "$this->table.title",
                            "$this->table.slug",
                            "$this->table.image",
                            "$this->table.content",
                            "$this->table.status",
                            "$this->table.date_updated",
                        ))
                        ->where(array("$this->table.users_id"=>$users_id))
                        ->get($this->table)
                        ->result();
    }

    /**
     * get_blogs_by_users_id
     *
     * @return array
     * 
    **/
    function get_user_blog($id = NULL, $user_id = NULL)
    {
        return $this->db->select(array(
                            "$this->table.id",
                            "$this->table.title",
                            "$this->table.slug",
                            "$this->table.image",
                            "$this->table.content",
                            "$this->table.status",
                            "$this->table.meta_title",
                            "$this->table.meta_tags",
                            "$this->table.meta_description",
                            "$this->table.date_updated",
                        ))
                        ->where(array("$this->table.users_id"=>$user_id, "$this->table.id"=>$id))
                        ->get($this->table)
                        ->row();
    }

    /**
     * get_blogs
     *
     * @return array
     * 
    **/
    function get_blogs($limit = NULL)
    {
        $this->db->select(array(
                        "$this->table.id",
                        "$this->table.title",
                        "$this->table.slug",
                        "$this->table.image",
                        "$this->table.date_updated",
                        "users.username",
                        "users.first_name",
                        "users.last_name",
                        "users.image user_image",
                    ))
                    ->join('users', "users.id = $this->table.users_id", 'left')
                    ->where(array("$this->table.status"=>1));

        if($limit)
            $this->db->limit($limit);

        return  $this->db->get($this->table)
                         ->result();
    }

    /**
     * delete_blogs
     *
     * @return array
     * 
     **/
    public function delete_blogs($id = NULL, $data = array())
    {
        if($id && !empty($data)) // update
            $this->db->delete($this->table, array('id' => $id, 'title'=>$data->title)); 
        
        if($this->db->affected_rows())
            return TRUE;

        return FALSE;
    }

    /**
     * delete_user_blog
     *
     * @return array
     * 
     **/
    public function delete_user_blog($id = NULL, $user_id = NULL)
    {
        if($id && $user_id) // delete
            $this->db->delete($this->table, array('id' => $id, 'users_id'=>$user_id)); 
        
        if($this->db->affected_rows())
            return TRUE;

        return FALSE;
    }
    
}

/* Blogs model ends*/