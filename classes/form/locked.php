<?php

namespace Bootstrap;

class Form_Locked extends BootstrapModuleSurround {
	
	public function make($text = null)
	{
		$this->data['text'] = $text;
		
		return $this;
	}
	
	public function render()
	{
		list($prepend, $append) = $this->surround();
		
		$this->manager->addClass('uneditable-input');
		
		if ($size = $this->manager->attr("size"))
		{
			$this->manager->addClass('input-'.$size);
		}
		
		$this->html('span', $this->data['text']);
		
		if ($prepend or $append)
		{
			$this->html = html_tag('div', $this->cattrs, $prepend.$this->html.$append);
		}
		
		return parent::render();
	}	
	
	
}