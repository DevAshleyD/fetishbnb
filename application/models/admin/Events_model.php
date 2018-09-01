<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Events Model
 *
 * This model handles events module data
 *
 * @package     classiebit
 * @author      prodpk
*/

class Events_model extends CI_Model {

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
     * top_events_list
     */
    public function top_events_list()
    {
        return $this->db->query("SELECT e.id, e.title, 'admin/events/view/' url, 'event' type, (SELECT COUNT(ebm.id) FROM e_bookings_members ebm WHERE ebm.e_bookings_id IN (SELECT eb.id FROM e_bookings eb WHERE eb.events_id = e.id)) total_bookings FROM events e ORDER BY total_bookings DESC LIMIT 5")
                        ->result();
    }    
    
    /**
     * get_event_types_dropdown
     *
     * @return array
     * 
     **/
    public function get_event_types_dropdown()
    {
        return $this->db->select(array(
                                    "event_types.id", 
                                    "event_types.title",
                                ))
                        ->order_by('title')
                        ->get('event_types')
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
     * get_events_tutors
     *
     * @return array
     * 
     **/
    public function get_events_tutors($events_id = NULL)
    {
        return $this->db->select(array(
                                    "events_tutors.users_id",
                                    "users.username",
                                    "users.first_name",
                                    "users.last_name",
                                    "users.image",
                                ))
                        ->join('users', 'users.id = events_tutors.users_id', 'left')
                        ->where(array('events_id'=>$events_id))
                        ->get('events_tutors')
                        ->result();
    }

    /**
     * get_events
     *
     * @return array
     * 
     **/
    public function get_events_by_id($id = FALSE)
    {
        return $this->db->select(array(
                            "$this->table.id",
                            "$this->table.title",
                            "$this->table.description",
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
                            "$this->table.featured",
                            "$this->table.status",
                            "$this->table.date_added",
                            "$this->table.date_updated",
                            "$this->table.meta_title",
                            "$this->table.meta_tags",
                            "$this->table.meta_description",
                            "$this->table.event_types_id",
                            "event_types.title event_types_title",
                        ))
                        ->join("event_types", "event_types.id = $this->table.event_types_id")
                        ->where(array("$this->table.id"=>$id))
                        ->get($this->table)->row();
    }

    /**
     * save_events
     *
     * @return array
     * 
     **/
    public function save_events($data = array(), $data_2 = array(), $id = FALSE, $cur_tutors = array())
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
                    $this->db->delete('events_tutors', array('events_id'=>$id, 'users_id'=>$val));

            // insert tutors on the event
            foreach($data_2 as $val)
                $this->db->replace('events_tutors', array('events_id'=>$id, 'users_id'=>$val));

            $this->db->trans_complete();

            if ($this->db->trans_status() === TRUE)
                return TRUE;
        }
        else // insert
        {
            $this->db->trans_start();
            $this->db->insert($this->table, $data);
            
            $events_id =  $this->db->insert_id();

            // insert tutors on the event
            $data_3     = array();
            $i          = 0;
            foreach($data_2 as $val)
            {
                $data_3[$i]['events_id']   = $events_id;
                $data_3[$i]['users_id']     = $val;
                $i++;
            }

            $this->db->insert_batch('events_tutors', $data_3);
            $this->db->trans_complete();

            if ($this->db->trans_status() === TRUE)
                return TRUE;
        }

        return FALSE;
    }

    /**
     * save_events_tutors
     *
     * @return array
     * 
     **/
    public function save_events_tutors($data = array())
    {
        $this->db->insert_batch('events_tutors', $data);
        return $this->db->insert_id();
    }

    /**
     * delete_events
     *
     * @return array
     * 
     **/
    public function delete_events($id = NULL, $title = NULL, $tutors = array())
    {
        if(! ($id && $title)) 
            return FALSE;

        $this->db->trans_start();
        $this->db->delete($this->table, array('id' => $id, 'title'=>$title)); 

        foreach($tutors as $val)
            $this->db->delete('events_tutors', array('events_id'=>$id, 'users_id'=>$val->users_id));

        $this->db->trans_complete();

        if ($this->db->trans_status() === TRUE)
            return TRUE;        

        return FALSE;
    }

}

/*Events model ends*/