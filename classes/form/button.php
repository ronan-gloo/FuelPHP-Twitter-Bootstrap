<?php

namespace Bootstrap;

class Form_Button extends BootstrapModuleBtn {
	
	public function make($instance, $field, $value)
	{
		$this->data = compact('field', 'value');
		$this->instance = $instance;
		
		return $this;
	}
	
	public function render()
	{
		extract($this->data);
		
		$this->set_button()->set_icon($value);
		$this->manager->mergeAttrs()->clean();

		$this->html = $this->instance->core_button($field, $value, $this->manager->attrs());
		
		return parent::render();
	}
	
	/**
	 * @access public
	 * @param bool $bool (default: true)
	 * @return void
	 */
	public function disabled($bool = true)
	{
		parent::disabled($bool);
		
		if ($bool === true)
		{
			$this->manager->attr('disabled', 'disabled');
		}
		elseif ($bool === false and $this->manager->attr("disabled"))
		{
			$this->manager->removeAttr("disabled");
		}
		return $this;
	}

}