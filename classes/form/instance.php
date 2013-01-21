<?php

namespace Bootstrap;

use
	\Config;

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
	 * @access protected
	 * @param mixed $class
	 * @param mixed $attrs
	 * @return void
	 */
	protected function set_css_class($class, $attrs)
	{
		$attrs['class'] = empty($attrs['class']) ? $class : $attrs['class'].' '.$class;
		return $attrs;
	}
	
	/**
	 * Open div with specific class / attributes.
	 * 
	 * @access protected
	 * @param mixed $class
	 * @param array $attrs
	 * @return void
	 */
	protected function div_open($class, array $attrs)
	{
		return '<div '.array_to_attr($this->set_css_class($class, $attrs)).'>';
	}
	
	/**
	 * @access protected
	 * @return void
	 */
	protected function div_close()
	{
		return '</div>';
	}
	
	/**
	 * Set form type if provided.
	 * 
	 * @access public
	 * @param array $attrs (default: array())
	 * @param array $hidden (default: array())
	 * @return void
	 */
	public function open($attrs = array(), array $hidden = array())
	{
		if (isset($attrs['type']))
		{
			$attrs = $this->set_css_class('form-'.$attrs['type'], $attrs);
			unset($attrs['type']);
		}
		return parent::open($attrs, $hidden);
	}
	
	/**
	 * @access public
	 * @return void
	 */
	public function group_open($attrs = array())
	{
		is_string($attrs) and $attrs = array('status' => $attrs);
		
		$attrs = $this->set_css_class('control-group', $attrs);
				
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
			$output .= $this->div_close();
		}
		return $output;
	}
	
	/**
	 * @access public
	 * @return void
	 */
	public function control_open(array $attrs = array())
	{
		$output = '';
		
		if ($this->group['open'] === true and $this->group['control'] === false)
		{
			$output = $this->div_open('controls', $attrs);
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
			$output = $this->div_close();
			$this->group['control'] = false;
		}
		
		return $output;
	}
	
	/**
	 * @access public
	 * @param array $attrs (default: array())
	 * @return void
	 */
	public function action_open(array $attrs = array())
	{
		return $this->div_open('form-actions', $attrs);
	}

	/**
	 * @access public
	 * @param array $attrs (default: array())
	 * @return void
	 */
	public function action_close()
	{
		return $this->div_close();
	}
	
	/**
	 * @access public
	 * @static
	 * @param mixed $field
	 * @param mixed $value (default: null)
	 * @param array $attrs (default: array())
	 * @return void
	 */
	public function button($field, $value = null, array $attributes = array())
	{
		return Form_Button::forge($attributes)->make($this, $field, $value);
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
	 * Generates a form button, with submit type forced
	 * @access public
	 * @static
	 * @param string $field (default: 'submit')
	 * @param string $value (default: 'Submit')
	 * @param array $attrs (default: array())
	 * @return void
	 */
	public function submit($field = 'submit', $value = 'Submit', array $attributes = array())
	{
		$attributes['type'] = 'submit';
		return Form_Button::forge($attributes)->make($this, $field, $value);
	}

	/**
	 * Generates a form button, with reset type forced
	 * @access public
	 * @static
	 * @param string $field (default: 'submit')
	 * @param string $value (default: 'Submit')
	 * @param array $attrs (default: array())
	 * @return void
	 */
	public function reset($field = 'submit', $value = 'Submit', array $attributes = array())
	{
		$attributes['type'] = 'reset';
		return Form_Button::forge($attributes)->make($this, $field, $value);
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
		$attributes["type"]		= "search";
		$attributes["search"] = true;
		
		return $this->input($field, $value, $attributes);
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