<?php

namespace Bootstrap;


interface Activable {
	public function active($bool = true);
}

interface Deactivable {
	public function disabled($bool = true);
}

interface Nestable {
	public function nest($instance = array());
}

/**
 * Prevent some module to be attached (ie; attach popover on popover...etc).
 */
interface Unattachable {
	public function detach($attached);
}

interface ContainsItems {
	public function item($anchor, $title = '', $attrs = array(), $secure = false);
	public function items();
}

/**
 * BootstraoModule checks for Linkable instance when attaching a modal
 */
interface Linkable {}