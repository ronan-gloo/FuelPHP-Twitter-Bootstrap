<?php

namespace Bootstrap;

abstract class BootstrapModuleForm extends BootstrapModule {
	
	public function instance(\Form_Instance $instance)
	{
		$this->instance = $instance;
		
		return $this;
	}	
}