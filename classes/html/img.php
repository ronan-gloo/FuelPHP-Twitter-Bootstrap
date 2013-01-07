<?php

namespace Bootstrap;

class Html_Img extends BootstrapModule {
	
	/**
	 * @access public
	 * @param mixed $src
	 * @return Html_Img
	 */
	public function make($src)
	{				
		$this->data['src'] = $src;
		
		return $this;
	}
	
	/**
	 * @access public
	 * @return void
	 */
	public function render()
	{
		$css = array_key_exists('type', $this->attrs) ? 'img-'.$this->attrs['type'] : '';

		$this->css($css)->merge()->clean();
		
		$this->html = \Html::img($this->data['src'], $this->attrs);
		
		return parent::render();
	}
	
}