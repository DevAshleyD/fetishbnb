<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Users Controller
 *
 * This class handles users module functionality
 *
 * @package     classiebit
 * @author      prodpk
*/

class Users extends Admin_Controller {

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();

        $this->load->model('users_model');
        $this->lang->load('auth');

         // Page Title
        $this->set_title( lang('menu_users') );
    }

    /**
     * index
     */
    function index()
    {
        if(!$this->acl->get_method_permission($_SESSION['groups_id'], 'users', 'p_view'))
        {
            $this->session->set_flashdata('error', sprintf(lang('manage_acl_permission_no'), lang('menu_user').' '.lang('manage_acl_view')));
            redirect($this->uri->segment(1).'/dashboard');
        }

        /* Initialize assets */
        $this->include_index_plugins();
        $data = $this->includes;

        // Table Header
        $data['t_headers']  = array(
                                '#',
                                lang('users_first_name'),
                                lang('users_last_name'),
                                lang('users_username'),
                                lang('users_email'),
                                lang('menu_group'),
                                lang('common_updated'),
                                lang('common_status'),
                                lang('action_action'),
                            );
        // load views
        $content['content'] = $this->load->view('admin/index', $data, TRUE);
        $this->load->view($this->template, $content);
    }

    /**
     * ajax_list
    */
    public function ajax_list()
    {
        $this->load->library('datatables');

        $table              = 'users';        
        $columns            = array(
                                "$table.id",
                                "$table.first_name",
                                "$table.last_name",
                                "$table.username",
                                "$table.email",
                                "(SELECT gr.name FROM groups gr WHERE gr.id = (SELECT ug.group_id FROM users_groups ug WHERE ug.user_id = $table.id)) group_name",
                                "$table.active",
                                "$table.date_updated",
                            );
        $columns_order      = array(
                                "#",
                                "$table.first_name",
                                "$table.last_name",
                                "$table.username",
                                "$table.email",
                                "$table.group_name",
                                "$table.date_updated",
                                "$table.active",
                            );
        $columns_search     = array(
                                'first_name',
                                'last_name',
                                'username',
                                'email',
                            );
        $order              = array('date_updated' => 'DESC');

        // multi table sorting
        $sort_table2        = '';
        if($this->input->post('order')[0]['column'] == 4)
        {
            $sort_table2    = 'group_id';            
        }
        
        $result             = $this->datatables->get_datatables($table, $columns, $columns_order, $columns_search, $order, array(), $sort_table2);
        $data               = array();
        $no                 = $this->input->post('start');
        
        foreach ($result as $val) 
        {
            $no++;
            $row            = array();
            $row[]          = $no;
            $row[]          = $val->first_name;
            $row[]          = $val->last_name;
            $row[]          = $val->username;
            $row[]          = $val->email;
            $row[]          = $val->group_name;
            $row[]          = date('g:iA d/m/y ', strtotime($val->date_updated));
            $row[]          = status_switch($val->active, $val->id);
            $row[]          = action_buttons('users', $val->id, mb_substr($val->first_name.' '.$val->last_name, 0, 30, 'utf-8'), lang('menu_user'));
            $data[]         = $row;
        }
 
        $output             = array(
                                "draw"              => $this->input->post('draw'),
                                "recordsTotal"      => $this->datatables->count_all(),
                                "recordsFiltered"   => $this->datatables->count_filtered(),
                                "data"              => $data,
                            );
        
        //output to json format
        echo json_encode($output);exit;
    }

    /**
     * form
    */
    public function form($id = NULL)
    {
        /* Initialize assets */
        $this
        ->add_plugin_theme(array(
            'datepicker/datepicker3.css',
            'datepicker/bootstrap-datepicker.js',
        ), 'admin')
        ->add_js_theme( "pages/users/form_i18n.js", TRUE );
        $data                       = $this->includes;
        
        $id                         = (int) $id;

        // in case of edit
        if($id)
        {
            $result                 = $this->users_model->get_users_by_id($id);

            if(empty($result))
            {
                $this->session->set_flashdata('error', sprintf(lang('alert_not_found') ,lang('menu_user')));
                redirect($this->uri->segment(1).'/'.$this->uri->segment(2));            
            }

            // hidden field in case of update
            $data['id']             = $result->id; 
            
            // current image & icon
            $data['c_image']        = $result->image;
        }
        
        $data['first_name'] = array(
            'name'      => 'first_name',
            'id'        => 'first_name',
            'type'      => 'text',
            'class'     => 'form-control',
            'value'     => $this->form_validation->set_value('first_name', !empty($result->first_name) ? $result->first_name : ''),
        );
        $data['last_name'] = array(
            'name'      => 'last_name',
            'id'        => 'last_name',
            'type'      => 'text',
            'class'     => 'form-control',
            'value'     => $this->form_validation->set_value('last_name', !empty($result->last_name) ? $result->last_name : ''),
        );
        $data['username'] = array(
            'name'      => 'username',
            'id'        => 'username',
            'type'      => 'text',
            'class'     => 'form-control',
            'value'     => $this->form_validation->set_value('username', !empty($result->username) ? $result->username : ''),
        );
        $data['email'] = array(
            'name'      => 'email',
            'id'        => 'email',
            'type'      => 'email',
            'class'     => 'form-control',
            'value'     => $this->form_validation->set_value('email', !empty($result->email) ? $result->email : ''),
        );
        $data['profession'] = array(
            'name'      => 'profession',
            'id'        => 'profession',
            'type'      => 'text',
            'class'     => 'form-control',
            'value'     => $this->form_validation->set_value('profession', !empty($result->profession) ? $result->profession : ''),
        );
        $data['experience'] = array(
            'name'      => 'experience',
            'id'        => 'experience',
            'type'      => 'number',
            'class'     => 'form-control',
            'value'     => $this->form_validation->set_value('experience', !empty($result->experience) ? $result->experience : ''),
        );
        $data['about'] = array(
            'name'      => 'about',
            'id'        => 'about',
            'type'      => 'textarea',
            'class'     => 'form-control',
            'value'     => $this->form_validation->set_value('about', !empty($result->about) ? $result->about : ''),
        );
        $data['mobile'] = array(
            'name'      => 'mobile',
            'id'        => 'mobile',
            'type'      => 'text',
            'class'     => 'form-control',
            'value'     => $this->form_validation->set_value('mobile', !empty($result->mobile) ? $result->mobile : ''),
        );
        $data['address'] = array(
            'name'      => 'address',
            'id'        => 'address',
            'type'      => 'textarea',
            'class'     => 'form-control',
            'value'     => $this->form_validation->set_value('address', !empty($result->address) ? $result->address : ''),
        );
        $data['gender'] = array(
            'name'      => 'gender',
            'id'        => 'gender',
            'class'     => 'form-control show-tick',
            'options'   => array(
                                'male'      => lang('users_gender_male'), 
                                'female'    => lang('users_gender_female'), 
                                'other'     => lang('users_gender_other')),
            'selected'  => $this->form_validation->set_value('gender', !empty($result->gender) ? $result->gender : ''),
        );
        $data['dob']= array(
            'name'          => 'dob',
            'id'            => 'dob',
            'type'          => 'text',
            'class'         => 'form-control',
            'value'         => $this->form_validation->set_value('dob', !empty($result->dob) ? $result->dob : ''),
        );
        $data['password'] = array(
            'name'      => 'password',
            'id'        => 'password',
            'type'      => 'password',
            'class'     => 'form-control',
        );
        $data['password_confirm'] = array(
            'name'      => 'password_confirm',
            'id'        => 'password',
            'type'      => 'password',
            'class'     => 'form-control',
        );
        $data['image'] = array(
            'name'      => 'image',
            'id'        => 'image',
            'type'      => 'file',
            'accept'    => 'image/*',
            'class'     => 'form-control',
        );
        $data['language'] = array(
            'name'      => 'language',
            'id'        => 'language',
            'class'     => 'form-control show-tick',
            'data-live-search'=>"true",
            'options'   => $this->languages,
            'selected'  => $this->form_validation->set_value('language', !empty($result->language) ? $result->language : $this->config->item('language')),
        );
        
        if($id) // only in case of editing
        {
            /*Get groups*/
            $result_group       = $this->ion_auth->get_users_groups($result->id)->result_array();
            
            $groups             = $this->ion_auth->groups()->result_array();
            foreach($groups as $val)
                $data['group'][$val['id']] = $val['name'];
            
            $data['groups']     = array(
                'name'          => 'groups',
                'id'            => 'groups',
                'class'         => 'form-control show-tick text-capitalize',
                'options'       => $data['group'],
                'selected'      => $this->form_validation->set_value('groups', !empty($result_group) ? $result_group[0]['id'] : 3),
            );    
        }
        
        $data['status']     = array(
            'name'          => 'active',
            'id'            => 'active',
            'class'         => 'form-control show-tick',
            'options'       => array(
                                    '0' => lang('common_status_inactive'),
                                    '1' => lang('common_status_active'),
                                ),
            'selected'      => $this->form_validation->set_value('active', !empty($result->active) ? $result->active : 0),
        );
        
        /* Load Template */
        $content['content']    = $this->load->view('admin/users/form', $data, TRUE);
        $this->load->view($this->template, $content);
    }

    /**
     * save
    */
    public function save()
    {
        $id = NULL;

        // Unique columns
        $result             = (object) array();
        $result->username   = '';
        $result->email      = '';

        if(! empty($_POST['id']))
        {
            if(!$this->acl->get_method_permission($_SESSION['groups_id'], 'users', 'p_edit'))
            {
                echo '<p>'.sprintf(lang('manage_acl_permission_no'), lang('manage_acl_edit')).'</p>';exit;
            }

            $id                     = (int) $this->input->post('id');
            
            // users can only edit non-admins
            if(!$this->ion_auth->is_admin() && !$this->ion_auth->is_admin($id))
            {
                echo '<p>'.lang('users_only_admin_can').'</p>';exit;
            }   

            $result                 = $this->users_model->get_users_by_id($id);

            if(empty($result))
            {
                $this->session->set_flashdata('message', sprintf(lang('alert_not_found'), lang('menu_user')));
                echo json_encode(array(
                                        'flag'  => 0, 
                                        'msg'   => $this->session->flashdata('message'),
                                        'type'  => 'fail',
                                    ));
                exit;
            }
        }
        else
        {
            if(!$this->acl->get_method_permission($_SESSION['groups_id'], 'users', 'p_add'))
            {
                echo '<p>'.sprintf(lang('manage_acl_permission_no'), lang('manage_acl_add')).'</p>';exit;
            }
        }

        // validators
        $this->form_validation
        ->set_rules('username', lang('users_username'), 'trim|required|min_length[5]|max_length[30]|alpha_dot')
        ->set_rules('email', lang('users_email'), 'trim|required|max_length[128]|valid_email')
        ->set_rules('first_name', lang('users_first_name'), 'required|trim|min_length[2]|max_length[32]')
        ->set_rules('last_name', lang('users_last_name'), 'required|trim|min_length[2]|max_length[32]')
        ->set_rules('language', lang('users_language'), 'required|trim')
        ->set_rules('gender', lang('users_gender'), 'trim|required|in_list[male,female,other]')
        ->set_rules('dob', lang('users_dob'), 'required|trim')
        ->set_rules('profession', lang('users_profession'), 'required|trim|min_length[3]|max_length[256]')
        ->set_rules('experience', lang('users_experience'), 'required|trim|is_natural_no_zero')
        ->set_rules('about', lang('users_about'), 'required|trim|min_length[10]|max_length[256]')
        ->set_rules('mobile', lang('users_mobile'), 'required|trim|min_length[5]|max_length[20]')
        ->set_rules('address', lang('users_address'), 'required|trim|min_length[8]|max_length[256]');
        
        /*Validate password*/
        if($id)
        {
            $this->form_validation
            ->set_rules('password', lang('users_password'), 'trim|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]')
            ->set_rules('password_confirm', lang('users_password_confirm'), 'trim|matches[password]');
        }
        else
        {
            $this->form_validation
            ->set_rules('password', lang('users_password'), 'trim|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]|required')
            ->set_rules('password_confirm', lang('users_password_confirm'), 'trim|matches[password]|required');
        }
            
        /*Validate email & username for duplicacy*/
        if($this->input->post('username') !== $result->username)
            $this->form_validation->set_rules('username', lang('users_username'), 'trim|required|min_length[5]|max_length[30]|is_unique[users.username]|alpha_dot');

        if($this->input->post('email') != $result->email)
            $this->form_validation->set_rules('email', lang('users_email'), 'trim|required|max_length[128]|valid_email|is_unique[users.email]');

        if(! empty($_FILES['image']['name'])) // if image 
        {
            $file_image         = array('folder'=>'users/images', 'input_file'=>'image');
            // Remove old image
            if($id)
                $this->file_uploads->remove_file('./upload/'.$file_image['folder'].'/', $result->image);
            // update users image      
                  
            $filename_image     = $this->file_uploads->upload_file($file_image);
            // through image upload error
            if(!empty($filename_image['error']))
                $this->form_validation->set_rules('image_error', lang('common_image'), 'required', array('required'=>$filename_image['error']));
        }
        
        if($this->form_validation->run() === FALSE)
        {
            // for fetching specific fields errors in order to view errors on each field seperately
            $error_fields = array();
            foreach($_POST as $key => $val)
                if(form_error($key))
                    $error_fields[] = $key;
            
            echo json_encode(array('flag' => 0, 'msg' => validation_errors(), 'error_fields' => json_encode($error_fields)));
            exit;
        }

        // insert data
        $data                   = array();
        $data['first_name']     = strtolower($this->input->post('first_name'));
        $data['last_name']      = strtolower($this->input->post('last_name'));
        $data['gender']         = strtolower($this->input->post('gender'));
        $data['dob']            = date("Y-m-d h:i:s",strtotime($this->input->post('dob')));
        $data['profession']     = strtolower($this->input->post('profession'));
        $data['experience']     = $this->input->post('experience');
        $data['about']          = $this->input->post('about');
        $data['mobile']         = $this->input->post('mobile');
        $data['address']        = strtolower($this->input->post('address'));
        $data['language']       = strtolower($this->input->post('language'));
        $username               = strtolower($this->input->post('username'));
        $email                  = strtolower($this->input->post('email'));
        
        if(! empty($filename_image) && ! isset($filename_image['error']))
            $data['image']      = $filename_image;
        
        if(!$id) // register only in case of creating user
        {
            $password           = $this->input->post('password');
            $flag               = $this->ion_auth->register($username, $password, $email, $data);
        }
        else // follow the user update process of ion auth
        {
            // only admin and owner of account can edit account
            if ( (!$this->ion_auth->is_admin() && !($this->ion_auth->user()->row()->id == $id) ) ) 
            {
                $this->session->set_flashdata('error', lang('users_update_only_admin_owner'));
                echo json_encode(array(
                                        'flag'  => 0, 
                                        'msg'   => $this->session->flashdata('error'),
                                        'type'  => 'fail',
                                    ));
                exit;
            }

            $currentGroups      = $this->ion_auth->get_users_groups($id)->result();

            // Only allow updating groups if user is admin
            if ($this->ion_auth->is_admin())
            {
                //Update the groups user belongs to
                $groupData      = $this->input->post('groups');

                if (isset($groupData) && !empty($groupData)) 
                {
                    $this->ion_auth->remove_from_group('', $id);

                    $data['active']         = $this->input->post('active');

                    $this->ion_auth->add_to_group($groupData, $id);
                }

                if($this->input->post('password') && get_domain() !== 'classiebit.com')
                {
                    $data['password']       = $this->input->post('password');
                }
            }

            $data['email']      = $email;

            if(get_domain() !== 'classiebit.com')
                $data['username']   = $username;

            $flag       = $this->ion_auth->update($id, $data);
        }

        if($flag)
        {
            // add batch notification when new batch inserted
            if(!$id)
            {
                $notification   = array(
                    'users_id'  => $this->user['id'],
                    'n_type'    => 'users',
                    'n_content' => 'noti_new_users',
                    'n_url'     => site_url('admin/users'), 
                );
                $this->notifications_model->save_notifications($notification);    
            }

            if($id)
            {
                if(get_domain() !== 'classiebit.com')
                    $this->session->set_flashdata('message', sprintf(lang('alert_update_success'), lang('menu_user')));
                else
                    $this->session->set_flashdata('message', lang('demo_mode'));
            }
            else
            {
                $this->session->set_flashdata('message', sprintf(lang('alert_insert_success'), lang('menu_user')));
            }

            echo json_encode(array(
                                    'flag'  => 1, 
                                    'msg'   => $this->session->flashdata('message'),
                                    'type'  => 'success',
                                ));
            exit;
        }
        
        if($id)
            $this->session->set_flashdata('error', sprintf(lang('alert_update_fail'), lang('menu_user')));
        else
            $this->session->set_flashdata('error', sprintf(lang('alert_insert_fail'), lang('menu_user')));

        echo json_encode(array(
                                'flag'  => 0, 
                                'msg'   => $this->session->flashdata('error'),
                                'type'  => 'fail',
                            ));
        exit;
    }

     /**
     * view
     */
    public function view($id = NULL)
    {
        if(!$this->acl->get_method_permission($_SESSION['groups_id'], 'users', 'p_view'))
        {
            $this->session->set_flashdata('error', sprintf(lang('manage_acl_permission_no'), lang('menu_user').' '.lang('manage_acl_view')));
            redirect($this->uri->segment(1).'/dashboard');
        }
        
        /* Initialize assets */
        $data                   = $this->includes;
        
        /* Get Data */
        $id = (int) $id;
        $result = $this->users_model->get_users_by_id($id);

        if(empty($result))
        {
            $this->session->set_flashdata('error', sprintf(lang('alert_not_found') ,lang('menu_user')));
            redirect('admin/users');            
        }

        $data['users']         = $result;
        
        /* Load Template */
        $content['content']    = $this->load->view('admin/users/view', $data, TRUE);
        $this->load->view($this->template, $content);
    }

    /**
     * status_update
     */
    public function status_update()
    {
        if(!$this->acl->get_method_permission($_SESSION['groups_id'], 'users', 'p_edit'))
        {
            echo '<p>'.sprintf(lang('manage_acl_permission_no'), lang('manage_acl_edit')).'</p>';exit;
        }

        /* Validate form input */
        $this->form_validation
        ->set_rules('id', sprintf(lang('alert_id'), lang('menu_user')), 'required|numeric')
        ->set_rules('status', lang('common_status'), 'required|in_list[0,1]');

        if($this->form_validation->run() === FALSE)
        {
            echo json_encode(array(
                                'flag'  => 0, 
                                'msg'   => validation_errors(), 
                                'type'  => 'fail',
                            ));exit;
        }

        // data to insert in db table
        $data                       = array();
        $id                         = (int) $this->input->post('id');
        $data['active']             = $this->input->post('status');

        // users can only edit non-admins
        if(!$this->ion_auth->is_admin() && !$this->ion_auth->is_admin($id))
        {
            echo '<p>'.lang('users_only_admin_can').'</p>';exit;
        }   

        if($id === 1)
        {
            echo '<p>'.lang('users_super_admin_no_status').'</p>';exit;
        }

        if(empty($id))
        {
            $this->session->set_flashdata('message', sprintf(lang('alert_not_found'), lang('menu_user')));
            echo json_encode(array(
                                    'flag'  => 0, 
                                    'msg'   => $this->session->flashdata('message'),
                                    'type'  => 'fail',
                                ));
            exit;
        }
        
        $flag                       = $this->users_model->save_users($data, $id);

        if($flag)
        {
            echo json_encode(array(
                                    'flag'  => 1, 
                                    'msg'   => sprintf(lang('alert_status_success'), lang('menu_user')),
                                    'type'  => 'success',
                                ));
            exit;
        }

        echo json_encode(array(
                            'flag'  => 0, 
                            'msg'   => sprintf(lang('alert_status_fail'), lang('menu_user')),
                            'type'  => 'fail',
                        ));
        exit;
        
    }

    /**
     * delete
     */
    public function delete($id = NULL)
    {
        if(!$this->ion_auth->is_admin())
        {
            echo '<p>'.lang('users_only_admin_can').'</p>';exit;
        }

        /* Validate form input */
        $this->form_validation->set_rules('id', sprintf(lang('alert_id'), lang('menu_user')), 'required|numeric');

        /* Get Data */
        $id                 = (int) $this->input->post('id');
        $result             = $this->users_model->get_users_by_id($id);

        if($id === 1)
        {
            echo '<p>'.lang('users_super_admin_no_delete').'</p>';exit;
        }

        if(empty($result))
        {
            echo json_encode(array(
                                    'flag'  => 0, 
                                    'msg'   => sprintf(lang('alert_not_found') ,lang('menu_user')),
                                    'type'  => 'fail',
                                ));exit;
        }

        if(get_domain() !== 'classiebit.com')
            $flag                   = $this->ion_auth->delete_user($id);
        else
            $flag                   = 0;

        if($flag)
        {
            
            // Remove image
            if(!empty($result->image))
                $this->file_uploads->remove_file('./upload/users/images/', $result->image);
            
            echo json_encode(array(
                                    'flag'  => 1, 
                                    'msg'   => sprintf(lang('alert_delete_success'), lang('menu_user')),
                                    'type'  => 'success',
                                ));exit;
        }
        
        echo json_encode(array(
                            'flag'  => 0, 
                            'msg'   => get_domain() !== 'classiebit.com' ? sprintf(lang('alert_delete_fail'), lang('menu_user')) : lang('demo_mode_user'),
                            'type'  => 'fail',
                        ));
        exit;   
    }


}

/* Users controller ends */