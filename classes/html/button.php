<?php

namespace Bootstrap;

class Html_Button extends BootstrapModuleBtn implements Linkable {
	
	protected $data = array('href' => '', 'text' => '', 'secure' => false);
	
	/**
	 * @access public
	 * @param mixed $href
	 * @param mixed $text
	 * @param mixed $secure
	 * @return void
	 */
	public function make($href = '', $text = '', $secure = false)
	{
		$this->data['href'] 	= $href;
		$this->data['text'] 	= $text;
		$this->data['secure'] = $secure;
				
		return $this;
	}
	
	public function render()
	{
		extract($this->data);
		
		$this
			->set_template()
			->set_button()
			->set_icon($text)
			->merge()
			->clean();

		$this->html = \Html::anchor($href, $text, $this->attrs, $secure);
		$this->html .= $this->append_html;
		
		return parent::render();
	}
	
}