<?php

namespace Bootstrap;

/**
 * Html class: extends Core Html to provide Bootstrap markups support.
 * 
 */
class Html extends \Fuel\Core\Html {

	public static function image($src, $attrs = array())
	{
		return Html_Img::forge($attrs)->make($src);
	}

	public static function label($text = '', $attrs = array())
	{
		return Html_Label::forge($attrs)->make($text);
	}
	
	public static function badge($text = '', $attrs = array())
	{
		return Html_Badge::forge($attrs)->make($text);
	}
		
	public static function button($href, $text = null, $attrs = array(), $secure = false)
	{
		return Html_Button::forge($attrs)->make($href, $text, $secure);
	}

	public static function button_group($array = array(), $attrs = array())
	{
		$tmp = is_array($array) ? reset($array) : null;
		
		if (is_array($tmp) or $tmp instanceof Html_Button)
			return Html_Button_Group::forge($attrs)->make($array);
		else
			return Html_Button_Group::forge($array)->make();
	}
	
	public static function button_dropdown($anchor = '', $text = '', $attrs = array(), $secure = false)
	{
		return Html_Button_Dropdown::forge($attrs)->make($anchor, $text, $secure);
	}

	public static function alert($title = null, $text = null, $attrs = array())
	{
		return Html_Alert::forge($attrs)->make($title, $text, $attrs);
	}

	public static function progress($val = null, $attrs = array(), $content = '')
	{
		return Html_Progress::forge($attrs)->make($val, $content);
	}
		
	public static function navlist($attrs = array())
	{
		return Html_Nav_List::forge($attrs);
	}

	public static function navtab($attrs = array())
	{
		return Html_Nav_Tab::forge($attrs);
	}
	
	public static function dropdown($text = '', $attrs = array())
	{
		return Html_Dropdown::forge($attrs)->make($text);
	}

	public static function icon($icon, array $attrs = array())
	{
		return Html_Icon::forge($attrs)->make($icon);
	}

	public static function modal($attrs = array())
	{
		return Html_Modal::forge($attrs)->make();
	}

	public static function tabs(array $attrs = array())
	{
		return Html_Tab::forge($attrs);
	}
	
	public static function carousel(array $attrs = array())
	{
		return Html_Carousel::forge($attrs);
	}	
	
	public static function table($items = array(), array $properties = array(), array $attrs = array())
	{
		return Html_Table::forge($attrs)->make($items, $properties);
	}	
	
	public static function breadcrumb($attrs = array(), $items = array())
	{
		return Html_Breadcrumb::forge($attrs)->make($items);
	}

	public static function popover($attrs = array(), $title = '', $content = '')
	{
		return Html_Popover::forge($attrs)->make($title, $content);
	}
	
}