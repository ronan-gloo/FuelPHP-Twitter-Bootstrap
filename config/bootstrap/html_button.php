<?php

return array(	
	'attributes' => array(
		'icon'		=> null,
		'icon-pos'=> array('left', 'right'),
		'status'	=> array('primary', 'inverse', 'default', 'warning', 'danger', 'info', 'success'),
		'size'		=> array('small', 'mini', 'large'),
		'state' 	=> array('disabled', 'active', ''),
		'block'		=> array(true, false),
		'loading'	=> null,
		'toggle'	=> array(true, false)
	),
	
	'attribute' => 'status',
	
	'templates' => array(
		'example' => array(
			'status' => 'primary',
			'icon'	 => 'thumbs-up',
			'size'	 => 'large',
			'title'	 => 'Some Title',
			'class'	 => 'button-submit'
		)
	)
);