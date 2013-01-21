<?php

namespace Bootstrap;


/**
 * Build Bootstrap breacrumb component.
 */
class Html_Breadcrumb extends BootstrapModule implements ContainsItems {
	
	/**
	 * Breadcrumb items
	 * 
	 * @var mixed
	 * @access protected
	 */
	protected $items = array();
	
	
	/**
	 * generates the divider.
	 * 
	 * @access public
	 * @return void
	 */
	public function make()
	{
		$sep = $this->manager->attr('divider') ?: $this->config->module('divider');
		$this->divider = html_tag('li', array(), html_tag('span', array('class' => 'divider'), $sep));

		return $this;
	}
	
	/**
	 * Add item to the breadcrumb.
	 * 
	 * @access public
	 * @param mixed $anchor
	 * @param mixed $title
	 * @param array $attrs (default: array())
	 * @return void
	 */
	public function item($anchor, $title = '', $attrs = array(), $secure = false)
	{
		$item = Html_Item::forge($attrs)->make($anchor, $title, $secure);
		
		! $title and $item->active();
		
		array_push($this->items, $item, $this->divider);
		
		return $item;
	}
	
	/**
	 * Set multiple items at same time.
	 * 
	 * @access public
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
	 * Render Breadcrumb Html.
	 * 
	 * @access public
	 * @return void
	 */
	public function render()
	{
		$this->manager->addClass('breadcrumb');
		
		$items = $this->items and array_pop($items); // remove last divider
		
		$this->html('ul', implode($items));
		
		return parent::render();
	}
	
}