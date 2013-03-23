<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 * @package   WebinyFramework
 */

namespace WF\Tools\AutoLoader;

/**
 * Description
 *
 * @package         WebinyFramework
 * @category        Architecture
 * @subcategory        Environment
 */

class AutoLoader
{
    use    \WF\StdLib\Singleton;

    /**
     * Appends or prepends a new autoload function.
     *
     * @param string $autoLoadFunctionName    Name of the function that will be called when a class needs to load.
     * @param bool   $prepend                 Do you want to prepend or append the new auto load function to the current stack.
     *
     * @return bool
     */
    public function registerAutoLoader($autoLoadFunctionName, $prepend = false) {
        return spl_autoload_register($autoLoadFunctionName, false, $prepend);
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
}