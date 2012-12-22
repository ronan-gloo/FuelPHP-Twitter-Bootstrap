<?php

namespace Bootstrap;

class Form_Instance extends \Fuel\Core\Form_Instance {
	
	protected $field_opened = false;
	
	/**
	 * catch prefixed core_* methods to call parent's methods.
	 * 
	 * @access public
	 * @static
	 * @return void
	 */
	public function __call($m, $args)
	{
		if (strpos($m, 'core_') === 0)
		{
			$output = call_user_func_array(array('parent', substr($m, 5)), $args);
		}
		else
		{
			$output = call_user_func_array(array($this, $m), $args);
		}
		return $output;
	} 
	
	/**
	 * input function.
	 * 
	 * @access public
	 * @static
	 * @return void
	 */
	public function input($field, $value = null, array $attributes = array())
	{
		return Form_Input::forge($attributes)->make($this, $field, $value);
	}
	
	/**
	 * Force type and css for search inputs.
	 * 
	 * @access public
	 * @param mixed $field
	 * @param mixed $value (default: null)
	 * @param array $attributes (default: array())
	 * @return void
	 */
	public function search($field, $value = null, array $attributes = array())
	{
		$input = $this->input($field, $value, $attributes);
		$input->attrs['type'] 	= 'search';
		$input->attrs['search'] = true;
		
		return $input;
	}
	
	/**
	 * @access public
	 * @param mixed $text (default: null)
	 * @param array $attrs (default: array())
	 * @return void
	 */
	public function locked($text = null, $attrs = array())
	{
		return Form_Locked::forge($attrs)->make($text);
	}
	
	/**
	 * @access public
	 * @param mixed $text (default: null)
	 * @param array $attrs (default: array())
	 * @return void
	 */
	public function help($text = null, $attrs = array())
	{
		return Form_Help::forge($attrs)->make($text);
	}
	
	/**
	 * @access public
	 * @param mixed $field
	 * @param mixed $value (default: null)
	 * @param array $attributes (default: array())
	 * @return void
	 */
	public function checkbox($field, $value = null, $checked = null, array $attributes = array())
	{
		return Form_Checkbox::forge($attributes)->make($this, $field, $value, $checked);
	}

	/**
	 * @access public
	 * @param mixed $field
	 * @param mixed $value (default: null)
	 * @param array $attributes (default: array())
	 * @return void
	 */
	public function radio($field, $value = null, $checked = null, array $attributes = array())
	{
		return Form_Radio::forge($attributes)->make($this, $field, $value, $checked);
	}

	/**
	 * @access public
	 * @param mixed $field
	 * @param mixed $value (default: null)
	 * @param array $attributes (default: array())
	 * @return void
	 */
	public function typeahead($field, $value = null, $attributes = array())
	{
		return Form_Typeahead::forge($attributes)->make($this, $field, $value);
	}
}