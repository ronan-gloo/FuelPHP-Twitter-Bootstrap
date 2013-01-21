<?php

namespace Bootstrap;

/**
 * @extends BootstrapModule
 */
class Html_Progress extends BootstrapModule implements Activable {
	
	/**
	 * Store for generated bars
	 * 
	 * (default value: array())
	 * 
	 * @var array
	 * @access protected
	 */
	protected $bars = array();
	
	/**
	 * @access public
	 * @param mixed $value
	 * @return void
	 */
	public function make($value, $content = '')
	{
		// consider first arg as attrs if not numeric
		if (is_array($value))
		{
			$this->manager->attrs($value);
			$value = null;
		}
				
		! is_null($value) and $this->bar($value, $this->manager->attrs(), $content);
		
		return $this;
	}
	
	/**
	 * prepare store bars before rendering progress
	 * 
	 * @access protected
	 * @return void
	 */
	public function render()
	{
		$this->manager->addClass('progress');
		
		foreach ($this->manager->attrs() as $key => $attr)
		{
			switch ($key)
			{
				case 'striped':
				$attr === true and $this->manager->addClass('progress-'.$key);
				break;
				case 'active':
				$attr === true and $this->manager->addClass($this->config->package('class.'.$key));
				break;
				case 'status':
				$this->manager->addClass('progress-'.$attr);
				break;
			}
		}
		$this->html('div', implode($this->bars));
	
		return parent::render();
	}
	
	/**
	 * Set multiple bars.
	 * 
	 * @access public
	 * @return void
	 */
	public function bars()
	{
		foreach (func_get_args() as $array)
		{
			call_user_func_array(array($this, 'bar'), (array)$array);
		}
		return $this;
	}
	
	/**
	 * Generates progress bar
	 * 
	 * @access protected
	 * @return void
	 */
	public function bar($value, $attrs = array(), $content = '')
	{
		$bar = Html_Progress_Bar::forge($attrs)->make($value, $content);
		$this->bars[]	= $bar;
		
		return $bar;
	}
	
	/**
	 * @access public
	 * @param bool $bool (default: true)
	 * @return void
	 */
	public function active($bool = true)
	{
		$this->manager->attr("active", $bool);
		
		return $this;
	}
	
}