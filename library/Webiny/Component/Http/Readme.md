Http Component
==============
# About

The `Http` component consists of a `Response` component and `Request` component.
To access either of the components you have to `use` the `HttpTrait`.

```php
    class MyClass{
        use \Webiny\Component\Http\HttpTrait;

        function myFunction(){
            // access `Request` component
            $this->request();

            // access `Response` component
             $this->response();
        }
    }
```

# `Request` component

The request component provides different helper methods like:
- **getCurrentUrl** - returns current url
- **getClientIp** - returns the IP address of current client
- **isRequestSecured** - checks if the request is behind a 'https' protocol

And a lot of other methods.
**NOTE:** All of the functions check for forwarded response headers and validate them against
the defined list of trusted proxies.

Other than just providing helper functions, the `Request` component give you also objective wrappers for working with
global variables like `$_SERVER`, `$_GET`, `$_POST`, `$_FILES`, `$_SESSION` and `$_COOKIE`.

### `Server`

The `Server` class is a wrapper for all of (documented) $_SERVER properties, based on the list on official php.net documentation.
http://php.net/manual/en/reserved.variables.server.php

Here is an example usage:
```php
class MyClass{
        use \Webiny\Component\Http\HttpTrait;

        function myFunction(){
           // get request method
            $this->request()->server()->requestMethod(); // "GET"
        }
    }
```

**NOTE:** `Server` methods **do not** check forwarded response headers from reverse proxies. They are just a objective
wrapper for $_SERVER properties.
Use the methods from the `Request` class to get client ip, host name, and similar properties that validate against
trusted proxies.

## `$_GET` and `$_POST`

To access the `$_GET` properties use the `query` method, and to access the `$_POST` use the `post` method.
Both methods take two params. First param is the key of the property, and the second is the default value that will be
returned in case if the key does not exist.

Here is an example usage:
```php
class MyClass{
        use \Webiny\Component\Http\HttpTrait;

        function myFunction(){
            // get 'name' param from current query string
            $this->request()->query('name');

            // get 'color' param from $_POST, and if color is not defined, return 'blue'
            $this->request()->post('color', 'blue');
        }
    }
```

## `$_FILES`

The `$_FILES` wrapper provides a much better way of handling uploaded files. The process consists of two steps. In the
first step, you get the file by over `files` method on the `Request` class. After that you can move the file to desired
destination and optionally return a `StdLib\StdObject\FileObject` that allows you different file manipulations and validation
methods.

```php
class MyClass{
        use \Webiny\Component\Http\HttpTrait;

        function myFunction(){
            // get the uploaded file
            $file = $this->request()->files('avatar');

            // move it to desired destination
            $file->store('/var/tmp');

            // get file as file standard object
            $fileObject = $file->asFileObject();
        }
    }
```

## `$_SESSION`

Webiny framework provides you with two built-in session storage handlers, the native handler and a cache handler.
Native handler uses the built-in PHP session handler, while cache handler uses the cache driver defined in your config.
Using the cache handler you can easily share your sessions across multiple servers and boost performance. Current supported
cache drivers are all supported drivers by the `Cache` component.

### Session cache handler configuration

The default defined storage handler is the native handler. If you want to use the cache handler you must first setup a
cache driver (read the `Cache` component readme file) and then just link the cache driver to the session handler like this:

```yaml
    components:
        http:
            session:
                storage:
                    driver: '\Webiny\Component\Http\Request\Session\Storage\CacheStorage'
                    params:
                        cache_id: 'wfc'
                prefix: 'wfs_'
                expiretime: 86400
```

There are two most important properties you have to change, the `driver` and `params.cache_id`. The `driver` property
must point to `\Webiny\Component\Http\Request\Session\Storage\CacheStorage` and `params.cache_id` must have the name
of your cache driver defined under `system.cache`.
No other changes are required in your code, you can work with sessions using the `Request` class like you did before.

### Custom session storage handler

You can implement your own session storage handler by creating a class that implements
`\Webiny\Component\Http\Request\Session\SessionStorageInterface`. After you have created such a class, just point the
`driver` param to your class and, optionally, pass the requested constructor params using the `params` config attribute.

### Working with sessions

To work with sessions is rather easy, just access the current session handler which then provides you with the necessary
session methods like `get`, `save` and `getSessionId`.

Here is an example:

```php
class MyClass{
        use \Webiny\Component\Http\HttpTrait;

        function myFunction(){
            // save into session
            $this->request()->session()->save('my_key', 'some value');

            // read from session
            $this->request()->session()->get('my_key');
        }
    }
```

## `$_COOKIE`

Working with cookies is similar to working with sessions, you have a cookie storage handler that gives you the
necessary methods for storing and accessing cookie values. By default there is only a native built-in storage handler.

### Cookie configuration

The cookie configuration consists of defining the default storage driver and some optional parameters like `prefix`,
`http_only` and `expiretime`.

```yaml
    components:
        http:
            cookie:
                storage:
                    driver: '\Webiny\Component\Http\Request\Cookie\Storage\Native'
                prefix: 'wfc_'
                http_only: 'true'
                expiretime: 86400
```

### Custom cookie storage handler

To implement a custom storage handler, you first need to create you storage handler class wich implements the
`\Webiny\Component\Http\Request\Cookie\CookieStorageHandler` interface. After you have successfully created your class,
you now have to change the `storage.driver` parameter in your cookie configuration to point to your class.

### Working with cookies

In order to read and store cookies you have to get the instance of current cookie storage driver which provides you with
the necessary methods. The `Request` class provides you with that access:

```php
class MyClass{
        use \Webiny\Component\Http\HttpTrait;

        function myFunction(){
            // save cookie
            $this->request()->cookie()->save('my_cookie', 'some value');

            // read cookie
            $this->request()->cookie()->get('my_key');
        }
    }
```

# Response

// TODO