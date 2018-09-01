<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Profile Controller
 *
 * This class handles profile module functionality
 *
 * @package     classiebit
 * @author      prodpk
*/


class Profile extends Private_Controller {

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();

        // load the users model
        $this->load->model('users_model');
        $this->load->library('file_uploads');
    }


    /**
	 * Profile Editor
     */
	function index()
	{
        // validators
        $this->form_validation
        ->set_error_delimiters($this->config->item('error_delimeter_left'), $this->config->item('error_delimeter_right'))
        ->set_rules('username', lang('users_username'), 'required|trim|min_length[5]|max_length[30]|callback__check_username')
        ->set_rules('first_name', lang('users_first_name'), 'trim|min_length[2]|max_length[32]')
        ->set_rules('last_name', lang('users_last_name'), 'trim|min_length[2]|max_length[32]')
        ->set_rules('email', lang('users_email'), 'required|trim|max_length[128]|valid_email|callback__check_email')
        ->set_rules('profession', lang('users_profession'), 'trim|min_length[3]|max_length[256]')
        ->set_rules('experience', lang('users_experience'), 'trim|is_natural_no_zero')
        ->set_rules('gender', lang('users_gender'), 'trim|in_list[male,female,other]')
        ->set_rules('dob', lang('users_dob'), 'trim')
        ->set_rules('mobile', lang('users_mobile'), 'trim|min_length[5]|max_length[20]')
        ->set_rules('address', lang('users_address'), 'trim|min_length[8]|max_length[256]')
        ->set_rules('language', lang('users_language'), 'trim')
        ->set_rules('password', lang('users_password'), 'required|trim|min_length['.$this->settings->i_min_password.']|max_length['.$this->settings->i_max_password.']')
        ->set_rules('password_confirm', lang('users_password_confirm'), 'matches[password]');

        // upload users image
        if(! empty($_FILES['image']['name'])) // if image
        {
            $file_image         = array('folder'=>'users/images', 'input_file'=>'image');
            // update users image
            $filename_image     = $this->file_uploads->upload_file($file_image);
            // through image upload error
            if(!empty($filename_image['error']))
                $this->form_validation->set_rules('image_error', lang('common_image'), 'required', array('required'=>$filename_image['error']));
        }

        if ($this->form_validation->run() == TRUE)
        {
            // save the changes
            $data                  = array();

            if(!empty($filename_image) && !isset($filename_image['error']))
                $data['image']     = $filename_image;

            if($this->input->post('password') && get_domain() !== 'classiebit.com')
                $data['password']  = $this->input->post('password');

            $data['first_name']    = $this->input->post('first_name');
            $data['last_name']     = $this->input->post('last_name');
            $data['email']         = $this->input->post('email');
            $data['gender']        = $this->input->post('gender');
            $data['dob']           = date("Y-m-d",strtotime($this->input->post('dob')));
            $data['mobile']        = $this->input->post('mobile');
            $data['profession']    = $this->input->post('profession');
            $data['experience']    = $this->input->post('experience');
            $data['address']       = $this->input->post('address');
            $data['language']      = $this->input->post('language');

            $saved                 = $this->ion_auth->update($this->user['id'], $data);

            if ($saved)
            {
                // reload the new user data and store in session
                $this->user = (array) $this->users_model->get_users_by_id($this->user['id']);
                // unset($this->user['password']);
                // unset($this->user['salt']);

                $_SESSION['groups_id']      = $this->ion_auth->get_users_groups($this->user['id'])->row()->id;

                $this->session->set_userdata('logged_in', $this->user);
                $this->session->language = $this->user['language'];
                $this->session->set_flashdata('message', sprintf(lang('alert_update_success'), lang('action_profile')));
            }
            else
            {
                $this->session->set_flashdata('error', sprintf(lang('alert_update_fail'), lang('action_profile')));
            }

            // reload page and display message
            redirect('profile');
        }

        // setup page header data
        $this
        ->add_plugin_theme(array(
            "datepicker/datepicker3.css",
            "datepicker/bootstrap-datepicker.js",
        ), 'default')
        ->add_js_theme( "pages/user/index_i18n.js", TRUE );

        $this->set_title( lang('menu_user').' '.lang('action_profile'));
        $data = $this->includes;

        // set content data
        $content_data = array(
            'cancel_url'        => base_url(),
            'user'              => $this->user,
            'password_required' => FALSE
        );

        // load views
        $data['content'] = $this->load->view('auth/profile_form', $content_data, TRUE);
        $this->load->view($this->template, $data);
	}


    /**************************************************************************************
     * PRIVATE VALIDATION CALLBACK FUNCTIONS
     **************************************************************************************/


    /**
     * Make sure username is available
     *
     * @param  string $username
     * @return int|boolean
     */
    function _check_username($username)
    {
        if (trim($username) != $this->user['username'] && $this->users_model->username_exists($username))
        {
            $this->form_validation->set_message('_check_username', sprintf(lang('users_username_error'), $username));
            return FALSE;
        }
        else
        {
            return $username;
        }
    }


    /**
     * Make sure email is available
     *
     * @param  string $email
     * @return int|boolean
     */
    function _check_email($email)
    {
        if (trim($email) != $this->user['email'] && $this->users_model->email_exists($email))
        {
            $this->form_validation->set_message('_check_email', sprintf(lang('users_email_error'), $email));
            return FALSE;
        }
        else
        {
            return $email;
        }
    }

}

/*End User Profile*/
