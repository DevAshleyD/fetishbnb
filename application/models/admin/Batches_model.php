<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Batches Model
 *
 * This model handles batches module data
 *
 * @package     classiebit
 * @author      prodpk
*/

class Batches_model extends CI_Model {

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
    private $table = 'batches';

    /**
     * top_batches_list
     */
    public function top_batches_list()
    {
        return $this->db->query("SELECT b.id, b.title, 'admin/batches/view/' url, 'batch' type, (SELECT COUNT(bbm.id) FROM b_bookings_members bbm WHERE bbm.b_bookings_id IN (SELECT bb.id FROM b_bookings bb WHERE bb.batches_id = b.id)) total_bookings FROM batches b ORDER BY total_bookings DESC LIMIT 5")
                        ->result();
    }    
    
    /**
     * get_course_categories_dropdown
     *
     * @return array
     * 
     **/
    public function get_courses_dropdown()
    {
        return $this->db->select(array(
                                    "courses.id", 
                                    "courses.title",
                                    "(SELECT cc.title FROM course_categories cc WHERE courses.course_categories_id = cc.id) courses_category_name",
                                ))
                        ->where(array('status !='=>0))
                        ->order_by('title')
                        ->get('courses')
                        ->result();
    }

    /**
     * get_users_dropdown
     *
     * @return array
     * 
     **/
    public function get_users_dropdown($ids = array())
    {
        return $this->db->select(array(
                                    "users.id", 
                                    "users.first_name",
                                    "users.last_name",
                                    "users.username",
                                    "users.profession",
                                ))
                        ->where(array('active !='=>0))
                        ->where_in('id', $ids)
                        ->order_by('first_name')
                        ->get('users')
                        ->result();
    }

    /**
     * get_batches_tutors
     *
     * @return array
     * 
     **/
    public function get_batches_tutors($batches_id = NULL)
    {
        return $this->db->select(array(
                                    "batches_tutors.users_id",
                                    "users.username",
                                    "users.first_name",
                                    "users.last_name",
                                    "users.image",
                                ))
                        ->join('users', 'users.id = batches_tutors.users_id', 'left')
                        ->where(array('batches_id'=>$batches_id))
                        ->get('batches_tutors')
                        ->result();
    }

    /**
     * get_batches
     *
     * @return array
     * 
     **/
    public function get_batches_by_id($id = FALSE)
    {
        return $this->db->select(array(
                            "$this->table.id",
                            "$this->table.title",
                            "$this->table.description",
                            "$this->table.fees",
                            "$this->table.capacity",
                            "$this->table.start_date",
                            "$this->table.end_date",
                            "$this->table.start_time",
                            "$this->table.end_time",
                            "$this->table.weekdays",
                            "$this->table.recurring",
                            "$this->table.recurring_type",
                            "$this->table.status",
                            "$this->table.date_added",
                            "$this->table.date_updated",
                            "$this->table.courses_id",
                            "courses.title courses_title",
                            "course_categories.title course_categories_title",
                        ))
                        ->join("courses", "courses.id = $this->table.courses_id")
                        ->join("course_categories", "course_categories.id = courses.course_categories_id")
                        ->where(array("$this->table.id"=>$id))
                        ->get($this->table)->row();
    }

    /**
     * save_batches
     *
     * @return array
     * 
     **/
    public function save_batches($data = array(), $data_2 = array(), $id = FALSE, $cur_tutors = array())
    {
        // for status update only
        if(empty($data_2)) // if data2 is empty
        {
            $this->db->set($data)
                     ->where(array('id'=>$id))
                     ->update($this->table);
                     
            return TRUE;
        }

        if($id) // update
        {
            $this->db->trans_start();
            $this->db->set($data)
                     ->where(array('id'=>$id))
                     ->update($this->table);

            // if any current tutors removed then remove them from table as well         
            $to_delete = array();
            foreach($cur_tutors as $val)
                if(! in_array($val, $data_2))
                    $this->db->delete('batches_tutors', array('batches_id'=>$id, 'users_id'=>$val));

            // insert tutors on the batch
            foreach($data_2 as $val)
                $this->db->replace('batches_tutors', array('batches_id'=>$id, 'users_id'=>$val));

            $this->db->trans_complete();

            if ($this->db->trans_status() === TRUE)
                return TRUE;
        }
        else // insert
        {
            $this->db->trans_start();
            $this->db->insert($this->table, $data);
            
            $batches_id =  $this->db->insert_id();

            // insert tutors on the batch
            $data_3     = array();
            $i          = 0;
            foreach($data_2 as $val)
            {
                $data_3[$i]['batches_id']   = $batches_id;
                $data_3[$i]['users_id']     = $val;
                $i++;
            }

            $this->db->insert_batch('batches_tutors', $data_3);
            $this->db->trans_complete();

            if ($this->db->trans_status() === TRUE)
                return TRUE;
        }

        return FALSE;
    }

    /**
     * save_batches_tutors
     *
     * @return array
     * 
     **/
    public function save_batches_tutors($data = array())
    {
        $this->db->insert_batch('batches_tutors', $data);
        return $this->db->insert_id();
    }

    /**
     * delete_batches
     *
     * @return array
     * 
     **/
    public function delete_batches($id = NULL, $title = NULL, $tutors = array())
    {
        if(! ($id && $title)) 
            return FALSE;

        $this->db->trans_start();
        $this->db->delete($this->table, array('id' => $id, 'title'=>$title)); 

        foreach($tutors as $val)
            $this->db->delete('batches_tutors', array('batches_id'=>$id, 'users_id'=>$val->users_id));

        $this->db->trans_complete();

        if ($this->db->trans_status() === TRUE)
            return TRUE;        

        return FALSE;
    }

}

/*Batches model ends*/