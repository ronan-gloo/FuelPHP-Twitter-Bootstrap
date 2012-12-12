<?php

return array(	
	
	// properties to validate (optionnal) and clean before render
	'attributes' => array(
		'striped' => array(true, false),
		'active' 	=> array(true, false),
		'status'	=> array('', 'info', 'success', 'warning', 'danger')
	),
	
	'attribute' => 'status'
	
);