<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Component\EventManager;

use Webiny\Component\StdLib\SingletonTrait;
use Webiny\Component\StdLib\StdLibTrait;

/**
 * EventProcessor is a class that takes EventListeners and Event object and processes the event.
 * @package         Webiny\Component\EventManager
 */
class EventProcessor
{
	use StdLibTrait, SingletonTrait;

	/**
	 * Process given event
	 *
	 * @param array|ArrayObject $eventListeners EventListeners that are subscribed to this event
	 * @param Event             $event          Event data object
	 *
	 * @param null|string       $resultType Type of event result to enforce (can be any class or interface name)
	 *
	 * @return array
	 */
	public function process($eventListeners, Event $event, $resultType = null) {

		$eventListeners = $this->_orderByPriority($eventListeners);

		$results = [];

		/* @var $eventListener EventListener */
		foreach ($eventListeners as $eventListener) {

			$handler = $eventListener->getHandler();
			if($this->isNull($handler)){
				continue;
			}

			$method = $eventListener->getMethod();

			if($this->isCallable($handler)) {
				/** @var $handler \Closure */
				$result = $handler($event);
			} else {
				$result = call_user_func_array([$handler, $method], [$event]);
			}

			if($this->isNull($resultType) || (!$this->isNull($resultType) && $this->isInstanceOf($result, $resultType))) {
				$results[] = $result;
			}

			if($event->isPropagationStopped()) {
				break;
			}
		}

		return $results;
	}

	private function _orderByPriority($eventListeners){
		$comparisonFunction = function($a, $b){
			if ($a->getPriority() == $b->getPriority()) {
				// This will keep the order of same priority listeners in the order of subscribing
				return 1;
			}
			return ($a->getPriority() > $b->getPriority()) ? -1 : 1;
		};

		return $this->arr($eventListeners)->sortUsingFunction($comparisonFunction)->val();
	}
}