<?php

namespace Bootstrap;

class Html_Icon extends BootstrapModuleIcon {
	
	/**
	 * @access public
	 * @param mixed $icon
	 * @param array $attrs (default: array())
	 * @param bool $tab (default: false)
	 * @return void
	 */
	public function make($icon)
	{
		$this->manager->addClass('icon');
		
		if (is_array($icon))
		{
			$this->manager->attrs($icon);
		}
		else
		{
			$this->manager->attr('icon', $icon);
		}
		if ($status = $this->manager->attr("status"))
		{
			$this->manager->addClass('icon-'.$status);
		}

		$this->manager->addClass('icon-'.$this->manager->attr("icon"));
		
		return $this;
	}
	
	/**
	 * @access public
	 * @return void
	 */
	public function render()
	{
		$this->html('i');
		
		return parent::render();
	}
	
}