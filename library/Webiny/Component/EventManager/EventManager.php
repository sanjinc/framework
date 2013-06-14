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
use Webiny\StdLib\StdLibTrait;
use Webiny\StdLib\StdObject\ArrayObject\ArrayObject;

/**
 * @package         Webiny\Component\EventManager
 */
class EventManager
{
	use StdLibTrait, SingletonTrait;

	/**
	 * @var ArrayObject
	 */
	private $_events;

	/**
	 * @var EventProcessor
	 */
	private $_eventProcessor;

	/**
	 * Subscribe to event
	 *
	 * @param string        $eventName
	 * @param EventListener $eventListener
	 *
	 * @return EventListener
	 */
	public function listen($eventName, EventListener $eventListener = null) {
		if($this->isNull($eventListener)) {
			$eventListener = new EventListener();
		}

		$eventListeners = $this->_events->key($eventName, [], true);
		$eventListeners[] = $eventListener;
		$this->_events->key($eventName, $eventListeners);

		return $eventListener;
	}

	/**
	 * Subscribe to events using event subscriber
	 *
	 * @param EventSubscriberInterface $subscriber
	 */
	public function subscribe(EventSubscriberInterface $subscriber) {
		$subscriber->subscribe();
	}

	/**
	 * Fire event
	 *
	 * @param string $eventName
	 * @param mixed  $data
	 *
	 * @param null   $resultType
	 *
	 * @return array
	 */
	public function fire($eventName, $data = null, $resultType = null) {
		if(!$this->_events->keyExists($eventName)) {
			return null;
		}

		$eventListeners = $this->_events->key($eventName);
		if(!$this->isInstanceOf($data, 'Event')) {
			$data = new Event($data);
		}

		return $this->_eventProcessor->process($eventListeners, $data, $resultType);
	}

	/**
	 * Singleton constructor
	 */
	protected function init() {
		$this->_events = $this->arr();
		$this->_eventProcessor = EventProcessor::getInstance();
	}
}