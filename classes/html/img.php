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
		$css = 'img-'.$this->manager->attr('type');

		$this->manager->addClass($css)->mergeAttrs()->clean();
		
		$this->html = \Html::img($this->data['src'], $this->manager->attrs());
		
		return parent::render();
	}
	
}