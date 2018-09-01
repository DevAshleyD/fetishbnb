<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Bbookings Model
 *
 * This model handles b_bookings module data
 *
 * @package     classiebit
 * @author      prodpk
*/

class Bbookings_model extends CI_Model {

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
    private $table = 'b_bookings';
    
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
     * get_courses_dropdown
     *
     * @return array
     * 
     **/
    public function get_courses_dropdown($id = NULL)
    {
        return $this->db->select(array('id', 'title'))
                        ->where(array('course_categories_id'=>$id, 'status !='=>0))
                        ->order_by('title')
                        ->get('courses')
                        ->result();
    }

    /**
     * get_batches
     *
     * @return array
     * 
     **/
    public function get_batches($course_id = FALSE)
    {
        return $this->db->select(array(
                            "batches.id",
                            "batches.title",
                            "batches.fees",
                            "batches.capacity",
                            "batches.start_date",
                            "batches.end_date",
                            "batches.start_time",
                            "batches.end_time",
                            "batches.weekdays",
                            "batches.recurring",
                            "batches.recurring_type",
                            "batches.status",
                            "batches.date_updated",
                        ))
                        ->where(array("courses_id"=>$course_id))
                        ->order_by('end_date', 'ASC')
                        ->get('batches')
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
    public function get_net_fees($batch_id = '0')
    {
        return $this->db->select(array('batches.title batch_title', 'batches.fees', 'batches.recurring', 'batches.weekdays', 'batches.capacity', 'taxes.title', 'taxes.rate_type', 'taxes.rate', 'taxes.net_price'))
                        ->join('settings', "settings.name = 'default_tax_id'", 'left')
                        ->join('taxes', "taxes.id = settings.value", 'left')
                        ->where(array('batches.id'=>$batch_id))
                        ->get('batches')
                        ->row();
    }

    /**
     * get_total_b_bookings
     *
     * @return array
     * 
    **/
     public function get_total_b_bookings($batches_id = NULL, $booking_date = NULL)
     {  
        $result      = $this->db->select(array(
                                            "COUNT(BM.id) count_b_bookings",
                                        ))
                                ->join("$this->table B", 'B.id = BM.b_bookings_id', 'left')
                                ->where(array(
                                            "B.batches_id"=>$batches_id, 
                                            "B.booking_date"=>$booking_date,
                                            "B.cancellation <"=>'2',
                                            "B.status"=>'1',
                                        ))
                                ->get('b_bookings_members BM')
                                ->row();

        return empty($result) ? 0 : $result->count_b_bookings;
     }
   
    /**
     * get_b_bookings
     *
     * @return array
     * 
     **/
    public function get_b_bookings_by_id($id = FALSE, $user_id = NULL)
    {
        $this->db->select(array(
                            "$this->table.id",
                            "$this->table.customers_id",
                            "$this->table.batches_id",
                            "$this->table.courses_id",
                            "$this->table.course_categories_id",
                            "$this->table.fees",
                            "$this->table.net_fees",
                            "$this->table.booking_date",
                            "$this->table.batch_title",
                            "$this->table.batch_description",
                            "$this->table.batch_capacity",
                            "$this->table.batch_weekdays",
                            "$this->table.batch_recurring",
                            "$this->table.batch_start_date",
                            "$this->table.batch_end_date",
                            "$this->table.batch_start_time",
                            "$this->table.batch_end_time",
                            "$this->table.course_title",
                            "$this->table.course_category_title",
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
     * get_user_b_bookings
     *
     * @return array
     * 
     **/
    public function get_user_b_bookings($id = FALSE, $user_id = NULL)
    {
        return $this->db->select(array(
                            "$this->table.id",
                            "$this->table.customers_id",
                            "$this->table.booking_date",
                            "$this->table.batch_start_date",
                            "$this->table.batch_end_date",
                            "$this->table.batch_start_time",
                            "$this->table.batch_end_time",
                            "$this->table.cancellation",
                            "$this->table.status",
                            "$this->table.date_updated",
                        ))
                        ->where(array('id'=>$id, 'customers_id'=>$user_id))
                        ->get($this->table)
                        ->row();
        
    }

    /**
     * get_b_bookings_members
     *
     * @return array
     * 
     **/
    public function get_b_bookings_members($id = FALSE)
    {
        return $this->db->select(array(
                            "id",
                            "fullname",
                            "email",
                            "mobile",
                        ))
                        ->where(array('b_bookings_id'=>$id))
                        ->get('b_bookings_members')
                        ->result();
        
    }

    /**
     * get_b_bookings_payments
     *
     * @return array
     * 
     **/
    public function get_b_bookings_payments($id = FALSE)
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
                        ->where(array('b_bookings_id'=>$id))
                        ->get('b_bookings_payments')
                        ->row();
        
    }

    /**
     * save_b_bookings
     *
     * @return array
     * 
     **/
    public function save_b_bookings($data = array(), $members = array(), $payments = array(), $id = FALSE)
    {
        if($id) // update
        {
            $this->db->trans_start();
            $this->db->where(array('id'=>$id))
                     ->update($this->table, $data);

            if(!empty($payments))
            {
                $this->db->where(array('b_bookings_id'=>$id))
                     ->update('b_bookings_payments', $payments);    
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

            // insert members into b_bookings_members
            foreach($members as $val)
                $this->db->insert('b_bookings_members', $val);

            // insert payments into b_bookings_payments
            $this->db->insert('b_bookings_payments', $payments);

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

    /**
     * get_my_batches
     *
     * @return array
     * 
     **/
    public function get_my_batches($user_id = NULL)
    {
        return $this->db->select(array(
                            "$this->table.id", 
                            "$this->table.batches_id",
                            "$this->table.batch_title",
                            "$this->table.batch_start_date",
                            "$this->table.batch_end_date",
                            "$this->table.batch_start_time",
                            "$this->table.batch_end_time",
                            "$this->table.course_title",
                            "$this->table.booking_date",
                            "$this->table.cancellation",
                            "b_bookings_payments.payment_type",
                            "b_bookings_payments.payment_status",
                            "b_bookings_payments.currency",
                            "b_bookings_payments.date_added",
                            "transactions.txn_id",
                            "transactions.total_amount",
                        ))
                        ->join('b_bookings_payments', "b_bookings_payments.b_bookings_id = $this->table.id", 'left')
                        ->join('transactions', "transactions.id = b_bookings_payments.transactions_id", 'left')
                        ->where(array('customers_id'=>$user_id))
                        ->get($this->table)
                        ->result();
    }

     /**
     * cancel_b_bookings
     *
     * @return array
     * 
     **/
    public function cancel_b_bookings($id = FALSE, $user_id = NULL, $data = array())
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

/*Bbookings model ends*/