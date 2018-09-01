<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Courses Controller
 *
 * This class handles courses module functionality
 *
 * @package     classiebit
 * @author      prodpk
*/

class Courses extends Admin_Controller {

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();

        $this->load->model('admin/courses_model');

        /* Page Title */
        $this->set_title( lang('menu_courses') );
    }

    /**
     * index
     */
    function index()
    {

        if(!$this->acl->get_method_permission($_SESSION['groups_id'], 'courses', 'p_view'))
        {
            $this->session->set_flashdata('error', sprintf(lang('manage_acl_permission_no'), lang('menu_course').' '.lang('manage_acl_view')));
            redirect($this->uri->segment(1).'/dashboard');
        }

        /* Initialize assets */
        $this->include_index_plugins();
        $data = $this->includes;

        // Table Header
        $data['t_headers']  = array(
                                '#',
                                lang('common_title'),
                                lang('courses_category'),
                                lang('common_updated'),
                                lang('common_featured'),
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

        $table              = 'courses';        
        $columns            = array(
                                "$table.id",
                                "$table.title",
                                "$table.course_categories_id",
                                "$table.featured",
                                "$table.status",
                                "$table.date_updated",
                                "(SELECT cc.title FROM course_categories cc WHERE cc.id = $table.course_categories_id) category_name",
                            );
        $columns_order      = array(
                                "#",
                                "$table.title",
                                "$table.course_categories_id",
                                "$table.date_updated",
                                "$table.featured",
                                "$table.status",
                            );
        $columns_search     = array(
                                'title',
                            );
        $order              = array('date_updated' => 'DESC');
        
        $result             = $this->datatables->get_datatables($table, $columns, $columns_order, $columns_search, $order);
        $data               = array();
        $no                 = $_POST['start'];
        
        foreach ($result as $val) 
        {
            $no++;
            $row            = array();
            $row[]          = $no;
            $row[]          = mb_substr($val->title, 0, 30, 'utf-8');
            $row[]          = '<a href="'.site_url('admin/categories/view/').$val->course_categories_id.'" target="_blank">'.$val->category_name.'</a>';
            $row[]          = date('g:iA d/m/y', strtotime($val->date_updated));
            $row[]          = featured_switch($val->featured, $val->id);
            $row[]          = status_switch($val->status, $val->id);
            $row[]          = action_buttons('courses', $val->id, mb_substr($val->title, 0, 20, 'utf-8'), lang('menu_course'));
            $data[]         = $row;
        }
 
        $output             = array(
                                "draw"              => $_POST['draw'],
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
        $this->add_plugin_theme(array(   
                                "tinymce/tinymce.js",
                            ), 'admin')
             ->add_js_theme( "pages/courses/form_i18n.js", TRUE );
        $data                       = $this->includes;

        // in case of edit
        $id                         = (int) $id;
        if($id)
        {
            $result                 = $this->courses_model->get_courses_by_id($id);

            if(empty($result))
            {
                $this->session->set_flashdata('error', sprintf(lang('alert_not_found') ,lang('menu_course')));
                redirect($this->uri->segment(1).'/'.$this->uri->segment(2));
            }

            // hidden field in case of update
            $data['id']             = $result->id; 
            
            // current images
            $data['c_images']   = json_decode($result->images);

            // render category levels for current category in case of edit
            $categories_levels              = $this->courses_model->get_courses_levels($result->course_categories_id);
            $count_level                    = count($categories_levels);
            $top_level_category             = $categories_levels[$count_level-1]; // the last category will be top category

            // populate subcategory dropdown
            $data['category_l']     = array();
            if(count($categories_levels) > 0) 
            {
                for($i = (count($categories_levels) - 2); $i >= 0; $i--)
                {
                    $data['category_l'][$i] = array(
                        'name'  => 'category[]',
                        'options'=> array($categories_levels[$i]['id'] => $categories_levels[$i]['title']),
                        'class' => 'form-control parent',
                        'selected' => $this->form_validation->set_value('category[]', $categories_levels[$i]['id']),
                    );
                }    
            }
        }

         // render category dropdown
        $categories                     = $this->courses_model->get_course_categories_dropdown();
        $data['categories']['0']        = '-- '.lang('courses_category').' --';

        foreach($categories as $category)
            $data['categories'][$category->id] = $category->title;

        $data['category']   = array(
            'name'          => 'category[]',
            'options'       => $data['categories'],
            'id'            => 'category',
            'class'         => 'category form-control show-tick parent',
            'data-live-search'=>"true",
            'selected'      => $this->form_validation->set_value('category[]', !empty($top_level_category['id']) ? $top_level_category['id'] : ''),
        );
        $data['title'] = array(
            'name'      => 'title',
            'id'        => 'title',
            'type'      => 'text',
            'class'     => 'form-control',
            'value'     => $this->form_validation->set_value('title', !empty($result->title) ? $result->title : ''),
        );
        $data['description'] = array(
            'name'      => 'description',
            'id'        => 'description',
            'type'      => 'textarea',
            'class'     => 'tinymce form-control',
            'value'     => $this->form_validation->set_value('description', !empty($result->description) ? $result->description : ''),
        );
        $data['images']     = array(
            'name'          => 'images[]',
            'id'            => 'images',
            'type'          => 'file',
            'multiple'      => 'multiple',
            'class'         => 'form-control',
            'accept'        => 'image/*',
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
        $data['featured'] = array(
            'name'      => 'featured',
            'id'        => 'featured',
            'class'     => 'form-control',
            'options'   => array('1' => lang('common_featured_enabled'), '0' => lang('common_featured_disabled')),
            'selected'  => $this->form_validation->set_value('featured', !empty($result->featured) ? $result->featured : 0),
        );
        $data['status'] = array(
            'name'      => 'status',
            'id'        => 'status',
            'class'     => 'form-control',
            'options'   => array('1' => lang('common_status_active'), '0' => lang('common_status_inactive')),
            'selected'  => $this->form_validation->set_value('status', !empty($result->status) ? $result->status : 0),
        );

        /* Load Template */
        $content['content']    = $this->load->view('admin/courses/form', $data, TRUE);
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
            if(!$this->acl->get_method_permission($_SESSION['groups_id'], 'courses', 'p_edit'))
            {
                echo '<p>'.sprintf(lang('manage_acl_permission_no'), lang('manage_acl_edit')).'</p>';exit;
            }

            $id                     = (int) $this->input->post('id');
            $result                 = $this->courses_model->get_courses_by_id($id);

            if(empty($result))
            {
                $this->session->set_flashdata('message', sprintf(lang('alert_not_found'), lang('menu_course')));
                echo json_encode(array(
                                        'flag'  => 0, 
                                        'msg'   => $this->session->flashdata('message'),
                                        'type'  => 'fail',
                                    ));exit;
            }
        }
        else
        {
            if(!$this->acl->get_method_permission($_SESSION['groups_id'], 'courses', 'p_add'))
            {
                echo '<p>'.sprintf(lang('manage_acl_permission_no'), lang('manage_acl_add')).'</p>';exit;
            }
        }

        /* Validate form input */
        $this->form_validation
        ->set_rules('title', lang('common_title'), 'trim|required|alpha_dash_spaces|max_length[256]')
        ->set_rules('description', lang('common_description'), 'trim')
        ->set_rules('featured', lang('common_featured'), 'required|in_list[0,1]')
        ->set_rules('status', lang('common_status'), 'required|in_list[0,1]')
        ->set_rules('meta_title', lang('common_meta_title'), 'trim|max_length[128]')
        ->set_rules('meta_tags', lang('common_meta_tags'), 'trim|max_length[256]')
        ->set_rules('meta_description', lang('common_meta_description'), 'trim')
        ->set_rules('category[]', lang('courses_category'), 'required|is_natural_no_zero', array('is_natural_no_zero'=>lang('courses_select_category')));

        // update courses image
        if(! empty($_FILES['images']['name'][0])) // if image 
        {
            $files         = array('folder'=>'courses/images', 'input_file'=>'images');
            
            // Remove old image
            if($id)
                if(!empty($result->images))
                    $this->file_uploads->remove_files('./upload/'.$files['folder'].'/', json_decode($result->images));

            // update courses image            
            $filenames     = $this->file_uploads->upload_files($files);
            // through image upload error
            if(!empty($filenames['error']))
                $this->form_validation->set_rules('image_error', lang('common_images'), 'required', array('required'=>$filenames['error']));
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

        // data to insert in db table
        $data                       = array();
        $data['title']              = strtolower($this->input->post('title'));
        $data['description']        = $this->input->post('description');
        $data['meta_title']         = $this->input->post('meta_title');
        $data['meta_tags']          = strtolower($this->input->post('meta_title'));
        $data['meta_description']   = $this->input->post('meta_description');
        $data['featured']           = $this->input->post('featured');    
        $data['status']             = $this->input->post('status');    

        // pick the last selected category
        $data['course_categories_id']= count($this->input->post('category'))-1;
        $data['course_categories_id']= $this->input->post('category')[$data['course_categories_id']];
        
        if(!empty($filenames) && !isset($filenames['error']))
            $data['images']             = json_encode($filenames);
        
        $flag                           = $this->courses_model->save_courses($data, $id);

        if($flag)
        {
            if($id)
                $this->session->set_flashdata('message', sprintf(lang('alert_update_success'), lang('menu_course')));
            else
                $this->session->set_flashdata('message', sprintf(lang('alert_insert_success'), lang('menu_course')));

            echo json_encode(array(
                                    'flag'  => 1, 
                                    'msg'   => $this->session->flashdata('message'),
                                    'type'  => 'success',
                                ));
            exit;
        }
        
        if($id)
            $this->session->set_flashdata('error', sprintf(lang('alert_update_fail'), lang('menu_course')));
        else
            $this->session->set_flashdata('error', sprintf(lang('alert_insert_fail'), lang('menu_course')));

        echo json_encode(array(
                                'flag'  => 0, 
                                'msg'   => $this->session->flashdata('message'),
                                'type'  => 'fail',
                            ));
        exit;
    }

    /**
     * view
     */
    public function view($id)
    {
        if(!$this->acl->get_method_permission($_SESSION['groups_id'], 'courses', 'p_view'))
        {
            $this->session->set_flashdata('error', sprintf(lang('manage_acl_permission_no'), lang('menu_course').' '.lang('manage_acl_view')));
            redirect($this->uri->segment(1).'/dashboard');
        }
        
        /* Initialize Assets */
        $data = $this->includes;

        /* Get Data */
        $id         = (int) $id;
        $result     = $this->courses_model->get_courses_by_id($id);

        if(empty($result))
        {
            $this->session->set_flashdata('error', lang('courses_not_found'));
            /* Redirect */
            redirect('admin/courses');            
        }

        // render category levels for current category
        $categories_levels              = $this->courses_model->get_courses_levels($result->course_categories_id);

        $data['category']               = '';
        for($i = (count($categories_levels)-1); $i >= 0; $i--)
        {
            $data['category']          .= '<a href="'.site_url('admin/categories/view/').$categories_levels[$i]['id'].'" target="_blank">'.$categories_levels[$i]['title'].'</a>';
            if($i != 0) 
                $data['category']      .= ' -> ';
        }

        $data['courses']                = $result;

        /* Load Template */
        $content['content']    = $this->load->view('admin/courses/view', $data, TRUE);
        $this->load->view($this->template, $content);
    }

     /**
     * status_update
     */
    public function status_update()
    {
        if(!$this->acl->get_method_permission($_SESSION['groups_id'], 'courses', 'p_edit'))
        {
            echo '<p>'.sprintf(lang('manage_acl_permission_no'), lang('manage_acl_edit')).'</p>';exit;
        }
        /* Validate form input */
        $this->form_validation
        ->set_rules('id', sprintf(lang('alert_id'), lang('menu_course')), 'required|numeric')
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
        $data['status']             = $this->input->post('status');

        if(empty($id))
        {
            $this->session->set_flashdata('message', sprintf(lang('alert_not_found'), lang('menu_course')));
            echo json_encode(array(
                                    'flag'  => 0, 
                                    'msg'   => $this->session->flashdata('message'),
                                    'type'  => 'fail',
                                ));
            exit;
        }
        
        $flag                       = $this->courses_model->save_courses($data, $id);

        if($flag)
        {
            echo json_encode(array(
                                    'flag'  => 1, 
                                    'msg'   => sprintf(lang('alert_status_success'), lang('menu_course')),
                                    'type'  => 'success',
                                ));
            exit;
        }

        echo json_encode(array(
                            'flag'  => 0, 
                            'msg'   => sprintf(lang('alert_status_fail'), lang('menu_course')),
                            'type'  => 'fail',
                        ));
        exit;
    }

    /**
     * featured_update
     */
    public function featured_update()
    {
        if(!$this->acl->get_method_permission($_SESSION['groups_id'], 'courses', 'p_edit'))
        {
            echo '<p>'.sprintf(lang('manage_acl_permission_no'), lang('manage_acl_edit')).'</p>';exit;
        }
        /* Validate form input */
        $this->form_validation
        ->set_rules('id', sprintf(lang('alert_id'), lang('menu_course')), 'required|numeric')
        ->set_rules('featured', lang('common_featured'), 'required|in_list[0,1]');

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
        $data['featured']             = $this->input->post('featured');

        if(empty($id))
        {
            $this->session->set_flashdata('message', sprintf(lang('alert_not_found'), lang('menu_course')));
            echo json_encode(array(
                                    'flag'  => 0, 
                                    'msg'   => $this->session->flashdata('message'),
                                    'type'  => 'fail',
                                ));
            exit;
        }
        
        $flag                       = $this->courses_model->save_courses($data, $id);

        if($flag)
        {
            echo json_encode(array(
                                    'flag'  => 1, 
                                    'msg'   => $data['featured'] ? sprintf(lang('alert_featured_added'), lang('menu_course')) : sprintf(lang('alert_featured_removed'), lang('menu_course')),
                                    'type'  => 'success',
                                ));
            exit;
        }

        echo json_encode(array(
                            'flag'  => 0, 
                            'msg'   => sprintf(lang('alert_featured_fail'), lang('menu_course')),
                            'type'  => 'fail',
                        ));
        exit;
    }


    /**
     * delete
     */
    public function delete()
    {
        if(!$this->acl->get_method_permission($_SESSION['groups_id'], 'courses', 'p_delete'))
        {
            echo '<p>'.sprintf(lang('manage_acl_permission_no'), lang('manage_acl_delete')).'</p>';exit;
        }
        /* Validate form input */
        $this->form_validation->set_rules('id', sprintf(lang('alert_id'), lang('menu_course')), 'required|numeric');

        /* Data */
        $id                     = (int) $this->input->post('id');
        $result                 = $this->courses_model->get_courses_by_id($id);

        if(empty($result))
        {
            echo json_encode(array(
                                    'flag'  => 0, 
                                    'msg'   => sprintf(lang('alert_not_found') ,lang('menu_course')),
                                    'type'  => 'fail',
                                ));exit;
        }
        
        $flag                   = $this->courses_model->delete_courses($id, $result->title);
        
        if($flag)
        {
            
            // Remove courses images
            if(!empty($result->images))
                $this->file_uploads->remove_files('./upload/courses/', json_decode($result->images));

            echo json_encode(array(
                                    'flag'  => 1, 
                                    'msg'   => sprintf(lang('alert_delete_success'), lang('menu_course')),
                                    'type'  => 'success',
                                ));exit;
        }
        
        echo json_encode(array(
                                    'flag'  => 0, 
                                    'msg'   => sprintf(lang('alert_delete_fail'), lang('menu_course')),
                                    'type'  => 'fail',
                                ));
        exit;
    }


    /**
     * get_course_categories_levels
     */
    public function get_course_categories_levels()
    {
        /* Validate form input */
        $this->form_validation->set_rules('category_id', sprintf(lang('alert_id') ,lang('courses_category')), 'required|is_natural_no_zero', array('is_natural_no_zero'=>'<span class="loader text-danger">*'.lang('courses_select_category').'</span>'));

        if($this->form_validation->run() === FALSE)
        {
            echo validation_errors();exit;
        }

        $category_id        = $this->input->post('category_id');

        /* Data */
        $course_levels       = $this->courses_model->get_course_categories_levels($category_id);

        if(empty($course_levels))
        {
            echo FALSE;exit;
        }

        $gen_html           = '<select name="category[]" class="show-tick form-control parent" data-live-search="true">
                                <option value="" selected="selected">-- '.lang('courses_sub_category').' --</option>';

        foreach($course_levels as $val)
        {
            $gen_html .= '<option value="'.$val->id.'">'.$val->title.'</option>';
        }

        $gen_html          .= '</select>';
        
        echo $gen_html;exit;
    }

}

/* CLasses controller ends */