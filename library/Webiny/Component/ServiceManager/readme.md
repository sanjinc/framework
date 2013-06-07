Service Manager Component
==========================

Example `Service` definition:
```
EventManager:
	class: "%logger.class%"
	arguments: ["EventManager", "%logger.driver.class%"]
	calls:
	  - [addHandler, [@logger.handlers.UDPTray]]
	scope: container #Singleton - default
```

Loading a service:
* Call to ```php $this->service('my.service')```
* `ServiceManager` needs to get all services and try locating the requested service
* Check requested service scope
* If scope is `container` - instantiate service, store instance to cache and return it
* If scope is `request` or `prototype` - do not store the instance

Instantiation process:
* ```php $service = new Service($config); ```
* Assign class, arguments, calls and scope
* Parse arguments
* Parse calls (requires parsing of call arguments)
