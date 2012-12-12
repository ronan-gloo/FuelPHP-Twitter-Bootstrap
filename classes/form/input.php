<?php

namespace Bootstrap;

class Form_Input extends BootstrapModuleSurround implements Deactivable {
	
	protected $data	= array('field' => '', 'value' => '');
	
	/**
	 * @access public
	 * @param Closure $callback
	 * @return void
	 */
	public function make($instance, $field, $value = '')
	{
		$this->instance = $instance;
		
		$this->data['field'] = $field;
		$this->data['value'] = $value;
		
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
			$this->attrs['disabled'] = 'disabled';
			break;
			
			case 0:
			if (isset($this->attrs['disabled'])) unset($this->attrs['disabled']);
			break;
		}
		
		return $this;
	}
	
	/**
	 * render function.
	 * 
	 * @access public
	 * @return void
	 */
	public function render()
	{
		list($prepend, $append) = $this->surround();
		
		// parse input attributes
		foreach ($this->attrs as $key => $attr)
		{
			switch ($key)
			{
				case 'size':
				$this->css('input-'.$attr);
				break;
				
				case 'search':
				$this->css('search-query');
				break;
			}
		}
		
		$this->merge()->clean();
		
		$input = $this->instance->core_input($this->data['field'], $this->data['value'], $this->attrs);
		
		$this->html = ($prepend or $append) ? html_tag('div', $this->cattrs, $prepend.$input.$append) : $input;
		
		return parent::render();
	}	
	
	/**
	 * Add default popover trigger attribute to focus.
	 * 
	 * @access public
	 * @return void
	 */
	public function popover($title = '', $value = '', $attrs = array())
	{
		parent::popover($title, $value, $attrs);
		
		empty($this->attrs['data-trigger']) and $this->attrs['data-trigger'] = 'focus';

		return $this;
	}
	
	
}