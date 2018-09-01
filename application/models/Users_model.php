<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Users Model
 *
 * This model handles users module data
 *
 * @package     classiebit
 * @author      prodpk
*/

class Users_model extends CI_Model {

    /**
     * @vars
     */
    private $table          = 'users';
    
    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();
    }

    /**
     * count_users
     */
    public function count_users()
    {
        return $this->db->count_all_results($this->table);
    }

    /**
     * get_users_by_id
     *
     * @return array
     * 
     **/
    public function get_users_by_id($id = FALSE, $is_array = FALSE)
    {
        $this->db->select(array(
                        "$this->table.id",
                        "$this->table.first_name",
                        "$this->table.last_name",
                        "$this->table.username",
                        "$this->table.email",
                        "$this->table.mobile",
                        "$this->table.address",
                        "$this->table.gender",
                        "$this->table.dob",
                        "$this->table.profession",
                        "$this->table.experience",
                        "$this->table.about",
                        "$this->table.image",
                        "$this->table.language",
                        "$this->table.active",
                        "$this->table.date_added",
                        "$this->table.date_updated",
                        "(SELECT gr.name FROM groups gr WHERE gr.id = (SELECT ug.group_id FROM users_groups ug WHERE ug.user_id = $this->table.id)) group_name",
                    ))
                    ->where(array('id'=>$id));
                        

        if($is_array)
            return $this->db->get($this->table)->row_array();
        else
            return $this->db->get($this->table)->row();
    }

    public function get_group_name($g_id = NULL)
    {
        if($g_id)
            return $this->db->where('id', $g_id)
                            ->get('groups')
                            ->row();

        return FALSE;
    }

    /**
     * save_users
     *
     * @return array
     * 
     **/
    public function save_users($data = array(), $id = FALSE, $email = FALSE)
    {
        if($id) // update
        {
            $this->db->where(array('id'=>$id))
                     ->update($this->table, $data);
            return $id;
        }
        else if($email)
        {
            $this->db->where(array('email'=>$email))
                     ->update($this->table, $data);
            return $email;   
        }
        else // insert
        {
            $this->db->insert($this->table, $data);
            return $this->db->insert_id();
        }
        
    }

    
    /**
     * Check to see if a username already exists
     *
     * @param  string $username
     * @return boolean
     */
    function username_exists($username)
    {
        $sql = "
            SELECT id
            FROM {$this->table}
            WHERE username = " . $this->db->escape($username) . "
            LIMIT 1
        ";

        $query = $this->db->query($sql);

        if ($query->num_rows())
        {
            return TRUE;
        }

        return FALSE;
    }

   /**
     * Check to see if an email already exists
     *
     * @param  string $email
     * @return boolean
     */
    function email_exists($email)
    {
        $sql = "
            SELECT id
            FROM {$this->table}
            WHERE email = " . $this->db->escape($email) . "
            LIMIT 1
        ";

        $query = $this->db->query($sql);

        if ($query->num_rows())
        {
            return TRUE;
        }

        return FALSE;
    }


    /**
     * Check for valid login oauth
     *
     * @param  string $username
     * @param  string $password
     * @return array|boolean
     */
    function login_oauth($email = NULL)
    {
        return  $this->db
                    ->select(array(
                        'id',
                        'username',
                        'first_name',
                        'last_name',
                        'email',
                        'gender',
                        'experience',
                        'dob',
                        'mobile',
                        'address',
                        'image',
                        'language',
                        'active',
                        'date_added',
                        'date_updated',
                        "(SELECT gr.name FROM groups gr WHERE gr.id = (SELECT ug.group_id FROM users_groups ug WHERE ug.user_id = $this->table.id)) group_name",
                    ))
                    ->where(array('email'=>$email))
                    ->get($this->table)
                    ->row_array();
    }

    /**
     * Check to see if an oauth_uid already exists
     *
     * @param  string $oauth_uid
     * @return boolean
     */
    function oauth_uid_exists($fb_uid = NULL, $g_uid = NULL)
    {
        if($fb_uid)
        {
            $row =  $this->db->select(array('id'))
                            ->where(array('fb_uid'=>$fb_uid))
                            ->limit(1)
                            ->get($this->table)
                            ->row();

            if(!empty($row))
                return TRUE;

        }

        if($g_uid)
        {
            $row =  $this->db->select(array('id'))
                            ->where(array('g_uid'=>$g_uid))
                            ->limit(1)
                            ->get($this->table)
                            ->row();

            if(!empty($row))
                return TRUE;
        }

        return FALSE;
    }


    /**
    * count_users_batch
    *
    * @return array
    * 
    **/
    public function count_users_batch($id = NULL)
    {
        return $this->db->where(array('users_id'=>$id))
                        ->count_all_results('batches_tutors');
    }

}

/*Users model ends*/