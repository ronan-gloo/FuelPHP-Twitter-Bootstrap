<?php

namespace Bootstrap;

use 
	\Config,
	\Inflector,
	\OutOfBoundsException,
	\InvalidArgumentException
;

/**
 * Provides some initializers and methods for modules.
 */
abstract class BootstrapModule {
	
	/**
	 * Path to the config file / items
	 * 
	 * (default value: null)
	 * 
	 * @var mixed
	 * @access protected
	 * @static
	 */
	protected $configpath = null;
	
	/**
	 * Throw OutOfBoundsException on invalid property or not
	 * 
	 * (default value: true)
	 * 
	 * @var bool
	 * @access protected
	 * @static
	 */
	protected $strict = true;
	
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
	 * Instance attributes
	 * 
	 * (default value: array())
	 * 
	 * @var array
	 * @access protected
	 */
	public $attrs = array();
	
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
	 * Css class for elements
	 * 
	 * (default value: array())
	 * 
	 * @var array
	 * @access public
	 */
	public $css = array();
	
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
		$this->name = $this->name ?: strtolower(Inflector::denamespace(get_called_class()));
		$this->configpath = 'bootstrap/'.$this->name;
		
		// value is an array, that means we have stacked bars
		! is_array($attrs) and $attrs = array($this->attribute => $attrs);

		$this->attrs = $attrs;
		
		$this->config();
	}

	/**
	 * Get config items
	 * 
	 * @access protected
	 * @return void
	 */
	protected function config($key = null)
	{
		$path = $this->configpath;
		
		! is_null($key) and $path .= '.'.$key;

		if (! $config = Config::get($path))
		{
			Config::load($this->configpath, true);
			
			$config = Config::get($path);
		}
		
		return $config;
	}
	
	/**
	 * add css classes
	 * 
	 * @access public
	 * @return void
	 */
	public function css()
	{
		// check for a template if no css rules
		empty($this->css) and $this->set_template();
		
		// store new rules
		$this->css = array_merge($this->css, func_get_args());

		return $this;
	}
	
	/**
	 * Gets template items from module config $key
	 * 
	 * @access public
	 * @param mixed $key: template name
	 * @return Array
	 */
	protected function set_template()
	{
		if (! empty($this->attrs['from']))
		{
			if ($tpl = $this->config('templates.'.$this->attrs['from']))
			{
				$attrs = $this->attrs + $tpl;
			}
			elseif (! $tpl and $this->strict === true)
			{
				throw new OutOfBoundsException(__METHOD__.'(): "'.$this->attrs['from'].'" Template does not exists');
			}
			unset($this->attrs['from']);
		}
		return $this;
	}
		
	/**
	 * Check an arg into array or against value.
	 * 
	 * 
	 * @access protected
	 * @static
	 * @param mixed string $needle
	 * @param mixed $haystack
	 * @return Bool
	 */
	protected function validate($needle, $haystack)
	{
		// use en wild card
		if (in_array('*', (array)$haystack))
		{
			return true;
		}
		switch (is_array($haystack))
		{
			case 1:
			$out = (in_array($needle, $haystack));
			break;
			
			case 0:
			$out = ($needle === $haystack);
			break;
		}
		if ($out === false)
		{
			throw new InvalidArgumentException("'$needle' is not a valid argument for ".get_called_class());
		}
		return $out;
	}
	
	/**
	 * Remove extra attributes before render.
	 * 
	 * @access public
	 * @static
	 * @return void
	 */
	protected function clean()
	{
		if ($this->attrs)
		{
			// Key not defined, throw an exception to prevent exotics html attrs
			$conf = $this->config();
			
			if (empty($conf['attributes']))
			{
				throw new OutOfBoundsException(get_class($this).': "attributes" config key not found or invalid');
			}
			foreach ($conf['attributes'] as $name => $vals)
			{
				if (array_key_exists($name, $this->attrs))
				{
					$vals and $this->strict === true and $this->validate($this->attrs[$name], $vals);
					
					unset($this->attrs[$name]);
				}
			}
		}
		return $this;
	}
	
	/**
	 * Mainly to merge exiting class with specific Bootstrap classes
	 * 
	 * @access public
	 * @static
	 * @param mixed $a_attr
	 * @param mixed $b_attrs
	 * @return void
	 */
	protected function merge()
	{
		return $this->_merge($this->attrs, $this->css);
	}
	
	/**
	 * Merge attr['class'] with css class elements array.
	 * 
	 * @access protected
	 * @param mixed $attrs
	 * @param mixed $css
	 * @return void
	 */
	protected function _merge(&$attrs, $css)
	{
		if (! array_key_exists('class', $attrs))
		{
			$attrs['class'] = '';
		}
		else
		{
			$attrs['class'] .= ' ';
		}
		
		if ($css) $attrs['class'] .= implode(' ', $css);
		
		if (! $attrs['class']) unset($attrs['class']);
		
		return $this;
	}
	
	/**
	 * shortcut.
	 * 
	 * @access protected
	 * @return void
	 */
	protected function build_css()
	{
		return $this->set_template()->merge()->clean();
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
		$this->merge()->clean();
		
		// extra contents set by the append() method
		$this->append_html and $content .= $this->append_html;
		
		$this->html = html_tag($tag, $this->attrs, $content);
				
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
		$this->attrs = array_merge($this->attrs, $data);

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
		$this->attrs = array_merge($this->attrs, $attrs);

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
			$instance->attrs['id'] = ltrim($this->data['href'], '#');
		}
		
		// check if a id atribute exist, or generates it
		if (empty($instance->attrs['id']))
		{
			$instance->attrs['id'] = 'modal'.crc32(uniqid());
		}
		
		// setup modal relation attributes
		$this->data['href'] = '#'.$instance->attrs['id'];
		$this->attrs['data-toggle']	= 'modal';
		
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