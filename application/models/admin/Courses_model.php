<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Courses Model
 *
 * This model handles courses module data
 *
 * @package     classiebit
 * @author      prodpk
*/

class Courses_model extends CI_Model {

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
    private $table = 'courses';
    
    /**
     * get_course_categories_dropdown
     *
     * @return array
     * 
     **/
    public function get_course_categories_dropdown()
    {
        return $this->db->select(array('id', 'title'))
                        ->where(array('parent_id'=>0, 'status !='=>0))
                        ->order_by('parent_id')
                        ->get('course_categories')
                        ->result();
    }

    /**
     * get_courses_levels
     *
     * @return array
     * 
     **/
    var $categories = array();
    var $i          = 0;
    public function get_courses_levels($course_categories_id) 
    {
        $data = $this->db->select(array('id', 'title', 'parent_id'))
                         ->where(array('id'=>$course_categories_id, 'status !='=>'0'))
                         ->get('course_categories')
                         ->result_array();

        if(count($data))
        {
            $this->categories[$this->i] = $data[0];
            $this->i++;

            $this->get_courses_levels($data[0]['parent_id']);
        }
        
        return $this->categories;    
        
    }

    /**
     * get_courses
     *
     * @return array
     * 
     **/
    public function get_courses_by_id($id = FALSE)
    {
        return $this->db->select(array(
                            "$this->table.id",
                            "$this->table.title",
                            "$this->table.description",
                            "$this->table.meta_title",
                            "$this->table.meta_tags",
                            "$this->table.meta_description",
                            "$this->table.featured",
                            "$this->table.status",
                            "$this->table.images",
                            "$this->table.course_categories_id",
                            "$this->table.date_added",
                            "$this->table.date_updated",
                        ))
                        ->where(array('id'=>$id))
                        ->get($this->table)->row();
        
    }

    /**
     * save_courses
     *
     * @return array
     * 
     **/
    public function save_courses($data = array(), $id = FALSE)
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
     * delete_courses
     *
     * @return array
     * 
     **/
    public function delete_courses($id = NULL, $title = NULL)
    {
        if($id) // update
        {
            $this->db->delete($this->table, array('id' => $id, 'title'=>$title)); 
        }
        
        return $id;
    }


    /**
     * get_course_categories_levels
     *
     * @return array
     * 
     **/
    public function get_course_categories_levels($id = NULL)
    {
        return $this->db->select(array('id', 'title'))
                        ->where(array('parent_id'=>$id, 'status !='=>'0'))
                        ->get('course_categories')
                        ->result();
    }

}

/*Courses model ends*/