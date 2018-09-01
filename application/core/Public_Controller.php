<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Base Public Class - used for all public pages
 */

class Public_Controller extends MY_Controller {

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();

        
        if(isset($_SESSION['redirect_url']) && $this->session->userdata('logged_in'))
        {
            $redirect_url = $_SESSION['redirect_url'];
            unset($_SESSION['redirect_url']);
            redirect($redirect_url);
        }

        // load stripe if enabled
        if($this->settings->s_secret_key && $this->settings->s_publishable_key)
        {
            $this->stripe_creds = array(
                "secret_key"      => $this->settings->s_secret_key,
                "publishable_key" => $this->settings->s_publishable_key,
            );

            \Stripe\Stripe::setApiKey($this->stripe_creds['secret_key']);
            \Stripe\Stripe::setVerifySslCerts(false);
        }

        // prepare theme name
        $this->settings->theme = strtolower($this->config->item('public_theme'));
        $this->load->helper(array('my_func_helper', 'menu_helper')); 

        // Visits Count
        $this->load->library('visits_count');
        $this->visits_count->hit_visit();

        // set up global header data
        $this
        ->add_plugin_theme(array(
            'animate-css/animate.min.css',
            
            // Bootstrap Select
            'bootstrap-select/css/bootstrap-select.min.css',
            'bootstrap-select/js/bootstrap-select.min.js',
            
            'font-awesome/css/font-awesome.min.css',
            
            'univershicon/univershicon.css',

            'pretty-photo/prettyPhoto.css',
            'pretty-photo/jquery.prettyPhoto.js',

            'theme-lib/jquery.appear.min.js',
            'theme-lib/jquery.easing.min.js',
            'theme-lib/menu.js',
            'theme-lib/modernizr.js',
        ), 'default')
        ->add_css_theme( "{$this->settings->theme}.css" )
        ->add_js_theme( "{$this->settings->theme}_i18n.js", TRUE )
        ->add_external_css(
            array(
                base_url("/themes/default/css/menu.css"),
                base_url("/themes/default/css/skins/default.css"),
                base_url("/themes/default/css/theme.css"),
                base_url("/themes/default/css/theme-responsive.css"),
                base_url("/themes/default/css/ie.css"),
                base_url("/themes/default/css/custom.css"),
            ))
        ->add_external_js(
            array(
                base_url("/themes/default/js/theme.js"),
            ))
        ->add_js_theme( "custom_i18n.js", TRUE );
        
        // declare main template
        $this->template = "../../{$this->settings->root_folder}/themes/{$this->settings->theme}/template.php";
    }

}
