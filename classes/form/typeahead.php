<?php

namespace Bootstrap;

class Form_Typeahead extends Form_Input {
	
	/**
	 * @access protected
	 * @return void
	 */
	protected function parse_attrs()
	{
		parent::parse_attrs();
		
		foreach ($this->manager->attrs() as $key => $val)
		{
			switch ($key)
			{
				case 'source':
				$values = array_values((array)$val);
				$this->manager->attr('data-source', htmlentities(json_encode($values)));
				break;
				
				case 'multiple':
				$val == true and $this->manager->attr('data-mode', $key);
				break;
				
				case 'items':
				case 'minLength':
				 $this->manager->attr('data-'.$key, $val);
				break;
			}
		}
		
		$this->manager->attr('data-provide', 'typeahead');
		$this->manager->attr('autocomplete', 'off');
	}
	
	
	/**
	 * Set Typeahead configuration
	 * 
	 * @access public
	 * @param mixed $data
	 * @param mixed $items (default: null)
	 * @param mixed $minlength (default: null)
	 * @return void
	 */
	public function data($data, $items = null, $minle = null)
	{
		$this->manager->attr('source', $data);
		
		$items = (int)$items and $this->manager->attr('items', $items);
		$minle = (int)$minle and $this->manager->attr('minLength', $minle);
		
		return $this;
	}
	
	/**
	 * @access public
	 * @param bool $bool (default: true)
	 * @return void
	 */
	public function multiple($bool = true)
	{
		switch ($bool)
		{
			case 1:
			$this->manager->attr('multiple', $bool);
			break;
			
			case 0:
			$this->manager->removeAttr('multiple');
			break;
		}
		return $this;
	}
	
}