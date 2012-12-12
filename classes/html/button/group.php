<?php

namespace Bootstrap;

class Html_Button_Group extends BootstrapModule {
	
	protected $items = array();
	
	/**
	 * each arg is a potential button
	 * 
	 * @access public
	 * @return void
	 */
	public function make($buttons = array())
	{
		call_user_func_array(array($this, 'btns'), $buttons);
		
		return $this;
	}
	
	/**
	 * Add button.
	 * 
	 * @access public
	 * @param string $anchor (default: '') : anchore or Html_Button instance
	 * @param string $text (default: '')
	 * @param array $attrs (default: array())
	 * @param bool $secure (default: false)
	 * @return void
	 */
	public function btn($anchor = '', $text = '', $attrs = array(), $secure = false)
	{
		if (! $anchor instanceof Html_Button)
		{
			$anchor = Html_Button::forge($attrs)->make($anchor, $text, $secure);
		}
		$this->items[] = $anchor;
		
		return $anchor;
	}

	/**
	 * Add button.
	 * 
	 * @access public
	 * @param string $anchor (default: '') : anchore or Html_Button instance
	 * @param string $text (default: '')
	 * @param array $attrs (default: array())
	 * @param bool $secure (default: false)
	 * @return void
	 */
	public function btns()
	{
		foreach (func_get_args() as $args)
		{
			call_user_func_array(array($this, 'btn'), $args);
		}
		return $this;
	}
	
	/**
	 * @access public
	 * @return void
	 */
	public function render()
	{
		if (! $this->items) return $this->html;
		
		$this->css('btn-group');
		
		$this->parse_attributes();
		$this->parse_items();
		
		$this->html('div', implode(PHP_EOL, $this->items));
		
		return parent::render();
	}
	
	/**
	 * @access protected
	 * @return void
	 */
	protected function parse_attributes()
	{
		foreach ($this->attrs as $key => $attr)
		{
			switch ($key)
			{
				case 'toggle':
				$this->attrs['data-toggle'] = 'buttons-'.$attr;
				break;
				
				case 'vertical':
				$attr === true && $this->css('btn-group-vertical');
				break;
			}
		}
	}
	
	/**
	 * @access protected
	 * @return void
	 */
	protected function parse_items()
	{
		foreach($this->items as &$item)
		{
			// do not override item status...?
			if (! empty($this->attrs['status']) and empty($item->attrs['status']))
			{
				$item->attrs['status'] = $this->attrs['status'];
			}
			if (! empty($this->attrs['size']))
			{
				$item->attrs['size'] = $this->attrs['size'];
			}
		}
	}
	
}