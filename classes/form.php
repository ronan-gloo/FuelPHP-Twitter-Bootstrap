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
}