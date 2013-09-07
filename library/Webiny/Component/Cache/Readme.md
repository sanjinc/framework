Cache Component
===============
`Cache` component give you ability to store different information into memory for a limited time.

## Supported drivers

The cache component supports following cache drivers:
* `APC` (http://php.net/manual/en/book.apc.php)
* `Couchbase` (http://www.couchbase.com/develop/php/current)
* `Memcache` (http://php.net/manual/en/book.memcache.php)
* `Redis` (http://redis.io/clients)

If you are not sure which driver to use, we suggest `APC`.

Based on the selected driver, you'll have to pass different options to the constructor.

For example:

```php
    // APC
    $cache = \Webiny\Component\Cache\Cache::APC('cache-id');

    // Couchbase
    $cache = \Webiny\Component\Cache\Cache::Couchbase('cache-id', 'username', 'password', 'bucket', '127.0.0.1:8091');

    // Memcache
    $cache = \Webiny\Component\Cache\Cache::Memcache('cache-id', 'localhost', 11211);

    // Redis
    $cache = \Webiny\Component\Cache\Cache::Redis('cache-id', 'localhost', 6379);
```

## Common operations

Once you have created your `Cache` instance, you can start using your cache.
The cache methods are the same, no matter which driver you use:

```php
    // write to cache
    $cache->save('my-key', 'some value', 600, ['tag1', 'tag2']);

    // read from cache
    $cache->read('my-key');

    // delete from cache
    $cache->delete('my-key');

    // delete by tag
    $cache->deleteByTag(['tag1']);
```

## Cache config

The preferred way of defining cache drivers is creating them inside your the config file of your application.

```yaml
    system:
        cache:
            wfc:
                driver: '\Webiny\Component\Cache\Drivers\Memcache::getInstance'
                params:
                    host: '127.0.0.1'
                    port: 11211
                options:
                    status: false
                    ttl: 84600
            frontend:
                 driver: '\Webiny\Component\Cache\Drivers\APC::getInstance'
                 options:
                    ttl: 3600
```

Under `system.cache` you define the cache drivers by giving each of them a `cache id` and underneath you nest its config.
The driver configuration depends on which driver you are using.
The `system.cache.{driver_id}.options.status` defines if the driver is caching or not. Set this to `false` when you want
to turn off the caching by this driver.

The `driver` driver parameter must be a valid callback function that will return an instance of `CacheDriver`.

The benefit of defining cache drivers in this way is that the drivers are initialized the second Webiny Framework is loaded.
This enables you to access the cache either by 'WebinyTrait' or by 'CacheTrait'.

```php
    class MyClass
    {
        use \Webiny\Component\Cache\CacheTrait;

        public function myMethod(){
            $this->cache('frontend')->read('cache_key');
        }
    }
```

The `wfc` is the **system cache driver** used by Webiny Framework. This driver is used by several components such as `ClassLoader'.

## Custom `Cache` driver

To implement you own custom cache driver you must first create a class that will implement `\Webiny\Bridge\Cache\CacheInterface`.
Once you have that class, create another class with a static function that will return an instance of `CacheDriver`.

```php
    class CustomCacheDriver implements \Webiny\Bridge\Cache\CacheInterface
    {
        // implement the interface methods

        // static method that will return the CacheDriver
        function getDriver($cacheId, $param1, $param2, array $options){
            $driver = new static($cacheId, $param1, $param2);

            return \Webiny\Component\Cache\CacheDriver($driver, $options);
        }
    }
```

Now just set your class and the static method as the `driver` inside your config and you can access the cache
over the `CacheTrait`.

