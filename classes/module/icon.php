<?php

namespace Bootstrap;

abstract class BootstrapModuleIcon extends BootstrapModule {
	
	/**
	 * @access public
	 * @param mixed &$text
	 * @return void
	 */
	protected function set_icon(&$text)
	{
		$namager = $this->manager;
		
		if ($icon = $namager->attr('icon'))
		{
			$css = array();
			
			if ($status = $namager->attr("status") and $status !== 'default')
			{
				$css['class'] = 'icon-white';
			}
			
			$icon = Html::icon($icon, $css);
			
			$text =  ($pos = $namager->attr("icon-pos") and $pos == 'right')
				? $text.' '.$icon
				: $icon.' '.$text;
		}
		
		return $this;
	}
	
}