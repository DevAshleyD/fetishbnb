<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Categories Model
 *
 * This model handles course_categories module data
 *
 * @package     classiebit
 * @author      prodpk
*/

class Categories_model extends CI_Model {

    /**
     * @vars
     */
    private $table = 'course_categories';
    
    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();
    }

    /**
     * get_course_categories_dropdown
     *
     * @return array
     * 
     **/
    public function get_course_categories_dropdown($id = 0, $sub_categories = array())
    {
        $this->db->select(array('id', 'parent_id', 'title'));

        if($id)
            $this->db->where(array('id !='=>$id, 'parent_id !='=>$id, 'status'=>'1'));

        // no parent can be a child of their own child
        if(! empty($sub_categories))
        {
            $sub_categories = array_column($sub_categories, 'id');
            $this->db->where_not_in("$this->table.id", $sub_categories);            
        }

        $this->db->order_by('parent_id');
        
        return $this->db->get($this->table)->result();
    }

    /**
     * get_course_categories_levels (perfect tree view)
     *
     * @return array
     * 
     **/
    public function get_course_categories_levels($category_id) 
    {
        $data = $this->db->select(array('id', 'parent_id'))
                         ->where('parent_id',$category_id)
                         ->get($this->table)
                         ->result_array();

        for($i=0;$i<count($data);$i++)
        {
            if($this->get_course_categories_levels($data[$i]['id']))
            {
                $data[$i]['child'] = $this->get_course_categories_levels($data[$i]['id']);
            }
        }
        return $data;
    }

    /**
     * get_course_categories_levels_status_update
     *
     * @return array
     * 
     **/
    private $sub_categories = array();
    private $count          = 0;
    public function get_course_categories_levels_status_update($category_id) 
    {
        $data = $this->db->select(array('id', 'parent_id'))
                         ->where('parent_id',$category_id)
                         ->get($this->table)
                         ->result_array();
        
        for($i=0;$i<count($data);$i++)
        {
            $this->count++;
            $this->sub_categories[$this->count]['id'] = $data[$i]['id'];
            $data[$i]['child']              = $this->get_course_categories_levels_status_update($data[$i]['id']);
        }
        
        return $this->sub_categories;
    }


    /**
     * get_course_categories_by_id
     *
     * @return array
     * 
     **/
    public function get_course_categories_by_id($id = FALSE)
    {
        return $this->db->select(array(
                            "$this->table.id",
                            "$this->table.title",
                            "$this->table.description",
                            "$this->table.image",
                            "$this->table.icon",
                            "$this->table.parent_id",
                            "$this->table.status",
                            "$this->table.date_added",
                            "$this->table.date_updated",
                            "(SELECT cc.title FROM course_categories cc WHERE cc.id = $this->table.parent_id) category_name",
                        ))
                        ->where(array('id'=>$id))
                        ->get($this->table)
                        ->row();
    }

    /**
     * save_course_categories
     *
     * @return array
     * 
     **/
    public function save_course_categories($data = array(), $id = FALSE)
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
     * save_course_categories_batch
     *
     * @return array
     * 
     **/
    public function save_course_categories_batch($data = array())
    {
        foreach($data as $val)
        {
            $this->db->where(array('id'=>$val['id']))
                     ->update($this->table, array('status'=>$val['status']));
        }

        return TRUE;
    }

    /**
     * count_sub_categories
     *
     * @return count
     * 
     **/
    public function count_sub_categories($id = NULL)
    {
        return $this->db->where(array('parent_id'=>$id))
                        ->count_all_results($this->table);
    }

    /**
     * count_course_categories_courses
     *
     * @return count
     * 
     **/
    public function count_course_categories_courses($id = NULL)
    {
        return $this->db->where(array('course_categories_id'=>$id))
                        ->count_all_results('courses');
    }

    /**
     * delete_course_categories
     *
     * @return array
     * 
     **/
    public function delete_course_categories($id = NULL, $data = array())
    {
        if($id && !empty($data)) // update
            $this->db->delete($this->table, array('id' => $id, 'title'=>$data->title)); 
        
        if($this->db->affected_rows())
            return TRUE;

        return FALSE;
    }

}

/*Categories model ends*/