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