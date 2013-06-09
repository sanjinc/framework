Service Manager Component
==========================

Example `Service` definition:
```
MyService:
	class: "%logger.class%"
	arguments: ["EventManager", "%logger.driver.class%"]
	calls:
	  - [addHandler, [@logger.handlers.UDPTray]]
	scope: container #Singleton - default
```
