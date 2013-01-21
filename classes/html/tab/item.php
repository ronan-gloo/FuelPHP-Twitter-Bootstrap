<?php

namespace Bootstrap;

use \InvalidArgumentException;

/**
 * @extends Html_Item
 */
class Html_Tab_Item extends Html_Item {
	
	/**
	 * Check for dropdown as attribute.
	 * 
	 * @access protected
	 * @return void
	 */
	public function dropdown($dropdown)
	{
		if (! $dropdown instanceof Html_Dropdown)
		{
			throw new InvalidArgumentException(__METHOD__.': Only Html_Dropdown instances are accepted');
		}
		// setup attributes for the item's anchor
		$this->manager->classesToAttr($this->anchor_attrs, array('dropdown-toggle'));
		
		$this->anchor_attrs['data-toggle'] = 'dropdown';

		// add dropdown class to the item
		$merge = $dropdown->manager->attr('type') ?: 'dropdown';
		$this->manager->addClass($merge);
		
		// append caret to the text
		$this->data['text'] .= ' '.html_tag('b', array('class' => 'caret'), '');
		
		// append dropdown to html
		$this->append_html($dropdown);
		
		return $this;
	}

	
}