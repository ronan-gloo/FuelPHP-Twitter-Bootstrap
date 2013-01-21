<?php

namespace Bootstrap;

use
	\Closure,
	\InvalidArgumentException,
	\LogicException
;

/**
 * Generate modals.
 * 
 * @extends BootstrapModule
 */
class Html_Modal extends BootstrapModule implements Unattachable {
	
	/**
	 * (default value: array('header', 'body', 'footer'))
	 * 
	 * @var string
	 * @access protected
	 */
	protected $passthru = array('header', 'body', 'footer');
	
	/**
	 * (default value: array('data' => null, 'attrs' => null))
	 * 
	 * @var string
	 * @access protected
	 */
	protected $header	= array('data' => null, 'attrs' => array());
	
	/**
	 * (default value: array('data' => null, 'attrs' => null))
	 * 
	 * @var string
	 * @access protected
	 */
	protected $body		= array('data' => null, 'attrs' => array());
	
	/**
	 * (default value: array('data' => null, 'attrs' => null))
	 * 
	 * @var string
	 * @access protected
	 */
	protected $footer = array('data' => null, 'attrs' => array());
	
	
	/**
	 * Initialize properties arrays.
	 * 
	 * @access public
	 * @return void
	 */
	public function make()
	{
		return $this;
	}
	
	/**
	 * Set an element.
	 * 
	 * @access public
	 * @param mixed $key
	 * @param mixed $data: contents
	 * @param mixed $attrs: html attributes
	 * @return void
	 */
	public function set($key, $data = '', $attrs = array())
	{
		if (! in_array($key, $this->passthru))
		{
			throw new InvalidArgumentException('You can set: '.implode(' - ', $this->passthru));
		}
		// If closure returns void, we assume that dev uses data as reference.
		if ($data instanceof Closure)
		{
			$layout = $this->$key;
			$data		= ($output = $data($layout['data'])) ? $output : $layout['data'];
		}
		$this->$key = compact('data', 'attrs');
		
		return $this;
	}
	
	/**
	 * Set action dismiss button.
	 * Calls html::button object with function args
	 * 
	 * @access public
	 * @return void
	 */
	public function dismiss($href = '#', $text = '', $attrs = array(), $secure = false)
	{
		$attrs['data-dismiss'] = 'modal';
		
		$button = Html_Button::forge($attrs);
		
		$this->footer['data'] .= $button->make($href, $text, $secure);
		
		return $this;
	}
	
	/**
	 * submit function.
	 * 
	 * @access public
	 * @param string $href (default: '#')
	 * @param string $text (default: '')
	 * @param array $attrs (default: array())
	 * @param bool $secure (default: false)
	 * @return void
	 */
	public function submit($href = '#', $text = '', $attrs = array(), $secure = false)
	{
		$button = Html_Button::forge($attrs);
		
		$this->footer['data'] .= $button->make($href, $text, $secure);
		
		return $this;
	}
	
	/**
	 * Render the modal box.
	 * 
	 * @access public
	 * @return void
	 */
	public function render()
	{
		$html = '';
		
		foreach ($this->passthru as $elem) $html .= $this->{'render_'.$elem}();
		
		$this->manager->addClass('modal');
		
		foreach($this->manager->attrs() as $key => $val)
		{
			switch($key)
			{
				case 'fade':
				case 'hide':
				$val === true and $this->manager->addClass($key);
				break;
			}
		}

		$this->html('div', $html);
		
		return parent::render();
	}
	
	/**
	 * Set the modal header.
	 * 
	 * @access public
	 * @param mixed $html (default: null)
	 * @return void
	 */
	protected function render_header()
	{
		$header = '';
		
		if (! is_null($this->header['data']))
		{			
			if ($this->manager->attr('close'))
			{
				$header .= html_tag('a', array('class' => 'close', 'data-dismiss' => 'modal'), $this->config->module('close'));
			}
			
			$header .= $this->header['data'];
	
			$this->manager->classesToAttr($this->header['attrs'], array('modal-header'));
			$header = html_tag('div', $this->header['attrs'], $header);
		}
		return $header;
	}
	
	
	/**
	 * Set the modal content.
	 * 
	 * @access protected
	 * @return void
	 */
	protected function render_body()
	{
		$body = '';
		
		if (! is_null($this->body['data']))
		{
			$this->manager->classesToAttr($this->body['attrs'], array('modal-body'));
			
			$body = html_tag('div', $this->body['attrs'], $this->body['data']);
		}
		
		return $body;
	}
	
	
	/**
	 * render_footer function.
	 * 
	 * @access protected
	 * @return void
	 */
	protected function render_footer()
	{
		$footer = '';
		
		if (! is_null($this->footer['data']))
		{
			$this->manager->classesToAttr($this->footer['attrs'], array('modal-footer'));
			
			$footer = html_tag('div', $this->footer['attrs'], $this->footer['data']);
		}
		
		return $footer;	
	}
	
	/**
	 * Unattachable implementation
	 * @access public
	 * @param array $attrs (default: array())
	 * @return void
	 */
	public function detach($attached)
	{
		throw new LogicException(__CLASS__.": You can't attach a $attached with modal !");
	}	

	
}