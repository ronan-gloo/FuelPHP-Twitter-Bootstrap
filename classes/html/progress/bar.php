<?php

namespace Bootstrap;

/**
 * Html_Progress_Bar class.
 * 
 * @extends BootstrapModule
 */
class Html_Progress_Bar extends BootstrapModule {
	
	protected $data = array('value' => '', 'content' => '');
	
	/**
	 * 
	 * @access public
	 * @param mixed $value: Value of the width, in percent
	 * @param string $content (default: ''): text
	 * @return void
	 */
	public function make($value, $content = '')
	{
		$this->data['value']		= (float)$value;
		$this->data['content']	= $content;
		
		return $this;
	}
	
	public function render()
	{
		$this->manager->addClass('bar');
		$this->manager->css('width', $this->data['value'].'%');
				
		if ($status = $this->manager->attr("status"))
		{
			$this->manager->addClass('bar-'.$status);
		}
		
		$this->html('div', $this->data['content']);
		
		return parent::render();
	}
	
}