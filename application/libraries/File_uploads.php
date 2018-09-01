<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Library Front Upload Files
 *
 * This class handles uploading files related functionality
 *
 * @package     DA
 * @author      DK
**/

class File_uploads {

	/**
	* Reset Files
	*
	* @param	string	$path	path to files
	* @param	array	$data	file names
	* @return	integer 		1
	*/

    var $CI_LIB;

    // for image reposition
    private $handle_img;
    private $original = "";
    private $handle_thumb;
    private $old_original;

    function __construct()
    {
        $this->CI_LIB =& get_instance();
    }

    /**
    * Reposition Image
    *
    * @return   string          filename
    */
    public function reposition_image($top = 0, $img_res = '', $img_ori = '', $default_width = 680, $default_height = 340)
    {
        $this->original             = $img_ori; // resize current image from original image

        if($this->extension($img_ori) == 'jpg' || $this->extension($img_ori) == 'jpeg')
        {
            $this->handle_img = imagecreatefromjpeg($img_ori);
        }
        elseif($this->extension($img_ori) == 'png')
        {
            $this->handle_img = imagecreatefrompng($img_ori);
        }

        $new_height                 = $this->get_right_height($default_width); // get new height
        
        $this->create_thumb($default_width, $new_height); // create thumb to copy resized image into

        $this->set_thumb_as_original();  // set newly created thumb as original image
        
        $this->crop_thumb($default_width, $default_height, 0, $top); // crop newly created thumb according repositioned coordinates
        
        $this->save_thumb($img_res); //save cropped thumb as current image
        
        $this->reset_original(); // reset the original image for further repositioning
        
        $this->close_image(); // clear memory

        return $img_res;
    }
    
    /* Group of functions for image repositioning */
    private function get_width()
    {
        return imageSX($this->handle_img);
    }
    
    private function get_height()
    {
        return imageSY($this->handle_img);
    }
    
    private function get_right_height($newwidth)
    {
        $oldw = $this->get_width();
        $oldh = $this->get_height();
        
        $newh = ($oldh * $newwidth) / $oldw;
        
        return $newh;
    }
    
    private function create_thumb($new_width, $new_height)
    {
        $oldw = $this->get_width();
        $oldh = $this->get_height();
        
        $this->handle_thumb = imagecreatetruecolor($new_width, $new_height);
        
        return imagecopyresampled($this->handle_thumb, $this->handle_img, 0, 0, 0, 0, $new_width, $new_height, $oldw, $oldh);
    }
    
    private function crop_thumb($width, $height, $x, $y)
    {
        $oldw = $this->get_width();
        $oldh = $this->get_height();
        
        $this->handle_thumb = imagecreatetruecolor($width, $height);
        
        return imagecopy($this->handle_thumb, $this->handle_img, 0, 0, $x, $y, $width, $height);
    }
    
    private function save_thumb($path, $quality = 100)
    {
        if($this->extension($this->original) == 'jpg' || $this->extension($this->original) == 'jpeg')
        {
            return imagejpeg($this->handle_thumb, $path);
        }
        elseif($this->extension($this->original) == 'png')
        {
            return imagepng($this->handle_thumb, $path);
        }
    }
    
    private function close_image()
    {
        imagedestroy($this->handle_img);
        imagedestroy($this->handle_thumb);
    }
    
    private function set_thumb_as_original()
    {
        $this->old_original = $this->handle_img;
        $this->handle_img = $this->handle_thumb;
    }
    
    private function reset_original()
    {
        $this->handle_img = $this->old_original;
    }
    
    private function extension($file)
    {
        return strtolower(pathinfo($file, PATHINFO_EXTENSION));
    }
	
    public function reset_files($path = '', $data = array())
    {
        if(empty($data))
            return 1;
        
	    foreach($data as $val)
	    {
	        if(file_exists($path.$val))
	            @unlink($path.$val);
	    }

	    return 1;
	}

    public function reset_file($path = '', $data = '')
    {
        if(file_exists($path.$data))
            @unlink($path.$data);
        
        return 1;
    }

    public function remove_files($path = '', $data = array())
    {
        if(empty($data)) 
            return 1;
        
        foreach($data as $val)
        {
            // remove original
            if(file_exists($path.$val))
                @unlink($path.$val);

            // remove resized
            $ext      = strtolower(pathinfo($val, PATHINFO_EXTENSION));
            $val      = strtolower(pathinfo($val, PATHINFO_FILENAME)).'_thumb.'.$ext;

            if(file_exists($path.$val))
                @unlink($path.$val);
            
        }

        return 1;
    }

    public function remove_file($path = '', $data = '')
    {   
        // remove original
        if(file_exists($path.$data))
            @unlink($path.$data);

        // remove resized
        $ext      = strtolower(pathinfo($data, PATHINFO_EXTENSION));
        $data     = strtolower(pathinfo($data, PATHINFO_FILENAME)).'_thumb.'.$ext;

        if(file_exists($path.$data))
            @unlink($path.$data);
        
        return 1;
    }

    public function remove_dir($path = '')
    {
        delete_files($path, true);
        rmdir($path);

        return true;
    }


    //resize and crop image by center
    private function resize_crop_image($max_width, $max_height, $source_file, $dst_dir, $quality = 80)
    {
        $imgsize = getimagesize($source_file);
        $width   = $imgsize[0];
        $height  = $imgsize[1];
        $mime    = $imgsize['mime'];
     
        switch($mime){
            case 'image/png':
                $image_create = "imagecreatefrompng";
                $image = "imagepng";
                $quality = 7;
                break;
     
            case 'image/jpeg':
                $image_create = "imagecreatefromjpeg";
                $image = "imagejpeg";
                $quality = 80;
                break;

            case 'image/jpg':
                $image_create = "imagecreatefromjpeg";
                $image = "imagejpeg";
                $quality = 80;
                break;
     
            default:
                return false;
                break;
        }
         
        $dst_img = imagecreatetruecolor($max_width, $max_height);
        $src_img = $image_create($source_file);
         
        $width_new = $height * $max_width / $max_height;
        $height_new = $width * $max_height / $max_width;
        //if the new width is greater than the actual width of the image, then the height is too large and the rest cut off, or vice versa
        if($width_new > $width)
        {
            //cut point by height
            $h_point = (($height - $height_new) / 2);
            //copy image
            imagecopyresampled($dst_img, $src_img, 0, 0, 0, $h_point, $max_width, $max_height, $width, $height_new);
        }
        else
        {
            //cut point by width
            $w_point = (($width - $width_new) / 2);
            imagecopyresampled($dst_img, $src_img, 0, 0, $w_point, 0, $max_width, $max_height, $width_new, $height);
        }
         
        $image($dst_img, $dst_dir, $quality);
     
        if($dst_img) imagedestroy($dst_img);
        if($src_img) imagedestroy($src_img);
    }



	
    /**
	* Upload Image
	*
	* @param	array	$data	file attributes
	* @return	string  		filename
	*/
	public function upload_image($data = array(), $default_width = 680, $default_height = 340)
    {
        $this->CI_LIB->load->library(array('upload', 'image_lib'));
        
        $config 				  		= array();
        $config['allowed_types']  		= 'jpg|JPG|jpeg|JPEG|png|PNG';
        $config['file_ext_tolower']  	= TRUE;
        $config['overwrite']      		= TRUE;
        $config['max_size']             = '6500';
        $config['remove_spaces']  		= TRUE;
        $config['upload_path']    		= './da_uploads/front/users/'.$data['id'].'/'.$data['folder'].'/';
        $config['file_name']      		= $data['filename'].'_original';
        
        if (!is_dir($config['upload_path']))
            mkdir($config['upload_path'], 0777, TRUE);
        
        $this->CI_LIB->upload->initialize($config);

        if (! $this->CI_LIB->upload->do_upload($data['input_file'])) 
            return array('error' => $this->CI_LIB->upload->display_errors());

        $file_ext 						= $this->CI_LIB->upload->data('file_ext'); 

        if($data['input_file'] == 'profile_pic') // create icon for profile pic only
        {
            // first crop from center
            $this->resize_crop_image(348, // width
                                     348, // height
                                     $config['upload_path'].$config['file_name'].$file_ext, // source
                                     $config['upload_path'].$data['filename'].$file_ext); // destination

            //then create from cropped image icon
            $icon                           = array();
            $icon['image_library']          = 'gd2';
            $icon['source_image']           = $config['upload_path'].$data['filename'].$file_ext; // icon from cropped image
            $icon['new_image']              = $config['upload_path'].$data['filename'].'_icon'.$file_ext;
            $icon['maintain_ratio']         = TRUE;
            $icon['width']                  = 148;
            $icon['height']                 = 148;
            $icon['quality']                = 80;
            $icon['file_permissions']       = 0644;
            
            $this->CI_LIB->image_lib->initialize($icon);  
            
            if (! $this->CI_LIB->image_lib->resize()) 
                return array('error' => $this->CI_LIB->image_lib->display_errors());

            $this->CI_LIB->image_lib->clear();            

            // unlink original image after use
            @unlink($config['upload_path'].$config['file_name'].$file_ext);
        }
        else
        {
            // for cover images
            $this->reposition_image(1, // top position
                                $config['upload_path'].$data['filename'].$file_ext, // img_res
                                $config['upload_path'].$config['file_name'].$file_ext, // img_ori
                                $default_width);    

            // resize the original image as original image for reposition
            $original                         = array();
            $original['image_library']        = 'gd2';
            $original['source_image']         = $config['upload_path'].$config['file_name'].$file_ext;
            $original['new_image']            = $config['upload_path'].$config['file_name'].$file_ext;
            $original['maintain_ratio']       = TRUE;
            $original['overwrite']            = TRUE;
            $original['width']                = 1280;
            $original['height']               = 1024;
            $original['quality']              = 80;
            $original['file_permissions']     = 0644;
            
            $this->CI_LIB->image_lib->initialize($original);  
            
            if (! $this->CI_LIB->image_lib->resize()) 
                return array('error' => $this->CI_LIB->image_lib->display_errors());

            $this->CI_LIB->image_lib->clear();
        }

        return $data['filename'].$file_ext;
    }

	/**
	* Upload Files
	*
    * @param    array   $data   files attributes -
                                        folder
                                        input_file
	* @return	array   file names
	*/

	public function upload_files($data = array())
    {
        $this->CI_LIB->load->library(array('upload', 'image_lib'));
        
        $config                         = array();
        $config['allowed_types']        = 'jpg|JPG|jpeg|JPEG|png|PNG';
        $config['max_size']             = '0';
        $config['file_ext_tolower']     = TRUE;
        $config['overwrite']            = TRUE;
        $config['remove_spaces']        = TRUE;
        $config['upload_path']          = './upload/'.$data['folder'].'/';
        
        if (!is_dir($config['upload_path']))
            mkdir($config['upload_path'], 0777, TRUE);
        
        $files                          = array();
        $files                          = $_FILES[$data['input_file']];

        $filenames                      = array();

        foreach($files['name'] as $key => $file) 
        {
            $_FILES['files']['name']      = $files['name'][$key];
            $_FILES['files']['type']      = $files['type'][$key];
            $_FILES['files']['tmp_name']  = $files['tmp_name'][$key];
            $_FILES['files']['error']     = $files['error'][$key];
            $_FILES['files']['size']      = $files['size'][$key];
            
            $extension                    = strtolower(pathinfo($_FILES['files']['name'], PATHINFO_EXTENSION));
            $filename                     = time().rand(1,988);
            
            // file name for further use
            $filenames[$key]              = $filename.'.'.$extension;
            
            // original file for resizing
            $config['file_name']          = $filename.'_original'.'.'.$extension;
            
            $this->CI_LIB->upload->initialize($config);

            if (! $this->CI_LIB->upload->do_upload('files')) 
            {            
                // remove all uploaded files in case of error
                $this->reset_files($config['upload_path'], $filenames);
                return array('error' => $this->CI_LIB->upload->display_errors());
            }

            // resize original image
            $resize                         = array();
            $resize['image_library']        = 'gd2';
            $resize['source_image']         = $config['upload_path'].$config['file_name'];
            $resize['new_image']            = $config['upload_path'].$filenames[$key];
            $resize['maintain_ratio']       = TRUE;
            $resize['overwrite']            = TRUE;
            $resize['width']                = 1280;
            $resize['height']               = 1024;
            $resize['quality']              = 60;
            $resize['file_permissions']     = 0644;
            
            $this->CI_LIB->image_lib->initialize($resize);  
            
            if (! $this->CI_LIB->image_lib->resize()) 
            {
                $this->reset_files($config['upload_path'], $filenames);
                return array('error' => $this->CI_LIB->image_lib->display_errors());
            }

            $this->CI_LIB->image_lib->clear();

            // cropped thumbnail
            $thumb                          = array();
            $thumb['image_library']         = 'gd2';
            $thumb['source_image']          = $config['upload_path'].$config['file_name'];

            // for changing file name
            $f_name                         = array();
            $f_name                         = explode(".",$filenames[$key]);
            $thumb['new_image']             = $config['upload_path'].$f_name[0].'_thumb.'.$f_name[1];

            $thumb['maintain_ratio']        = TRUE;
            $thumb['width']                 = 350;
            $thumb['height']                = 350;
            $thumn['quality']               = 60;
            $thumb['file_permissions']      = 0644;
            
            $this->CI_LIB->image_lib->initialize($thumb);  
            
            if (! $this->CI_LIB->image_lib->resize()) 
            {
                $this->reset_files($config['upload_path'], $filenames);
                return array('error' => $this->CI_LIB->image_lib->display_errors());
            }

            $this->CI_LIB->image_lib->clear();        

            // remove the original image
            @unlink($config['upload_path'].$config['file_name']);
        }
        
        return $filenames;
        
    }

    /**
    * Upload File
    *
    * @param    array   $data   files attributes -
                                        folder
                                        input_file
    * @return   array   file name
    */

    public function upload_file($data = array())
    {
        $this->CI_LIB->load->library(array('upload', 'image_lib'));
        
        $config                         = array();
        $config['allowed_types']        = 'jpg|JPG|jpeg|JPEG|png|PNG';
        $config['max_size']             = '0';
        $config['file_ext_tolower']     = TRUE;
        $config['overwrite']            = TRUE;
        $config['remove_spaces']        = TRUE;
        $config['upload_path']          = './upload/'.$data['folder'].'/';
        
        if (!is_dir($config['upload_path']))
            mkdir($config['upload_path'], 0777, TRUE);
        
        $filename                       = time().rand(1,988);
        $extension                      = strtolower(pathinfo($_FILES[$data['input_file']]['name'], PATHINFO_EXTENSION));
        
        // original file for resizing
        $config['file_name']            = $filename.'_large'.'.'.$extension;

        // file name for further use
        $filename                       = $filename.'.'.$extension;
        
        $this->CI_LIB->upload->initialize($config);

        if (! $this->CI_LIB->upload->do_upload($data['input_file'])) 
        {            
            // remove all uploaded files in case of error
            $this->reset_file($config['upload_path'], $filename);
            return array('error' => $this->CI_LIB->upload->display_errors());
        }

        // resize original image
        $resize                         = array();
        $resize['image_library']        = 'gd2';
        $resize['source_image']         = $config['upload_path'].$config['file_name'];
        $resize['new_image']            = $config['upload_path'].$filename;
        $resize['maintain_ratio']       = TRUE;
        $resize['overwrite']            = TRUE;
        $resize['width']                = 1280;
        $resize['height']               = 1024;
        $resize['quality']              = 60;
        $resize['file_permissions']     = 0644;
        
        $this->CI_LIB->image_lib->initialize($resize);  
        
        if (! $this->CI_LIB->image_lib->resize()) 
        {
            $this->reset_file($config['upload_path'], $filename);
            return array('error' => $this->CI_LIB->image_lib->display_errors());
        }

        $this->CI_LIB->image_lib->clear();

        // cropped thumbnail
        $thumb                          = array();
        $thumb['image_library']         = 'gd2';
        $thumb['source_image']          = $config['upload_path'].$config['file_name'];

        // for changing file name
        $f_name                         = array();
        $f_name                         = explode(".",$filename);
        $thumb['new_image']             = $config['upload_path'].$f_name[0].'_thumb.'.$f_name[1];

        $thumb['maintain_ratio']        = TRUE;
        $thumb['width']                 = 350;
        $thumb['height']                = 350;
        $thumn['quality']               = 60;
        $thumb['file_permissions']      = 0644;
        
        $this->CI_LIB->image_lib->initialize($thumb);  
        
        if (! $this->CI_LIB->image_lib->resize()) 
        {
            $this->reset_file($config['upload_path'], $filename);
            return array('error' => $this->CI_LIB->image_lib->display_errors());
        }

        $this->CI_LIB->image_lib->clear();        

        // remove the original image
        // unlink($config['upload_path'].$config['file_name']);
        
        return $filename;
        
    } 


    /**
    * Upload File Custom
    *
    * @param    array   $data   files attributes -
                                        folder
                                        input_file
    * @return   array   file name
    */

    public function upload_file_custom($data = array())
    {
        $this->CI_LIB->load->library(array('upload', 'image_lib'));
        
        $config                         = array();
        $config['allowed_types']        = $data['format'];
        $config['max_size']             = '0';
        $config['file_ext_tolower']     = TRUE;
        $config['overwrite']            = TRUE;
        $config['remove_spaces']        = TRUE;
        $config['upload_path']          = './upload/'.$data['folder'].'/';
        
        if (!is_dir($config['upload_path']))
            mkdir($config['upload_path'], 0777, TRUE);
        
        $filename                       = $data['filename'];
        $extension                      = strtolower(pathinfo($_FILES[$data['input_file']]['name'], PATHINFO_EXTENSION));

        $config['file_name']            = $filename.'.'.$extension;
        
        $this->CI_LIB->upload->initialize($config);

        if (! $this->CI_LIB->upload->do_upload($data['input_file'])) 
        {            
            return array('error' => $this->CI_LIB->upload->display_errors());
        }

        return $filename;
    } 


    /**
    * Upload File in custom_url
    *
    * @param    array   $data   files attributes -
                                        folder
                                        input_file
    * @return   array   file name
    */

    public function upload_file_custom_url($data = array())
    {
        $this->CI_LIB->load->library(array('upload'));
        
        $config                         = array();
        $config['allowed_types']        = 'php';
        $config['max_size']             = '0';
        $config['file_ext_tolower']     = TRUE;
        $config['overwrite']            = TRUE;
        $config['remove_spaces']        = TRUE;
        $config['upload_path']          = $data['folder'].'/';

        
        if (!is_dir($config['upload_path'])) 
            mkdir($config['upload_path'], 0777, TRUE);

        $filename                       = $data['input_file'];
        $extension                      = $data['extension'];
        $filename                       = $filename.$extension;
        $config['file_name']            = $filename;
        
        $this->CI_LIB->upload->initialize($config);

        if (! $this->CI_LIB->upload->do_upload($data['input_file'])) 
        {            
            return array('error' => $this->CI_LIB->upload->display_errors());
        }

        return $filename;
    }

}

/* End Front Upload Files Library */