<?php

namespace Bootstrap;


/**
 * Build Bootstrap breacrumb component.
 */
class Html_Breadcrumb extends BootstrapModule {
	
	
	/**
	 * Default attr key if string is provided
	 * 
	 * (default value: 'divider')
	 * 
	 * @var string
	 * @access protected
	 */
	protected $attribute = 'divider';
	
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
		$sep = array_key_exists('divider', $this->attrs) ? $this->attrs['divider'] : $this->config('divider');
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
	 * Render Breadcrumb Html.
	 * 
	 * @access public
	 * @return void
	 */
	public function render()
	{
		$this->css('breadcrumb');
		
		$items = $this->items and array_pop($items); // remove last divider
		
		$this->html('ul', implode(PHP_EOL, $items));
		
		return parent::render();
	}
	
}