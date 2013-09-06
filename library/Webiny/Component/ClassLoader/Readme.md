ClassLoader Component
=====================
Class loader component loads your PHP files automatically as long as they follow some standard naming convention.
For naming standard please refer to Webiny coding standard PDF od PSR-0 naming convention.

To use the ClassLoader, get its instance by calling ClassLoader::getInstance() method.

```php
    require_once 'Webiny/Component/ClassLoader/ClassLoader.php'

    use Webiny\Component\ClassLoader;

    ClassLoader::getInstance();
```

Once you have the ClassLoader instance, you can register map rules. The ClassLoader automatically detects if you are
registering a namespace or a PEAR rule. PEAR rules are identified by having a underline '_' at the end of the prefix.

```php
    ClassLoader::getInstance()->registerMap([
    										// a namespace rule
    										'Webiny' => realpath(dirname(__FILE__)).'/library/Webiny',
    										// a PEAR rule
    										'Swift_' => realpath(dirname(__FILE__)).'/library/Swift',
    										]);
```

As you can see the registerMap method takes an array of multiple rules. Each rule consists of a prefix and a location.

For better performance you can provide a Cache component to ClassLoader. Doing so, ClassLoader will cache the paths and
files resulting in a faster performance.

```php
    ClassLoader::getLoader()->registerCacheDriver($instanceOfCacheInterface);
```

## Non-standardized libraries

If you have a library that is not following neither the PSR-0 naming convention nor the PEAR naming convention, you'll
have to manually define some of the settings.

Let's take a look at this example:
```yaml
additional_libraries:
    'Jamm\Memory': '../Memory'
    'Psr': '../Psr'
    'CryptLib': '../PHP-CryptLib/lib/CryptLib'
    'OAuth2': '../OAuth2'
    Swift_: '../SwiftMailer/lib/classes'
    Imagine: '../Imagine/lib/Imagine'
    Smarty_:
        path: '../Smarty/libs/sysplugins'
        normalize: false
        case: lower
```yaml

You can see that the `Smarty_` library is defined as an array that has `path`, `normalize` and `case` parameter.

### `path`

Defines the path to the library, relative to the WebinyFramework root.

### `normalize`

The `normalize` parameter tells the autoloder if to change the `_` into directory separators. For example if you have a
class names `Smarty_Internal_Compile` the normalized path would be `Smarty/Internal/Compiler`.
If you set the `normalize` parameter to `false`, the original class name will be used.

### `case`

By default the autoloader transfers all the class names to camel-case, you can set the `case` parameter to `lower` if
you wish that the class names are used in lower case inside the class path.