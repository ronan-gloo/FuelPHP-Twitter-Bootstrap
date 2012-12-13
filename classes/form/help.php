<?php

namespace Bootstrap;

class Form_Help extends BootstrapModule  {
	
	protected $data	= array('text' => '');
	
	/**
	 * @access public
	 * @param Closure $callback
	 * @return void
	 */
	public function make($text = '')
	{
		$this->data['text'] = $text;
		
		return $this;
	}
		
	/**
	 * render function.
	 * 
	 * @access public
	 * @return void
	 */
	public function render()
	{
		! array_key_exists('type', $this->attrs) and $this->attrs['type'] = 'inline';
		
		$this->css('help-'.$this->attrs['type']);
		
		$this->html('span', $this->data['text']);
		
		return parent::render();
	}
	
}