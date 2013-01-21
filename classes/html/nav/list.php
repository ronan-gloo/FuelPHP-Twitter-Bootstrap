<?php

namespace Bootstrap;

/**
 * @extends Html_Nav
 * @implements Nestable
 */
class Html_Nav_List extends Html_Nav implements Nestable {
	
	protected $use_item = 'Html_Nav_Item';
	
	/**
	 * Force type
	 * 
	 * @access public
	 * @static
	 * @param array $attrs (default: array())
	 * @return void
	 */
	public function __construct($attrs = array())
	{
		parent::__construct($attrs);
		
		$this->manager->attr('type', 'list');
	}
	
	/**
	 * Create an header separator.
	 * 
	 * @access public
	 * @return void
	 */
	public function header($text, $attrs = array())
	{
		$this->manager->classesToAttr($attrs, array('nav-header'));
		
		$this->items[] = html_tag('li', $attrs, $text);
		
		return $this;
	}
	
	
	/**
	 * set a divider.
	 * 
	 * @access public
	 * @return void
	 */
	public function divider($attrs = array())
	{
		$this->manager->classesToAttr($attrs, array('divider'));
		
		$this->items[] = html_tag('li', $attrs, '');
		
		return $this;
	}
	
	/**
	 * Add new sublist.
	 * 
	 * @access public
	 * @return void
	 */
	public function nest($attrs = array())
	{
		if (! $attrs instanceof Html_Nav_List) $attrs = static::forge($attrs);
		
		$this->items[] = $attrs;
		
		return $attrs;
	}
	
	/**
	 * 
	 * @access public
	 * @return void
	 */
	public function render()
	{
		foreach ($this->items as $k => $item)
		{
			if ($item instanceof Html_Nav)
			{
				$this->items[$k] = html_tag('li', array('nav-sub'), $item);
			}
		}
		return parent::render();
	}
	
}

