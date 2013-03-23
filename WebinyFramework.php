<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 * @package   WebinyFramework
 */
error_reporting(E_ALL);
ini_set('display_erors', 1);

/**
 * Register default autoloader before we can do anything else.
 */
if(!defined('WF_ENV_STATUS')) {
    define('WF_ABS_PATH', realpath(dirname(__FILE__)));
    function wf_autoload($class) {
        //$path = WF_ABS_PATH.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.str_replace('\\', DIRECTORY_SEPARATOR, $class);
        //$path = str_replace('WF', 'wf', $path);
        $path = WF_ABS_PATH . str_replace('\\', DIRECTORY_SEPARATOR, $class);
        $path = str_replace('WF', '', $path);
        require_once $path . '.php'; // no validations nor error checks to make the script faster
    }

    define('WF_ENV_STATUS', 1);
    spl_autoload_register('wf_autoload');
}

/**
 * WebinyFramework environment setup class.
 * Just include this class in places you wish to use WebinyFramework.
 *
 * @package         WebinyFramework
 */

class WebinyFramework
{
    use \WF\StdLib\SingletonTrait,
        \WF\StdLib\StdLibTrait;

    private $_envStatus = false; // is the environment ready

    function init() {
        if($this->_envStatus) {
            return true;
        }

        // @TODO: Do environment setup here

        // flag environment as ready
        $this->_envStatus = true;

        return true;
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

WebinyFramework::getInstance()->init();