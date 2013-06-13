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
	 * @return array
	 */
	public function process($eventListeners, Event $event) {
		/**
		 * @TODO: Order listeners by priority
		 */

		$results = [];

		/* @var $eventListener EventListener */
		foreach ($eventListeners as $eventListener) {
			$handler = $eventListener->getHandler();
			$method = $eventListener->getMethod();

			if($this->isCallable($handler)){
				/** @var $handler \Closure */
				$result = $handler($event);
			} else {
				$result = call_user_func_array([$handler, $method], [$event]);
			}
			$results[] = $result;
		}

		return $results;
	}
}