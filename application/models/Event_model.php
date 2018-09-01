<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Event Listings Model
 *
 * This model handles events_listings module data
 *
 * @package     classiebit
 * @author      prodpk
*/

class Event_model extends CI_Model {

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
    private $table = 'events';

    /**
     * count_events
     */
    public function count_events()
    {
        return $this->db->count_all_results($this->table);
    }

    /**
     * count_e_bookings
     */
    public function count_e_bookings()
    {
        return $this->db->count_all_results('e_bookings_members');
    }

    /**
     * count_total_e_sales
     */
    public function count_total_e_sales()
    {
        return $this->db->select(array(
                            'SUM(e_bookings_payments.total_amount) total_amount',
                            'e_bookings_payments.date_added',
                        ))
                        ->order_by('e_bookings_payments.date_added', 'ASC')
                        ->group_by('e_bookings_payments.date_added')
                        ->get('e_bookings_payments')
                        ->result();
    }    

    /**
     * todays_events_list
     */
    public function todays_events_list()
    {
        return $this->db->select(array(
                            'id',
                            'title',
                            'start_time',
                            'end_time',
                            ' "admin/events/view/" url',
                            ' "event" type',
                        ))
                        ->where(array('start_date <='=>date('Y-m-d'), 'end_date >='=>date('Y-m-d')))
                        ->order_by('start_date', 'ASC')
                        ->get('events')
                        ->result();
    }    

    /**
     * top_events_list
     */
    public function top_events_list()
    {
        return $this->db->query("SELECT e.id, e.title, e.images, 'events/detail/' url, 'event' type, (SELECT COUNT(ebm.id) FROM e_bookings_members ebm WHERE ebm.e_bookings_id IN (SELECT eb.id FROM e_bookings eb WHERE eb.events_id = e.id)) total_bookings FROM events e ORDER BY total_bookings DESC LIMIT 5")
                        ->result();
    }    
    
    /**
     * get_events
     */
    public function get_events($category_id = NULL)
    {
        $this->db->select(array(
                            "$this->table.id",
                            "$this->table.title",
                            "$this->table.status",
                            "$this->table.images",
                            "$this->table.event_types_id",
                            "$this->table.fees",
                            "$this->table.capacity",
                            "$this->table.recurring",
                            "$this->table.start_date",
                            "$this->table.end_date",
                            "$this->table.start_time",
                            "$this->table.end_time",
                            "$this->table.date_added",
                            "$this->table.date_updated",
                            "(SELECT users.id FROM users WHERE users.id = (SELECT et.users_id FROM events_tutors et WHERE et.events_id = $this->table.id LIMIT 1) LIMIT 1) users_id",
                            "(SELECT cc.title FROM event_types cc WHERE cc.id = $this->table.event_types_id) category_name",
                            "(SELECT COUNT(DISTINCT(et.users_id)) FROM events_tutors et WHERE et.events_id = $this->table.id) total_tutors",
                            "(SELECT COUNT(em.id) FROM e_bookings_members em WHERE em.e_bookings_id IN (SELECT ek.id FROM e_bookings ek WHERE ek.events_id = $this->table.id)) total_e_bookings",
                        ))
                        ->where(array("$this->table.status != " => 0));

        if(! empty($category_id))
            $this->db->where("$this->table.event_types_id", $category_id);

        return $this->db->order_by('id', 'DESC')
                        ->get($this->table)
                        ->result();
    }

    /**
     * get_f_events
     */
    public function get_f_events()
    {
        $this->db->select(array(
                            "$this->table.id",
                            "$this->table.title",
                            "$this->table.status",
                            "$this->table.images",
                            "$this->table.event_types_id",
                            "$this->table.fees",
                            "$this->table.capacity",
                            "$this->table.recurring",
                            "$this->table.start_date",
                            "$this->table.end_date",
                            "$this->table.start_time",
                            "$this->table.end_time",
                            "$this->table.date_added",
                            "$this->table.date_updated",
                            "(SELECT users.id FROM users WHERE users.id = (SELECT et.users_id FROM events_tutors et WHERE et.events_id = $this->table.id LIMIT 1) LIMIT 1) users_id",
                            "(SELECT cc.title FROM event_types cc WHERE cc.id = $this->table.event_types_id) category_name",
                            "(SELECT COUNT(DISTINCT(et.users_id)) FROM events_tutors et WHERE et.events_id = $this->table.id) total_tutors",
                            "(SELECT COUNT(em.id) FROM e_bookings_members em WHERE em.e_bookings_id IN (SELECT ek.id FROM e_bookings ek WHERE ek.events_id = $this->table.id)) total_e_bookings",
                        ))
                        ->where(array("$this->table.status != " => 0, "$this->table.featured"=>1));

        return $this->db->order_by('date_updated', 'DESC')
                        ->get($this->table)
                        ->result();
    }

    /**
     * get_u_events upcoming events
     */
    public function get_u_events()
    {
        $this->db->select(array(
                            "$this->table.id",
                            "$this->table.title",
                            "$this->table.status",
                            "$this->table.images",
                            "$this->table.event_types_id",
                            "$this->table.fees",
                            "$this->table.capacity",
                            "$this->table.recurring",
                            "$this->table.start_date",
                            "$this->table.end_date",
                            "$this->table.start_time",
                            "$this->table.end_time",
                            "$this->table.date_added",
                            "$this->table.date_updated",
                            "(SELECT users.id FROM users WHERE users.id = (SELECT et.users_id FROM events_tutors et WHERE et.events_id = $this->table.id LIMIT 1) LIMIT 1) users_id",
                            "(SELECT cc.title FROM event_types cc WHERE cc.id = $this->table.event_types_id) category_name",
                            "(SELECT COUNT(DISTINCT(et.users_id)) FROM events_tutors et WHERE et.events_id = $this->table.id) total_tutors",
                            "(SELECT COUNT(em.id) FROM e_bookings_members em WHERE em.e_bookings_id IN (SELECT ek.id FROM e_bookings ek WHERE ek.events_id = $this->table.id)) total_e_bookings",
                        ))
                        ->where(array("$this->table.status != " => 0, "$this->table.start_date >="=>date('Y-m-d')));

        return $this->db->order_by('start_date', 'ASC')
                        ->limit(6)
                        ->get($this->table)
                        ->result();
    }

    /**
     * get_event_types_id
     *
     * @return array
     * 
     **/
    public function get_event_types_id($category = NULL) 
    {
        return $this->db->select(array('id'))
                         ->where(array('title'=>$category))
                         ->get('event_types')
                         ->row();
        
    }

    /**
     * get_event_id_by_title
     *
     * @return array
     * 
     **/
    public function get_event_id_by_title($title = NULL) 
    {
        return $this->db->select(array('id', 'event_types_id'))
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
     * get_event_detail
     */
    public function get_event_detail($event_title = NULL)
    {
        $this->db->select(array(
                            "$this->table.id",
                            "$this->table.title",
                            "$this->table.description",
                            "$this->table.status",
                            "$this->table.images",
                            "$this->table.fees",
                            "$this->table.capacity",
                            "$this->table.start_date",
                            "$this->table.end_date",
                            "$this->table.start_time",
                            "$this->table.end_time",
                            "$this->table.weekdays",
                            "$this->table.recurring",
                            "$this->table.recurring_type",
                            "$this->table.event_types_id",
                            "$this->table.meta_title",
                            "$this->table.meta_tags",
                            "$this->table.meta_description",
                            "$this->table.date_added",
                            "$this->table.date_updated",
                            "(SELECT cc.title FROM event_types cc WHERE cc.id = $this->table.event_types_id) category_name",
                            "(SELECT COUNT(DISTINCT(et.users_id)) FROM events_tutors et WHERE et.events_id = $this->table.id) total_tutors",
                            "(SELECT COUNT(em.id) FROM e_bookings_members em WHERE em.e_bookings_id IN (SELECT ek.id FROM e_bookings ek WHERE ek.events_id = $this->table.id)) total_e_bookings",
                        ))
                        ->where(array("$this->table.title" => $event_title));

        return $this->db->get($this->table)
                        ->row();
    }

    /**
     * get_events_tutors
     *
     * @return array
     * 
     **/
    public function get_events_tutors($events_id = NULL)
    {
        return $this->db->query("SELECT users.id, 
                                        users.first_name, 
                                        users.last_name, 
                                        users.username, 
                                        users.image , 
                                        (SELECT COUNT(DISTINCT(et.events_id)) FROM events_tutors et WHERE et.users_id = users.id) total_events
                                 FROM users WHERE users.id IN (SELECT et.users_id FROM events_tutors et WHERE et.events_id = $events_id)")
                        ->result();
    }

    /**
     * get_tutor_events
     *
     * @return array
     * 
     **/
    public function get_tutor_events($user_id = NULL)
    {
        return $this->db->query("SELECT $this->table.id, 
                                        $this->table.title, 
                                        $this->table.status, 
                                        $this->table.images,
                                        (SELECT cc.title FROM event_types cc WHERE cc.id = $this->table.event_types_id) category_name,
                                        (SELECT COUNT(et.users_id) FROM events_tutors et WHERE et.events_id = $this->table.id) total_tutors
                                 FROM $this->table WHERE $this->table.id IN (SELECT et.events_id FROM events_tutors et WHERE et.users_id = $user_id)")
                        ->result();
    }

    /**
     * get_events_tutor
     *
     * @return array
     * 
     **/
    public function get_events_tutor($username = NULL)
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
                            'event_types.title',
                        ))
                        ->like('event_types.title', $search, 'both')
                        ->where(array('event_types.status !='=>'0'))
                        ->get('event_types')
                        ->result();
    }    

}

/*Event model ends*/