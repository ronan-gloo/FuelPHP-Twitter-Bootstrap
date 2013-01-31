<?php
/**
 * Fuel is a fast, lightweight, community driven PHP5 framework.
 *
 * @package    Fuel
 * @version    1.0
 * @author     Fuel Development Team
 * @license    MIT License
 * @copyright  2010 - 2011 Fuel Development Team
 * @link       http://fuelphp.com
 */


Autoloader::add_core_namespace('Bootstrap');

Autoloader::add_classes(array(
	
	// Extra Helpers
	'Bootstrap\\Helper_Config'		=> __DIR__.'/classes/helper/config.php',
	'Bootstrap\\Helper_Attribute'	=> __DIR__.'/classes/helper/attribute.php',
	
	// Package Classes
	'Bootstrap\\BootstrapModule'					=> __DIR__.'/classes/module.php',
	'Bootstrap\\BootstrapModuleBtn'				=> __DIR__.'/classes/module/button.php',
	'Bootstrap\\BootstrapModuleIcon'			=> __DIR__.'/classes/module/icon.php',
	'Bootstrap\\BootstrapModuleSurround'	=> __DIR__.'/classes/module/surround.php',
	'Bootstrap\\BootstrapModuleForm'			=> __DIR__.'/classes/module/form.php',
	
	// Package Interfaces
	'Bootstrap\\Activable' 		=> __DIR__.'/classes/interfaces.php',
	'Bootstrap\\Deactivable'	=> __DIR__.'/classes/interfaces.php',
	'Bootstrap\\Nestable'			=> __DIR__.'/classes/interfaces.php',
	'Bootstrap\\Linkable'			=> __DIR__.'/classes/interfaces.php',
	'Bootstrap\\ContainsItems'=> __DIR__.'/classes/interfaces.php',
	
	// Form Modules
	'Bootstrap\\Form'						=> __DIR__.'/classes/form.php',
	'Bootstrap\\Form_Button'		=> __DIR__.'/classes/form/button.php',
	'Bootstrap\\Form_Instance'	=> __DIR__.'/classes/form/instance.php',
	'Bootstrap\\Form_Input'			=> __DIR__.'/classes/form/input.php',
	'Bootstrap\\Form_Label'			=> __DIR__.'/classes/form/label.php',
	'Bootstrap\\Form_Locked'		=> __DIR__.'/classes/form/locked.php',
	'Bootstrap\\Form_Help'			=> __DIR__.'/classes/form/help.php',
	'Bootstrap\\Form_Checkbox'	=> __DIR__.'/classes/form/checkbox.php',
	'Bootstrap\\Form_Radio'			=> __DIR__.'/classes/form/radio.php',
	'Bootstrap\\Form_Typeahead'	=> __DIR__.'/classes/form/typeahead.php',
	
	// Html Modules
	'Bootstrap\\Html'						=> __DIR__.'/classes/html.php',
	'Bootstrap\\Html_Alert'			=> __DIR__.'/classes/html/alert.php',
	'Bootstrap\\Html_Badge'			=> __DIR__.'/classes/html/badge.php',
	'Bootstrap\\Html_Breadcrumb'=> __DIR__.'/classes/html/breadcrumb.php',
	'Bootstrap\\Html_Button'		=> __DIR__.'/classes/html/button.php',
	'Bootstrap\\Html_Button_Group' => __DIR__.'/classes/html/button/group.php',
	'Bootstrap\\Html_Button_Dropdown'	=> __DIR__.'/classes/html/button/dropdown.php',
	'Bootstrap\\Html_Carousel'	=> __DIR__.'/classes/html/carousel.php',
	'Bootstrap\\Html_Dropdown'	=> __DIR__.'/classes/html/dropdown.php',
	'Bootstrap\\Html_Dropdown_Item'	=> __DIR__.'/classes/html/dropdown/item.php',
	'Bootstrap\\Html_Icon'			=> __DIR__.'/classes/html/icon.php',
	'Bootstrap\\Html_Img'				=> __DIR__.'/classes/html/img.php',
	'Bootstrap\\Html_Item'			=> __DIR__.'/classes/html/item.php',
	'Bootstrap\\Html_Label'			=> __DIR__.'/classes/html/label.php',
	'Bootstrap\\Html_Modal'			=> __DIR__.'/classes/html/modal.php',
	'Bootstrap\\Html_Nav'				=> __DIR__.'/classes/html/nav.php',
	'Bootstrap\\Html_Nav_Tab'		=> __DIR__.'/classes/html/nav/tab.php',
	'Bootstrap\\Html_Tab_Item'	=> __DIR__.'/classes/html/tab/item.php',
	'Bootstrap\\Html_Nav_List'	=> __DIR__.'/classes/html/nav/list.php',
	'Bootstrap\\Html_Nav_Item'	=> __DIR__.'/classes/html/nav/item.php',
	'Bootstrap\\Html_Popover'		=> __DIR__.'/classes/html/popover.php',
	'Bootstrap\\Html_Progress'	=> __DIR__.'/classes/html/progress.php',
	'Bootstrap\\Html_Progress_Bar'	=> __DIR__.'/classes/html/progress/bar.php',
	'Bootstrap\\Html_Table'			=> __DIR__.'/classes/html/table.php',
	'Bootstrap\\Html_Tooltip'		=> __DIR__.'/classes/html/tooltip.php',
));

/* End of file bootstrap.php */