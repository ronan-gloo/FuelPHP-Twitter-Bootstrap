<?php

namespace Bootstrap;

class Html_Label extends BootstrapModule {
	
	protected $entity = 'label';
	
	/**
	 * Label contents
	 * 
	 * (default value: '')
	 * 
	 * @var string
	 * @access protected
	 */
	protected $text = '';
	
	/**
	 * 
	 * @access public
	 * @param string $value (default: ''): contents of the label
	 * @return Html_Label
	 */
	public function make($text = '')
	{
		! $this->manager->attrs() and $this->manager->attr("status", 'default');
		
		$this->manager->addClass($this->entity, $this->entity.'-'. $this->manager->attr("status"));
		
		$this->text = $text;
		
		return $this;
	}
	
	
	/**
	 * @access public
	 * @return void
	 */
	public function render()
	{
		$this->html('span', $this->text);
		
		return parent::render();
	}
	
}