<?php

use Webiny\Component\EventManager\Event;
use Webiny\Component\EventManager\EventHandlerInterface;
use Webiny\Component\EventManager\EventManagerTrait;

require_once '../../library/autoloader.php';

class Handler
{

	public function handle(Event $event) {
		$name = $event->name;
		$event->name = 'Adrian';
		return $name;
	}
}

class EventManagerTest
{
	use EventManagerTrait;

	function index() {
		$handler = function(Event $event){
			return $event->name;
		};

		$this->eventManager()->listen('webiny.payment')->handler($handler);
		$this->eventManager()->listen('webiny.payment')->handler(new Handler())->priority(500);


	}

	function event() {
		$data = [
			'name'   => 'pavel',
			'id'     => 12,
			'amount' => 124.99
		];
		$result = $this->eventManager()->fire('webiny.*', $data);
		die(print_r($result));
	}

}

$test = new EventManagerTest();
$test->index();
$test->event();


