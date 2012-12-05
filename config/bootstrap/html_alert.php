<?php

return array(	
	
	// properties to validate (optionnal) and clean before render
	'attributes' => array(
		'status' 	=> array('error', 'success', 'info'),
		'type'		=> array('block', 'inline'),
		'close'		=> array(true, false, 'fade')
	)
	
);