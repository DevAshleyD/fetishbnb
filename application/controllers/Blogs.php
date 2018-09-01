<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Blogs Controller
 *
 * This class handles blogs listings module functionality
 *
 * @package     classiebit
 * @author      prodpk
*/

class Blogs extends Public_Controller {

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();

        $this->load->model(array(
                            'admin/blogs_model',
                            'users_model',
                        ));
    }


    /**
     * index
    */
    function index()
    {
        // setup page header data
        $this->set_title(lang('menu_blogs'));
        $data           = $this->includes;

        $content_data['blogs']   = $this->blogs_model->get_blogs();
        
        // load views
        $data['content'] = $this->load->view('blogs', $content_data, TRUE);
        $this->load->view($this->template, $data);
    }

    /**
     * blog
    */
    function blog($slug = NULL)
    {
        /* Initialize assets and title */
        $this
        ->add_js_theme( "pages/blogs/index_i18n.js", TRUE )
        ->set_title(lang('menu_blog'));
        $data                     = $this->includes;

        if(! $slug)
            redirect(site_url('blogs'));
        
        $blogs                    = $this->blogs_model->get_blogs_by_slug($slug);

        if(empty($blogs))
            show_404();

        if($blogs->meta_title)
            $this->meta_title                = $blogs->meta_title;
        if($blogs->meta_tags)
            $this->meta_tags                 = $blogs->meta_tags;
        if($blogs->meta_description)
            $this->meta_description          = $blogs->meta_description;
        if($blogs->image)
            $this->meta_image                = base_url('upload/blogs/images/').$blogs->image;
        if($blogs->slug)
            $this->meta_url                  = site_url('blogs/').$blogs->slug;
        
        // set content data
        $content_data['blogs']    = $blogs;
        
        // load views
        $data['content'] = $this->load->view('blog', $content_data, TRUE);
        $this->load->view($this->template, $data);
    }

}

/* Blogs controller ends */