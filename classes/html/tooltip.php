<?php

namespace Bootstrap;

/**
 * Generates a pppover element.
 * 
 * @extends BootstrapModule
 */
class Html_Tooltip extends BootstrapModule implements Unattachable {
	
	/**
	 * Render as static or not
	 * 
	 * (default value: true)
	 * 
	 * @var bool
	 * @access protected
	 */
	protected $static = true;
	
	/**
	 * If popover is not called as static element,
	 * we just returns html attributes to attach to the paretn element
	 * 
	 * @access public
	 * @param bool $static (default: true): display popover as static or not.
	 * @return void
	 */
	public function make($title = '')
	{
		foreach ($this->manager->attrs() as $key => $attr)
		{
			$this->manager->attr('data-'.$key, $attr);
		}
		
		$this->manager->clean();
			
		return array_merge($this->manager->attrs(), array('rel' => 'tooltip', 'title' => $title));
	}
	
	/**
	 * Unattachable implementation
	 * @access public
	 * @param array $attrs (default: array())
	 * @return void
	 */
	public function detach($attached)
	{
		throw new LogicException(__CLASS__.": You can't attach a $attached with tooptip !");
	}	

	
}