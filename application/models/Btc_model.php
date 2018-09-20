<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * BTC Transaction Model
 *
 * This model handles btc_transaction module data
 *
 * @package     starcoders
 * @author      robreyes
*/

class Btc_model extends CI_Model {

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
    private $table = 'btc_transaction';

    public function count_transactions()
    {
        return $this->db->count_all_results($this->table);
    }

    public function get_user_transaction($id = NULL)
    {
        return $this->db->select(array('id','user_id','event_id','amount','date'))
                         ->where(array('user_id'=>$id))
                         ->get($this->table)
                         ->result_array();

    }
}
