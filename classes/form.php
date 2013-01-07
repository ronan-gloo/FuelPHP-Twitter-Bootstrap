<?php

namespace Bootstrap;

class Form extends \Fuel\Core\Form {
	
	public static function group_open($attrs = array())
	{
		return static::$instance->group_open($attrs);
	}
	public static function group_close()
	{
		return static::$instance->group_close();
	}
	public static function control_open()
	{
		return static::$instance->control_open();
	}
	public static function control_close()
	{
		return static::$instance->control_close();
	}
	public static function search($name, $value = null, array $attrs = array())
	{
		return static::$instance->search($name, $value, $attrs);
	}
	public static function locked($text = null, $attrs = array())
	{
		return static::$instance->locked($text, $attrs);
	}
	public static function help($text = null, $attrs = array())
	{
		return static::$instance->help($text, $attrs);
	}
	public static function typeahead($name, $value = null, $attrs = array())
	{
		return static::$instance->typeahead($name, $value, $attrs);
	}
}