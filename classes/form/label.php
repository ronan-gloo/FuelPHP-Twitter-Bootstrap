<?php

namespace Bootstrap;

class Form_Label extends BootstrapModuleForm {
	
	public function make($text, $id)
	{
		$this->data['text'] = $text;
		$this->data['id'] 	= $id;
		
		return $this;
	}
	
	public function render()
	{		
		if ($this->instance->group['open'] === true)
		{
			$this->manager->addClass('control-label');
		}
		
		$this->manager->mergeAttrs();
			
		extract($this->data);
	
		$this->html = $this->instance->core_label($text, $id, $this->manager->attrs());
		
		return parent::render();
	}
	
}