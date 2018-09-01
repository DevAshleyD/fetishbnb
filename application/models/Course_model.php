<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Course Listings Model
 *
 * This model handles courses_listings module data
 *
 * @package     classiebit
 * @author      prodpk
*/

class Course_model extends CI_Model {

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
     * count_courses
     */
    public function count_courses()
    {
        return $this->db->count_all_results($this->table);
    }

    /**
     * count_batches
     */
    public function count_batches()
    {
        return $this->db->count_all_results('batches');
    }

    /**
     * count_b_bookings
     */
    public function count_b_bookings()
    {
        return $this->db->count_all_results('b_bookings_members');
    }

    /**
     * count_total_b_sales
     */
    public function count_total_b_sales()
    {
        return $this->db->select(array(
                            'SUM(b_bookings_payments.total_amount) total_amount',
                            'b_bookings_payments.date_added',
                        ))
                        ->order_by('b_bookings_payments.date_added', 'ASC')
                        ->group_by('b_bookings_payments.date_added')
                        ->get('b_bookings_payments')
                        ->result();
    }    

    /**
     * todays_batches_list
     */
    public function todays_batches_list()
    {
        return $this->db->select(array(
                            'id',
                            'title',
                            'start_time',
                            'end_time',
                            ' "admin/batches/view/" url',
                            ' "batch" type',
                        ))
                        ->where(array('start_date <='=>date('Y-m-d'), 'end_date >='=>date('Y-m-d')))
                        ->order_by('start_date', 'ASC')
                        ->get('batches')
                        ->result();
    }    

    /**
     * top_courses_list
     */
    public function top_courses_list()
    {
        return $this->db->query("SELECT c.id, c.title, c.images, 'courses/detail/' url, 'course' type, (SELECT COUNT(bbm.id) FROM b_bookings_members bbm WHERE bbm.b_bookings_id IN (SELECT bb.id FROM b_bookings bb WHERE bb.courses_id = c.id)) total_bookings FROM courses c ORDER BY total_bookings DESC LIMIT 5")
                        ->result();
    }    
    
    /**
     * get_courses
     */
    public function get_courses($categories = array())
    {
        $this->db->select(array(
                            "$this->table.id",
                            "$this->table.title",
                            "$this->table.status",
                            "$this->table.images",
                            "$this->table.course_categories_id",
                            "$this->table.date_added",
                            "$this->table.date_updated",
                            "(SELECT users.id FROM users WHERE users.id = (SELECT bt.users_id FROM batches_tutors bt WHERE bt.batches_id = (SELECT ba.id FROM batches ba WHERE ba.courses_id = $this->table.id LIMIT 1) LIMIT 1) LIMIT 1) users_id",
                            "(SELECT cc.title FROM course_categories cc WHERE cc.id = $this->table.course_categories_id) category_name",
                            "(SELECT COUNT(ba.id) FROM batches ba WHERE ba.courses_id = $this->table.id) total_batches",
                            "(SELECT MIN(ba2.fees) FROM batches ba2 WHERE ba2.courses_id = $this->table.id) starting_price",
                            "(SELECT COUNT(DISTINCT(bt.users_id)) FROM batches_tutors bt WHERE bt.batches_id IN (SELECT ba3.id FROM batches ba3 WHERE ba3.courses_id = $this->table.id)) total_tutors",
                            "(SELECT COUNT(ba4.id) FROM batches ba4 WHERE ba4.courses_id = $this->table.id AND ba4.recurring = 1) total_recurring",
                            "(SELECT COUNT(bm.id) FROM b_bookings_members bm WHERE bm.b_bookings_id IN (SELECT bk.id FROM b_bookings bk WHERE bk.courses_id = $this->table.id)) total_b_bookings",
                        ))
                        ->where(array("$this->table.status != " => 0));

        if(! empty($categories))
            $this->db->where_in("$this->table.course_categories_id", $categories);

        return $this->db->order_by('id', 'DESC')
                        ->get($this->table)
                        ->result();
    }

    /**
     * get_f_courses
     */
    public function get_f_courses()
    {
        $this->db->select(array(
                            "$this->table.id",
                            "$this->table.title",
                            "$this->table.status",
                            "$this->table.images",
                            "$this->table.course_categories_id",
                            "$this->table.date_added",
                            "$this->table.date_updated",
                            "(SELECT users.id FROM users WHERE users.id = (SELECT bt.users_id FROM batches_tutors bt WHERE bt.batches_id = (SELECT ba.id FROM batches ba WHERE ba.courses_id = $this->table.id LIMIT 1) LIMIT 1) LIMIT 1) users_id",
                            "(SELECT cc.title FROM course_categories cc WHERE cc.id = $this->table.course_categories_id) category_name",
                            "(SELECT COUNT(ba.id) FROM batches ba WHERE ba.courses_id = $this->table.id) total_batches",
                            "(SELECT MIN(ba2.fees) FROM batches ba2 WHERE ba2.courses_id = $this->table.id) starting_price",
                            "(SELECT COUNT(DISTINCT(bt.users_id)) FROM batches_tutors bt WHERE bt.batches_id IN (SELECT ba3.id FROM batches ba3 WHERE ba3.courses_id = $this->table.id)) total_tutors",
                            "(SELECT COUNT(ba4.id) FROM batches ba4 WHERE ba4.courses_id = $this->table.id AND ba4.recurring = 1) total_recurring",
                            "(SELECT COUNT(bm.id) FROM b_bookings_members bm WHERE bm.b_bookings_id IN (SELECT bk.id FROM b_bookings bk WHERE bk.courses_id = $this->table.id)) total_b_bookings",
                        ))
                        ->where(array("$this->table.status != " => 0, "$this->table.featured"=>1));

        return $this->db->order_by('date_updated', 'DESC')
                        ->get($this->table)
                        ->result();
    }

    /**
     * get_course_categories_id
     *
     * @return array
     * 
     **/
    public function get_course_categories_id($category = NULL) 
    {
        return $this->db->select(array('id'))
                         ->where(array('title'=>$category))
                         ->get('course_categories')
                         ->row();
        
    }

    /**
     * get_course_id_by_title
     *
     * @return array
     * 
     **/
    public function get_course_id_by_title($title = NULL) 
    {
        return $this->db->select(array('id', 'course_categories_id'))
                         ->where(array('title'=>$title, 'status !='=>'0'))
                         ->get($this->table)
                         ->row();
        
    }

    /**
     * get_title_by_id
     *
     * @return array
     * 
     **/
    public function get_title_by_id($id = NULL, $table = NULL) 
    {
        return $this->db->select(array('title'))
                         ->where(array('id'=>$id))
                         ->get($table)
                         ->row();
        
    }

    /**
     * get_course_detail
     */
    public function get_course_detail($course_title = NULL)
    {
        $this->db->select(array(
                            "$this->table.id",
                            "$this->table.title",
                            "$this->table.description",
                            "$this->table.status",
                            "$this->table.images",
                            "$this->table.course_categories_id",
                            "$this->table.meta_title",
                            "$this->table.meta_tags",
                            "$this->table.meta_description",
                            "$this->table.date_added",
                            "$this->table.date_updated",
                            "(SELECT cc.title FROM course_categories cc WHERE cc.id = $this->table.course_categories_id) category_name",
                            "(SELECT COUNT(ba.id) FROM batches ba WHERE ba.courses_id = $this->table.id) total_batches",
                            "(SELECT MIN(ba2.fees) FROM batches ba2 WHERE ba2.courses_id = $this->table.id) starting_price",
                            "(SELECT MIN(ba2.start_date) FROM batches ba2 WHERE ba2.courses_id = $this->table.id) starting_date",
                            "(SELECT COUNT(DISTINCT(bt.users_id)) FROM batches_tutors bt WHERE bt.batches_id IN (SELECT ba3.id FROM batches ba3 WHERE ba3.courses_id = $this->table.id)) total_tutors",
                            "(SELECT COUNT(ba4.id) FROM batches ba4 WHERE ba4.courses_id = $this->table.id AND ba4.recurring = 1) total_recurring",
                            "(SELECT COUNT(bm.id) FROM b_bookings_members bm WHERE bm.b_bookings_id IN (SELECT bk.id FROM b_bookings bk WHERE bk.courses_id = $this->table.id)) total_b_bookings",

                        ))
                        ->where(array("$this->table.title" => $course_title));

        return $this->db->get($this->table)
                        ->row();
    }

    /**
     * get_courses_tutors
     *
     * @return array
     * 
     **/
    public function get_courses_tutors($courses_id = NULL)
    {
        return $this->db->query("SELECT users.id, 
                                        users.first_name, 
                                        users.last_name, 
                                        users.username, 
                                        users.image , 
                                        (SELECT COUNT(DISTINCT(bt.batches_id)) FROM batches_tutors bt WHERE bt.users_id = users.id) total_batches
                                 FROM users WHERE users.id IN (SELECT bt.users_id FROM batches_tutors bt WHERE bt.batches_id IN (SELECT ba.id FROM batches ba WHERE ba.courses_id = $courses_id))")
                        ->result();
    }

    /**
     * get_tutor_courses
     *
     * @return array
     * 
     **/
    public function get_tutor_courses($user_id = NULL)
    {
        return $this->db->query("SELECT $this->table.id, 
                                        $this->table.title, 
                                        $this->table.status, 
                                        $this->table.images,
                                        (SELECT cc.title FROM course_categories cc WHERE cc.id = $this->table.course_categories_id) category_name,
                                        (SELECT COUNT(ba.id) FROM batches ba WHERE ba.courses_id = $this->table.id) total_batches,
                                        (SELECT MIN(ba2.fees) FROM batches ba2 WHERE ba2.courses_id = $this->table.id) starting_price,
                                        (SELECT COUNT(bt.users_id) FROM batches_tutors bt WHERE bt.batches_id IN (SELECT ba3.id FROM batches ba3 WHERE ba3.courses_id = $this->table.id)) total_tutors
                                 FROM $this->table WHERE $this->table.id IN (SELECT ba.courses_id FROM batches ba WHERE ba.id IN (SELECT bt.batches_id FROM batches_tutors bt WHERE bt.users_id = $user_id))")
                        ->result();
    }

    /**
     * get_courses_tutor
     *
     * @return array
     * 
     **/
    public function get_courses_tutor($username = NULL)
    {
        return $this->db->select(array(
                            'users.id',
                            'users.username',
                            'users.first_name',
                            'users.last_name',
                            'users.gender',
                            'users.dob',
                            'users.email',
                            'users.mobile',
                            'users.address',
                            'users.profession',
                            'users.experience',
                            'users.about',
                            'users.image',
                        ))
                        ->where(array('users.username'=>$username))
                        ->get('users')
                        ->row();
    }

    /**
     * get_tutors
     *
     * @return array
     * 
     **/
    public function get_tutors($ids = array())
    {
        return $this->db->select(array(
                            'users.id',
                            'users.username',
                            'users.first_name',
                            'users.last_name',
                            'users.gender',
                            'users.dob',
                            'users.email',
                            'users.mobile',
                            'users.address',
                            'users.profession',
                            'users.experience',
                            'users.about',
                            'users.image',
                            "(SELECT COUNT(DISTINCT(bt.batches_id)) FROM batches_tutors bt WHERE bt.users_id = users.id) total_batches",
                            "(SELECT COUNT(DISTINCT(et.events_id)) FROM events_tutors et WHERE et.users_id = users.id) total_events",
                        ))
                        ->where_in('id', $ids)
                        ->get('users')
                        ->result();
    }    

    /**
     * get_categories
     *
     * @return array
     * 
     **/
    public function get_categories($search = '')
    {
        return $this->db->select(array(
                            'course_categories.title',
                        ))
                        ->like('course_categories.title', $search, 'both')
                        ->where(array('course_categories.status !='=>'0'))
                        ->get('course_categories')
                        ->result();
    }    

}

/*Course model ends*/