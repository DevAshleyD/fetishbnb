<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Auth Controller
 *
 * This class handles login functionality
 *
 * @package     ibs
 * @author      prodpk
*/

class Auth extends Public_Controller {

	var $facebook;

	public function __construct()
	{
		parent::__construct();

		// load the users model
        $this->load->model('users_model');
        $this->load->model('notifications_model');

        // facebook login
        if($this->settings->fb_app_id && $this->settings->fb_app_secret)
        {
            $this->facebook         = new Facebook\Facebook(array(
                                    'app_id'                => $this->settings->fb_app_id,
                                    'app_secret'            => $this->settings->fb_app_secret,
                                    'default_graph_version' => 'v2.8'
                                ));
        }

		$this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));

		$this->load->library('file_uploads');
	}

	// redirect if needed, otherwise display the user list
	public function index()
	{
		if ($this->ion_auth->logged_in())
		{
			// if user is logged in the redirect tot home page
			redirect('', 'refresh');
		}
		else
		{
			redirect('auth/login');
		}
	}

	// log the user in
	public function login()
	{
		if ($this->ion_auth->logged_in())
		{
			// if user is logged in the redirect tot home page
			redirect('', 'refresh');
		}

		// if facebook login
        if(!empty($_POST['fb_access_token']))
        {
            $this->oauth_login();
        }

		//validate form input
		$this->form_validation->set_rules('identity', str_replace(':', '', $this->lang->line('login_identity_label')), 'trim|required');
		$this->form_validation->set_rules('password', str_replace(':', '', $this->lang->line('login_password_label')), 'trim|required');

		if ($this->form_validation->run() == true)
		{
			// check to see if the user is logging in
			// check for "remember me"
			$remember = (bool) $this->input->post('remember');

			if ($this->ion_auth->login($this->input->post('identity'), $this->input->post('password'), $remember))
			{
				//if the login is successful
				//redirect them back to the home page
				$this->session->set_flashdata('message', $this->ion_auth->messages());

                $result = $this->users_model->get_users_by_id($_SESSION['user_id'], TRUE);

                $_SESSION['groups_id']      = $this->ion_auth->get_users_groups($result['id'])->row()->id;

            	$this->session->set_userdata('logged_in', $result);

				redirect('/', 'refresh');
			}
			else
			{
				// if the login was un-successful
				// redirect them back to the login page
				$this->session->set_flashdata('error', $this->ion_auth->errors());
				redirect('auth/login', 'refresh'); // use redirects instead of loading views for compatibility with MY_Controller libraries
			}
		}
		else
		{
			// the user is not logging in so display the login page
			// setup page header data
        	$this->set_title(lang('users_login'));
        	$data = $this->includes;

        	// load views
        	$data['content'] = $this->load->view('auth/login', NULL, TRUE);
        	$this->load->view($this->template, $data);
		}
	}

	/**
     * Registration Google
     */
    public function g_register()
    {
    	if ($this->ion_auth->logged_in())
		{
			// if user is logged in the redirect tot home page
			redirect('', 'refresh');
		}

        // google login
        if(!$this->settings->g_client_id && !$this->settings->g_client_secret)
        {
            redirect(site_url('auth/login'));
        }

        // Google Project API Credentials
        $clientId       = $this->settings->g_client_id;
        $clientSecret   = $this->settings->g_client_secret;
        $redirectUrl    = site_url().'auth/g_r_callback';

        // Google Client Configuration
        $gClient        = new Google_Client();
        $gClient->setApplicationName($this->settings->site_name);
        $gClient->setClientId($clientId);
        $gClient->setClientSecret($clientSecret);
        $gClient->setRedirectUri($redirectUrl);
        $gClient->addScope("email");
        $gClient->addScope("profile");
        $google_oauthV2 = new Google_Service_Oauth2($gClient);

        $authUrl        = $gClient->createAuthUrl();
        header('Location: '.$authUrl);
    }

    public function g_r_callback()
    {
    	if ($this->ion_auth->logged_in())
		{
			// if user is logged in the redirect tot home page
			redirect('', 'refresh');
		}

        // Google Project API Credentials
        $clientId       = $this->settings->g_client_id;
        $clientSecret   = $this->settings->g_client_secret;
        $redirectUrl    = site_url().'auth/g_r_callback';

        // Google Client Configuration
        $gClient        = new Google_Client();
        $gClient->setApplicationName($this->settings->site_name);
        $gClient->setClientId($clientId);
        $gClient->setClientSecret($clientSecret);
        $gClient->setRedirectUri($redirectUrl);
        $gClient->addScope("email");
        $gClient->addScope("profile");

        $google_oauthV2 = new Google_Service_Oauth2($gClient);

        if(!isset($_GET['code']))
            redirect(site_url('auth/register'));

        $gClient->authenticate($_GET['code']);

        $_SESSION['g_access_token'] = $gClient->getAccessToken()['access_token'];

        // User information retrieval starts..............................
        $user_detail = $google_oauthV2->userinfo->get(); //get user info

        $_SESSION['g_user_id']      = $user_detail->id;
        $_SESSION['g_email']        = $user_detail->email;
        $_SESSION['g_fullname']     = $user_detail->name;

        redirect(site_url('auth/oauth_login'));
    }


    /**
     * Registration Facebook
     */
    public function f_register()
    {
        if ($this->ion_auth->logged_in())
        {
            // if user is logged in the redirect tot home page
            redirect('', 'refresh');
        }

        // facebook login
        if(!$this->settings->fb_app_id && !$this->settings->fb_app_secret)
        {
            redirect(site_url('auth/login'));
        }

        // facebook Project API Credentials
        $appId       = $this->settings->fb_app_id;
        $appSecret   = $this->settings->fb_app_secret;
        $redirectUrl = site_url().'auth/f_r_callback';

        // facebook Client Configuration
        $fb = new Facebook\Facebook([
          'app_id'      => $appId,
          'app_secret'  => $appSecret,
          'default_graph_version' => 'v2.10',
        ]);

        $helper = $fb->getRedirectLoginHelper();

        $permissions = ['email']; // Optional permissions
        $loginUrl = $helper->getLoginUrl(site_url('auth/f_r_callback'), $permissions);

        redirect($loginUrl, 'refresh');
    }

    public function f_r_callback()
    {
        if ($this->ion_auth->logged_in())
        {
            // if user is logged in the redirect tot home page
            redirect('', 'refresh');
        }

        // facebook login
        if(!$this->settings->fb_app_id && !$this->settings->fb_app_secret)
        {
            redirect(site_url('auth/login'));
        }

        // facebook Project API Credentials
        $appId       = $this->settings->fb_app_id;
        $appSecret   = $this->settings->fb_app_secret;
        $redirectUrl = site_url().'auth/f_r_callback';

        $fb = new Facebook\Facebook([
          'app_id'      => $appId,
          'app_secret'  => $appSecret,
          'default_graph_version' => 'v2.10',
        ]);

        $helper = $fb->getRedirectLoginHelper();

        try {
          $accessToken = $helper->getAccessToken(base_url('/auth/f_r_callback'));
          $response    = $fb->get("/me?fields=id, name, email", $accessToken);
        } catch(Facebook\Exceptions\FacebookResponseException $e) {
          // When Graph returns an error
          echo 'Graph returned an error: ' . $e->getMessage();
          exit;
        } catch(Facebook\Exceptions\FacebookSDKException $e) {
          // When validation fails or other local issues
          echo 'Facebook SDK returned an error: ' . $e->getMessage();
          exit;
        }

        if (! isset($accessToken)) {
          if ($helper->getError()) {
            header('HTTP/1.0 401 Unauthorized');
            echo "Error: " . $helper->getError() . "\n";
            echo "Error Code: " . $helper->getErrorCode() . "\n";
            echo "Error Reason: " . $helper->getErrorReason() . "\n";
            echo "Error Description: " . $helper->getErrorDescription() . "\n";
          } else {
            header('HTTP/1.0 400 Bad Request');
            echo 'Bad request';
          }
          exit;
        }

        // Logged in
        // echo '<h3>Access Token</h3>';
        // var_dump($accessToken->getValue());

        // The OAuth 2.0 client handler helps us manage access tokens
        $oAuth2Client = $fb->getOAuth2Client();

        // Get the access token metadata from /debug_token
        $tokenMetadata = $oAuth2Client->debugToken($accessToken);
        // echo '<h3>Metadata</h3>';
        // var_dump($tokenMetadata);

        // Validation (these will throw FacebookSDKException's when they fail)
        $tokenMetadata->validateAppId($appId); // Replace {app-id} with your app id
        // If you know the user ID this access token belongs to, you can validate it here
        //$tokenMetadata->validateUserId('123');
        $tokenMetadata->validateExpiration();

        // fb account user data
        $user_detail                    = $response->getDecodedBody();
        // $user_detail
        // $_SESSION['fb_access_token']

        if(empty($user_detail))
            redirect(site_url('auth/login'));

        $_SESSION['fb_access_token']    = (string) $accessToken;
        $_SESSION['fb_user_id']         = (string) $user_detail['id'];
        $_SESSION['fb_email']           = (string) $user_detail['email'];
        $_SESSION['fb_fullname']        = (string) $user_detail['name'];

        redirect(site_url('auth/oauth_login'));
    }



    /*
     * Common login & register method for google and facebook
     */
    public function oauth_login()
    {
    	if ($this->ion_auth->logged_in())
		{
			// if user is logged in the redirect tot home page
			redirect('', 'refresh');
		}

        $_POST['fb_uid']        = NULL;
        $_POST['fb_token']      = NULL;
        $_POST['g_uid']         = NULL;
        $_POST['g_token']       = NULL;

        if(!isset($_POST['fb_access_token']) && empty($_SESSION['g_access_token']))
            redirect(site_url('auth/login'));

        if(isset($_POST['fb_access_token'])) // when registering with facebook
        {
            $_POST['first_name']        = explode(' ', $_POST['fb_fullname'])[0];
            $_POST['last_name']         = '';
            foreach(explode(' ', $_POST['fb_fullname']) as $k => $v)
                if($k) $_POST['last_name'].= $v;

            if(empty($_SESSION['fb_email'])) // if in case of no email
            {
                $_POST['username']      = strtolower($_POST['first_name']).mt_rand(); // email string ~ default username
                $_SESSION['fb_email']   = $_POST['username'].'@'.get_domain();
            }
            else
            {
                $_POST['username']      = str_replace('.', '', explode('@', $_SESSION['fb_email'])[0]); // email string ~ default username
            }

            $_POST['email']             = $_SESSION['fb_email']; // email string ~ default username
            $_POST['fb_uid']            = $_SESSION['fb_user_id'];
            $_POST['fb_token']          = $_SESSION['fb_access_token'];
            $_POST['language']          = 'english';

            // unset unnecessary fields
            unset($_POST['fb_access_token']);
            unset($_POST['fb_user_id']);
            unset($_POST['fb_fullname']);
            unset($_POST['fb_email']);
        }

        if(! (empty($_SESSION['g_access_token']) && empty($_SESSION['g_user_id']) && empty($_SESSION['g_email']) && empty($_SESSION['g_fullname'])) ) // when registering with google
        {
            $_POST['first_name']        = explode(' ', $_SESSION['g_fullname'])[0];
            $_POST['last_name']         = '';
            foreach(explode(' ', $_SESSION['g_fullname']) as $k => $v)
                if($k) $_POST['last_name'].= $v;

            $_POST['email']             = $_SESSION['g_email']; // email string ~ default username
            $_POST['username']          = explode('@', $_POST['email'])[0]; // email string ~ default username
            $_POST['g_uid']             = $_SESSION['g_user_id'];
            $_POST['g_token']           = $_SESSION['g_access_token'];
            $_POST['language']          = 'english';

            // unset unnecessary fields
            unset($_SESSION['g_access_token']);
            unset($_SESSION['g_user_id']);
            unset($_SESSION['g_fullname']);
            unset($_SESSION['g_email']);
        }

        $flag     = FALSE;
        if($this->users_model->email_exists($_POST['email']) || $this->users_model->oauth_uid_exists($_POST['fb_uid'], $_POST['g_uid']))
        {
            if($_POST['fb_uid'])
            {
                $data['fb_uid']                 = $_POST['fb_uid'];
                $data['fb_token']               = $_POST['fb_token'];
            }
            if($_POST['g_uid'])
            {
                $data['g_uid']                  = $_POST['g_uid'];
                $data['g_token']                = $_POST['g_token'];
            }

            $flag = $this->users_model->save_users($data, '', $_POST['email']);
        }
        else
        {
            if($_POST['fb_uid'])
            {
                $data['fb_uid']                 = $_POST['fb_uid'];
                $data['fb_token']               = $_POST['fb_token'];
            }
            if($_POST['g_uid'])
            {
                $data['g_uid']                  = $_POST['g_uid'];
                $data['g_token']                = $_POST['g_token'];
            }

            $data['first_name']                 = $_POST['first_name'];
            $data['last_name']                  = $_POST['last_name'];
            $data['username']                   = $_POST['username'];
            $data['email']                      = $_POST['email'];
            $data['language']                   = 'english';

            $flag                               = $this->users_model->save_users($data);

            $notification   = array(
                'users_id'  => 1,
                'n_type'    => 'users',
                'n_content' => 'noti_new_users',
                'n_url'     => site_url('admin/users'),
            );
            $this->notifications_model->save_notifications($notification);
        }

        if($flag)
        {
            $result                     = $this->users_model->login_oauth($_POST['email']);

            $_SESSION['language'] 		= $result['language'];
		    $_SESSION['identity'] 		= $result['email'];
		    $_SESSION['email'] 			= $result['email'];
            $_SESSION['user_id']        = $result['id'];
		    $_SESSION['groups_id'] 		= $this->ion_auth->get_users_groups($result['id'])->row()->id;

            if(empty($_SESSION['groups_id']))
                $this->ion_auth->add_to_group(3, $result['id']);

            $_SESSION['groups_id']      = $this->ion_auth->get_users_groups($result['id'])->row()->id;

            $this->session->set_userdata('logged_in', $result);
			redirect(site_url());
        }

        // if nothing works then redirect to login page
        redirect(site_url('auth/login'));
    }

     /**
     * Registration Form
     */
    public function register()
    {
    	if ($this->ion_auth->logged_in())
		{
			// if user is logged in the redirect tot home page
			redirect('', 'refresh');
		}

        // if facebook login
        if(!empty($_POST['fb_access_token']))
            $this->oauth_login();


        $this->add_plugin_theme(array(
                                "datepicker/datepicker3.css",
                                "datepicker/bootstrap-datepicker.js",
                            ), 'default')
             ->add_js_theme( "pages/user/index_i18n.js", TRUE);

        // validators
        $this->form_validation
        ->set_error_delimiters($this->config->item('error_delimeter_left'), $this->config->item('error_delimeter_right'))
        ->set_rules('username', lang('users_username'), 'required|trim|min_length[3]|max_length[128]|callback__check_username')
        ->set_rules('first_name', lang('users_first_name'), 'trim|min_length[2]|max_length[128]')
        ->set_rules('last_name', lang('users_last_name'), 'trim|min_length[2]|max_length[128]')
        ->set_rules('email', lang('users_email'), 'required|trim|max_length[256]|valid_email|callback__check_email')
        ->set_rules('profession', lang('users_profession'), 'trim|min_length[3]|max_length[256]')
        ->set_rules('experience', lang('users_experience'), 'trim|is_natural_no_zero')
        ->set_rules('gender', lang('users_gender'), 'trim|in_list[male,female,other]')
        ->set_rules('dob', lang('users_dob'), 'trim')
        ->set_rules('mobile', lang('users_mobile'), 'trim|min_length[5]|max_length[20]')
        ->set_rules('address', lang('users_address'), 'trim|min_length[8]|max_length[256]')
        ->set_rules('language', lang('users_language'), 'trim')
        ->set_rules('password', lang('users_password'), 'required|trim|min_length['.$this->settings->i_min_password.']|max_length['.$this->settings->i_max_password.']')
        ->set_rules('password_confirm', lang('users_password_confirm'), 'required|trim|matches[password]');

        // upload users image
        $filename               = NULL;
        if(! empty($_FILES['image']['name'])) // if image
        {
            $file               = array('folder'=>'users/images', 'input_file'=>'image');
            $filename           = $this->file_uploads->upload_file($file);
            // through image upload error
            if(!empty($filename['error']))
                $this->form_validation->set_rules('image_error', lang('users_image'), 'required', array('required'=>$filename['error']));
        }


        if ($this->form_validation->run() === TRUE)
        {
            if(! empty($filename) && ! isset($filename['error']))
                $_POST['image']          	= $filename;
            else
                $_POST['image']          	= '';


      $username 						= $this->input->post('username');
			$email 							= $this->input->post('email');
			$password 						= $this->input->post('password');
			$additional_data 				= array(
												'first_name' 	=> $this->input->post('first_name'),
												'last_name' 	=> $this->input->post('last_name'),
												'gender' 		=> $this->input->post('gender'),
												'mobile' 		=> $this->input->post('mobile'),
												'image' 		=> $this->input->post('image'),
												'address' 		=> $this->input->post('address'),
												'language' 		=> $this->input->post('language'),
											);

			if($this->input->post('dob') == ''){
				$additional_data['dob'] = "1970-01-01";
			}else{
				$additional_data['dob'] =	$this->input->post('dob');
			};

			$flag 							= $this->ion_auth->register($username, $password, $email, $additional_data);

            if ($flag)
            {
                $this->session->language = $this->input->post('language');
                $this->session->set_flashdata('message', sprintf(lang('users_register_success'), $this->input->post('first_name', TRUE)));

                $notification   = array(
                    'users_id'  => 1,
                    'n_type'    => 'users',
                    'n_content' => 'noti_new_users',
                    'n_url'     => site_url('admin/users'),
                );
                $this->notifications_model->save_notifications($notification);

                /*Direct login after signup*/
                $result                     = $this->users_model->login_oauth($email);

                $_SESSION['language']       = $additional_data['language'];
                $_SESSION['identity']       = $email;
                $_SESSION['email']          = $email;
                $_SESSION['user_id']        = $flag['id'];
                $_SESSION['groups_id']      = $this->ion_auth->get_users_groups($flag['id'])->row()->id;

                if(empty($_SESSION['groups_id']))
                //    $this->ion_auth->add_to_group(3, $flag['id']);

                $_SESSION['groups_id']      = $this->ion_auth->get_users_groups($flag['id'])->row()->id;

                $this->session->set_userdata('logged_in', $result);
                redirect(site_url());
            }
            else
            {
                $this->session->set_flashdata('error', lang('users_register_failed'));
                redirect('', 'refresh');
            }

            // redirect home and display message
            redirect(site_url('auth/login'));
        }

        // setup page header data
        $this->set_title( lang('users_register') );

        $data = $this->includes;

        // set content data
        $content_data = array(
            'cancel_url'        => site_url(),
            'user'              => NULL,
            'password_required' => TRUE
        );

        // load views
        $data['content'] = $this->load->view('auth/register_form', $content_data, TRUE);
        $this->load->view($this->template, $data);
    }

    // activate the user
    public function activate($id, $code=false)
    {
        if ($code !== false)
        {
            $activation = $this->ion_auth->activate($id, $code);
        }
        else if ($this->ion_auth->is_admin())
        {
            $activation = $this->ion_auth->activate($id);
        }

        if ($activation)
        {
            // redirect them to the auth page
            $this->session->set_flashdata('message', $this->ion_auth->messages());
            redirect("auth", 'refresh');
        }
        else
        {
            // redirect them to the forgot password page
            $this->session->set_flashdata('message', $this->ion_auth->errors());
            redirect("auth/forgot_password", 'refresh');
        }
    }

	// log the user out
	public function logout()
	{
		// log the user out
		$logout = $this->ion_auth->logout();

        $this->session->sess_destroy();

		// redirect them to the login page
		$this->session->set_flashdata('message', $this->ion_auth->messages());
		redirect('', 'refresh');
	}

	// forgot password
	public function forgot_password()
	{
		// setup page header data
        $this->set_title( lang('users_forgot') );
        $data = $this->includes;

		if ($this->ion_auth->logged_in())
		{
			// if user is logged in the redirect tot home page
			redirect('', 'refresh');
		}

		// setting validation rules by checking whether identity is username or email
		$this->form_validation->set_rules('identity', $this->lang->line('forgot_password_validation_email_label'), 'required|valid_email');

		if ($this->form_validation->run() == false)
		{
			// load views
	        $data['content'] = $this->load->view('auth/forgot_password', NULL, TRUE);
	        $this->load->view($this->template, $data);
		}
		else
		{
			$identity 		 = $this->ion_auth->where('email', $this->input->post('identity'))->users()->row();

			if(empty($identity))
			{
        		$this->ion_auth->set_error('forgot_password_email_not_found');

                $this->session->set_flashdata('error', $this->ion_auth->errors());
        		redirect("auth/forgot_password", 'refresh');
    		}

			// run the forgotten password method to email an activation code to the user
			$forgotten = $this->ion_auth->forgotten_password($identity->email);

			if ($forgotten)
			{
				// if there were no errors
				$this->session->set_flashdata('message', $this->ion_auth->messages());
				redirect("auth/login", 'refresh'); //we should display a confirmation page here instead of the login page
			}
			else
			{
				$this->session->set_flashdata('error', $this->ion_auth->errors());
				redirect("auth/forgot_password", 'refresh');
			}
		}
	}

	// reset password - final step for forgotten password
	public function reset_password($code = NULL)
	{
		if (!$code)
		{
			show_404();
		}

		// setup page header data
	    $this->set_title( lang('reset_password_heading') );
	    $data = $this->includes;

		$user = $this->ion_auth->forgotten_password_check($code);

		if ($user)
		{
			// if the code is valid then display the password reset form
			$this->form_validation->set_rules('new', $this->lang->line('reset_password_validation_new_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[new_confirm]');
			$this->form_validation->set_rules('new_confirm', $this->lang->line('reset_password_validation_new_password_confirm_label'), 'required');

			if ($this->form_validation->run() == false)
			{
				// display the form
				$this->data['user_id'] = array(
					'name'  => 'user_id',
					'id'    => 'user_id',
					'type'  => 'hidden',
					'value' => $user->id,
				);
				$this->data['csrf'] = $this->_get_csrf_nonce();
				$this->data['code'] = $code;

				// load views
		        $data['content'] = $this->load->view('auth/reset_password', $this->data, TRUE);
		        $this->load->view($this->template, $data);
			}
			else
			{
				// do we have a valid request?
				if ($this->_valid_csrf_nonce() === FALSE || $user->id != $this->input->post('user_id'))
				{

					// something fishy might be up
					$this->ion_auth->clear_forgotten_password_code($code);

					show_error($this->lang->line('error_csrf'));

				}
				else
				{
					// finally change the password
					$identity = $user->{$this->config->item('identity', 'ion_auth')};

					$change = $this->ion_auth->reset_password($identity, $this->input->post('new'));

					if ($change)
					{
						// if the password was successfully changed
						$this->session->set_flashdata('message', $this->ion_auth->messages());
						redirect("auth/login", 'refresh');
					}
					else
					{
						$this->session->set_flashdata('message', $this->ion_auth->errors());
						redirect('auth/reset_password/' . $code, 'refresh');
					}
				}
			}
		}
		else
		{
			// if the code is invalid then send them back to the forgot password page
			$this->session->set_flashdata('message', $this->ion_auth->errors());
			redirect("auth/forgot_password", 'refresh');
		}
	}

	/**
     * Make sure username is available
     *
     * @param  string $username
     * @return int|boolean
     */
    function _check_username($username)
    {
        if ($this->users_model->username_exists($username))
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
        if ($this->users_model->email_exists($email))
        {
            $this->form_validation->set_message('_check_email', sprintf(lang('users_email_error'), $email));
            return FALSE;
        }
        else
        {
            return $email;
        }
    }


    /**
     * Make sure email exists
     *
     * @param  string $email
     * @return int|boolean
     */
    function _check_email_exists($email)
    {
        if ( ! $this->users_model->email_exists($email))
        {
            $this->form_validation->set_message('_check_email_exists', sprintf(lang('users_email_not_exists'), $email));
            return FALSE;
        }
        else
        {
            return $email;
        }
    }

	public function _get_csrf_nonce()
	{
		$this->load->helper('string');
		$key   = random_string('alnum', 8);
		$value = random_string('alnum', 20);
		$this->session->set_flashdata('csrfkey', $key);
		$this->session->set_flashdata('csrfvalue', $value);

		return array($key => $value);
	}

	public function _valid_csrf_nonce()
	{
		$csrfkey = $this->input->post($this->session->flashdata('csrfkey'));
		if ($csrfkey && $csrfkey == $this->session->flashdata('csrfvalue'))
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}

}
