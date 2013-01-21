<?php

namespace Bootstrap;

class Html_Button_Group extends BootstrapModule implements ContainsItems {
	
	protected $items = array();
	
	/**
	 * each arg is a potential button
	 * 
	 * @access public
	 * @return void
	 */
	public function make($buttons = array())
	{
		call_user_func_array(array($this, 'items'), $buttons);
		
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
	public function item($anchor = '', $text = '', $attrs = array(), $secure = false)
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
	public function items()
	{
		foreach (func_get_args() as $args)
		{
			call_user_func_array(array($this, 'item'), $args);
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
		
		$this->manager->addClass('btn-group');
		
		$this->parse_attributes();
		$this->parse_items();
		
		$this->html('div', implode($this->items));
		
		return parent::render();
	}
	
	/**
	 * @access protected
	 * @return void
	 */
	protected function parse_attributes()
	{
		foreach ($this->manager->attrs() as $key => $attr)
		{
			switch ($key)
			{
				case 'toggle':
				$this->manager->attr('data-toggle', 'buttons-'.$attr);
				break;
				
				case 'vertical':
				$attr === true && $this->manager->addClass('btn-group-vertical');
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
		foreach($this->items as $item)
		{
			// do not override item status...?
			if ($status = $this->manager->attr('status') and ! $item->manager->attr('status'))
			{
				$item->manager->attr("status", $status);
			}
			// Setup global btns size
			if ($size = $this->manager->attr('size'))
			{
				$item->manager->attr("size", $size);
			}
		}
	}
	
}