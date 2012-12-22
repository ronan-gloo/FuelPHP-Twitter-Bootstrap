<?php

namespace Bootstrap;

class Form extends \Fuel\Core\Form {
	
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
	public static function checkbox($name, $value = null, $checked = null, array $attrs = array())
	{
		return static::$instance->checkbox($name, $value, $checked, $attrs);
	}
	public static function radio($name, $value = null, $checked = null, array $attrs = array())
	{
		return static::$instance->radio($name, $value, $checked, $attrs);
	}
	public static function typeahead($name, $value = null, $attrs = array())
	{
		return static::$instance->typeahead($name, $value, $attrs);
	}

}