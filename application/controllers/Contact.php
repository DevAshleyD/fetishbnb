<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Contact extends Public_Controller {

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();

        // load the model file
        $this->load->model('contact_model');
        $this->load->model('notifications_model');

        // load the captcha helper
        $this->load->helper('captcha');
    }


    /**************************************************************************************
     * PUBLIC FUNCTIONS
     **************************************************************************************/


    /**
     * Default
     */
    public function index()
    {
        // validators
        $this->form_validation->set_error_delimiters($this->config->item('error_delimeter_left'), $this->config->item('error_delimeter_right'));
        $this->form_validation->set_rules('name', lang('contacts_name'), 'required|trim|max_length[64]');
        $this->form_validation->set_rules('email', lang('contacts_email'), 'required|trim|valid_email|min_length[10]|max_length[256]');
        $this->form_validation->set_rules('title', lang('common_title'), 'required|trim|max_length[128]');
        $this->form_validation->set_rules('message', lang('contacts_message'), 'required|trim|min_length[10]');
        $this->form_validation->set_rules('captcha', lang('contacts_captcha'), 'required|trim|callback__check_captcha');

        if ($this->form_validation->run() == TRUE)
        {
            // attempt to save and send the message
            $post_data = $this->security->xss_clean($this->input->post());
            $saved_and_sent = $this->contact_model->save_and_send_message($post_data, $this->settings);

            if ($saved_and_sent)
            {
                $notification   = array(
                    'users_id'  => 1,
                    'n_type'    => 'contacts',
                    'n_content' => 'noti_new_message',
                    'n_url'     => site_url('admin/contacts'), 
                );
                $this->notifications_model->save_notifications($notification);    

                // redirect to home page
                $this->session->set_flashdata('message', sprintf(lang('contacts_send_success'), $this->input->post('name', TRUE)));
                redirect(site_url('contact'));
            }
            else
            {
                // stay on contact page
                $this->error = sprintf(lang('contacts_error_send_failed'), $this->input->post('name', TRUE));
            }
        }

        // create captcha image
        $captcha = create_captcha(array(
            'img_path'   => "./captcha/",
            'img_url'    => base_url('/captcha') . "/",
            'font_path'  => FCPATH . "themes/core/fonts/bromine/Bromine.ttf",
            'img_width'	 => 300,
            'img_height' => 100
        ));

        $captcha_data = array(
            'captcha_time' => $captcha['time'],
            'ip_address'   => $this->input->ip_address(),
            'word'	       => $captcha['word']
        );

        // store captcha image
        $this->contact_model->save_captcha($captcha_data);

        // setup page header data
        $this
        ->add_js_theme( "pages/contact/index.js")
        ->set_title( lang('menu_contact') );

        $data = $this->includes;

        // set content data
        $content_data = array(
            'captcha_image' => $captcha['image']
        );

        // load views
        $data['content'] = $this->load->view('contact/form', $content_data, TRUE);
        $this->load->view($this->template, $data);
    }


    /**************************************************************************************
     * PRIVATE VALIDATION CALLBACK FUNCTIONS
     **************************************************************************************/


    /**
     * Verifies correct CAPTCHA value
     *
     * @param  string $captcha
     * @return string|boolean
     */
    function _check_captcha($captcha)
    {
        $verified = $this->contact_model->verify_captcha($captcha);

        if ($verified == FALSE)
        {
            $this->form_validation->set_message('_check_captcha', lang('contacts_error_captcha'));
            return FALSE;
        }
        else
        {
            return $captcha;    
        }
    }

}
