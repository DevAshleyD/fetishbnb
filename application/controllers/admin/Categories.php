<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Categories Controller
 *
 * This class handles course_categories module functionality
 *
 * @package     classiebit
 * @author      prodpk
*/

class Categories extends Admin_Controller {

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();

        $this->load->model('admin/categories_model');

        /* Page Title */
        $this->set_title( lang('menu_course_categories') );
    }

    /**
     * index
     */
    function index()
    {
        if(!$this->acl->get_method_permission($_SESSION['groups_id'], 'categories', 'p_view'))
        {
            $this->session->set_flashdata('error', sprintf(lang('manage_acl_permission_no'), lang('menu_course_category').' '.lang('manage_acl_view')));
            redirect($this->uri->segment(1).'/dashboard');
        }

        /* Initialize assets */
        $this->include_index_plugins();
        $data = $this->includes;

        // Table Header
        $data['t_headers']  = array(
                                '#',
                                lang('common_title'),
                                lang('course_categories_level'),
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

        $table              = 'course_categories';        
        $columns            = array(
                                "$table.id",
                                "$table.title",
                                "$table.parent_id",
                                "$table.status",
                                "$table.date_updated",
                                "(SELECT cc.title FROM $table cc WHERE cc.id = $table.parent_id) category_name",
                            );
        $columns_order      = array(
                                "#",
                                "$table.title",
                                "$table.parent_id",
                                "$table.date_updated",
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
            $row[]          = $val->category_name ? $val->category_name : lang('course_categories_level_top');
            $row[]          = date('g:iA d/m/y', strtotime($val->date_updated));
            $row[]          = status_switch($val->status, $val->id);
            $row[]          = action_buttons('categories', $val->id, mb_substr($val->title, 0, 20, 'utf-8'), lang('menu_course_category'));
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
                                "tinymce/tinymce.min.js",
                            ), 'admin')
             ->add_js_theme( "pages/course_categories/form_i18n.js", TRUE );
        $data                       = $this->includes;

        // in case of edit
        $id                         = (int) $id;
        if($id)
        {
            $result                 = $this->categories_model->get_course_categories_by_id($id);

            if(empty($result))
            {
                $this->session->set_flashdata('error', sprintf(lang('alert_not_found') ,lang('menu_course_category')));
                redirect($this->uri->segment(1).'/'.$this->uri->segment(2));
            }

            // hidden field in case of update
            $data['id']             = $result->id; 
            
            // current image & icon
            $data['c_image'] = $result->image;
            $data['c_icon']  = $result->icon;

            // for category dropdown
            $data['parent_id']      = $result->parent_id;

            // no parent can be a child of their own child
            $sub_categories         = $this->categories_model->get_course_categories_levels_status_update($id);
        }

        // render category dropdown
        $course_categories_levels    = $this->categories_model->get_course_categories_dropdown(!empty($result->id) ? $result->id : 0, !empty($sub_categories) ? $sub_categories : array());
        $data['levels'][0]          = 'Top Level';

        foreach($course_categories_levels as $val)
            $data['levels'][$val->id] = $val->title;

        $data['level'] = array(
            'name'      => 'level',
            'options'   => $data['levels'],
            'id'        => 'level',
            'class'     => 'form-control show-tick ',
            'data-live-search'=>"true",
            'selected'  => $this->form_validation->set_value('level', !empty($result->parent_id) ? $result->parent_id : ''),
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
        $data['image'] = array(
            'name'      => 'image',
            'id'        => 'image',
            'type'      => 'file',
            'class'     => 'form-control',
            'accept'    => 'image/*',
        );
        $data['icon'] = array(
            'name'      => 'icon',
            'id'        => 'icon',
            'type'      => 'file',
            'class'     => 'form-control',
            'accept'    => 'image/*',
        );
        $data['status'] = array(
            'name'      => 'status',
            'id'        => 'status',
            'class'     => 'form-control',
            'options'   => array('1' => lang('common_status_active'), '0' => lang('common_status_inactive')),
            'selected'  => $this->form_validation->set_value('status', !empty($result->status) ? $result->status : 0),
        );

        /* Load Template */
        $content['content']    = $this->load->view('admin/course_categories/form', $data, TRUE);
        $this->load->view($this->template, $content);
    }

    /**
     * save
    */
    public function save()
    {
        $id = NULL;

        // Unique columns
        $result         = (object) array();
        $result->title  = '';

        if(! empty($_POST['id']))
        {

            if(!$this->acl->get_method_permission($_SESSION['groups_id'], 'categories', 'p_edit'))
            {
                echo '<p>'.sprintf(lang('manage_acl_permission_no'), lang('manage_acl_edit')).'</p>';exit;
            }

            $id                     = (int) $this->input->post('id');

            $result                 = $this->categories_model->get_course_categories_by_id($id);

            if(empty($result))
            {
                $this->session->set_flashdata('message', sprintf(lang('alert_not_found'), lang('menu_course_category')));
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
            if(!$this->acl->get_method_permission($_SESSION['groups_id'], 'categories', 'p_add'))
            {
                echo '<p>'.sprintf(lang('manage_acl_permission_no'), lang('manage_acl_add')).'</p>';exit;
            }
        }

        /* Validate form input */
        $this->form_validation
        ->set_rules('title', lang('common_title'), 'trim|required|alpha_dash_spaces|min_length[2]|max_length[128]')
        ->set_rules('description', lang('common_description'), 'trim')
        ->set_rules('status', lang('common_status'), 'required|in_list[0,1]')
        ->set_rules('level', lang('course_categories_level'), 'required|numeric');

        // Check duplicacy for unique columns
        if($this->input->post('title') !== $result->title)
            $this->form_validation->set_rules('title', lang('common_title'), 'trim|required|alpha_dash_spaces|min_length[2]|max_length[128]|is_unique[course_categories.title]');
        
        if(! empty($_FILES['image']['name'])) // if image 
        {
            $file_image         = array('folder'=>'course_categories/images', 'input_file'=>'image');
            // Remove old image
            if($id)
                $this->file_uploads->remove_file('./upload/'.$file_image['folder'].'/', $result->image);
            // update course_categories image            
            $filename_image     = $this->file_uploads->upload_file($file_image);
            // through image upload error
            if(!empty($filename_image['error']))
                $this->form_validation->set_rules('image_error', lang('common_image'), 'required', array('required'=>$filename_image['error']));
        }

        if(! empty($_FILES['icon']['name'])) // if icon
        {
            $file_icon         = array('folder'=>'course_categories/icons', 'input_file'=>'icon');
            // Remove old icon
            if($id)
                $this->file_uploads->remove_file('./upload/'.$file_icon['folder'].'/', $result->icon);
            // update course_categories icon            
            $filename_icon     = $this->file_uploads->upload_file($file_icon);
            // through icon upload error
            if(!empty($filename_icon['error']))
                $this->form_validation->set_rules('icon_error', lang('course_categories_icon'), 'required', array('required'=>$filename_icon['error']));
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
        $data['parent_id']          = $this->input->post('level');
        $data['status']             = $this->input->post('status');    

        if(! empty($filename_image) && ! isset($filename_image['error']))
            $data['image']          = $filename_image;

        if(! empty($filename_icon) && ! isset($filename_icon['error']))
            $data['icon']          = $filename_icon;

        // change the status of all belonging sub-categories
        if($id) // in case of editing
            if($result->status !== $data['status'])
            {
                $sub_categories         = $this->categories_model->get_course_categories_levels_status_update($id);
                // insert top-level category id at first
                array_unshift($sub_categories, array('id'=>$id));
                // append status row in all dimensions
                foreach($sub_categories as $key => $val)
                    $sub_categories[$key]['status'] = $data['status'];

                $flag                   = $this->categories_model->save_course_categories_batch($sub_categories);
            }
        
        $flag                           = $this->categories_model->save_course_categories($data, $id);

        if($flag)
        {
            if($id)
                $this->session->set_flashdata('message', sprintf(lang('alert_update_success'), lang('menu_course_category')));
            else
                $this->session->set_flashdata('message', sprintf(lang('alert_insert_success'), lang('menu_course_category')));

            echo json_encode(array(
                                    'flag'  => 1, 
                                    'msg'   => $this->session->flashdata('message'),
                                    'type'  => 'success',
                                ));
            exit;
        }
        
        if($id)
            $this->session->set_flashdata('error', sprintf(lang('alert_update_fail'), lang('menu_course_category')));
        else
            $this->session->set_flashdata('error', sprintf(lang('alert_insert_fail'), lang('menu_course_category')));

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
    public function view($id = NULL)
    {
        if(!$this->acl->get_method_permission($_SESSION['groups_id'], 'categories', 'p_view'))
        {
            $this->session->set_flashdata('error', sprintf(lang('manage_acl_permission_no'), lang('menu_course_category').' '.lang('manage_acl_view')));
            redirect($this->uri->segment(1).'/dashboard');
        }
        
        /* Initialize Assets */
        $data = $this->includes;

        /* Get Data */
        $id                     = (int) $id;
        $result                 = $this->categories_model->get_course_categories_by_id($id);

        if(empty($result))
        {
            $this->session->set_flashdata('error', sprintf(lang('alert_not_found') ,lang('menu_course_category')));
            /* Redirect */
            redirect('admin/categories');            
        }

        $data['course_categories']   = $result;

        /* Load Template */
        $content['content']         = $this->load->view('admin/course_categories/view', $data, TRUE);
        $this->load->view($this->template, $content);
    }

    
     /**
     * status_update
     */
    public function status_update()
    {
        if(!$this->acl->get_method_permission($_SESSION['groups_id'], 'categories', 'p_edit'))
        {
            echo '<p>'.sprintf(lang('manage_acl_permission_no'), lang('manage_acl_edit')).'</p>';exit;
        }

        /* Validate form input */
        $this->form_validation
        ->set_rules('id', sprintf(lang('alert_id'), lang('menu_course_category')), 'required|numeric')
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
            $this->session->set_flashdata('message', sprintf(lang('alert_not_found'), lang('menu_course_category')));
            echo json_encode(array(
                                    'flag'  => 0, 
                                    'msg'   => $this->session->flashdata('message'),
                                    'type'  => 'fail',
                                ));
            exit;
        }
        
        $flag                       = $this->categories_model->save_course_categories($data, $id);

        if($flag)
        {
            // For Course Categories
            $sub_categories             = $this->categories_model->get_course_categories_levels_status_update($id);
            // insert top-level category id at first
            array_unshift($sub_categories, array('id'=>$id));
            // append status row in all dimensions
            foreach($sub_categories as $key => $val) 
                $sub_categories[$key]['status'] = $data['status'];

            $this->categories_model->save_course_categories_batch($sub_categories);

            echo json_encode(array(
                                    'flag'  => 1, 
                                    'msg'   => sprintf(lang('alert_status_success'), lang('menu_course_category')).'<br><small>'.lang('course_categories_status_alert').'</small>',
                                    'type'  => 'success',
                                ));
            exit;
        }

        echo json_encode(array(
                            'flag'  => 0, 
                            'msg'   => sprintf(lang('alert_status_fail'), lang('menu_course_category')),
                            'type'  => 'fail',
                        ));
        exit;
        
    }

     /**
     * delete
     */
    public function delete()
    {
        if(!$this->acl->get_method_permission($_SESSION['groups_id'], 'categories', 'p_delete'))
        {
            echo '<p>'.sprintf(lang('manage_acl_permission_no'), lang('manage_acl_delete')).'</p>';exit;
        }

        /* Validate form input */
        $this->form_validation->set_rules('id', sprintf(lang('alert_id'), lang('menu_course_category')), 'required|numeric');

        /* Get Data */
        $id                 = (int) $this->input->post('id');
        $result             = $this->categories_model->get_course_categories_by_id($id);

        if(empty($result))
        {
            echo json_encode(array(
                                    'flag'  => 0, 
                                    'msg'   => sprintf(lang('alert_not_found') ,lang('menu_course_category')),
                                    'type'  => 'fail',
                                ));exit;
        }

        // check if any sub-categories exists
        $count_sub_cat      = $this->categories_model->count_sub_categories($result->id);

        if($count_sub_cat)
        {
            echo json_encode(array(
                                'flag'  => 0, 
                                'msg'   => sprintf(lang('course_categories_delete_alert'), $result->title),
                                'type'  => 'fail',
                            ));exit;
        }
        
        // check if any courses of the category running
        $count_courses      = $this->categories_model->count_course_categories_courses($result->id);

        if($count_courses)
        {
            echo json_encode(array(
                                'flag'  => 0, 
                                'msg'   => sprintf(lang('course_categories_courses_delete_alert'), $result->title),
                                'type'  => 'fail',
                            ));exit;
        }

        $flag                   = $this->categories_model->delete_course_categories($id, $result);

        if($flag)
        {
            
            // Remove image
            if(!empty($result->image))
                $this->file_uploads->remove_file('./upload/course_categories/images/', $result->image);
            // Remove icon
            if(!empty($result->icon))
                $this->file_uploads->remove_file('./upload/course_categories/icons/', $result->icon);

            echo json_encode(array(
                                    'flag'  => 1, 
                                    'msg'   => sprintf(lang('alert_delete_success'), lang('menu_course_category')),
                                    'type'  => 'success',
                                ));exit;
        }
        
        echo json_encode(array(
                                    'flag'  => 0, 
                                    'msg'   => sprintf(lang('alert_delete_fail'), lang('menu_course_category')),
                                    'type'  => 'fail',
                                ));
        exit;
    }

}

/* Categories controller ends */