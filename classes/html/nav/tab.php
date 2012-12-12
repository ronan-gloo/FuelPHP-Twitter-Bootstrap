<?php

namespace Bootstrap;


/**
 * @extends Html_Nav
 */
class Html_Nav_Tab extends Html_Nav {
	
	protected $use_item = 'Html_Tab_Item';
	
	/**
	 * Check for tabs or pills navtype.
	 * 
	 * @access public
	 * @static
	 * @param array $attrs (default: array())
	 * @return void
	 */
	public function __construct($attrs = array())
	{
		parent::__construct($attrs);
		
		empty($this->attrs['type']) and $this->attrs['type'] = 'tabs';
	}
	
	/**
	 * Setup specific attributes.
	 * 
	 * @access public
	 * @return void
	 */
	public function render()
	{
		if (! empty($this->attrs['stacked']))
		{
			$this->css('nav-stacked');
		}
		
		return parent::render();
	}
	
	
}

