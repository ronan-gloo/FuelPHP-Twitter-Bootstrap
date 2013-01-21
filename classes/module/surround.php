<?php

namespace Bootstrap;


/**
 * MOdule shared by form components (inputs, locked..).
 * 
 * @extends BootstrapModule
 */
class BootstrapModuleSurround extends BootstrapModuleForm {
	
	protected $cattrs 	= array();
	protected $append		= array();
	protected $prepend	= array();
	
	/**
	 * @access public
	 * @return void
	 */
	public function prepend()
	{
		$this->prepend = array_merge($this->prepend, func_get_args());
		
		return $this;
	}
	
	/**
	 * @access public
	 * @return void
	 */
	public function append()
	{
		$this->append = array_merge($this->append, func_get_args());
		
		return $this;
	}
	
	
	/**
	 * Get surrounding elements.
	 * 
	 * @access protected
	 * @return void
	 */
	protected function surround()
	{
		$out[] = $this->prepend_append('prepend');
		$out[] = $this->prepend_append('append');
		
		return $out;
	}

	/**
	 * $type is 'prepend', or 'append' logic.
	 * 
	 * @access protected
	 * @param mixed $type
	 * @return void
	 */
	protected function prepend_append($type)
	{
		// merge prepend / append attributes with prp / app methods
		$array = ($atype = $this->manager->attr($type)) ? array_merge((array)$atype, $this->$type): $this->$type;
		
		if (! $array) return '';
		
		$this->manager->classesToAttr($this->cattrs, array('input-'.$type));
		
		foreach ($array as &$data)
		{
			if ($data instanceof BootstrapModule)
			{
				continue;
			}
			
			if (strpos($data, 'icon-') === 0)
			{
				$icon	= substr($data, 5);
				$data	= Html_Icon::forge(array())->make($icon);
			}
			
			$data = html_tag('span', array('class' => 'add-on'), $data);
		}
		return implode($array);
	}

	
}