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
		
		$this
			->set_template()
			->set_button()
			->set_icon($value)
			->merge()
			->clean();

		$this->html = $this->instance->core_button($field, $value, $this->attrs);
		
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
			$this->attrs['disabled'] = 'disabled';
		}
		elseif ($bool === false and array_key_exists('disabled', $this->attrs))
		{
			unset($this->attrs['disabled']);
		}
		return $this;
	}

}