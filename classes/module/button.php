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
		$this->css('btn');
		
		foreach ($this->attrs as $key => $attr)
		{
			switch ($key)
			{
				case 'status':
				case 'size':
				$this->css('btn-'.$attr);
				break;

				case 'state':
				$this->css(Config::get('bootstrap.utilities.'.$attr));
				$attr === 'disabled' and $this->attrs['disabled'] = 'disabled';
				break;

				case 'block':
				$attr === true and $this->css('btn-block');
				break;
				
				case 'loading':
				$this->attrs['data-loading-text'] = $attr;
				break;
				
				case 'toggle':
				$this->attrs['data-toggle'] = 'button';
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
		$this->attrs['loading'] = $text;
		
		return $this;
	}
	
	/**
	 * @access public
	 * @param bool $bool (default: true)
	 * @return void
	 */
	public function toggle($bool = true)
	{
		$this->attrs['toggle'] = $bool;
		
		return $this;
	}
	
	/**
	 * @access public
	 * @param bool $bool (default: true)
	 * @return void
	 */
	public function active($bool = true)
	{
		$this->attrs['state'] = $bool === true ? Config::get('bootstrap.utilities.active') : '';
		
		return $this;
	}
	
	/**
	 * @access public
	 * @param bool $bool (default: true)
	 * @return void
	 */
	public function disabled($bool = true)
	{
		$this->attrs['state'] = $bool === true ? Config::get('bootstrap.utilities.disabled') : '';
		
		return $this;
	}
	
}