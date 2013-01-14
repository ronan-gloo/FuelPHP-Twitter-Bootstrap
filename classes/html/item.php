<?php

namespace Bootstrap;

use
	\InvalidArgumentException
;

/**
 * Share item element (breadcrumb, dropdowns, tabs).
 * 
 * @extends BootstrapModuleIcon
 */
class Html_Item extends BootstrapModuleIcon implements Activable, Deactivable, Linkable {
	
	protected $name	= 'html_item';
	
	protected $data = array('href' => '', 'text' => '', 'secure' => false);
	
	protected $anchor_attrs = array();
	
	/**
	 * @access public
	 * @param string $anchor (default: '')
	 * @param string $text (default: '')
	 * @param bool $secure (default: false)
	 * @return void
	 */
	public function make($href = '', $text = '', $secure = false)
	{
		$this->data['href'] 	= $href;
		$this->data['text'] 	= $text;
		$this->data['secure'] = $secure;
		
		return $this;
	}
	
	/**
	 * @access public
	 * @return void
	 */
	public function render()
	{		
		$this->set_template()->set_icon($this->data['text']);
		$this->parse_attributes();
		
		extract($this->data);
		
		// setup content
		if ($text)
		{
			if (strpos($href, 'mailto:') === 0)
			{
				$content = \Html::mail_to(substr($href, 7), $text, $secure, $this->anchor_attrs);
			}
			else
			{
				$content = \Html::anchor($href, $text, $this->anchor_attrs, $secure);
			}
		}
		else
		{
			$content = html_tag('span', $this->attrs, $href);
		}
		
		$this->html('li', $content);

		return parent::render();
	}
	
	
	/**
	 * Setup attributes.
	 * 
	 * @access protected
	 * @return void
	 */
	protected function parse_attributes()
	{
		foreach ($this->attrs as $key => $val)
		{
			switch($key)
			{
				case 'active':	
				case 'disabled':
				$val === true and $this->css($key);
				break;
				case 'data-toggle':
				$this->anchor_attrs = array($key => $val);
				unset($this->attrs[$key]);
				break;
			}
		}
		$this->clean();
	}
			
	/**
	 * Set item as active.
	 * 
	 * @access public
	 * @param mixed $bool
	 * @return void
	 */
	public function active($bool = true)
	{
		$this->attrs['active'] = $bool;
		
		return $this;
	}
	
	/**
	 * Set item as disabled.
	 * 
	 * @access public
	 * @param bool $bool (default: true)
	 * @return void
	 */
	public function disabled($bool = true)
	{
		$this->attrs['disabled'] = $bool;
		
		return $this;
	}
		
}