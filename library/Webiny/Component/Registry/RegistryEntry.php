<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Component\Registry;

/**
 * Registry entry class.
 *
 * @package         Webiny\Component\Registry
 */

class RegistryEntry
{
	/**
	 * @var string
	 */
	private $_name;

	/**
	 * Create a RegistryEntry instance.
	 *
	 * @param string $name The name under which the $value will be stored.
	 * @param mixed $value Value you want to store.
	 */
	function __construct($name, $value) {
		$this->{$name} = $value;
		$this->_name = $value;
	}

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

		/*
		if($name == $this->_name) {
			return $this->_value;
		}
		*/

		return $this->{$name};
	}

	/**
	 * Magic method for registering dynamic values to the object.
	 *
	 * @param string $name The name under which you want to store the $value.
	 * @param mixed $value Value that will be stored.
	 */
	public function __set($name, $value) {
		if(is_array($value)) {
			array_walk($value, function (&$item, $key) {
				$item = new RegistryEntry($key, $item);
			});
		}

		$this->{$name} = $value;
	}

	/**
	 * To string magic method.
	 * @return string
	 */
	public function __toString() {
		return (string)$this->_name;
	}
}