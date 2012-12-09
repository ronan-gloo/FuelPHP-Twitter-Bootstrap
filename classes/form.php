<?php

namespace Bootstrap;

class Form extends \Fuel\Core\Form {
	
	static $forminstance;
	
	/**
	 * When autoloaded this will method will be fired, load once and once only
	 *
	 * @return  void
	 */
	public static function _init()
	{
		parent::_init();
	}
}