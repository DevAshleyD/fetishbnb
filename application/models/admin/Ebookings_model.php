<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Ebookings Model
 *
 * This model handles e_bookings module data
 *
 * @package     classiebit
 * @author      prodpk
*/

class Ebookings_model extends CI_Model {

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
    private $table = 'e_bookings';
    
    /**
     * get_events
     *
     * @return array
     * 
     **/
    public function get_events($event_type_id = FALSE)
    {
        return $this->db->select(array(
                            "events.id",
                            "events.title",
                            "events.fees",
                            "events.capacity",
                            "events.start_date",
                            "events.end_date",
                            "events.start_time",
                            "events.end_time",
                            "events.weekdays",
                            "events.recurring",
                            "events.recurring_type",
                            "events.status",
                            "events.date_updated",
                        ))
                        ->where(array("event_types_id"=>$event_type_id))
                        ->order_by('end_date', 'ASC')
                        ->get('events')
                        ->result_array();
    }

    /**
     * get_taxes_dropdown
     *
     * @return array
     * 
     **/
    public function get_taxes_dropdown()
    {
        return $this->db->select(array('id', 'title', 'rate_type', 'rate', 'net_price'))
                        ->where(array('status !='=>0))
                        ->order_by('title')
                        ->get('taxes')
                        ->result();
    }

    /**
     * get_net_fees
     *
     * @return array
     * 
     **/
    public function get_net_fees($event_id = '0')
    {
        return $this->db->select(array('events.title event_title', 'events.fees', 'events.recurring', 'events.weekdays', 'events.capacity', 'taxes.title', 'taxes.rate_type', 'taxes.rate', 'taxes.net_price'))
                        ->join('settings', "settings.name = 'default_tax_id'", 'left')
                        ->join('taxes', "taxes.id = settings.value", 'left')
                        ->where(array('events.id'=>$event_id))
                        ->get('events')
                        ->row();
    }

    /**
     * get_total_e_bookings
     *
     * @return array
     * 
    **/
     public function get_total_e_bookings($events_id = NULL, $booking_date = NULL)
     {  
        $result      = $this->db->select(array(
                                            "COUNT(BM.id) count_e_bookings",
                                        ))
                                ->join("$this->table B", 'B.id = BM.e_bookings_id', 'left')
                                ->where(array(
                                            "B.events_id"=>$events_id, 
                                            "B.booking_date"=>$booking_date,
                                            "B.cancellation <"=>'2',
                                            "B.status"=>'1',
                                        ))
                                ->get('e_bookings_members BM')
                                ->row();

        return empty($result) ? 0 : $result->count_e_bookings;
     }
   
    /**
     * get_e_bookings
     *
     * @return array
     * 
     **/
    public function get_e_bookings_by_id($id = FALSE, $user_id = NULL)
    {
        $this->db->select(array(
                            "$this->table.id",
                            "$this->table.customers_id",
                            "$this->table.event_types_id",
                            "$this->table.events_id",
                            "$this->table.fees",
                            "$this->table.net_fees",
                            "$this->table.booking_date",
                            "$this->table.event_title",
                            "$this->table.event_description",
                            "$this->table.event_capacity",
                            "$this->table.event_weekdays",
                            "$this->table.event_recurring",
                            "$this->table.event_start_date",
                            "$this->table.event_end_date",
                            "$this->table.event_start_time",
                            "$this->table.event_end_time",
                            "$this->table.event_title",
                            "$this->table.event_type_title",
                            "$this->table.customer_name",
                            "$this->table.customer_email",
                            "$this->table.customer_address",
                            "$this->table.customer_mobile",
                            "$this->table.cancellation",
                            "$this->table.status",
                            "$this->table.date_added",
                        ))
                        ->where(array('id'=>$id));
        
        if($user_id)
            $this->db->where(array('customers_id'=>$user_id));

        return $this->db->get($this->table)
                        ->row();
        
    }

    /**
     * get_user_e_bookings
     *
     * @return array
     * 
     **/
    public function get_user_e_bookings($id = FALSE, $user_id = NULL)
    {
        return $this->db->select(array(
                            "$this->table.id",
                            "$this->table.customers_id",
                            "$this->table.booking_date",
                            "$this->table.event_start_date",
                            "$this->table.event_end_date",
                            "$this->table.event_start_time",
                            "$this->table.event_end_time",
                            "$this->table.cancellation",
                            "$this->table.status",
                            "$this->table.date_updated",
                        ))
                        ->where(array('id'=>$id, 'customers_id'=>$user_id))
                        ->get($this->table)
                        ->row();
        
    }

    /**
     * get_e_bookings_members
     *
     * @return array
     * 
     **/
    public function get_e_bookings_members($id = FALSE)
    {
        return $this->db->select(array(
                            "id",
                            "fullname",
                            "email",
                            "mobile",
                        ))
                        ->where(array('e_bookings_id'=>$id))
                        ->get('e_bookings_members')
                        ->result();
        
    }

    /**
     * get_e_bookings_payments
     *
     * @return array
     * 
     **/
    public function get_e_bookings_payments($id = FALSE)
    {
        return $this->db->select(array(
                            "id",
                            "paid_amount",
                            "payment_type",
                            "payment_status",
                            "currency",
                            "tax_title",
                            "tax_rate_type",
                            "tax_rate",
                            "tax_net_price",
                            "date_added",
                        ))
                        ->where(array('e_bookings_id'=>$id))
                        ->get('e_bookings_payments')
                        ->row();
        
    }

    /**
     * save_e_bookings
     *
     * @return array
     * 
     **/
    public function save_e_bookings($data = array(), $members = array(), $payments = array(), $id = FALSE)
    {
        if($id) // update
        {
            $this->db->trans_start();
            $this->db->where(array('id'=>$id))
                     ->update($this->table, $data);

            if(!empty($payments))
            {
                $this->db->where(array('e_bookings_id'=>$id))
                     ->update('e_bookings_payments', $payments);    
            }

            $this->db->trans_complete();

            if ($this->db->trans_status() === TRUE)
                return TRUE;                     
        }
        else // insert
        {
            $this->db->trans_start();
            $this->db->insert($this->table, $data);
            $insert_id = $this->db->insert_id();

            // update default_starting_booking_id in settings
            $this->db->set(array('value'=>$insert_id+1))
                     ->where(array('name'=>'default_starting_booking_id'))
                     ->update('settings');

            // insert members into e_bookings_members
            foreach($members as $val)
                $this->db->insert('e_bookings_members', $val);

            // insert payments into e_bookings_payments
            $this->db->insert('e_bookings_payments', $payments);

            $this->db->trans_complete();

            if ($this->db->trans_status() === TRUE)
                return TRUE;                     
        }

        return FALSE;
        
    }

        
    /**
     * save_transactions
     *
     * @return array
     * 
     **/
    public function save_transactions($data = array())
    {
        $this->db->insert('transactions', $data);

        return $this->db->insert_id();
    }

    /**
     * get_my_events
     *
     * @return array
     * 
     **/
    public function get_my_events($user_id = NULL)
    {
        return $this->db->select(array(
                            "$this->table.id", 
                            "$this->table.events_id",
                            "$this->table.event_title",
                            "$this->table.event_start_date",
                            "$this->table.event_end_date",
                            "$this->table.event_start_time",
                            "$this->table.event_end_time",
                            "$this->table.event_type_title",
                            "$this->table.booking_date",
                            "$this->table.cancellation",
                            "e_bookings_payments.payment_type",
                            "e_bookings_payments.payment_status",
                            "e_bookings_payments.currency",
                            "e_bookings_payments.date_added",
                            "transactions.txn_id",
                            "transactions.total_amount",
                        ))
                        ->join('e_bookings_payments', "e_bookings_payments.e_bookings_id = $this->table.id", 'left')
                        ->join('transactions', "transactions.id = e_bookings_payments.transactions_id", 'left')
                        ->where(array('customers_id'=>$user_id))
                        ->get($this->table)
                        ->result();
    }

     /**
     * cancel_e_bookings
     *
     * @return array
     * 
     **/
    public function cancel_e_bookings($id = FALSE, $user_id = NULL, $data = array())
    {
        if($id && $user_id) // update
        {
            $this->db->where(array('id'=>$id, 'customers_id'=>$user_id))
                     ->update($this->table, $data);

            if($this->db->affected_rows())
                return TRUE;
            else
                return 'already';
        }

        return FALSE;
    }


}

/*Ebookings model ends*/