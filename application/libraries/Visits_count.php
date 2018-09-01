<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Library Visits Count
 *
 * This class handles visitors count related functionality
 *
 * @package     DA
 * @author      DK
**/

class Visits_count {

	var $CI_LIB;

    function __construct()
    {
        $this->CI_LIB =& get_instance();
    }

    function hit_visit()
    {
        $user_ip = trim($this->get_real_ip_addr());

        // check if ip already exist and update existing row
        $this->CI_LIB->db->where(array('user_ip'=>$user_ip))
                         ->set('total_visits', 'total_visits+1', FALSE)
                         ->set('user_ip', $user_ip)
                         ->update('visitors');

        if($this->CI_LIB->db->affected_rows())
        {
            return TRUE;
        }
        else
        {
            $this->CI_LIB->db->insert('visitors', array('user_ip'=>$user_ip, 'total_visits'=>1));
            return TRUE;
        }
    }

    function get_real_ip_addr()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
          $ip=$_SERVER['HTTP_CLIENT_IP'];

        elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
          $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];

        else
          $ip=$_SERVER['REMOTE_ADDR'];
        
        return $ip;
    }

    function get_visitors()
    {
        $query = "SELECT `visitors`.`id` FROM `visitors` GROUP BY `visitors`.`date_updated`";

        if( $this->CI_LIB->db->simple_query($query) )
        {
            // safe mode is off
            $select = array(
                            'visitors.id',
                            'visitors.user_ip',
                            'SUM(visitors.total_visits) total_visits',
                            'visitors.date_added',
                            'visitors.date_updated',
                        );
        }
        else
        {
            // safe mode is on
            $select = array(
                            'ANY_VALUE(visitors.id) as id',
                            'ANY_VALUE(visitors.user_ip) as user_ip',
                            'SUM(visitors.total_visits) total_visits',
                            'ANY_VALUE(visitors.date_added) as date_added',
                            'ANY_VALUE(visitors.date_updated) as date_updated',
                        );
        }

        return $this->CI_LIB->db->select($select)
                                ->order_by('visitors.date_updated', 'ASC')
                                ->group_by('visitors.date_updated')
                                ->get('visitors')->result();
    }

}

/*End Visits Count Lib*/