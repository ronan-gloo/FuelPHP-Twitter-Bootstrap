<?php

namespace Bootstrap;

use \InvalidArgumentException, \LogicException;

/**
 * Generates a pppover element.
 * 
 * @extends BootstrapModule
 */
class Html_Popover extends BootstrapModule implements Unattachable {
	
	/**
	 * Render as static or not
	 * 
	 * (default value: true)
	 * 
	 * @var bool
	 * @access protected
	 */
	protected $static = true;
	
	/**
	 * Allowed setters
	 * 
	 * (default value: array('title', 'content'))
	 * 
	 * @var string
	 * @access protected
	 */
	protected $passthru = array('title', 'content');
	
	/**
	 * (default value: array('data' => null, 'attrs' => array()))
	 * 
	 * @var string
	 * @access protected
	 */
	protected $title = array('data' => null, 'attrs' => array());
	
	/**
	 * (default value: array('data' => null, 'attrs' => array()))
	 * 
	 * @var string
	 * @access protected
	 */
	protected $content = array('data' => null, 'attrs' => array());
	
	/**
	 * If popover is not called as static element,
	 * we just returns html attributes to attach to the paretn element
	 * 
	 * @access public
	 * @param bool $static (default: true): display popover as static or not.
	 * @return void
	 */
	public function make($title = '', $content = '', $static = true)
	{
		$this->static = $static;
		
		$data = ($this->static === true) ? $this->make_static($title, $content) : $this->make_attributes($title, $content);
				
		return $data;
	}
	
	
	/**
	 * Build a static popover.
	 * 
	 * @access protected
	 * @return void
	 */
	protected function make_static($title, $content)
	{
		$this->title['data']		= $title;
		$this->content['data']	= $content;
		
		return $this;
	}
	
	
	/**
	 * Set title / contents.
	 * 
	 * @access public
	 * @param mixed $key
	 * @param mixed $data
	 * @param array $attrs (default: array())
	 * @return void
	 */
	public function set($key, $data = '', $attrs = array())
	{
		if (! in_array($key, $this->passthru))
		{
			throw new InvalidArgumentException('You can set: '.implode(' - ', $this->passthru));
		}

		if ($data instanceof Closure)
		{
			$layout = $this->$key;
			$data		= ($output = $data($layout['data'])) ? $output : $layout['data'];
		}
		
		$this->$key = compact('data', 'attrs');
		
		return $this;
	}
	
	
	/**
	 * Render static popoper.
	 * 
	 * @access public
	 * @return void
	 */
	public function render()
	{
		if ($this->static === true)
		{
			// set arrow
			$html = html_tag('div', array('class' => 'arrow'), '');
			
			// set title
			$this->_merge($this->title['attrs'], array('popover-title'));
			$html .= html_tag('h3', $this->title['attrs'], $this->title['data']);
			
			// set content
			$this->manager->classesToAttr($this->content['attrs'], array('popover-content'));
			$html .= html_tag('div', $this->content['attrs'], $this->content['data']);
			
			$this->manager->addClass('popover');
			
			if ($placement = $this->manager->attr('placement'))
			{
				$this->manager->addClass($placement);
			}
			
			$this->html('div', $html);
		}
		
		return parent::render();
	}
	
	/**
	 * Only parse attributes
	 * 
	 * @access protected
	 * @return $this->attrs
	 */
	protected function make_attributes($title, $content)
	{
		$title and $this->manager->attr('title', $title);
		$content and $this->manager->attr('content', $content);
		
		foreach ($this->manager->attrs() as $key => $attr)
		{
			$this->manager->attr('data-'.$key, $attr);
		}
		
		$this->manager->clean();
		
		return array_merge($this->manager->attrs(), array('rel' => 'popover'));
	}
	
	/**
	 * Unattachable implementation
	 * @access public
	 * @param array $attrs (default: array())
	 * @return void
	 */
	public function detach($attached)
	{
		throw new LogicException(__CLASS__.": You can't attach a $attached with popover !");
	}	
}