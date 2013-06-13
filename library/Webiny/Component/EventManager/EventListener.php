<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Component\EventManager;

use Webiny\StdLib\StdLibTrait;
use Webiny\StdLib\StdObject\StdObjectWrapper;


/**
 * EventListener is a class that holds event handler information.
 * A new EventListener is created each time you subscribe to an event
 * @package         Webiny\Component\EventManager
 */
class EventListener
{
	use StdLibTrait;

	private $_handler;
	private $_method = 'handle';
	private $_priority = 100;

	public function handler($handler) {

		if($this->isNumber($handler) || $this->isBoolean($handler) || $this->isEmpty($handler)) {
			throw new EventManagerException(EventManagerException::INVALID_EVENT_HANDLER);
		}

		if($this->isString($handler)) {
			if(!class_exists($handler)) {
				throw new EventManagerException(EventManagerException::INVALID_EVENT_HANDLER);
			}
			$handler = new $handler;
		}
		$this->_handler = $handler;

		return $this;
	}

	/**
	 * @param $method
	 *
	 * @return $this
	 * @throws EventManagerException
	 */
	public function method($method) {
		if(!$this->isString($method) && !$this->isStringObject($method)){
			throw new EventManagerException(EventManagerException::MSG_INVALID_ARG, ['$method', 'string|StringObject']);
		}
		$this->_method = StdObjectWrapper::toString($method);

		return $this;
	}

	public function priority($priority) {
		if(!$this->isNumber($priority)){
			throw new EventManagerException(EventManagerException::MSG_INVALID_ARG, ['$priority', 'integer']);
		}

		if($priority <= 100 || $priority >= 1000) {
			throw new EventManagerException(EventManagerException::INVALID_PRIORITY_VALUE);
		}
		$this->_priority = $priority;

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getHandler() {
		return $this->_handler;
	}

	/**
	 * @return string
	 */
	public function getMethod() {
		return $this->_method;
	}

	/**
	 * @return int
	 */
	public function getPriority() {
		return $this->_priority;
	}


}