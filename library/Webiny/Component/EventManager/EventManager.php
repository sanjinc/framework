<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Component\EventManager;

use Webiny\StdLib\SingletonTrait;
use Webiny\StdLib\StdObjectTrait;

/**
 * @package         Webiny\Component\EventManager
 */
class EventManager
{
	use StdObjectTrait, SingletonTrait;

	private $_events;

	public function init() {
		$this->_events = $this->arr();
	}

	public function listen($eventName) {
		if($this->_events->keyExists($eventName)) {
			return $this->_events->key($eventName);
		}
		$eventListener = new EventListener($eventName);
		$this->_events[$eventName] = $eventListener;

		return $eventListener;
	}

	public function subscribe(EventSubscriberInterface $subscriber) {
		$subscriber->subscribe();
	}
}