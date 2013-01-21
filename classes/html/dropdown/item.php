<?php

namespace Bootstrap;

class Html_Dropdown_Item extends Html_Item implements Nestable {
	
	/**
	 * Add new sublist.
	 * 
	 * @access public
	 * @return void
	 */
	public function nest($instance = array())
	{
		if (! $instance instanceof Html_Dropdown) $instance = Html_Dropdown::forge($instance);
		
		// add sub-menu class
		$this->manager->addClass('dropdown-submenu');
		
		$this->append_html = $instance;
		
		return $instance;
	}

	
}