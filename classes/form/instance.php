<?php

namespace Bootstrap;

class Form_Instance extends \Fuel\Core\Form_Instance {
	
	public $group = array('open' => false, 'control' => false);
	
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
	 * @access public
	 * @return void
	 */
	public function group_open($attrs = array())
	{
		is_string($attrs) and $attrs = array('status' => $attrs);
		
		$attrs['class'] = empty($attrs['class']) ? 'control-group' : $attrs['class'].' control-group';
				
		if (array_key_exists('status', $attrs))
		{
			$attrs['class'] .= ' '.$attrs['status'];
			unset($attrs['status']);
		}
		
		$this->group['open'] = true;
		return '<div '.array_to_attr($attrs).'>';
	}
	
	/**
	 * @access public
	 * @return void
	 */
	public function group_close()
	{
		$output = $this->control_close();

		if ($this->group['open'] === true)
		{
			$this->group['open'] = false;
			$output .= '</div>';
		}
		return $output;
	}
	
	
	/**
	 * @access public
	 * @return void
	 */
	public function control_open()
	{
		$output = '';
		
		if ($this->group['open'] === true and $this->group['control'] === false)
		{
			$output = '<div class="controls">';
			$this->group['control'] = true;
		}
		return $output;
	}
	
	/**
	 * @access public
	 * @return void
	 */
	public function control_close()
	{
		$output = '';
		
		if ($this->group['control'] === true)
		{
			$output = '</div>';
			$this->group['control'] = false;
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
		return Form_Input::forge($attributes)->instance($this)->make($field, $value);
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
	 * @param mixed $field
	 * @param mixed $values (default: null)
	 * @param array $options (default: array())
	 * @param array $attributes (default: array())
	 * @return void
	 */
	public function select($field, $values = null, array $options = array(), array $attributes = array())
	{
		$output  = $this->control_open();
		$output .= parent::select($field, $values, $options, $attributes);
		
		return $output;
	}
	
	/**
	 * @access public
	 * @param mixed $field
	 * @param mixed $value (default: null)
	 * @param array $attributes (default: array())
	 * @return void
	 */
	public function textarea($field, $value = null, array $attributes = array())
	{
		$output  = $this->control_open();
		$output .= parent::textarea($field, $value, $attributes);
		
		return $output;
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
	public function label($text, $id = null, array $attrs = array())
	{
		return Form_Label::forge($attrs)->instance($this)->make($text, $id);
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
		return Form_Checkbox::forge($attributes)->instance($this)->make($field, $value, $checked);
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
		return Form_Radio::forge($attributes)->instance($this)->make($field, $value, $checked);
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
		return Form_Typeahead::forge($attributes)->instance($this)->make($field, $value);
	}
}