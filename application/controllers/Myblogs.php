<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Myblogs Controller
 *
 * This class handles profile module functionality
 *
 * @package     classiebit
 * @author      prodpk
*/


class Myblogs extends Private_Controller {

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();

        // load the users model
        $this->load->model(array(
                            'users_model',
                            'admin/blogs_model',
                          ));

        $this->load->library('file_uploads');
    }


    /**
     * index
     */
    function index()
    {
        $this->set_title( lang('menu_my_blogs'));
        $data = $this->includes;

        $content_data['my_blogs'] = $this->blogs_model->get_blogs_by_users_id($this->user['id']);

        // load views
        $data['content'] = $this->load->view('my_blogs', $content_data, TRUE);
        $this->load->view($this->template, $data);
    }

    /**
     * form
    */
    public function form($id = NULL)
    {
        /* Initialize assets */
        $this->add_plugin_theme(array(   
                                "tinymce/tinymce.min.js",
                            ), 'default')
             ->add_js_theme( "pages/my_blogs/form_i18n.js", TRUE );

        if($id)
          $this->set_title(sprintf(lang('menu_edit'), lang('menu_my_blogs')));
        else
          $this->set_title(lang('blogs_add_new'));
             
        $data                       = $this->includes;

        // in case of edit
        $id                         = (int) $id;
        if($id)
        {
            $result                 = $this->blogs_model->get_user_blog($id, $this->user['id']);

            if(empty($result))
            {
                $this->session->set_flashdata('error', sprintf(lang('alert_not_found') ,lang('menu_my_blog')));
                redirect(site_url('myblogs'));            
            }

            // hidden field in case of update
            $data['id']             = $result->id; 
            
            // current image 
            $data['c_image']        = $result->image;
        }
            
        $data['title']      = array(
            'name'          => 'title',
            'id'            => 'title',
            'type'          => 'text',
            'class'         => 'form-control',
            'value'         => $this->form_validation->set_value('title', !empty($result->title) ? $result->title : ''),
        );
        $data['slug']      = array(
            'name'          => 'slug',
            'id'            => 'slug',
            'type'          => 'text',
            'readonly'      => 'readonly',
            'class'         => 'form-control',
            'value'         => $this->form_validation->set_value('slug', !empty($result->slug) ? $result->slug : ''),
        );
        $data['content']      = array(
            'name'          => 'content',
            'id'            => 'content',
            'type'          => 'textarea',
            'class'         => 'form-control tinymce',
            'value'         => $this->form_validation->set_value('content', !empty($result->content) ? $result->content : ''),
        );
        $data['image'] = array(
            'name'      => 'image',
            'id'        => 'image',
            'type'      => 'file',
            'class'     => 'form-control',
            'accept'    => 'image/*',
        );
        $data['meta_title']= array(
            'name'          => 'meta_title',
            'id'            => 'meta_title',
            'type'          => 'text',
            'class'         => 'form-control',
            'value'         => $this->form_validation->set_value('meta_title', !empty($result->meta_title) ? $result->meta_title : ''),
        );
        $data['meta_tags']= array(
            'name'          => 'meta_tags',
            'id'            => 'meta_tags',
            'type'          => 'text',
            'class'         => 'form-control',
            'value'         => $this->form_validation->set_value('meta_tags', !empty($result->meta_tags) ? $result->meta_tags : ''),
        );
        $data['meta_description']= array(
            'name'          => 'meta_description',
            'id'            => 'meta_description',
            'type'          => 'textarea',
            'class'         => 'form-control',
            'value'         => $this->form_validation->set_value('meta_description', !empty($result->meta_description) ? $result->meta_description : ''),
        );
        if($id)
          $data['status']     = $result->status == 1 ? '<span class="label label-success">'.lang('blogs_status_published').'</span>' : ($result->status == 2 ? '<span class="label label-warning">'.lang('blogs_status_pending').'</span>' : '<span class="label label-default">'.lang('blogs_status_disabled').'</span>');

        /* Load Template */
        $content['content']    = $this->load->view('my_blogs_form', $data, TRUE);
        $this->load->view($this->template, $content);
    }

    /**
     * save
    */
    public function save()
    {
        $id = NULL;
        if(! empty($_POST['id']))
        {
            $id                     = (int) $this->input->post('id');

            $result                 = $this->blogs_model->get_user_blog($id, $this->user['id']);

            if(empty($result))
            {
                $this->session->set_flashdata('message', sprintf(lang('alert_not_found'), lang('menu_my_blog')));
                echo json_encode(array(
                                        'flag'  => 0, 
                                        'msg'   => $this->session->flashdata('message'),
                                        'type'  => 'fail',
                                    ));
                exit;
            }
        }

        /* Validate form input */
        $this->form_validation
        ->set_rules('title', lang('common_title'), 'trim|required|max_length[256]|alpha_dash_spaces')
        ->set_rules('content', lang('blogs_content'), 'trim')
        ->set_rules('meta_title', lang('common_meta_title'), 'trim')
        ->set_rules('meta_tags', lang('common_meta_tags'), 'trim')
        ->set_rules('meta_description', lang('common_meta_description'), 'trim');
        
        /*Validate Title duplicacy & level*/
        if($id) 
        {
            if(isset($_POST['title']))
                if($this->input->post('title') !== $result->title)
                    $this->form_validation->set_rules('title', lang('common_title'), 'trim|required|max_length[256]|alpha_dash_spaces|is_unique[blogs.title]');
        }
        else
        {   
            $this->form_validation
            ->set_rules('title', lang('common_title'), 'trim|required|max_length[256]|alpha_dash_spaces|is_unique[blogs.title]');
        }

        if(! empty($_FILES['image']['name'])) // if image 
        {
            $file_image         = array('folder'=>'blogs/images', 'input_file'=>'image');
            // Remove old image
            if($id)
                $this->file_uploads->remove_file('./upload/'.$file_image['folder'].'/', $result->image);
            // update blogs image            
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

        $data                           = array();
        $data['users_id']               = $this->user['id'];
        $data['title']                  = $this->input->post('title');
        $data['slug']                   = url_title($data['title'], 'dash', TRUE);
        $data['content']                = $this->input->post('content');
        $data['meta_title']             = $this->input->post('meta_title');
        $data['meta_tags']              = $this->input->post('meta_tags');
        $data['meta_description']       = $this->input->post('meta_description');
        
        if(! empty($filename_image) && ! isset($filename_image['error']))
            $data['image']          = $filename_image;
        
        $flag                           = $this->blogs_model->save_blogs($data, $id);

        if($flag)
        {
            if($id)
                $this->session->set_flashdata('message', sprintf(lang('alert_update_success'), lang('menu_my_blog')));
            else
                $this->session->set_flashdata('message', sprintf(lang('alert_insert_success'), lang('menu_my_blog')));

            echo json_encode(array(
                                    'flag'  => 1, 
                                    'msg'   => $this->session->flashdata('message'),
                                    'type'  => 'success',
                                ));
            exit;
        }
        
        if($id)
            $this->session->set_flashdata('error', sprintf(lang('alert_update_fail'), lang('menu_my_blog')));
        else
            $this->session->set_flashdata('error', sprintf(lang('alert_insert_fail'), lang('menu_my_blog')));

        echo json_encode(array(
                                'flag'  => 0, 
                                'msg'   => $this->session->flashdata('message'),
                                'type'  => 'fail',
                            ));
        exit;
    }


    /**
     * delete
     */
    public function delete()
    {
        /* Validate form input */
        $this->form_validation->set_rules('id', sprintf(lang('alert_id'), lang('menu_my_blog')), 'required|numeric');

        /* Get Data */
        $id                 = (int) $this->input->post('id');
        $result             = $this->blogs_model->get_user_blog($id, $this->user['id']);

        if(empty($result))
        {
            echo json_encode(array(
                                    'flag'  => 0, 
                                    'msg'   => sprintf(lang('alert_not_found') ,lang('menu_my_blog')),
                                    'type'  => 'fail',
                                ));exit;
        }

        $flag                   = $this->blogs_model->delete_user_blog($id, $this->user['id']);

        if($flag)
        {
            // Remove image
            if(!empty($result->image))
                $this->file_uploads->remove_file('./upload/blogs/images/', $result->image);
            
            echo json_encode(array(
                                    'flag'  => 1, 
                                    'msg'   => sprintf(lang('alert_delete_success'), lang('menu_my_blog')),
                                    'type'  => 'success',
                                ));exit;
        }
        
        echo json_encode(array(
                                    'flag'  => 0, 
                                    'msg'   => sprintf(lang('alert_delete_fail'), lang('menu_my_blog')),
                                    'type'  => 'fail',
                                ));
        exit;
    }
    

}

/*End Myblogs*/