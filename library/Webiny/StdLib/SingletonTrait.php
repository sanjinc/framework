<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 * @package   WebinyFramework
 */

namespace Webiny\StdLib;

/**
 * Description
 *
 * @package         Webiny\StdLib
 */

trait SingletonTrait
{
    protected static $_wfInstance;

    /**
     * Singleton trait.
     * NOTE: This function must be declared static.
     * This function must return:
     * self::_getInstance();
     *
     * @return $this
     */
    abstract function getInstance();

    /**
     * @return self;
     */
    final private static function _getInstance() {
		if(isset(static::$_wfInstance)){
			return static::$_wfInstance;
		}else{
			static::$_wfInstance = new static;
			return static::$_wfInstance;
		}
    }

    public function __construct() {
        #$this->exception('You cannot create a new instance of this object. Please use the getInstance static call.');
		if(method_exists($this, '_postConstructCallback')){
			$this->_postConstructCallback();
		}
    }

    final private function __wakeup() {
    }

    final private function __clone() {
    }
}