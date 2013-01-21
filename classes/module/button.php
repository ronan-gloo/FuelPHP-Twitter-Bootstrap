<?php

namespace Bootstrap;

use \Config;

abstract class BootstrapModuleBtn extends BootstrapModuleIcon implements Activable, Deactivable {
	
	/**
	 * Set button.
	 * 
	 * @access public
	 * @param mixed &$value
	 * @return void
	 */
	protected function set_button()
	{
		$this->manager->addClass('btn');
		
		foreach ($this->manager->attrs() as $key => $attr)
		{
			switch ($key)
			{
				case 'status':
				case 'size':
				$this->manager->addClass('btn-'.$attr);
				break;

				case 'state':
				$this->manager->addClass($this->config->package('class.'.$attr));
				$attr === 'disabled' and $this->manager->attr('disabled', 'disabled');
				break;

				case 'block':
				$attr === true and $this->manager->addClass('btn-block');
				break;
				
				case 'loading':
				$this->manager->attr('data-loading-text', $attr);
				break;
				
				case 'toggle':
				$this->manager->attr('data-toggle', 'button');
				break;
			}
		}
		return $this;
	}
	
	/**
	 * loading text js implementation.
	 * 
	 * @access public
	 * @param string $text (default: '')
	 * @return void
	 */
	public function loading($text = '')
	{
		$this->manager->attr('loading', $text);
		
		return $this;
	}
	
	/**
	 * @access public
	 * @param bool $bool (default: true)
	 * @return void
	 */
	public function toggle($bool = true)
	{
		$this->manager->attr('toggle', $bool);
		
		return $this;
	}
	
	/**
	 * @access public
	 * @param bool $bool (default: true)
	 * @return void
	 */
	public function active($bool = true)
	{
		$this->manager->attr('state', ($bool === true ? $this->config->package('class.active') : ''));
		
		return $this;
	}
	
	/**
	 * @access public
	 * @param bool $bool (default: true)
	 * @return void
	 */
	public function disabled($bool = true)
	{
		$this->manager->attr('state', ($bool === true ? $this->config->package('class.disabled') : ''));
		
		return $this;
	}
	
}