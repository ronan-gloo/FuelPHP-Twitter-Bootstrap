<?php

namespace Bootstrap;

class Form extends \Fuel\Core\Form {
	
	/**
	 * input function.
	 * 
	 * @access public
	 * @static
	 * @param mixed $field
	 * @param mixed $value (default: null)
	 * @param array $attrs (default: array())
	 * @return void
	 */
	public static function input($field, $value = null, array $attrs = array())
	{
		return Form_Input::forge($attrs)->make($field, $value);
	}
	
}