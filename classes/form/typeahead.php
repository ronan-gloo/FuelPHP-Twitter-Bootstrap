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
		
		foreach ($this->attrs as $key => $val)
		{
			switch ($key)
			{
				case 'source':
				$values = array_values((array)$val);
				$this->attrs['data-source'] = htmlentities(json_encode($values));
				break;
				
				case 'multiple':
				$val == true and $this->attrs['data-mode'] = $key;
				break;
				
				case 'items':
				case 'minLength':
				$this->attrs['data-'.$key] = $val;
				break;
			}
		}
		
		$this->attrs['data-provide'] = 'typeahead';
		$this->attrs['autocomplete'] = 'off';
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
	public function data($data, $items = null, $minlength = null)
	{
		$this->attrs['source'] = $data;
		
		(int)$items and $this->attrs['items'] = (int)$items;
		(int)$minlength and $this->attrs['minLength'] = (int)$minlength;
		
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
			$this->attrs['multiple'] = $bool;
			break;
			
			case 0:
			if (isset($this->attrs['multiple'])) unset($this->attrs['multiple']);
			break;
		}
		return $this;
	}
	
}