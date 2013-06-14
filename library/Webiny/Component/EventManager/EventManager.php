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
 * EventManager is responsible for handling events. It is the main class for subscribing to events and firing events.<br />
 * Besides regular event names, it supports firing of wildcard events, ex: 'webiny.*' will fire all events starting with 'webiny.'
 *
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
	 * @throws EventManagerException
	 * @return EventListener Return instance of EventListener
	 */
	public function listen($eventName, EventListener $eventListener = null) {

		if(!$this->isString($eventName) || $this->str($eventName)->length() == 0){
			throw new EventManagerException(EventManagerException::INVALID_EVENT_NAME);
		}

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
	 * @return $this Return instance of EventManager
	 */
	public function subscribe(EventSubscriberInterface $subscriber) {
		$subscriber->subscribe();
		return $this;
	}

	/**
	 * Fire event
	 *
	 * @param string $eventName Event to fire
	 * @param mixed|Event  $data
	 *
	 * @param null   $resultType
	 *
	 * @return array Array of results from EventListeners
	 */
	public function fire($eventName, $data = null, $resultType = null) {

		if($this->str($eventName)->endsWith('*')){
			return $this->_fireWildcardEvents($eventName, $data, $resultType);
		}

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

	/**
	 * Process events starting with given prefix (ex: webiny.* will process all events starting with 'webiny.')
	 * @param $eventName
	 * @param $data
	 * @param $resultType
	 *
	 * @return null|array
	 */
	private function _fireWildcardEvents($eventName, $data, $resultType){
		// Get event prefix
		$eventPrefix = $this->str($eventName)->subString(0, -1)->val();
		// Find events starting with the prefix
		$events = [];
		foreach($this->_events as $eventName => $eventListeners){
			if($this->str($eventName)->startsWith($eventPrefix)){
				$events[$eventName] = $eventListeners;
			}
		}

		if($this->arr($events)->count() > 0){
			if(!$this->isInstanceOf($data, 'Event')) {
				$data = new Event($data);
			}

			$results = $this->arr();
			foreach($events as $eventListeners){
				$result = $this->_eventProcessor->process($eventListeners, $data, $resultType);
				$results->merge($result);
			}
			return $results->val();
		}
		return null;
	}
}