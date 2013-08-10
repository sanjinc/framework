<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Component\StdLib;

use Webiny\Component\StdLib\Exception\Exception;

/**
 * FactoryLoader provides a simple function that can load classes, pass arguments to their constructors and validate
 * if the created instance implements, or extends, a defined class or interface.
 *
 * @package         Webiny\StdLib
 */

trait FactoryLoaderTrait
{

	/**
	 * Create an instance of given $className.
	 *
	 * @param string     $className           Full class name, with namespace, of the class you wish to load.
	 * @param string     $classImplementation If you wish to validate that $className implements this $classImplementation,
	 *                                        just pass full classified interface or class name here.
	 * @param array|null $arguments           A list of arguments passed to $className constructor.
	 *
	 * @throws Exception\Exception
	 * @return mixed|string
	 */
	static protected function factory($className, $classImplementation = '', $arguments = null) {
		try {
			if(class_exists($className)) {
				try {
					$instance = call_user_func_array([
													 new \ReflectionClass($className),
													 'newInstance'
													 ], ((is_null($arguments) ? [] : $arguments)));
				} catch (\Exception $e) {
					throw new Exception($e->getMessage());
				}
			} else {
				throw new Exception('FactoryLoader: The class "' . $className . '" does not exist.');
			}
		} catch (\Exception $e) {
			throw new Exception($e->getMessage());
		}

		if($classImplementation != '') {
			if(!($instance instanceof $classImplementation)) {
				throw new Exception($className . ' must be instance of ' . $classImplementation);
			}
		}

		return $instance;
	}

}