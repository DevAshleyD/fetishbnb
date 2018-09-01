<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Base Admin Class - used for all administration pages
 */
class Admin_Controller extends MY_Controller {

    var $breadcrumb;
    var $notifications;

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();

        if (empty($_SESSION['logged_in'])) // not for customers
        {
            if (current_url() != base_url())
            {
                //store requested URL to session - will load once logged in
                $data = array('redirect' => current_url());
                $this->session->set_userdata($data);
            }

            redirect('auth/');
        }

        if($_SESSION['groups_id'] == 3) 
            redirect('auth/', 'refresh');
        
        // load notifications model
        $this->load->model('notifications_model');
        $this->notifications = $this->notifications_model->get_notifications($this->user['id']);

        // File Upload Library
        $this->load->library('file_uploads');

        // Acl Library
        $this->load->library('acl');

        // prepare theme name
        $this->settings->theme = strtolower($this->config->item('admin_theme'));

        // set up global header data for admin panel
        $this
        ->add_plugin_theme(array(
            // Bootstrap Select
            'bootstrap-select/css/bootstrap-select.min.css',
            'bootstrap-select/js/bootstrap-select.min.js',
            // Jquery slimscroll
            'jquery-slimscroll/jquery.slimscroll.min.js',
            // Waves Effect Css
            'node-waves/waves.min.css',
            'node-waves/waves.min.js',
            // Animation Css
            'animate-css/animate.min.css',
            // Bootstrap Notify
            'bootstrap-notify/bootstrap-notify.min.js',
        ), 'admin')
        ->add_css_theme(array(
            'style.min.css',
            'theme/all-themes.min.css',
            'custom.css',
        ))
        ->add_js_theme(array(
            'admin.js',
        ))
        ->add_js_theme( "custom_i18n.js", TRUE )
        ->add_js_theme( "{$this->settings->theme}_i18n.js", TRUE );

        // declare main template
        $this->template = "../../{$this->settings->root_folder}/themes/{$this->settings->theme}/template.php";
    }

    function include_index_plugins()
    {
        $this
        ->add_plugin_theme(array(
            // Jquery DataTable
            'jquery-datatable/datatables.min.css',
            'jquery-datatable/datatables.min.js',
            'jquery-datatable/pdfmake.min.js',
            'jquery-datatable/vfs_fonts.js',
        ), 'admin')
        ->add_js_theme('index_i18n.js', TRUE);
    }

}
