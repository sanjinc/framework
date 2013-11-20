Logger Component
=================

Logger components is used to handle logging during code execution in different parts of the system. Webiny framework uses default system logger during the bootstrap process. You can define as many loggers as you want and use different loggers in different parts of your code or have one logger with multiple handlers to have your logs stored to multiple destinations, ex: stored in a log file, to database and pushed via network to logging service of yours or simply have it send  error logs to your e-mail.

Logger consists of the following functional components:

* `Drivers` - each driver is implemented using PSR-3 Logger Interface. In 99% of cases you will be fine with `Webiny` driver.
* `Handlers` - define how log messages are stored: to file, to database, to sockets, etc.
* `Processors` - used to modify log message data at the time the message is being logged
* `Formatters` - Formatters are used to format log message or multiple messages before handler writes them to destination.


## Configuring a logger
The simplest logger possible will contain a driver, a handler and a formatter. These are the minimum requirements for a logger to function. But we won't go into the simplest loggers, and will jump to Webiny Tray logging as an example, to cover all components at once.

The idea behind this logger is to push all log messages to developer's machine, which has a Tray Notifier installed. Each time a request is made to your application, this logger will pack the whole log message stack and push one big message log to a UDP service (a middleman), which will in turn, unpack it and push it to all the tray applications you have specified in your `components.logger.tray` config parameter.

The complete configuration looks like this:

```yaml
parameters:
    logger.class: \Webiny\Component\Logger\Logger
    logger.driver.class: \Webiny\Component\Logger\Drivers\Webiny
    logger.formatter.tray.class: \Webiny\Component\Logger\Formatters\WebinyTrayFormatter
    logger.processor.file_line.class: \Webiny\Component\Logger\Processors\FileLineProcessor
    logger.processor.memory_usage.class: \Webiny\Component\Logger\Processors\MemoryUsageProcessor

components:
    logger:
        formatters:
            webiny_tray:
                method: new_notification
                date_format: 'Y-m-d H:i:s'
        handlers:
            udp:
                host: 192.168.1.10:41234
        tray:
            - 192.168.1.10:5000
services:
    logger:
        handlers:
            udp:
                class: \Webiny\Component\Logger\Handlers\UDPHandler
                arguments: [[], true, true]
                calls:
                    - [addProcessor, [%logger.processor.file_line.class%]]
                    - [addProcessor, [%logger.processor.memory_usage.class%]]
                    - [setFormatter, [%logger.formatter.tray.class%]]
        webiny_logger:
            class: %logger.class%
            arguments: [System, %logger.driver.class%]
            calls:
                - [addHandler, [@logger.handlers.udp]]
```

`components.logger` contains default configuration parameters for all logger components.

`services.logger` contains logger services as well as handlers services.

## Step 1: Logger service

You begin by defining a logger service, in our case `webiny_logger`. It consists of the main logger class `\Webiny\Component\Logger\Logger` (we use parameters, defined on top of our yaml code), which accepts 2 parameters: logger name and logger driver.
Our logger name is "System" - that's the name that will be used to identify which logger a message was logged by. Driver we are using is `\Webiny\Component\Logger\Drivers\Webiny`, this is the most common logger driver. After logger is instantiated, `ServiceManager` will call a method `addHandler` and add a UDP handler to the logger. As you can see, that handler is defined as a reference to another service. Move on to the next step to see how a handler is configured.

## Step 2: Handler setup

Since handlers are a complex logger component, we define them as services, because you will want to add processors and a formatter to it, pass different constructor parameters, etc.

`services.logger.handlers.udp` contains the definition of UDP handler service: it instantiates `\Webiny\Component\Logger\Handlers\UDPHandler` class, which can take the following constructor parameters:

####HandlerInterface parameters:

* `$levels = []` - array of log levels this handler will process (alrt, info, etc.) - empty array to process all levels.
* `$bubble = true` - whether the messages that are handled can bubble up the stack or not
* `$buffer = false` - buffer messages until system shutdown or output them as they are being logged

####UDPHandler specific parameters:

* `UrlObject $host = null` - destination host for log messages (if `null`, this parameter will be taken from `components.handlers.udp.host`)


`components.handlers.udp.host` tells the UDP handler where to push the messages, it's the IP:PORT combination of the middleman service, a dispatcher, in other words. You can also specify a different destination for each UDP handler in service definition, directly.

In the `calls` parameter, you specify processors and a formatter for this handler. In our example, we add a `\Webiny\Component\Logger\Processors\FileLineProcessor` to add a line number, where the message logging took place, and we add a `\Webiny\Component\Logger\Processors\MemoryUsageProcessor` to add current memory usage. In the end we set handler formatter, `\Webiny\Component\Logger\Formatters\WebinyTrayFormatter`. Formatter specific parameters can be set in `components.logger.formatters.webiny_tray`.

Notice: Webiny Tray Notifier uses JSON-RPC protocol. In case you want to create your own application for accepting logs, desired RPC method can be set using `components.logger.formatters.webiny_tray.method`.