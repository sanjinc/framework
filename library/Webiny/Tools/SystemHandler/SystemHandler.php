<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 * @package   WebinyFramework
 */

namespace Webiny\Tools\SystemHandler;

/**
 * This class registers handlers for some common system events, like shutdown, exception, error.
 * Note that if one of the registered callbacks stops the execution of the current requests, other callbacks will not be executed.
 * The shutdown stack is executed only if error or exception is not called before.
 *
 * @package         WebinyFramework
 * @category        Tools
 * @subcategory        SystemCallbacks
 */

class SystemHandler
{
    use \Webiny\StdLib\StdLibTrait;

    private static $_shutdownStack = null;
    private static $_exceptionStack = null;
    private static $_errorStack = null;


    /**
     * Register a callback that will be executed upon the shutdown of the current request.
     *
     * @param ShutdownInterface $callback
     * @param bool              $prepend
     */
    static function registerShutdownHandler(ShutdownInterface $callback, $prepend = false) {
        if(!self::isObject(self::$_shutdownStack)) {
            self::$_shutdownStack = self::arr();
        }

        if($prepend) {
            self::$_shutdownStack->prepend($callback);
        } else {
            self::$_shutdownStack->append($callback);
        }
    }

    /**
     * Register a callback that will be executed where an uncaught error occurs in the system.
     *
     * @param ErrorInterface $callback
     * @param bool           $prepend
     */
    static function registerErrorHandler(ErrorInterface $callback, $prepend = false) {
        if(!self::isObject(self::$_errorStack)) {
            self::$_errorStack = self::arr();
        }

        if($prepend) {
            self::$_errorStack->prepend($callback);
        } else {
            self::$_errorStack->append($callback);
        }
    }

    /**
     * Register a callback that will be executed where an uncaught exception occurs in the system.
     *
     * @param ExceptionInterface $callback
     * @param bool               $prepend
     */
    static function registerExceptionHandler(ExceptionInterface $callback, $prepend = false) {
        if(!self::isObject(self::$_exceptionStack)) {
            self::$_exceptionStack = self::arr();
        }

        if($prepend) {
            self::$_exceptionStack->prepend($callback);
        } else {
            self::$_exceptionStack->append($callback);
        }
    }
}