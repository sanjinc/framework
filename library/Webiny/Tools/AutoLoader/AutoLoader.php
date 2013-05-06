<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 * @package   WebinyFramework
 */

namespace Webiny\Tools\AutoLoader;

use Webiny\StdLib\StdObject\ArrayObject\ArrayObject;
use Webiny\StdLib\ValidatorTrait;

/**
 * Description
 *
 * @package         WebinyFramework
 * @category        Architecture
 * @subcategory        Environment
 */
class AutoLoader
{
	use ValidatorTrait;

	/**
	 * @var ArrayObject
	 */
	static private $_autoloadQueue = null;

    /**
     * Appends or prepends a new autoload function.
     *
     * @param string $autoLoadFunctionName    Name of the function that will be called when a class needs to load.
     * @param bool   $prepend                 Do you want to prepend or append the new auto load function to the current stack.
     *
     * @return bool
     */
    public function registerAutoLoader($autoLoadFunctionName, $prepend = false) {
		self::_init();
		self::$_autoloadQueue->append($autoLoadFunctionName);

        return spl_autoload_register($autoLoadFunctionName, false, $prepend);
    }

	/**
	 * Unregisteres the defined autoader.
	 *
	 * @param string $autoLoadFunctionName Name of the autoloader function you want to unregister.
	 *
	 * @return bool
	 */
	static function unregisterAutoLoader($autoLoadFunctionName){
		return spl_autoload_unregister($autoLoadFunctionName);
	}

    /**
     * Singleton trait.
     * NOTE: This function must be declared static.
     * This function must return:
     * self::_getInstance();
     *
     * @return $this
     */
    static function getInstance() {
        return self::_getInstance();
    }

	static private function _init(){
		if(self::isNull(self::$_autoloadQueue)){
			self::$_autoloadQueue = new ArrayObject([]);
		}
	}
}