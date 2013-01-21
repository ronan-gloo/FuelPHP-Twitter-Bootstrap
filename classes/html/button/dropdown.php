<?php

namespace Bootstrap;

class Html_Button_dropdown extends Html_Dropdown {
	
	protected $btn_attrs = array();
	
	/**
	 * Call the html_button instance to build our button,
	 * the store it for a later usage
	 * 
	 * @access public
	 * @return void
	 */
	public function make($text = null)
	{
		$this->data['args'] = func_get_args();
		
		$this->filter_attributes();
				
		return $this;
	}
	
	/**
	 * Separates specific button attributes and current obj attributes.
	 * 
	 * @access public
	 * @return void
	 */
	protected function filter_attributes()
	{
		$filter = array_flip(array('type', 'split', 'align'));
		
		$attrs = $this->manager->attrs();
		
		$this->btn_attrs = array_diff_key($attrs, $filter);
		$this->manager->attrs(array_intersect_key($attrs, $filter));
	}
	
	/**
	 * Prepare splited button 
	 * 
	 * @access protected
	 * @return void
	 */
	protected function btn_split()
	{
		// store status for split element
		$attrs = $this->toggle_attrs();
		
		if (isset($this->btn_attrs['status']))
		{
			$attrs['status'] = $this->btn_attrs['status'];
		}
		
		// supports size
		if (isset($this->btn_attrs['size']))
		{
			$attrs['size'] = $this->btn_attrs['size'];
		}
		
		$extrab = Html_Button::forge($attrs)->make('#', $this->caret());
		
		return $this->get_button().' '.$extrab;
	}
	
	/**
	 * Prepare classic dropdown
	 * 
	 * @access protected
	 * @return void
	 */
	protected function btn_classic()
	{
		$this->btn_attrs = array_merge($this->btn_attrs, $this->toggle_attrs());
		
		$this->data['args'][1] .= ' '.$this->caret();
		
		return $this->get_button();
	}
	
	/**
	 * get_button function.
	 * 
	 * @access protected
	 * @return void
	 */
	protected function get_button()
	{
		$button = Html_Button::forge($this->btn_attrs);
		
		return call_user_func_array(array($button, 'make'), $this->data['args']);
	}
			
	/**
	 * Autowrap dropdown.
	 * 
	 * @access public
	 * @return void
	 */
	public function render()
	{
		$attrs = array('class' => 'btn-group');
		
		if ($type = $this->manager->attr("type"))
		{
			$attrs['class'] .= ' drop'.$type;
		}

		$button		= $this->manager->attr('split') ? $this->btn_split() : $this->btn_classic();
		$dropdown = parent::render();
				
		$this->html = html_tag('div', $attrs, $button.$dropdown);
		
		return $this->html;
	}
}