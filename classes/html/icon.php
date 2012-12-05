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
		if (is_array($icon))
		{
			$this->attrs = $icon;
		}
		else
		{
			$this->attrs['icon'] = $icon;
		}
				
		$this->set_template();
		
		$this->css('icon');
		
		if (array_key_exists('status', $this->attrs))
		{
			$this->css('icon-'.$this->attrs['status']);
		}

		$this->css('icon-'.$this->attrs['icon']);
		
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