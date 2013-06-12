<?php
EventManager::getInstance()->listen('neki.event')->handler(function(){});
EventManager::getInstance()->listen('drugi.event')->handler($handler)->method('process');
EventManager::getInstance()->subscribe($subscriber);

class MyHandler implements EventHandlerInterface{

	public function process($eventData){

	}

}

class UserEventSubscriber implements EventSubscriberInterface {
	use EventManagerTrait;

    /**
     * Handle user login events.
     */
    public function onUserLogin($event)
    {
        //
    }

    /**
     * Handle user logout events.
     */
    public function onUserLogout($event)
    {
        //
    }

    /**
     * Register the listeners for the subscriber.
     */
    public function subscribe()
    {
        $this->eventManager()->listen('user.login')->handler($this)->method('onUserLogin');
        $this->eventManager()->listen('user.logout')->handler($this)->method('onUserLogout');
    }

}

class EventManager{
	use SingletonTrait;

	private $_events = [];

	public function listen($eventName){
		$event = new Event($eventName);
		$this->_events[$eventName] = $event;
		return $event;
	}

	public function subscribe(EventSubscriberInterface $subscriber){
		$subscriber->subscribe();
	}
}

class Event implements ArrayAccess{

	private $_name;
	private $_propagationStopped;
	private $_handlers = [];
	private $_eventData [];

	public function __get($name){

	}

	public function __set($name){

	}

	public function __construct($eventName){
		$this->_name = $eventName;
	}

	public function handler($handler){
		$eventHandler = new EventHandler($handler);
		$this->_handlers[] = $eventHandler;
		return $eventHandler;
	}

	public function isPropagationStopped()
    {
        return $this->propagationStopped;
    }

    /**
     * Stops the propagation of the event to further event listeners.
     *
     * If multiple event listeners are connected to the same event, no
     * further event listener will be triggered once any trigger calls
     * stopPropagation().
     *
     * @api
     */
    public function stopPropagation()
    {
        $this->propagationStopped = true;
    }

    /**
     * Gets the event's name.
     *
     * @return string
     *
     * @api
     */
    public function getName()
    {
        return $this->name;
    }
}