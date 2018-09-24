<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * User Billing Model
 *
 * This model handles user_billing module data
 *
 * @package     eastvantage
 * @author      rob reyes
*/

class Billing_model extends CI_Model {

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
    private $table = 'users_billing';

    public function get_user_billing($id = FALSE, $is_array = FALSE)
    {
          $this->db->select(array(
                          "$this->table.user_id",
                          "$this->table.billing_name",
                          "$this->table.billing_lastname",
                          "$this->table.billing_method",
                          "$this->table.billing_address_1",
                          "$this->table.billing_address_2",
                          "$this->table.billing_city",
                          "$this->table.billing_state",
                          "$this->table.billing_country",
                          "$this->table.billing_zip",
                          "$this->table.paypal",
                          "$this->table.btc_id",
                          "$this->table.card_number",
                          "$this->table.card_cvc",
                          "$this->table.card_exp1",
                          "$this->table.card_exp2",
                          "$this->table.card_type",
                      ))
                      ->where(array('user_id'=>$id));


          if($is_array)
              return $this->db->get($this->table)->row_array();
          else
              return $this->db->get($this->table)->row();
    }

    public function get_user_billing_id($id)
    {
      $this->db->select('user_id')->where(array('user_id'=>$id));

      return $this->db->get($this->table)->row()->user_id;
    }

    public function save_user_billing($data = array(), $id = FALSE)
    {
      $bid = $this->get_user_billing_id($id);

      if($bid) // update
      {
          $this->db->where(array('user_id'=>$id))
                   ->update($this->table, $data);
          return $bid;
      }
      else // insert
      {
        $data['user_id'] = $id;
        $this->db->insert($this->table, $data);
        return $this->get_user_billing_id($id);
      }
    }

  }
