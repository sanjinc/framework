Service Manager Component
==========================

Available service configuration parameters are:

* `class`
* `arguments` (`object` & `object_arguments`)
* `abstract`
* `calls`
* `scope`

Extra parameters, for factory services, are:

* `factory` - class or service
* `static` (Optional) - defaults to TRUE, means that `method` will be called statically on `factory` object
* `method` - a method to call on `factory` object
* `method_arguments` (Optional) - method arguments for `method`

There are 2 possible types of scope:

* `container` (Default) - only 1 instance of service is created, and is re-used on each subsequent request of service
* `prototype` - a new instance of service is created each time a service is requested

## Service definition

Basic service definition takes only `class` parameter:
```yaml
services:
    your_service:
        class: \My\Service\Class
```

You can also group your services in logic groups, by nesting the definitions:
```yaml
services:
    # Service in the root of `services` config
    your_logger:
            class: \My\Service\Class         
    # `mailers` service group
    mailers:
        your_mailer:
            class: \My\Mailer\Class
        your_second_mailer:
            class: \My\Second\Mailer\Class          
    # `custom` service group with 2 subgroups
    custom:
        group_1:
            service_1:
                class: \Your\Service\Class
        group_2:
            service_2:
                class: \Your\Service\Class
```

Access these services by using dotted notation: `mailers.your_mailer` or `custom.group_1.service2`.

## Constructor arguments

You can provide constructor arguments to your service class, by using `arguments` parameter. Argument can be any value, including class name (it will be instantiated and passed to constructor as a PHP object) and a reference to another service (enter service reference using `@` character):

```yaml
services:
    your_service:
        class: \My\Service\Class
        arguments: [FirstArgument, [1,2,3], \This\Class\Will\Be\Instantiated, @some.other.service]
```

In case you need to provide constructor parameters to your argument class or service, you will need to use an extended arguments syntax:

```yaml
services:
    your_service:
        class: \My\Service\Class
        arguments: 
            name: FirstArgument
            ids: [1,2,3] 
            some_instance:
                object: \This\Class\Will\Be\Instantiated
                object_arguments: [Name, Y-m-d]
            some_service:
                object: @some.other.service
                object_arguments: [Name]

```

## Service object method calls

In case you need to call some methods on your service instance, you can specify them using `calls` parameter:

```yaml
services:
    your_service:
        class: \My\Service\Class
        arguments: [FirstArgument, [1,2,3], \This\Class\Will\Be\Instantiated, @some.other.service]
        calls:
            - [yourMethod]
            - [yourMethodWithArguments, [Arg1, 123]]
            - [yourMethodWithClassArgument, [\Some\Class\That\Will\Be\Instantiated]]
            - [yourMethodWithServiceArgument, [@some_service]]
```

## Abstract services
Service manager also supports abstract services. When you have 2 or more services sharing similar functionality, you can extract common stuff into an abstract service. In the following example we also use `parameters`. Parameters are like variables, define them once, and reuse whenever you need them:


```yaml
parameters:
    logger.class: \Webiny\Component\Logger\Logger
    logger.driver.class: \Webiny\Component\Logger\Drivers\Webiny
    logger.handler.udp.class: \Webiny\Component\Logger\Drivers\Webiny\Handlers\UDPHandler
    
services:    
    logger:
        handlers:
            udp:
                class: %logger.handler.udp.class%
        tray_logger_abstract:
            abstract: true
            class: %logger.class%
            calls:
              - [addHandler, [@logger.handlers.udp]]
        webiny_system:
            parent: @logger.tray_logger_abstract
            arguments: [System, %logger.driver.class%]
        webiny_ecommerce:
            parent: logger.tray_logger_abstract
            arguments: [Ecommerce, %logger.driver.class%]
```

In this example we defined and abstract service `tray_logger_abstract` and 2 real loggers that extend the abstract service, `webiny_system` and `webiny_ecommerce`. These 2 loggers share same class and method calls, but have different constructor arguments.

You can also specify arguments in abstract class and later override them in the real class. Also, you can add more method calls from child service:

```yaml
parameters:
    logger.class: \Webiny\Component\Logger\Logger
    logger.driver.class: \Webiny\Component\Logger\Drivers\Webiny
    logger.handler.udp.class: \Webiny\Component\Logger\Drivers\Webiny\Handlers\UDPHandler
    
services:    
    logger:
        handlers:
            udp:
                class: %logger.handler.udp.class%
        tray_logger_abstract:
            abstract: true
            class: %logger.class%
            arguments: [Default, %logger.driver.class%]
            calls:
              - [addHandler, [@logger.handlers.udp]]
        webiny_system:
            parent: @logger.tray_logger_abstract
            calls:
            - [setSomething, [someParameter]]
        webiny_ecommerce:
            parent: logger.tray_logger_abstract
            arguments: [Ecommerce, %logger.driver.class%]
```

In this last example, `webiny_system` service will be constructed using the arguments from parent service and will also add an extra method call. `webiny_ecommerce` will provide it's own arguments to the parent constructor and will inherit the parent's `calls`.

If you need to replace a method in `calls` parameter, specify the third argument in call definition with the index of method to replace. In the following example, child method `setSomething` will replace the parent method at index 0, which is `addHandler`:

```yaml
parameters:
    logger.class: \Webiny\Component\Logger\Logger
    logger.driver.class: \Webiny\Component\Logger\Drivers\Webiny
    logger.handler.udp.class: \Webiny\Component\Logger\Drivers\Webiny\Handlers\UDPHandler
    
services:   
    logger:
        handlers:
            udp:
                class: %logger.handler.udp.class%
        tray_logger_abstract:
            abstract: true
            class: %logger.class%
            arguments: [Default, %logger.driver.class%]
            calls:
              - [addHandler, [@logger.handlers.udp]]
        webiny_system:
            parent: @logger.tray_logger_abstract
            calls:
            - [setSomething, [someParameter], 0]
```

If you want to replace all of the parent `calls`, put an exclamation mark in front of the `calls` key, and make it look like this - `!calls`:

```yaml
parameters:
    logger.class: \Webiny\Component\Logger\Logger
    logger.driver.class: \Webiny\Component\Logger\Drivers\Webiny
    logger.handler.udp.class: \Webiny\Component\Logger\Drivers\Webiny\Handlers\UDPHandler
    
services:   
    logger:
        handlers:
            udp:
                class: %logger.handler.udp.class%
        tray_logger_abstract:
            abstract: true
            class: %logger.class%
            arguments: [Default, %logger.driver.class%]
            calls:
              - [addHandler, [@logger.handlers.udp]]
        webiny_system:
            parent: @logger.tray_logger_abstract
            !calls:
            - [setSomething, [someParameter]]
```

In this case, child service `calls` will completely replace parent `calls`.

## Factory services

Factory service will return a product of your factory class, and not the factory class itself. You can define factory service as a static and non-static factory.

### Static factory
```yaml
services:   
    mailer_factory:
        factory: \My\Mailer\Factory
        method: getMailer
        method_arguments: [firstArgument, secondArgument]
```

In PHP this would look like this:
```php
\My\Mailer\Factory::getMailer($firstArgument, $secondArgument);
```

### Non-static factory
```yaml
services:   
    mailer_factory:
        factory: \My\Mailer\Factory
        static: false
        method: getMailer
        method_arguments: [firstArgument, secondArgument]
```

In PHP this would look like this:
```php
$factory = new \My\Mailer\Factory();
return $factory->getMailer($firstArgument, $secondArgument);
```

You can also provide constructor parameters on non-static factories:
```yaml
services:   
    mailer_factory:
        factory: \My\Mailer\Factory
        arguments: [factoryParameter1, factoryParameter2]
        static: false
        method: getMailer
        method_arguments: [firstArgument, secondArgument]
```

A PHP equivalent of this looks like this:
```php
$factory = new \My\Mailer\Factory($factoryParameter1, $factoryParameter2);
return $factory->getMailer($firstArgument, $secondArgument);
```

## Accessing services from PHP

To use `ServiceManager` in your code, the easiest way is to simply use `ServiceManagerTrait`. This will give you access to `$this->service()`.

```php
class YourClass{
    use ServiceManagerTrait;
    
    public function yourMethod(){
        $service = $this->service('your.service');
    }
}
```

If you do need to access ServiceManager class directly, use it like this:

```php
ServiceManager::getInstance()->getService('your.service')
```

## Accessing services by tags
You can group services by using tags and load all of related services using single call. To achieve that, you need to add `tags` key to your service configuration:


```yaml
services:   
    logger:
        webiny_system:
            parent: @logger.tray_logger_abstract
            !calls:
            - [setSomething, [someParameter]]
            tags: [logger]
        webiny_custom:
            parent: @logger.tray_logger_abstract
            tags: [logger, custom_logger]
```

Now execute the following piece of code. The result will be an array containing two services: `webiny_system` and `webiny_custom`:

```php
class YourClass{
    use ServiceManagerTrait;
    
    public function yourMethod(){
        $services = $this->servicesByTag('logger');
    }
}
```

You can also tell `ServiceManager` to filter the services using a given interface or a class name. It fill first fetch all services containing the requested tag and then filter them using the given class or interface name, before returning the final resultset to you. This way you are sure you only get what you need and don't have to make checks yourself, resulting in a cleaner code:

```php
class YourClass{
    use ServiceManagerTrait;
    
    public function yourMethod(){
        $services = $this->servicesByTag('cms_plugin', '\Your\Expected\Class\Or\Interface');
    }
}
```
