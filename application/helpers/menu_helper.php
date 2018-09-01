<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Menu Helper
 * for n-level menu
 * @package		ANT
 * @subpackage	Helpers
 * @category	Helpers
 * @author		dpkpro
 */

// ------------------------------------------------------------------------

if ( ! function_exists('fetch_tree'))
{
	/**
	 * Fetch Tree
	 *
	 * @return	array
	 */
	function fetch_tree() 
	{
		$CI = get_instance();
		//select all rows from the main_menu table
		$result = $CI->db->select(array('id', 'title', 'parent_id'))
						 ->where(array('status'=>'1'))
						 ->get("course_categories")
						 ->result_array();

		//create a multidimensional array to hold a list of menu and parent menu
		$menu = array(
			'menus' => array(),
			'parent_menus' => array()
		);

		//build the array lists with data from the menu table
		foreach ($result as $val) 
		{
			//creates entry into menus array with current menu id ie. $menus['menus'][1]
			$menu['menus'][$val['id']] = $val;
			//creates entry into parent_menus array. parent_menus array contains a list of all menus with children
			$menu['parent_menus'][$val['parent_id']][] = $val['id'];
		}	

		return $menu;
	}
}	

// ------------------------------------------------------------------------
if ( ! function_exists('build_menu_levels')) 
{
	/**
	 * Build Menu Levels
	 * Create the main function to build milti-level menu. It is a recursive function.
	 * @return	string
	**/
	function build_menu_levels($parent) 
	{
		$menu = fetch_tree();
		$html = "";
		if (isset($menu['parent_menus'][$parent])) 
		{
			$html .= "<ul class='dropdown-menu'>";
			foreach ($menu['parent_menus'][$parent] as $menu_id) 
			{
				if (!isset($menu['parent_menus'][$menu_id])) 
				{
					$html .= "<li><a href='" .site_url('courses/'). str_replace(' ', '+', $menu['menus'][$menu_id]['title']) . "'>" . $menu['menus'][$menu_id]['title'] . "</a></li>";
				}
				if (isset($menu['parent_menus'][$menu_id])) 
				{
					$html .= "<li class='dropdown-submenu'><a href='" . site_url('courses/'). str_replace(' ', '+', $menu['menus'][$menu_id]['title']) . "'>" . $menu['menus'][$menu_id]['title'] . "</a>";
					$html .= build_menu_levels($menu_id, $menu);
					$html .= "</li>";
				}
			}
			$html .= "</ul>";
		}
		return $html;
	}	
}
// ------------------------------------------------------------------------

// ------------------------------------------------------------------------
if ( ! function_exists('build_menu_events')) 
{
	/**
	 * Build Menu Events
	 * Create the main function to build events menu.
	 * @return	string
	**/
	function build_menu_events() 
	{
		$CI 			= get_instance();
		
		$event_types 	= $CI->db->query("SELECT et.id, et.title, (SELECT COUNT(e.id) FROM events e WHERE e.event_types_id = et.id) e_count FROM event_types et WHERE et.status = 1;")->result();
		
		$events 		= $CI->db->select(array('id', 'title', 'event_types_id'))
								 ->where(array('status !='=>'0'))
								 ->get("events")
						 		 ->result();
		
		if(empty($events) || empty($event_types))
			return true;

		$html = "<ul class='dropdown-menu'>";
		foreach($event_types as $key => $val)
		{
			if(! $val->e_count) continue;

			$html .= "<li class='dropdown-submenu'>
						<a href='".site_url('events/').str_replace(' ', '+', $val->title)."'>".$val->title."</a>
							<ul class='dropdown-menu'>";
							
			foreach ($events as $k => $v) 
			{
				if ($val->id === $v->event_types_id) 
				{
					$html .= "<li><a href='".site_url('events/').'detail/'.str_replace(' ', '+', $v->title)."'>".$v->title."</a></li>";
				}
			}
			$html .= "</ul></li>";
		}
		
		$html .= "</ul>";
		return $html;
	}	
}
// ------------------------------------------------------------------------

// ------------------------------------------------------------------------
if ( ! function_exists('build_event_types')) 
{
	/**
	 * Build Events Types Tags
	 * Create the main function to build events menu.
	 * @return	string
	**/
	function build_event_types() 
	{
		$CI 			= get_instance();
		
		$event_types 	= $CI->db->query("SELECT et.id, et.title, (SELECT COUNT(e.id) FROM events e WHERE e.event_types_id = et.id) e_count FROM event_types et;")->result();
		
		if(empty($event_types))
			return true;

		$html = "";
		foreach($event_types as $key => $val)
		{
			if($val->e_count) 
			{
				$html 	.= "<li><a href='".site_url('events/').str_replace(' ', '+', $val->title)."'>".$val->title.' &nbsp;<strong>+'.$val->e_count.' </strong>'."</a></li>";
			}
		}
		
		return $html;
	}	
}
// ------------------------------------------------------------------------

// ------------------------------------------------------------------------
if ( ! function_exists('build_filter_levels')) 
{
	/**
	 * Build Filter Levels
	 * Create the main function to build milti-level filter. It is a recursive function.
	 * @return	string
	**/
	function build_filter_levels($parent) 
	{
		$menu = fetch_tree();
		$html = "";
		if (isset($menu['parent_menus'][$parent])) 
		{
			$html .= "<ul>";
			foreach ($menu['parent_menus'][$parent] as $menu_id) 
			{
				if (!isset($menu['parent_menus'][$menu_id])) 
				{
					$html .= "<li><a href='" .site_url('courses/'). str_replace(' ', '+', $menu['menus'][$menu_id]['title']) . "'>" . $menu['menus'][$menu_id]['title'] . "</a></li>";
				}
				if (isset($menu['parent_menus'][$menu_id])) 
				{
					$html .= "<li><a>" . $menu['menus'][$menu_id]['title'] . "</a>";
					$html .= build_filter_levels($menu_id, $menu);
					$html .= "</li>";
				}
			}
			$html .= "</ul>";
		}
		return $html;
	}	
}
// ------------------------------------------------------------------------