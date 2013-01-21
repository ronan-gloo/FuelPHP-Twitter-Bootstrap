<?php

namespace Bootstrap;

use
	\Arr,
	\ArrayIterator,
	\Closure
;

class Html_Table extends BootstrapModule implements Unattachable {
	
	protected $caption		= array();
	protected $items			= array();
	protected $properties = array();
	protected $footers		= array();
	
	/**
	 * @var mixed
	 * @access protected
	 */
	protected $callbacks = array(
		"headers"	=> null,
		"cells"		=> null,
		"rows" 		=> null,
	);

	/**
	 * @access public
	 * @param array $items
	 * @param array $properties (default: array())
	 * @return void
	 */
	public function make($items, array $properties = array())
	{
		$this->items((array)$items);
		$this->expose($properties);
		
		// try to check for properties, and init them by default
		if (! ($this->items->count() && count($this->properties)))
		{
			$this->expose(array_keys((array)$this->items->current()));
		}
		
		$this->callbacks["headers"]	= new ArrayIterator(array());
		$this->callbacks["cells"]		= new ArrayIterator(array());
		
		return $this;
	}
	
	/**
	 * Set properies to disaply in table.
	 * Properties can be an associative array or simple array.
	 * If not assoc, keys will become vals.
	 * 
	 * @access public
	 * @return void
	 */
	public function expose($properties)
	{
		! is_array($properties) and $properties = (array)$properties;
		 
		if ($properties and ! Arr::is_assoc($properties))
		{
			$values = ($this->manager->attr("autoheader") === true)
				? $properties
				: array_fill(0, count($properties), "");
				
			$properties = array_combine(array_values($properties), $values);
		}
		$this->properties = $properties;
		return $this;
	}
	
	/**
	 * Set table items, and attach empty attributes to each row
	 * 
	 * @access public
	 * @param array $data
	 * @return void
	 */
	public function items(array $items)
	{
		$this->items = new ArrayIterator($items);
		return $this;
	}
	
	/**
	 * Create a item with data / attributes.
	 * 
	 * @access protected
	 * @param mixed $item
	 * @return void
	 */
	protected function create_item($data, array $attrs = array())
	{
		return compact("data", "attrs");
	}
	
	/**
	 * Table Caption.
	 * 
	 * @access public
	 * @param mixed $caption
	 * @return void
	 */
	public function caption($content, $attrs = array())
	{
		$this->caption = $this->create_item($content, $attrs);
		return $this;
	}
	
	/**
	 * Set footer
	 * 
	 * @access public
	 * @return void
	 */
	public function footer($content, $attrs = array())
	{
		$this->footers[] = $this->create_item((array)$content, $attrs);
		return $this;
	}
	
	/**
	 * 
	 * @access public
	 * @param mixed $key
	 * @param Closure $callback
	 * @return void
	 */
	public function header($key, Closure $callback)
	{
		$this->callbacks["headers"][$key] = $callback;
		return $this;
	}
	
	/**
	 * Register rows callbacks.
	 * 
	 * @access public
	 * @param mixed $num
	 * @param mixed $callback
	 * @return void
	 */
	public function row(Closure $callback)
	{
		$this->callbacks["rows"] = $callback;
		return $this;
	}
	
	/**
	 * Register cells callbacks.
	 * 
	 * @access public
	 * @param mixed $key
	 * @param mixed $callback
	 * @return void
	 */
	public function cell($key, Closure $callback)
	{
		$this->callbacks["cells"][$key] = $callback;
		return $this;
	}
	
	/**
	 * Prepend a new row.
	 * 
	 * @access public
	 * @param mixed $key
	 * @param mixed $name
	 * @param Closure $callback
	 * @return void
	 */
	public function prepend($key, $name, Closure $callback)
	{
		if (! array_key_exists($key, $this->properties))
		{
			$this->properties = array($key => $name) + $this->properties;
			$this->set($key, $name, $callback);
		}		
		return $this;
	}

	/**
	 * Append a new row.
	 * 
	 * @access public
	 * @param mixed $key
	 * @param mixed $name
	 * @param Closure $callback
	 * @return void
	 */
	public function append($key, $name, Closure $callback)
	{
		if (! array_key_exists($key, $this->properties))
		{
			$this->properties[$key] = $name;
			$this->set($key, $name, $callback);
		}		
		return $this;
	}
	
	/**
	 * Add key column before $key.
	 * 
	 * @access public
	 * @param mixed $key
	 * @param mixed $nkey
	 * @param mixed $name
	 * @param Closure $callback
	 * @return void
	 */
	public function before($key, $nkey, $name, Closure $callback)
	{
		return $this->insert($key, $nkey, $name, $callback, __FUNCTION__);
	}
	
	/**
	 * Add key column after $key.
	 * 
	 * @access public
	 * @param mixed $key
	 * @param mixed $nkey
	 * @param mixed $name
	 * @param Closure $callback
	 * @return void
	 */
	public function after($key, $nkey, $name, Closure $callback)
	{
		return $this->insert($key, $nkey, $name, $callback, __FUNCTION__);
	}
	
	/**
	 * Insert before or after.
	 * 
	 * @access protected
	 * @param mixed $key
	 * @param mixed $nkey
	 * @param mixed $name
	 * @param mixed $callback
	 * @param mixed $method
	 * @return void
	 */
	protected function insert($key, $nkey, $name, $callback, $method)
	{
		Arr::{"insert_{$method}_key"}($this->properties, $name, $key);
		$this->properties = Arr::replace_key($this->properties, array(0 => $nkey));
		
		$this->set($nkey, $name, $callback);
		
		return $this;
	}
	
	/**
	 * Set a specific row (can be overritten here).
	 * 
	 * @access public
	 * @param mixed $key
	 * @param mixed $name
	 * @param Closure $callback
	 * @return void
	 */
	protected function set($key, $name, Closure $callback)
	{
		$this->callbacks["cells"]->offsetSet($key, $callback);

		$this->set_item_property($key, $name);
		return $this;
	}
	
	/**
	 * init_property function.
	 * 
	 * @access protected
	 * @param mixed $key
	 * @return void
	 */
	protected function set_item_property($key, $val)
	{
		foreach ($this->items as &$item)
		{
			switch (gettype($item))
			{
				case "array";
				$item[$key] = $val;
				break;
				case "object":
				$item->$key = $val;
				break;
				default:
				$item = $val;
			}
		}
	}
	
	/**
	 * Run the callback.
	 * 
	 * @access protected
	 * @param mixed &$item
	 * @param Closure $callback
	 * @return void
	 */
	protected function callback($item, $data, &$attrs, Closure $callback)
	{
		// if the callback returns something, set the new value
		if ($data = $callback($item, $data, $attrs))
		{
			$item = $data;
		}
		return $item;
	}

	/**
	 * 
	 * @access public
	 * @return void
	 */
	public function render($part = null)
	{		
		$this->manager->addClass("table");
		
		foreach ($this->manager->attrs() as $key => $attr)
		{
			switch($key)
			{
				case "striped":
				case "bordered":
				case "condensed":
				$attr === true and $this->manager->addClass("table-".$key);
				break;
			}
		}
		
		$table = "";
				
		foreach (array("caption", "head", "footer", "body") as $element)
		{
			$table .= $this->{"get_".$element}();
		}
		
		$this->html("table", $table);
		
		return parent::render();
	}
	
	/**
	 * @access public
	 * @return void
	 */
	public function get_caption()
	{
		$caption = "";
		
		if (! empty($this->caption))
		{
			$caption = html_tag("caption", $this->caption["attrs"], $this->caption["data"]);
		}
		return $caption;
	}
	
	/**
	 * Get header data.
	 * 
	 * @access public
	 * @return void
	 */
	public function get_head()
	{
		$head = "";
		
		$callbacks = $this->callbacks["headers"];
		
		foreach ($this->properties as $key => $val)
		{
			$attrs = array();
						
			$data = $callbacks->offsetExists($key)
				? $this->callback($val, $this->properties, $attrs, $callbacks->offsetGet($key))
				: $val;

			$head .= html_tag("th", $attrs, $data);
		}
		return html_tag("thead", array(), html_tag("tr", array(), $head));
	}
	
	/**
	 * render_body function.
	 * 
	 * @access public
	 * @return void
	 */
	public function get_body()
	{
		$rows = "";
		
		$ccallbacks = $this->callbacks["cells"];
		$rcallbacks = $this->callbacks["rows"];
		
		$alternator = ($astr = $this->manager->attr("alternator"))
			? call_user_func_array(array('Str', 'alternator'), explode('|', $astr))
			: function(){ return null; };

		foreach ($this->items as $key => $row)
		{
			$cells	= "";
			$rattrs = array("class" => $alternator());
			
			$rout = ! is_null($rcallbacks)
				? $this->callback($row, $key, $rattrs, $rcallbacks)
				: $row;
			
			// loop throught the row, and build cols
			foreach ($this->properties as $name => $val)
			{
				$value 	= $row[$name];
				$cout		= "";
				$cattrs = array();
	
				// look at cell callbacks
				$cout = $ccallbacks->offsetExists($name)
					? $this->callback($value, $row, $cattrs, $ccallbacks->offsetGet($name))
					: $value;

				// generates cell html
				$cells .= html_tag("td", $cattrs, $cout);
			}
			// generates row html
			$rows .= html_tag("tr", $rattrs, $cells);
		}
		return html_tag("tbody", array(), $rows);
	}
	
	/**
	 * @access public
	 * @return void
	 */
	public function get_footer()
	{
		$footers = "";
		
		if (! $this->footers) return $footers;
		
		foreach ($this->footers as $item)
		{
			$footer = "";
			
			$span = $item["data"] ? floor(count($this->properties) / count($item["data"])) : 0;
			
			foreach ($item["data"] as $element)
			{
				$footer .= html_tag("td", array("colspan" => $span), $element);
			}
			
			$footers .= html_tag("tr", $item["attrs"], $footer);
		}
		return html_tag("tfoot", array(), $footers);
	}
	
	/**
	 * Unattachable implementation
	 * @access public
	 * @param array $attrs (default: array())
	 * @return void
	 */
	public function detach($attached)
	{
		throw new LogicException(__CLASS__.": You can't attach a $attached on table !");
	}	
	
}