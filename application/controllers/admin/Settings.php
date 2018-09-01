<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Settings Controller
 *
 * This class handles settings module functionality
 *
 * @package     classiebit
 * @author      prodpk
*/

class Settings extends Admin_Controller {

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();

        /* Page Title */
        $this->set_title( lang('menu_settings') );
    }


    /**
     * Settings Editor
     */
    function index()
    {
        if(!$this->acl->get_method_permission($_SESSION['groups_id'], 'settings', 'p_view'))
        {
            $this->session->set_flashdata('error', sprintf(lang('manage_acl_permission_no'), lang('menu_settings').' '.lang('manage_acl_view')));
            redirect($this->uri->segment(1).'/dashboard');
        }

        // get settings
        $settings       = $this->settings_model->get_settings();
        $settings_2     = array();

        // form validations
        $this->form_validation->set_error_delimiters($this->config->item('error_delimeter_left'), $this->config->item('error_delimeter_right'));
        foreach ($settings as $key => $setting)
        {
            $settings_2[$setting['setting_type']][$key] = $setting;
            
            if ($setting['validation'])
            {
                if ($setting['translate'])
                {
                    // setup a validation for each translation
                    foreach ($this->session->languages as $language_key=>$language_name)
                    {
                        $this->form_validation->set_rules($setting['name'] . "[" . $language_key . "]", $setting['label'] . " [" . $language_name . "]", $setting['validation']);
                    }
                }
                else
                {
                    // single validation
                    if(isset($_POST[''.$setting['name'].''])) // set validation seperately for each tab
                        $this->form_validation->set_rules($setting['name'], $setting['label'], $setting['validation']);
                }
            }
        }

        if ($this->form_validation->run() == TRUE)
        {
            if(!$this->acl->get_method_permission($_SESSION['groups_id'], 'settings', 'p_edit'))
            {
                $this->session->set_flashdata('error', sprintf(lang('manage_acl_permission_no'), lang('manage_acl_edit')));
                redirect(site_url('admin/settings'));
            }

            $user = $this->session->userdata('logged_in');

            if(! empty($_FILES['institute_logo']['name'])) // if institute logo
            {
                $file_image         = array('folder'=>'institute', 'input_file'=>'institute_logo', 'filename'=>'logo', 'format'=>'png|PNG');
                
                $filename_image     = $this->file_uploads->upload_file_custom($file_image);

                // through image upload error
                if(!empty($filename_image['error']))
                    $this->session->set_flashdata('error', $filename_image['error']);
            }

            if(! empty($_FILES['banner_image_1']['name'])) // if banner_image_1
            {
                $file_image         = array('folder'=>'home', 'input_file'=>'banner_image_1', 'filename'=>'banner_image_1', 'format'=>'jpg|JPG');
                
                $filename_image     = $this->file_uploads->upload_file_custom($file_image);

                // through image upload error
                if(!empty($filename_image['error']))
                    $this->session->set_flashdata('error', $filename_image['error']);
            }

            if(! empty($_FILES['banner_image_2']['name'])) // if banner_image_2
            {
                $file_image         = array('folder'=>'home', 'input_file'=>'banner_image_2', 'filename'=>'banner_image_2', 'format'=>'jpg|JPG');
                
                $filename_image     = $this->file_uploads->upload_file_custom($file_image);

                // through image upload error
                if(!empty($filename_image['error']))
                    $this->session->set_flashdata('error', $filename_image['error']);
            }

            if(! empty($_FILES['banner_image_3']['name'])) // if banner_image_3
            {
                $file_image         = array('folder'=>'home', 'input_file'=>'banner_image_3', 'filename'=>'banner_image_3', 'format'=>'jpg|JPG');
                
                $filename_image     = $this->file_uploads->upload_file_custom($file_image);

                // through image upload error
                if(!empty($filename_image['error']))
                    $this->session->set_flashdata('error', $filename_image['error']);
            }

            if(! empty($_FILES['banner_image_4']['name'])) // if banner_image_4
            {
                $file_image         = array('folder'=>'home', 'input_file'=>'banner_image_4', 'filename'=>'banner_image_4', 'format'=>'jpg|JPG');
                
                $filename_image     = $this->file_uploads->upload_file_custom($file_image);

                // through image upload error
                if(!empty($filename_image['error']))
                    $this->session->set_flashdata('error', $filename_image['error']);
            }

            if(! empty($_FILES['banner_image_5']['name'])) // if banner_image_5
            {
                $file_image         = array('folder'=>'home', 'input_file'=>'banner_image_5', 'filename'=>'banner_image_5', 'format'=>'jpg|JPG');
                
                $filename_image     = $this->file_uploads->upload_file_custom($file_image);

                // through image upload error
                if(!empty($filename_image['error']))
                    $this->session->set_flashdata('error', $filename_image['error']);
            }

            // save the settings
            $saved = $this->settings_model->save_settings($this->input->post(), $user['id']);

            if ($saved)
            {
                $this->session->set_flashdata('message', lang('admin settings msg save_success'));

                // reload the new settings
                $settings = $this->settings_model->get_settings();
                foreach ($settings as $setting)
                {
                    $this->settings->{$setting['name']} = @unserialize($setting['value']);
                }
            }
            else
            {
                $this->session->set_flashdata('error', lang('admin settings error save_failed'));
            }

            // reload the page
            redirect('admin/settings');
        }

        /* Initialize assets */
        $this
        ->add_plugin_theme(array(   
                            "tinymce/tinymce.js",
                        ), 'admin')
        ->add_js_theme( "pages/settings/form_i18n.js", TRUE );
        $data                       = $this->includes;

        // set content data
        $data['settings']   = $settings_2;
        $data['cancel_url'] = "/admin";

        // load views
        $content['content'] = $this->load->view('admin/settings/form', $data, TRUE);
        $this->load->view($this->template, $content);
    }

}

/* Settings controller ends */