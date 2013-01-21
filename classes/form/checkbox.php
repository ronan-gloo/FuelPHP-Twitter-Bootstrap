<?php

namespace Bootstrap;

class Form_Checkbox extends BootstrapModuleForm implements Deactivable {
	
	protected $data			= array();
	protected $entity		= 'checkbox';
	protected $name			= 'form_checkbox';

	/**
	 * @access public
	 * @param mixed $instance
	 * @param mixed $field
	 * @param mixed $value
	 * @param mixed $checked
	 * @return void
	 */
	public function make($field, $value, $checked)
	{
		$this->data['field'] 		= $field;
		$this->data['value'] 		= $value;
		$this->data['checked'] 	= $checked;
		
		return $this;
	}
	
	/**
	 * @access public
	 * @param mixed $str
	 * @param array $attrs (default: array())
	 * @return void
	 */
	public function label($str, array $attrs = array('class' => null))
	{
		$this->data['label']['text'] 	= $str;
		$this->data['label']['attrs'] = $attrs;
		
		return $this;
	}
	
	/**
	 * @access public
	 * @param bool $bool (default: true)
	 * @return void
	 */
	public function checked($bool = true)
	{
		is_bool($bool) and $this->data["checked"] = $bool;
		
		if ($bool === true and $this->manager->attr("disabled"))
		{
			$this->manager->removeAttr("disabled");
		}
		return $this;
	}
	
	/**
	 * disabled function.
	 * 
	 * @access public
	 * @param bool $bool (default: true)
	 * @return void
	 */
	public function disabled($bool = true)
	{
		$muted = $this->config->package("class.muted");
		switch ($bool)
		{
			case 1:
			$this->manager->attr("disabled", 'disabled');
			$this->config->module('automute') and $this->manager->addClass($muted);
			break;
			
			case 0:
			$this->manager->removeAttr("disabled");
			if ($this->config->module('automute') and $this->manager->hasClass($muted))
			{
				$this->manager->removeClass($muted);
			}
			break;
		}
		return $this;
	}
	
	/**
	 * @access public
	 * @param bool $bool (default: true)
	 * @return void
	 */
	public function inline($str = '', array $attrs = array())
	{
		$attrs['class'] = 'inline';
		
		return $this->label($str, $attrs);
	}
	
	/**
	 * @access public
	 * @return void
	 */
	public function render()
	{
		// parse attribute
		foreach ($this->manager->attrs() as $key => $val)
		{
			switch ($key)
			{
				case 'state':
				$this->{$key}();
				break;
				
				case 'inline':
				$css[] = $key;
				$this->label($val);
				break;
				
				case 'label':
				$this->label($val);
				break;
			}
		}

		// Build label and input
		if (isset($this->data['label']))
		{
			$css[] = $this->entity;
			$this->manager->classesToAttr($this->data['label']['attrs'], $css);
			
			if ($id = $this->manager->attr("id"))
			{
				$this->data['label']['attrs']['for'] = $id;
			}
			
			$text		= $this->data['label']['text'];
			$input	= $this->_input();
			
			$this->manager->attrs($this->data['label']['attrs']);
			$this->html('label', $input.$text);
		}
		else
		{
			$this->html = $this->_input();
		}
		
		$this->html = $this->instance->control_open().$this->html;
		
		return parent::render();
	}
	
	/**
	 * @access protected
	 * @return void
	 */
	protected function _input()
	{
		$this->manager->mergeAttrs()->clean();
		
		return Form::instance()->{'core_'.$this->entity}(
			$this->data['field'],
			$this->data['value'],
			$this->data['checked'],
			$this->manager->attrs()
		)->render();
	}
	
}