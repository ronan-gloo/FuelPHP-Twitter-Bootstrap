<?php

namespace Bootstrap;

use
	\OutOfBoundsException
;

/**
* Manage element attributes: styles, attrs, data, classes
*/
class Helper_Attribute  {
	
	/**
	 * Custom attributes with values
	 * from the module config file
	 * 
	 * @var mixed
	 * @access protected
	 */
	protected $attributes = array();
	
	/**
	 * Inline Css Styles
	 * 
	 * (default value: array())
	 * 
	 * @var array
	 * @access protected
	 */
	protected $styles = array();
	
	/**
	 * Css Classes,
	 * 
	 * (default value: array())
	 * 
	 * @var array
	 * @access protected
	 */
	protected $classes = array();
	
	/**
	 * Default attribute which can
	 * be provided string to the constructor
	 * 
	 * @var mixed
	 * @access protected
	 */
	protected $attribute = null;
	
	/**
	 * @access public
	 * @param array $attrs
	 * @param mixed $attr
	 * @return void
	 */
	public function __construct($attrs, $attr, $config, $strict)
	{
		! is_array($attrs) and $attrs = array($attr => $attrs);

		$this->attrs			= $attrs;
		$this->config 		= $config;
		$this->is_strict	= $strict;
	}
	
	/**
	 * Setup attributes form a template.
	 * 
	 * @access public
	 * @param mixed $templates
	 * @return void
	 */
	public function addTemplate($templates)
	{
		if ($key = $this->attr('from'))
		{
			$this->removeAttr('from');
			
			if ( array_key_exists($key, $templates))
			{
				$this->attrs = $this->attrs + $templates[$key];
			}
			else
			{
				if ($this->is_strict)
				{
					throw new OutOfBoundsException(__METHOD__.': "'.$key.'" Template does not exists');
				}
			}
		}
		return $this;
	}
	
	/**
	 * Getter / Setter for inline styles.
	 * 
	 * @access public
	 * @return void
	 */
	public function css($key, $val = null)
	{
		// Getter
		if (! is_array($key) and is_null($val))
		{
			return array_key_exists($key, $this->styles) ? $this->styles[$key] : null;
		}
		// Setter
		if (! is_array($key) and $val)
		{
			$key = array($key => $val);
		}
		foreach ($key as $rule => $value)
		{
			$this->styles[$rule] = $rule.":".$value.";";
		}
		// clean empty values
		$this->styles = array_filter($this->styles);
		
		return $this;
	}
	
	/**
	 * Returns current html attributes.
	 * 
	 * @access public
	 * @return void
	 */
	public function attrs($attributes = array())
	{
		if (is_array($attributes) and count($attributes) > 0)
		{
			$this->attrs = $attributes;
		}
		return $this->attrs;
	}
	
	/**
	 * Attribute Getter or Setter.
	 * 
	 * @access public
	 * @param mixed $key
	 * @param mixed $val (default: null)
	 * @return void
	 */
	public function attr($key, $val = null)
	{
		if ($key and ! is_null($val))
		{
			$output = $this->attrs[$key] = $val;
		}
		else if ($key and is_null($val) and array_key_exists($key, $this->attrs))
		{
			$output = $this->attrs[$key];
		}
		else
		{
			$output = null;
		}
		return $output;
	}
	
	/**
	 * Delete an attribute.
	 * 
	 * @access public
	 * @param mixed $key
	 * @return void
	 */
	public function removeAttr($key)
	{
		if ($this->attr($key) !== null)
		{
		 	unset($this->attrs[$key]);
		 	return true;
		}
		return false;
	}
	
	/**
	 * Add new css classes to the css elements.
	 * 
	 * @access public
	 * @param mixed $class
	 * @return void
	 */
	public function addClass()
	{
		$this->classes = array_merge($this->classes, func_get_args());
		
		return $this;
	}
	
	/**
	 * Check for the css class.
	 * 
	 * @access public
	 * @param mixed $class
	 * @return void
	 */
	public function hasClass($class)
	{
		return in_array($class, $this->classes);
	}
	
	/**
	 * Delete a css class.
	 * 
	 * @access public
	 * @param mixed $class
	 * @return void
	 */
	public function removeClass()
	{
		foreach (func_get_args() as $class)
		{
			if ($this->hasClass($class))
			{
				$index = array_search($class, $this->classes);
				unset($this->classes[$index]);
			}
		}
		return $this;
	}
	
	/**
	 * Merge css array under class attrs key.
	 * 
	 * @access public
	 * @return void
	 */
	public function mergeAttrs()
	{
		// generates styles
		if ($styles = implode($this->styles).$this->attr("style"))
		{
			$this->attr("style", $styles);
		}
		
		return $this->classesToAttr($this->attrs, $this->classes);
	}
	
	/**
	 * Generic function helper to .
	 * 
	 * @access public
	 * @param mixed &$attrs
	 * @param mixed $css
	 * @return void
	 */
	public function classesToAttr(&$attrs, $css)
	{
		if (! array_key_exists('class', $attrs))
		{
			$attrs['class'] = '';
		}
		else
		{
			$attrs['class'] .= ' ';
		}
		
		if ($css) $attrs['class'] .= implode(' ', $css);
		
		if (! $attrs['class']) unset($attrs['class']);
		
		return $this;
	}
	
	/**
	 * Validate attribute.
	 * 
	 * @access public
	 * @return void
	 */
	public function validate($needle, $haystack)
	{
		// use en wild card
		if (in_array('*', (array)$haystack))
		{
			return true;
		}
		switch (is_array($haystack))
		{
			case 1:
			$out = (in_array($needle, $haystack));
			break;
			
			case 0:
			$out = ($needle === $haystack);
			break;
		}
		if ($out === false)
		{
			throw new InvalidArgumentException("'$needle' is not a valid argument for ".get_called_class());
		}
		return $out;
	}
	
	/**
	 * Clean Extra attributes before outputing the html
	 * 
	 * @access public
	 * @return void
	 */
	public function clean()
	{
		if ($attributes = $this->attrs() and $config = $this->config)
		{
			// Extract attributes to clean
			foreach (array_intersect_key($config, $attributes) as $name => $values)
			{
				if (! $attr = $this->attr($name))
				{
					continue;
				}
				if ($values and $this->is_strict === true)
				{
					$this->validate($attr, $values);
				}
				$this->removeAttr($name);
			}
		}
	}

}
