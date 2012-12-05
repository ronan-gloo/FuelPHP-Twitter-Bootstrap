<?php

namespace Bootstrap;

/**
 * Share item element (breadcrumb, dropdowns, tabs).
 * 
 * @extends BootstrapModuleIcon
 */
class Html_Nav_Item extends Html_Item implements Nestable {

	/**
	 * Add new sublist.
	 * 
	 * @access public
	 * @return void
	 */
	public function nest($instance = array())
	{
		if (! $instance instanceof Html_Nav_List) $instance = Html_Nav_List::forge($instance);
		
		$this->append_html($instance);
		
		return $instance;
	}

}