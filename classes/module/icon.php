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
		if (isset($this->attrs['icon']))
		{
			$css = array();
			
			if (array_key_exists('status', $this->attrs) and $this->attrs['status'] !== 'default')
			{
				$css['class'] = 'icon-white';
			}
			
			$icon = Html::icon($this->attrs['icon'], $css);
			
			(array_key_exists('icon-pos', $this->attrs) and $this->attrs['icon-pos'] == 'right')
				? $text = $text.' '.$icon
				: $text = $icon.' '.$text;
		}
		
		return $this;
	}
	
}