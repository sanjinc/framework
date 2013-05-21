Cache Component
===============
Cache component give you ability to store different information into memory for a limited time.

The cache component supports following cache drivers:
* APC (http://php.net/manual/en/book.apc.php)
* Couchbase (http://www.couchbase.com/develop/php/current)
* Memcache (http://php.net/manual/en/book.memcache.php)
* Redis (http://redis.io/clients)

If you are not sure which driver to use, we suggest APC.

Based on the selected driver, you'll have to pass different options to the constuctor method.
Example:

```php
    // APC
    $cache = \Webiny\Component\Cache\Cache::APC('cache-id');

    // Couchbase
    $couchbase = new \Couchbase("127.0.0.1:8091", "username", "password", "default");
    $cache = \Webiny\Component\Cache\Cache::Couchbase('cache-id', $couchbase);

    // Memcache
    $cache = \Webiny\Component\Cache\Cache::Memcache('cache-id', 'localhost', 11211);

    // Redis
    $cache = \Webiny\Component\Cache\Cache::Redis('cache-id', 'localhost', 6379);
```

Once you have created your Cache instance, you can start using your cache. The cache methods are the same, no matter
which driver you use:

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

You can also implement your own cache driver and pass it to the cache class. The custom driver must implement
\Webiny\Bridge\Cache\CacheInterface

```php
    $myCustomCacheDriver = new \CustomCacheDriver();
    $cache = new \Webiny\Component\Cache\Cache($myCustomCacheDriver);
```