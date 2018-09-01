<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Outputs an array in a user-readable JSON format
 *
 * @param array $array
 */
if ( ! function_exists('display_json'))
{
    function display_json($array)
    {
        $data = json_indent($array);

        header('Cache-Control: no-cache, must-revalidate');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Content-type: application/json');

        echo $data;
    }
}


/**
 * Convert an array to a user-readable JSON string
 *
 * @param array $array - The original array to convert to JSON
 * @return string - Friendly formatted JSON string
 */
if ( ! function_exists('json_indent'))
{
    function json_indent($array = array())
    {
        // make sure array is provided
        if (empty($array))
            return NULL;

        //Encode the string
        $json = json_encode($array);

        $result        = '';
        $pos           = 0;
        $str_len       = strlen($json);
        $indent_str    = '  ';
        $new_line      = "\n";
        $prev_char     = '';
        $out_of_quotes = true;

        for ($i = 0; $i <= $str_len; $i++)
        {
            // grab the next character in the string
            $char = substr($json, $i, 1);

            // are we inside a quoted string?
            if ($char == '"' && $prev_char != '\\')
            {
                $out_of_quotes = !$out_of_quotes;
            }
            // if this character is the end of an element, output a new line and indent the next line
            elseif (($char == '}' OR $char == ']') && $out_of_quotes)
            {
                $result .= $new_line;
                $pos--;

                for ($j = 0; $j < $pos; $j++)
                {
                    $result .= $indent_str;
                }
            }

            // add the character to the result string
            $result .= $char;

            // if the last character was the beginning of an element, output a new line and indent the next line
            if (($char == ',' OR $char == '{' OR $char == '[') && $out_of_quotes)
            {
                $result .= $new_line;

                if ($char == '{' OR $char == '[')
                {
                    $pos++;
                }

                for ($j = 0; $j < $pos; $j++)
                {
                    $result .= $indent_str;
                }
            }

            $prev_char = $char;
        }

        // return result
        return $result . $new_line;
    }
}


/**
 * Save data to a CSV file
 *
 * @param array $array
 * @param string $filename
 * @return bool
 */
if ( ! function_exists('array_to_csv'))
{
    function array_to_csv($array = array(), $filename = "export.csv")
    {
        $CI = get_instance();

        // disable the profiler otherwise header errors will occur
        $CI->output->enable_profiler(FALSE);

        if ( ! empty($array))
        {
            // ensure proper file extension is used
            if ( ! substr(strrchr($filename, '.csv'), 1))
            {
                $filename .= '.csv';
            }

            try
            {
                // set the headers for file download
                header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
                header("Last-Modified: " . gmdate("D, d M Y H:i:s") . "GMT");
                header("Cache-Control: no-cache, must-revalidate");
                header("Pragma: no-cache");
                header("Content-type: text/csv");
                header("Content-Description: File Transfer");
                header("Content-Disposition: attachment; filename={$filename}");

                $output = @fopen('php://output', 'w');

                // used to determine header row
                $header_displayed = FALSE;

                foreach ($array as $row)
                {
                    if ( ! $header_displayed)
                    {
                        // use the array keys as the header row
                        fputcsv($output, array_keys($row));
                        $header_displayed = TRUE;
                    }

                    // clean the data
                    $allowed = '/[^a-zA-Z0-9_ %\|\[\]\.\(\)%&-]/s';
                    foreach ($row as $key => $value)
                    {
                        $row[$key] = preg_replace($allowed, '', $value);
                    }

                    // insert the data
                    fputcsv($output, $row);
                }

                fclose($output);

            }
            catch (Exception $e) {}
        }

        exit;
    }
}


/**
 * Generates a random password
 *
 * @return string
 */
if ( ! function_exists('generate_random_password'))
{
    function generate_random_password()
    {
        $characters = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
        $pass = array();
        $alpha_length = strlen($characters) - 1;

        for ($i = 0; $i < 8; $i++)
        {
            $n = rand(0, $alpha_length);
            $pass[] = $characters[$n];
        }

        return implode($pass);
    }
}


/**
 * Retrieves list of language folders
 *
 * @return array
 */
if ( ! function_exists('get_languages'))
{
    function get_languages()
    {
        $CI = get_instance();

        if ($CI->session->languages)
        {
            return $CI->session->languages;
        }

        $CI->load->helper('directory');

        $language_directories = directory_map(APPPATH . '/language/', 1);

        if ( ! $language_directories)
        {
            $language_directories = directory_map(BASEPATH . '/language/', 1);
        }

        $languages = array();
        foreach ($language_directories as $language)
        {
            if (substr($language, -1) == "/" || substr($language, -1) == "\\")
            {
                $languages[substr($language, 0, -1)] = ucwords(str_replace(array('-', '_'), ' ', substr($language, 0, -1)));
            }
        }

        $CI->session->languages = $languages;

        return $languages;
    }
}


// ------------------------------------------------------------------------


/**
 * Convert name image.jpg to image_thumb.jpg
 *
 * @param string $image
 */
if ( ! function_exists('image_to_thumb'))
{
    function image_to_thumb($image)
    {
        $ext      = strtolower(pathinfo($image, PATHINFO_EXTENSION));
        $image    = strtolower(pathinfo($image, PATHINFO_FILENAME)).'_thumb.'.$ext;

        return $image;
    }
}


// ------------------------------------------------------------------------


/**
 * Convert name image.jpg to image_large.jpg
 *
 * @param string $image
 */
if ( ! function_exists('image_to_large'))
{
    function image_to_large($image)
    {
        $ext      = strtolower(pathinfo($image, PATHINFO_EXTENSION));
        $image    = strtolower(pathinfo($image, PATHINFO_FILENAME)).'_large.'.$ext;
        return $image;
    }
}


// ------------------------------------------------------------------------

if ( ! function_exists('currency_menu'))
{
    /**
     * Currency Menu
     *
     * Generates a drop-down menu of currencies.
     *
     * @param   string  currency
     * @param   string  classname
     * @param   string  menu name
     * @param   mixed   attributes
     * @return  string
     */
    function currency_menu($default = 'USD', $class = 'form-control', $name = 'currencies', $attributes = '')
    {
        $CI =& get_instance();

        $default = ($default === 'USD') ? 'USD' : $default;
        
        $menu = '<select name="'.$name.'" data-live-search="true"';

        if ($class !== '')
        {
            $menu .= ' class="'.$class.'"';
        }

        $menu           .= _stringify_attributes($attributes).">\n";
        $currencies      = $CI->db->get('currencies')->result();

        foreach ($currencies as $key => $val)
        {
            $selected = ($default === $val->iso_code) ? ' selected="selected"' : '';
            $menu .= '<option value="'.$val->iso_code.'"'.$selected.'>'.$val->iso_code."</option>\n";
        }

        return $menu.'</select>';
    }
}

// ------------------------------------------------------------------------

if ( ! function_exists('get_default_currency'))
{
    /**
     * get_default_currency
     *
     * Fetch default currency
     *
     * @param   string  currency
     * @param   string  classname
     * @param   string  menu name
     * @param   mixed   attributes
     * @return  string
     */
    function get_default_currency($iso_code_only = TRUE)
    {
        $CI =& get_instance();

        $default_currency  = $CI->db->select(array(
                                                'currencies.iso_code', 
                                                'currencies.symbol',
                                                'currencies.unicode',
                                                'currencies.position',
                                            ))
                                    ->join('currencies', "settings.value = currencies.iso_code")
                                    ->where(array('settings.name'=>'default_currency'))
                                    ->get('settings')
                                    ->row();

        if($iso_code_only)
            return $default_currency->iso_code;
        
        return $default_currency;
    }
}

// ------------------------------------------------------------------------

if ( ! function_exists('email_template_menu'))
{
    /**
     * Email Template Menu
     *
     * Generates a drop-down menu of email-templates.
     *
     * @param   string  email_tempalte
     * @param   string  classname
     * @param   string  menu name
     * @param   mixed   attributes
     * @return  string
     */
    function email_template_menu($default = '1', $class = 'form-control', $name = 'email_templates', $attributes = '')
    {
        $CI =& get_instance();

        $default = ($default === '1') ? '1' : $default;
        
        $menu = '<select name="'.$name.'" data-live-search="true"';

        if ($class !== '')
        {
            $menu .= ' class="'.$class.'"';
        }

        $menu           .= _stringify_attributes($attributes).">\n";
        $currencies      = $CI->db->get('email_templates')->result();

        foreach ($currencies as $key => $val)
        {
            $selected = ($default === $val->id) ? ' selected="selected"' : '';
            $menu .= '<option value="'.$val->id.'"'.$selected.'>'.$val->title."</option>\n";
        }

        return $menu.'</select>';
    }
}

// ------------------------------------------------------------------------

if ( ! function_exists('tax_menu'))
{
    /**
     * tax Menu
     *
     * Generates a drop-down menu of taxes.
     *
     * @param   string  tax
     * @param   string  classname
     * @param   string  menu name
     * @param   mixed   attributes
     * @return  string
     */
    function tax_menu($default = '1', $class = 'form-control', $name = 'taxes', $attributes = '')
    {
        $CI =& get_instance();

        $default = ($default === '1') ? '1' : $default;
        
        $menu = '<select name="'.$name.'" data-live-search="true"';

        if ($class !== '')
        {
            $menu .= ' class="'.$class.'"';
        }

        $menu           .= _stringify_attributes($attributes).">\n";
        $taxes      = $CI->db->get('taxes')->result();

        foreach ($taxes as $key => $val)
        {
            $selected = ($default === $val->id) ? ' selected="selected"' : '';
            $menu .= '<option value="'.$val->id.'"'.$selected.'>'.$val->title.' ('.$val->rate.' - '.$val->rate_type.') ('.$val->net_price.')'."</option>\n";
        }

        return $menu.'</select>';
    }
}

// ------------------------------------------------------------------------

if ( ! function_exists('language_menu'))
{
    /**
     * Language Menu
     *
     * Generates a drop-down menu of languages.
     *
     * @param   string  language
     * @param   string  classname
     * @param   string  menu name
     * @param   mixed   attributes
     * @return  string
     */
    function language_menu($default = 'english', $class = 'form-control', $name = 'currencies', $attributes = '')
    {
        $CI =& get_instance();

        $default = ($default === 'english') ? 'english' : $default;
        
        $menu = '<select name="'.$name.'" data-live-search="true"';

        if ($class !== '')
        {
            $menu .= ' class="'.$class.'"';
        }

        $menu           .= _stringify_attributes($attributes).">\n";
        $languages       = get_languages();

        foreach ($languages as $key => $val)
        {
            $selected = ($default == $key) ? ' selected="selected"' : '';
            $menu .= '<option value="'.$key.'"'.$selected.'>'.$val."</option>\n";
        }

        return $menu.'</select>';
    }
}

// ------------------------------------------------------------------------

if ( ! function_exists('action_buttons'))
{
    /**
     * Action Buttons
     *
     * Generates a drop-down menu of form actions.
     *
     * @param   string  route
     * @param   string  id
     * @param   string  title
     * @param   string  cont (controller name)
     * @param   array   data
     */
    function action_buttons($route = NULL, $id = NULL, $title = NULL, $cont = NULL, $data = NULL)
    {
        $CI =& get_instance();

        $menu = '<div class="btn-group"><button type="button" class="btn bg-'.$CI->settings->admin_theme.' btn-xs waves-effect dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><i class="material-icons">more_vert</i></button>
                    <ul class="dropdown-menu pull-right">';
                        
        if($route === 'faqs')
            $menu .= '<li><a href="#modal-'.$data->id.'" data-toggle="modal" class="waves-effect waves-block"><i class="material-icons">visibility</i>'.lang('action_view').'</a></li>';
        elseif($route === 'custom_fields' || $route === 'groups') {}
        else
            $menu .= '<li><a href="'.site_url("admin/".$route."/view/".$id).'" class="waves-effect waves-block"><i class="material-icons">visibility</i>'.lang('action_view').'</a></li>';
                        
        if($route === 'ebookings' || $route === 'bbookings')
            $menu .= '<li><a href="'.site_url("admin/".$route."/view/".$id."/invoice").'" target="_blank" class="waves-effect waves-block"><i class="material-icons">description</i>'.lang('action_view_invoice').'</a></li>';

        $menu .=        '<li><a href="'.site_url("admin/".$route."/form/".$id).'" class="waves-effect waves-block"><i class="material-icons">edit</i>'.lang('action_edit').'</a></li>';
                    
        if($route !== 'bbookings' && $route !== 'ebookings' && $route !== 'pages')
            $menu .=  '<li role="separator" class="divider"></li>
                        <li><a role="button" class="waves-effect waves-block" onclick="ajaxDelete(`'.$id.'`, `'.$title.'`, `'.$cont.'`)"><i class="material-icons">delete_forever</i>'.lang('action_delete').'</a></li>';

        $menu .=    '</ul>
                </div>';

        if($route === 'faqs')
            $menu .= modal_faq($data);

        return $menu;   
    }
}


// ------------------------------------------------------------------------


if ( ! function_exists('status_switch'))
{
    /**
     * Status Switch
     *
     * Generates a switch for status update.
     *
     * @param   string  status
     * @param   string  id
     */
    function status_switch($status = NULL, $id = NULL)
    {
        $CI =& get_instance();

        $switch  = '<div class="switch">';

        $switch .= '<label><input type="checkbox" onchange="statusUpdate(this, `'.$id.'`)" '.($status == 1 ? 'checked' : '').'><span class="lever switch-col-'.$CI->settings->admin_theme.'"></span></label>';

        $switch .= '</div>';

        return $switch;
    }
}


// ------------------------------------------------------------------------


if ( ! function_exists('featured_switch'))
{
    /**
     * Featured Switch
     *
     * Generates a switch for featured update.
     *
     * @param   string  status
     * @param   string  id
     */
    function featured_switch($featured = NULL, $id = NULL)
    {
        $CI =& get_instance();

        $switch  = '<div class="switch">';

        $switch .= '<label><input type="checkbox" onchange="featuredUpdate(this, `'.$id.'`)" '.($featured == 1 ? 'checked' : '').'><span class="lever switch-col-'.$CI->settings->admin_theme.'"></span></label>';

        $switch .= '</div>';

        return $switch;
    }
}



// ------------------------------------------------------------------------


if ( ! function_exists('modal_contact'))
{
    /**
     * Modal Contact
     *
     * Generates a modal for contact.
     *
     * @param   object  data
     */
    function modal_contact($data)
    {
        $CI =& get_instance();

        $modal  = '<a href="#modal-'.$data->id.'" data-toggle="modal" class="btn '.($data->read ? 'btn-default' : 'bg-'.$CI->settings->admin_theme).' btn-circle waves-effect waves-circle waves-float"><i class="material-icons">'.($data->read ? 'drafts' : 'email').'</i></a>';

        $modal .= '<div class="modal fade" id="modal-'.$data->id.'" data-read="'.($data->read ? "true" : "false").'" data-id="'.$data->id.'" tabindex="-1" role="dialog" aria-labelledby="modal-label-'.$data->id.'">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" id="modal-label-'.$data->id.'">'.$data->title.'</h4>
                            </div>
                            <div class="modal-body">
                                '.$data->message.'
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">'.lang('action_cancel').'</button>
                            </div>
                        </div>
                    </div>
                </div>';
                
        return $modal;
    }
}

// ------------------------------------------------------------------------


if ( ! function_exists('modal_faq'))
{
    /**
     * Modal Faq
     *
     * Generates a modal for faq.
     *
     * @param   object  data
     */
    function modal_faq($data)
    {
        $CI =& get_instance();

        $modal = '<div class="modal fade" id="modal-'.$data->id.'" data-id="'.$data->id.'" tabindex="-1" role="dialog" aria-labelledby="modal-label-'.$data->id.'">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" id="modal-label-'.$data->id.'">'.$data->question.'</h4>
                            </div>
                            <div class="modal-body">
                                '.$data->answer.'
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">'.lang('action_cancel').'</button>
                            </div>
                        </div>
                    </div>
                </div>';
                
        return $modal;
    }
}

// ------------------------------------------------------------------------


if ( ! function_exists('time_elapsed_string'))
{
    /**
     * Time Elapsed
     *
     * Timestamp To Time Elapsed.
     *
     * @param   object  data
     */
    function time_elapsed_string($datetime, $full = false) 
    {
        $now = new DateTime;
        $ago = new DateTime($datetime);
        $diff = $now->diff($ago);

        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;

        $string = array(
            'y' => 'year',
            'm' => 'month',
            'w' => 'week',
            'd' => 'day',
            'h' => 'hour',
            'i' => 'minute',
            's' => 'second',
        );
        foreach ($string as $k => &$v) {
            if ($diff->$k) {
                $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
            } else {
                unset($string[$k]);
            }
        }

        if (!$full) $string = array_slice($string, 0, 1);
        return $string ? implode(', ', $string) . ' ago' : 'just now';
    }
}


// ------------------------------------------------------------------------


if ( ! function_exists('get_domain'))
{
    /**
     * Get Domain
     *
     * return domain name.
     *
     * @param   object  data
     */

    function get_domain()
    {
        $CI =& get_instance();
        return preg_replace("/^[\w]{2,6}:\/\/([\w\d\.\-]+).*$/","$1", $CI->config->slash_item('base_url'));
    }

}

// ------------------------------------------------------------------------


if ( ! function_exists('get_date_difference'))
{
    /**
     * Get Domain
     *
     * return domain name.
     *
     * @param   object  data
     */

    function get_date_difference($date_small, $date_big)
    {
        $diff       = abs(strtotime($date_big) - strtotime($date_small));

        $years      = floor($diff / (365*60*60*24));
        $months     = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
        $days       = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));

        $result     = '';

        if($years) {
            if($years > 1)
                $result .= $years.' Years, ';
            else    
                $result .= $years.' Year, ';
        }

        if($months) {
            if($years > 1)
                $result .= $months.' Months, ';
            else    
                $result .= $years.' Month, ';
        }

        if($days) {
            if($days > 1)
                $result .= $days.' Days';
            else    
                $result .= $days.' Day';
        }

        return $result;
    }

}

// ------------------------------------------------------------------------
