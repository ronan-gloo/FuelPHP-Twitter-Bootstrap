<?php

namespace Bootstrap;

class Html_Img extends BootstrapModule {
	
	protected $src = '';
	
	/**
	 * @access public
	 * @param mixed $src
	 * @return Html_Img
	 */
	public function make($src)
	{				
		$this->src = $src;
		
		return $this;
	}
	
	public function render()
	{
		$css = array_key_exists('type', $this->attrs) ? 'img-'.$this->attrs['type'] : '';

		$this->css($css)->merge()->clean();
		
		$this->html = \Html::img($this->src, $this->attrs);
		
		return parent::render();
	}
	
}