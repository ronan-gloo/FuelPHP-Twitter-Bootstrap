<?php

namespace Bootstrap;

class Form_Help extends BootstrapModuleForm  {
	
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
		! $type = $this->manager->attr("type") and $type = $this->manager->attr('type', 'inline');
		
		$this->manager->addClass('help-'.$type);
		
		$this->html('span', $this->data['text']);
		
		return parent::render();
	}
	
}