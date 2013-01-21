<?php

namespace Bootstrap;

use \InvalidArgumentException;

class Form_Input extends BootstrapModuleSurround implements Deactivable {
	
	protected $data	= array('field' => '', 'value' => '');
	
	/**
	 * @access public
	 * @param Closure $callback
	 * @return void
	 */
	public function make($field, $value = '')
	{
		$this->data['field'] = $field;
		$this->data['value'] = $value;
		
		return $this;
	}
		
	/**
	 * @access public
	 * @param bool $bool (default: true)
	 * @return void
	 */
	public function disabled($bool = true)
	{
		switch ($bool)
		{
			case 1:
			$this->manager->attr('disabled', 'disabled');
			break;
			
			case 0:
			$this->manager->attr('disabled') and $this->manager->removeAttr('disabled');
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
		
		$this->parse_attrs();
		
		$this->manager->mergeAttrs()->clean();
		
		$input = $this->instance->core_input($this->data['field'], $this->data['value'], $this->manager->attrs());
		
		$this->html  = $this->instance->control_open();
		$this->html .= ($prepend or $append) ? html_tag('div', $this->cattrs, $prepend.$input.$append) : $input;
		
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
		
		! $this->manager->attr('data-trigger') and $this->manager->attr('data-trigger', 'focus');

		return $this;
	}
	
	
	/**
	 * @access protected
	 * @return void
	 */
	protected function parse_attrs()
	{
		foreach ($this->manager->attrs() as $key => $attr)
		{
			switch ($key)
			{
				case 'size':
				$this->manager->addClass('input-'.$attr);
				break;
				
				case 'search':
				$this->manager->addClass('search-query');
				break;
				
				case 'state':
				method_exists($this, $attr) and $this->$attr(true);
				break;
			}
		}
	}
	
	
}