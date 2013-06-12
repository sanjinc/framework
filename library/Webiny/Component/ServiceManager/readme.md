Service Manager Component
==========================

Available service configuration parameters are:

* class
* arguments (object & object_arguments)
* abstract
* calls
* scope

Extra parameters, for factory services, are:

* factory - class or service
* static (Optional) - defaults to TRUE, means that `method` will be called statically on `factory` object
* method - a method to call on `factory` object
* method_arguments (Optional) - method arguments for `method`

There are 2 possible types of scope:

* `container` (Default) - only 1 instance of service is created, and is re-used on each subsequent request of service
* `prototype` - a new instance of service is created each time a service is requested

Basic service definition takes only `class` parameter:
```yaml
services:
    YourService:
        class: \My\Service\Class
```

You can provide constructor arguments to your service class, by using `arguments` parameter. Argument can be any value, including class name (it will be instantiated and passed to constructor as a PHP object) and a reference to another service (enter service reference using `@` character):

```yaml
services:
    YourService:
        class: \My\Service\Class
        arguments: ["FirstArgument", [1,2,3], "\This\Class\Will\Be\Instantiated", @some.other.service]
```

In case you need to provide constructor parameters to your argument class or service, you will need to use an extended arguments syntax:

```yaml
services:
    YourService:
        class: \My\Service\Class
        arguments: 
            name: "FirstArgument"
            ids: [1,2,3] 
            some_instance:
                object: "\This\Class\Will\Be\Instantiated"
                object_arguments: ["Name", "Y-m-d"]
            some_service:
                object: @some.other.service
                object_arguments: ["Name"]

```

In case you need to call some methods on your service instance, you can specify them using `calls` parameter:

```yaml
services:
    YourService:
        class: \My\Service\Class
        arguments: ["FirstArgument", [1,2,3], "\This\Class\Will\Be\Instantiated", @some.other.service]
        calls:
            - [yourMethod]
            - [yourMethodWithArguments, ["Arg1", 123]]
            - [yourMethodWithClassArgument, ["\Some\Class\That\Will\Be\Instantiated"]]
            - [yourMethodWithServiceArgument, [@some_service]]
```

Service manager also supports abstract services. When you have 2 or more services sharing similar functionality, you can extract common stuff into an abstract service. In the following example we also use `parameters`. Parameters are like variables, define them once, and reuse whenever you need them:


```yaml
parameters:
    logger.class: "\Webiny\Component\Logger\Logger"
    logger.driver.class: "\Webiny\Component\Logger\Drivers\Webiny"
    
services:    
    TrayLoggerAbstract:
        abstract: true
        class: "%logger.class%"
        calls:
          - [addHandler, [@logger.handlers.UDPTray]]
    WebinySystem:
        parent: @logger.TrayLoggerAbstract
        arguments: ["System", %logger.driver.class%]
    WebinyEcommerce:
        parent: logger.TrayLoggerAbstract
        arguments: ["Ecommerce", "%logger.driver.class%"]
```

In this example we defined and abstract service `TrayLoggerAbstract` and 2 real loggers that extend the abstract service, `WebinySystem` and `WebinyEcommerce`. These 2 loggers share same class and method calls, but have different constructor arguments.

You can also specify arguments in abstract class and later override them in the real class. Also, you can add more method calls from child service:

```yaml
parameters:
    logger.class: "\Webiny\Component\Logger\Logger"
    logger.driver.class: "\Webiny\Component\Logger\Drivers\Webiny"
    
services:    
    TrayLoggerAbstract:
        abstract: true
        class: "%logger.class%"
        arguments: ["Default", %logger.driver.class%]
        calls:
          - [addHandler, [@logger.handlers.UDPTray]]
    WebinySystem:
        parent: @logger.TrayLoggerAbstract
        calls:
        - [setSomething, ["someParameter"]]
    WebinyEcommerce:
        parent: logger.TrayLoggerAbstract
        arguments: ["Ecommerce", "%logger.driver.class%"]
```

In this last example, `WebinySystem` service will be constructed using the arguments from parent service and will also add an extra method call. `WebinyEcommerce` will provide it's own arguments to the parent constructor and will inherit the parent's `calls`.

If you need to replace a method in `calls` parameter, specify the third argument in call definition with the index of method to replace. In the following example, child method `setSomething` will replace the parent method at index 0, which is `addHandler`:

```yaml
parameters:
    logger.class: "\Webiny\Component\Logger\Logger"
    logger.driver.class: "\Webiny\Component\Logger\Drivers\Webiny"
    
services:   
    TrayLoggerAbstract:
        abstract: true
        class: "%logger.class%"
        arguments: ["Default", %logger.driver.class%]
        calls:
          - [addHandler, [@logger.handlers.UDPTray]]
    WebinySystem:
        parent: @logger.TrayLoggerAbstract
        calls:
        - [setSomething, ["someParameter"], 0]
```

If you want to replace all of the parent `calls`, put an exclamation mark in front of the `calls` key, and make it look like this - `!calls`:

```yaml
parameters:
    logger.class: "\Webiny\Component\Logger\Logger"
    logger.driver.class: "\Webiny\Component\Logger\Drivers\Webiny"
    
services:   
    TrayLoggerAbstract:
        abstract: true
        class: "%logger.class%"
        arguments: ["Default", %logger.driver.class%]
        calls:
          - [addHandler, [@logger.handlers.UDPTray]]
    WebinySystem:
        parent: @logger.TrayLoggerAbstract
        !calls:
        - [setSomething, ["someParameter"], 0]
```

In this case, child service `calls` will completely replace parent `calls`.