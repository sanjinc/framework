Router Component
================

Router component is used for mapping defined paths/urls to appropriate controllers or services.
Defining a route is rather an easy process, you set a route name and underneath you define the path and the callback.
Here is an example:

```yaml
routes:
    blog:
        path: blog
        callback: MyApp:Blog:index
    blog_tag:
        path: blog/tag/{tag}
        callback: MyApp:Blog:showTag
```

If a route is matched you will get an array that consists of two keys. The `callback` key returns the value of callback
parameter of the matched route. The second key is the `params` key that contains the values of the parameters defined
in the `path` section.

The best way to implement the `Router` component is by using the `RouterTrait`. The `RouterTrait` automatically reads all
the defined routes from your configuration.

## Matching routes

To check if a route matches the given path, use the `RouterTrait` and then just call `$this->router()->match()` method.
The `match` method takes either a string that is representing a url or it can take an instance of `Url` standard object.
If the router successfully matches one of the routes it will return an array with callback and params attributes.
If the router doesn't match any of the routes, it will return false.

```php
class MyClass
{
	use \Webiny\Component\Router\RouterTrait;

	function __construct(){
		$result = $this->router()->match('blog/tag/some_tag');
	}
}
```

This is an example result:

```txt
Array
(
    [callback] => blog_tag
    [params] => Array
        (
            [tag] => some_tag
        )

)
```

**NOTE:** `Router` component always returns the **first route** that matches the given path.

## Generating routes

With the `Router` your don't have write your urls inside your code or views, instead you can generate the urls from the
given routes like this:

```php
class MyClass
{
	use \Webiny\Component\Router\RouterTrait;

	function __construct(){
		// generate
		$url = $this->router()->generate('blog_tag',  ['tag'=>'html5', 'page'=>1]);
	}
}
```

Output:

```txt
http://www.webiny.com/blog/tag/html5/?page=1
```

You see that `Router` replaced the `{tag}` parameter with the provided value, in this case it was `html5`. You can also
notice that we don't have the `page` parameter defined in our route, so the `Router` appended that parameter as a query string.

## Configuration

The `Router` component take only one configuration parameter, and that is the `cache` parameter. If either defines
the name of the cache service that will be used to cache some of the internal mechanisms, or you can set it to `false` if
you don't want the component to use cache.