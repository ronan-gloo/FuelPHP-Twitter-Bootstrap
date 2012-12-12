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
		
		$this->css('uneditable-input');
		
		if (isset($this->attrs['size']))
		{
			$this->css('input-'.$this->attrs['size']);
		}
		
		$this->html('span', $this->data['text']);
		
		if ($prepend or $append)
		{
			$this->html = html_tag('div', $this->cattrs, $prepend.$this->html.$append);
		}
		
		return parent::render();
	}	
	
	
}