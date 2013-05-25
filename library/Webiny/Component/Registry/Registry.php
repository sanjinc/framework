<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Component\Registry;

use Webiny\StdLib\SingletonTrait;

/**
 * A simple registry class. Use it to store information you want to access from different parts of your application.
 *
 * Example:
 * $registry = \Webiny\Component\Registry\Registry::getInstance();
 * // store some data
 * $registry->myKey->subKey = 'Value';
 * // read the data
 * $myData = $registry->myKey->subKey;
 *
 * @package         Webiny\Component\Registry
 */

class Registry
{
	use SingletonTrait;

	/**
	 * Get magic method is used for returning the dynamically assigned values.
	 *
	 * @param string $name Name of the value you want to get.
	 *
	 * @return mixed Value for the given key, or null if the key is not registered.
	 */
	public function __get($name) {
		if(!isset ($this->{$name})) {
			$this->{$name} = new RegistryEntry($name, null);
		}

		return $this->{$name};
	}

	/**
	 * Magic method for registering dynamic values to the object.
	 *
	 * @param string $name  The name under which you want to store the $value.
	 * @param mixed  $value Value that will be stored.
	 */
	public function __set($name, $value) {
		$this->{$name} = $value;
	}

	/**
	 * Check if the $key exists in the registry.
	 *
	 * @param string|array     $name Name of the key you want to check. Send an array if you which to check for deeper levels.
	 * @param null|RegistryEntry $parent Parent under which you which to check for the key.
	 *
	 * @return bool True if the key exists, false if it doesn't.
	 */
	public function exists($name, $parent=null){
		if(!is_array($name)){
			if(!isset($this->{$name})){
				return false;
			}

			return true;
		}else{
			$parent = $this;
			foreach($name as $prop){
				if(!property_exists($parent, $prop)){
					return false;
				}
				$parent = $parent->{$prop};
			}

			return true;
		}
	}
}