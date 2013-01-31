<?php

namespace Bootstrap;

use 
	\Inflector,
	\OutOfBoundsException,
	\InvalidArgumentException
;

/**
 * Provides some initializers and methods for modules.
 */
abstract class BootstrapModule {
	
	/**
	 * Child's class name
	 * 
	 * (default value: null)
	 * 
	 * @var bool
	 * @access protected
	 * @static
	 */
	protected $name = null;
	
	/**
	 * If we nned to append some data at some point (see html())
	 * 
	 * (default value: '')
	 * 
	 * @var string
	 * @access protected
	 */
	protected $append_html = '';
	
	/**
	 * Where instances data should be stored to prevent collisions
	 * 
	 * (default value: array())
	 * 
	 * @var array
	 * @access protected
	 */
	protected $data = array();
	
	
	/**
	 * Config class
	 * 
	 * @var mixed
	 * @access protected
	 */
	protected $config;
	
	/**
	 * Attrs / css manager
	 * 
	 * @var mixed
	 * @access protected
	 */
	protected $manager;
	
	/**
	 * generated Html
	 * 
	 * (default value: '')
	 * 
	 * @var string
	 * @access public
	 */
	public $html	= '';
	
	/**
	 * @access public
	 * @static
	 * @param array $attrs (default: array())
	 * @return void
	 */
	public static function forge($attrs = array())
	{
		return new static($attrs);
	}
	
	/**
	 * @access public
	 * @param array $attrs (default: array())
	 * @return void
	 */
	public function __construct($attrs = array())
	{
		$name	= $this->name ?: strtolower(Inflector::denamespace(get_called_class()));
		$this->config = new Helper_Config($name);
		
		$attribute	= $this->config->module('attribute');
		$attributes	= $this->config->module('attributes');
		$strict			= $this->config->package("strict");
		
		$this->manager = new Helper_Attribute($attrs, $attribute, $attributes, $strict);
		$this->manager->addTemplate($this->config->module('templates'));
	}

	/**
	 * Clean attributes and Build final html.
	 * 
	 * @access public
	 * @return void
	 */
	protected function html($tag = null, $content = '')
	{
		// check for attrs to clean
		$this->manager->mergeAttrs()->clean();
		
		// extra contents set by the append() method
		$this->append_html and $content .= $this->append_html;
		
		$this->html = html_tag($tag, $this->manager->attrs(), $content);
				
		return $this;
	}
	
	/**
	 * Append elements .
	 * 
	 * @access protected
	 * @param mixed $item
	 * @return void
	 */
	protected function append_html($item)
	{
		$this->append_html = $item;
		
		return $this;
	}
	
	/**
	 * Add hide class to the attrs.
	 * 
	 * @access public
	 * @return void
	 */
	public function hide()
	{
		$this->manager->addClass($this->config->package("class.hide"));
		return $this;
	}
	
	/**
	 * fades.
	 * 
	 * @access public
	 * @return void
	 */
	public function fade($opt = '')
	{
		$this->manager->addClass($this->config->package("class.fade"));
		return $this;
	}
		
	/**
	 * Attach popover to the current element.
	 * 
	 * @access public
	 * @param array $attrs (default: array())
	 * @return void
	 */
	public function popover($title, $content = '', $attrs = array())
	{
		if ($this instanceof Unattachable)
		{
			$this->detach('popover');
		}
		
		$data = Html_Popover::forge($attrs)->make($title, $content, false);
		$this->manager->attrs(array_merge($this->manager->attrs(), $data));

		return $this;
	}

	/**
	 * Attach Tooltip to the current element.
	 * 
	 * @access public
	 * @param array $attrs (default: array())
	 * @return void
	 */
	public function tooltip($text, $attrs = array())
	{
		if ($this instanceof Unattachable)
		{
			$this->detach('tooltip');
		}
		
		$attrs = Html_Tooltip::forge($attrs)->make($text);
		$this->manager->attrs(array_merge($this->manager->attrs(), $attrs));

		return $this;
	}
	
	
	/**
	 * Attach a modal to the current
	 * 
	 * @access public
	 * @param mixed $instance
	 * @return Html_Modal
	 */
	public function modal($instance)
	{
		// can't attach anything
		if ($this instanceof Unattachable)
		{
			$this->detach('modal');
		}
		// only modal accepted
		if (! $instance instanceof Html_Modal)
		{
			throw new InvalidArgumentException(__METHOD__.': only instances of Html_Modal are accepted');
		}
		// module should be linkable
		if (! $this instanceof Linkable)
		{
			throw new LogicException(__METHOD__.": You can't attach modal on non Linkable module");
		}
		
		// first, try to define / override modal id from the href attribute
		if (strpos($this->data['href'], '#') === 0 and strlen($this->data['href']) > 1)
		{
			$id = $instance->manager->attr('id', ltrim($this->data['href'], '#'));
		}
		
		// check if a id atribute exist, or generates it
		if (empty($id))
		{
			$id = $instance->manager->attr('id', 'modal'.crc32(uniqid()));
		}
		
		// setup modal relation attributes
		$this->data['href'] = '#'.$id;
		$this->manager->attr('data-toggle', 'modal');
		
		// append modal html
		$this->append_html .= $instance->render();

		return $this;
	}
	
	/**
	 * @access public
	 * @return void
	 */
	public function render()
	{
		if ($this->config->package("prettyprint") === true)
		{
			import("htmlawed", "vendor/htmlawed");
			return hl_tidy($this->html, "t", "div");
		}
		return $this->html;
	}
	
	/**
	 * Auto output generated html
	 * 
	 * @access public
	 * @return void
	 */
	public function __toString()
	{
		try {
			return $this->render();
		}
		catch (\Exception $e){
			exit($e->getMessage());
		}
	}
	
}