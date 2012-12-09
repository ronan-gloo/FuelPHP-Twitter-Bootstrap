<?php

namespace Bootstrap;

class Form_BootstrapInstance {
	
		/**
	 * input function.
	 * 
	 * @access public
	 * @static
	 * @return void
	 */
	public function input($field, $value = null, array $attributes = array())
	{
		debug(parent);
		return Form_Input::forge($attributes)->make($this, $field, $value);
	}
	
		
}