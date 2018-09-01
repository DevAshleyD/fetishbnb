<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Custom Form Helpers
 *
 * @package		ANT
 * @subpackage	Helpers
 * @category	Helpers
 * @author		dpkpro
 */

// ------------------------------------------------------------------------

if ( ! function_exists('form_input_custom'))
{
	/**
	 * Custom Input Field
	 *
	 * @param	mixed
	 * @param	string
	 * @param	mixed
	 * @return	string
	 */
	function form_input_custom($data = '', $type = '', $value = '', $extra = '')
	{
		$defaults = array(
			'type' => $type,
			'name' => is_array($data) ? '' : $data,
			'value' => $value
		);

		return '<input '._parse_form_attributes($data, $defaults)._attributes_to_string($extra)." />\n";
	}
}