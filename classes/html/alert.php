<?php

namespace Bootstrap;

class Html_Alert extends BootstrapModule {
	
	protected $attribute = 'status';
	
	/**
	 * @access public
	 * @param mixed $title (default: null)
	 * @param mixed $text (default: null)
	 * @param array $attrs (default: array())
	 * @return void
	 */
	public function make($title = null, $text = null)
	{
		$attrs =& $this->attrs;
		
		! array_key_exists('type', $attrs) and $attrs['type'] = 'inline';
		
		$this->css('alert');
		
		foreach ($attrs as $name => $attr)
		{
			switch ($name)
			{
				case 'close':
				$attr and $content[0] = html_tag('a', array('class' => 'close', 'data-dismiss' => 'alert'), '&times;');
				$attr === 'fade' and $this->css('fade in');
				break;
				
				case 'type':
				$content[1] = $attr === 'block'
					? html_tag('h4', array('class' => 'alert-heading'), $title)
					: html_tag('strong', array(), $title);
				break;
				
				case 'status':
				$this->css('alert-'.$attr);
				break;
			}
		}
		
		$content[2] = $text;
		
		// reorder elements
		ksort($content);
		
		return $this->html('div', implode(PHP_EOL, $content));
	}
	
}