<?php

namespace Bootstrap;

class Form_Input extends BootstrapModule implements Deactivable {
	
	protected $data			= array('field' => '', 'value' => '');
	protected $append		= array();
	protected $prepend	= array();
	protected $cattrs		= array();
	
	/**
	 * @access public
	 * @param Closure $callback
	 * @return void
	 */
	public function make(&$instance, $field, $value = '')
	{
		$this->instance = $instance;
		
		$this->data['field'] = $field;
		$this->data['value'] = $value;
		
		return $this;
	}
	
	/**
	 * @access public
	 * @return void
	 */
	public function prepend()
	{
		$this->prepend = array_merge($this->prepend, func_get_args());
		
		return $this;
	}
	
	/**
	 * @access public
	 * @return void
	 */
	public function append()
	{
		$this->append = array_merge($this->append, func_get_args());
		
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
		$prepend  = $this->prepend_append('prepend');
		$append		= $this->prepend_append('append');
		
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
		
		$input = $this->instance->input($this->data['field'], $this->data['value'], $this->attrs);
		
		$this->html = ($prepend or $append) ? html_tag('div', $this->cattrs, $prepend.$input.$append) : $this->html = $input;

		return parent::render();
	}
	
	
	/**
	 * @access protected
	 * @return void
	 */
	protected function prepend_append($type)
	{		
		// merge prepend / append attributes with prp / app methods
		$array = isset($this->attrs[$type]) ? array_merge((array)$this->attrs[$type], $this->$type): $this->$type;
		
		if (! $array) return '';
		
		$this->_merge($this->cattrs, array('input-'.$type));
		
		foreach ($array as &$data)
		{
			if ($data instanceof BootstrapModule)
			{
				continue;
			}
			
			if (strpos($data, 'icon-') === 0)
			{
				$icon	= substr($data, 5);
				$data	= Html_Icon::forge(array())->make($icon);
			}
			
			$data = html_tag('span', array('class' => 'add-on'), $data);
		}
		return implode(PHP_EOL, $array);
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