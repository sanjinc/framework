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

use Webiny\StdLib\Exception\Exception;

/**
 * Description
 *
 * @package         Webiny\StdLib
 */

trait SingletonTrait
{
    protected static $_wfInstance;

    /**
     * @return $this;
     */
    final public static function getInstance() {
		if(isset(static::$_wfInstance)){
			return static::$_wfInstance;
		}else{
			static::$_wfInstance = new static;
			return static::$_wfInstance;
		}
    }

	final private function __construct() {
		$this->init();
    }

	protected function init() {}

    final private function __wakeup() {
    }

    final private function __clone() {
    }
}