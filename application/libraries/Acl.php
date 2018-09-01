<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Library Acl
 *
 * This class handles uploading files related functionality
 *
 * @package     DA
 * @author      DK
**/


class Acl
{
    
    var $CI_LIB;

    function __construct()
    {
        $this->CI_LIB =& get_instance();
    }

    /**
    * get_method_permission
    *
    * @param    integer  $groups_id  user group id
    * @param    string   $c_name     controller name
    * @param    string   $column     column name
    * @return   boolean  permission
    */
    public function get_method_permission($groups_id = 0, $c_name = '', $column = '')
    {
        if($column != 'p_view' && $column != 'p_add' && $column != 'p_edit' && $column != 'p_delete')
            return FALSE;

        if($groups_id == 1)
            return TRUE;

        if($groups_id == 3)
            return FALSE;

        $row = $this->CI_LIB->db->select("p.$column")
                                ->join('controllers c', 'c.id = p.controllers_id', 'left')
                                ->where(array('p.groups_id'=>$groups_id, 'c.name'=>$c_name, "p.$column"=>1))
                                ->get('permissions p')
                                ->row();

        if(!empty($row))
            return $row->{$column};
        else
            return FALSE;
    }

}

/*Ends Acl Library*/