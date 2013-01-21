<?php

namespace Bootstrap;

use \Config;

/**
 * Get config from current module or global.
 */
class Helper_Config {
	
	/**
	 * Package basepath config
	 * 
	 * @var mixed
	 * @access protected
	 */
	protected $basepath;
	
	/**
	 * Module name
	 * 
	 * @var mixed
	 * @access protected
	 */
	protected $module;
	
	/**
	 * PAth to modules config file
	 * 
	 * @var mixed
	 * @access protected
	 */
	protected $modulepath;
	
	/**
	 * Setup config paths and load config.
	 * 
	 * @access public
	 * @param mixed $module_name
	 * @return void
	 */
	public function __construct($module_name)
	{
		$namespace				= strtolower(__NAMESPACE__);
		$this->basepath		= $namespace."/".$namespace;
		$this->module			= $module_name;
		$this->modulepath = $namespace."/".$module_name;
	}
	
	/**
	 * Get global config item.
	 * 
	 * @access public
	 * @param mixed $key (default: null)
	 * @return void
	 */
	public function package($key = null)
	{
		return $this->get($this->basepath, $key);
	}
	
	/**
	 * Get local config item.
	 * 
	 * @access public
	 * @param mixed $key (default: null)
	 * @return void
	 */
	public function module($key = null)
	{
		return $this->get($this->modulepath, $key);
	}
	
	/**
	 * Get config item.
	 * 
	 * @access protected
	 * @param mixed $path
	 * @param mixed $key
	 * @param mixed $base
	 * @param mixed $alias
	 * @return void
	 */
	protected function get($path, $key)
	{
		$base = $path;
		
		! is_null($key) and $path .= '.'.$key;

		if (! $config = Config::get($path))
		{
			Config::load($base, true);
			$config = Config::get($path);
		}
		return $config;
	}
	
	
}