<?php

namespace Bootstrap;

class Form_Checkbox extends BootstrapModule implements Deactivable {
	
	protected $instance	= null;
	protected $data			= array();
	protected $entity		= 'checkbox';
	protected $name			= 'form_checkbox';

	/**
	 * @access public
	 * @param mixed $instance
	 * @param mixed $field
	 * @param mixed $value
	 * @param mixed $checked
	 * @return void
	 */
	public function make($instance, $field, $value, $checked)
	{
		$this->instance = $instance;
		
		$this->data['field'] 		= $field;
		$this->data['value'] 		= $value;
		$this->data['checked'] 	= $checked;
		
		return $this;
	}
	
	/**
	 * @access public
	 * @param mixed $str
	 * @param array $attrs (default: array())
	 * @return void
	 */
	public function label($str, array $attrs = array('class' => null))
	{
		$this->data['label']['text'] 	= $str;
		$this->data['label']['attrs'] = $attrs;
		
		return $this;
	}
	
	/**
	 * @access public
	 * @param bool $bool (default: true)
	 * @return void
	 */
	public function checked($bool = true)
	{
		is_bool($bool) and $this->data["checked"] = $bool;
		
		if ($bool === true and isset($this->attrs["disabled"]))
		{
			unset($this->attrs["disabled"]);
		}
		return $this;
	}
	
	/**
	 * disabled function.
	 * 
	 * @access public
	 * @param bool $bool (default: true)
	 * @return void
	 */
	public function disabled($bool = true)
	{
		switch ($bool)
		{
			case 1:
			$this->attrs["disabled"] = 'disabled';
			$this->config('automute') and $this->css('muted');
			break;
			
			case 0:
			if (isset($this->attrs["disabled"])) unset($this->attrs["disabled"]);
			if ($this->config('automute') and in_array('muted', $this->css))
			{
				unset($this->css[array_search('muted', $this->css)]);
			}
			break;
		}
		return $this;
	}
	
	/**
	 * @access public
	 * @param bool $bool (default: true)
	 * @return void
	 */
	public function inline($str = '', array $attrs = array())
	{
		$attrs['class'] = 'inline';
		
		return $this->label($str, $attrs);
	}
	
	/**
	 * @access public
	 * @return void
	 */
	public function render()
	{
		// parse attribute
		foreach ($this->attrs as $key => $val)
		{
			switch ($key)
			{
				case 'state':
				$this->{$key}();
				break;
				
				case 'inline':
				$css[] = 'inline';
				$this->label($val);
				break;
				
				case 'label':
				$this->label($val);
				break;
			}
		}

		// Build label and input
		if (isset($this->data['label']))
		{
			$css[] = $this->entity;
			$this->_merge($this->data['label']['attrs'], $css);
			
			if (isset($this->attrs['id']))
			{
				$this->data['label']['attrs']['for'] = $this->attrs['id'];
			}
			
			$text		= $this->data['label']['text'];
			$input	= $this->_input();
			
			$this->attrs = $this->data['label']['attrs'];
			$this->html('label', $input.PHP_EOL.$text);
		}
		else
		{
			$this->html = $this->_input();
		}
		return parent::render();
	}
	
	/**
	 * @access protected
	 * @return void
	 */
	protected function _input()
	{
		$this->merge()->clean();
		
		return $this->instance->{'core_'.$this->entity}(
			$this->data['field'],
			$this->data['value'],
			$this->data['checked'],
			$this->attrs
		)->render();
	}
	
}