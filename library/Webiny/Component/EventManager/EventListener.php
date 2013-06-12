<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Component\EventManager;

use Webiny\StdLib\StdObjectTrait;

/**
 * @package         Webiny\Component\EventManager
 */
class EventListener
{
	use StdObjectTrait;

	private $_handler;
	private $_method = 'handle';

	public function handler($handler) {
		$this->_handler = $handler;
		return $this;
	}

	public function method($method){
		$this->_method = $method;
		return $this;
	}
}