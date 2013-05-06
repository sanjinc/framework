ClassLoader Component
=====================
Class loader component loads your PHP files automatically as long as they follow some standard naming convention.
For naming standard please refer to Webiny coding standard PDF od PSR-0 naming convention.

To use the ClassLoader, just include it and call ClassLoader::getLoader()->register() method. By default it will have all the rules
to load the default libraries included with WebinyFramework.
    require_once 'WebinyFramework/Component/ClassLoader/ClassLoader.php'

    use WF\Component\ClassLoader;

    ClassLoader::getLoader()->register();

Optionally you can register a namespace prefix:

    ClassLoader::getLoader()->registerNamespace('Monolog', '/vendor/monolog/src');

You can register multiple paths for one namespace, and the ClassLoader will go over them in the same order in which
they were registered.

    ClassLoader::getLoader()->registerNamespace('Monolog', ['/vendor/monolog/src', '/var/www/monolog/]);

    // it's the same as
    ClassLoader::getLoader()->registerNamespace('Monolog', '/vendor/monolog/src');
    ClassLoader::getLoader()->registerNamespace('Monolog', '/var/www/monolog/');

You can also register multiple namespaces and their pats at once, like this:
    ClassLoader::getLoader()->registerNamespace([
        'Monolog'  => ['/vendor/monolog/src', '/var/www/monolog/],
        'WF'       => '/vendor/wf'
    ]);

For better performance you can provide a Cache component to ClassLoader. Doing so, ClassLoader will cache the paths and
files resulting in a faster performance.

    ClassLoader::getLoader()->setCache(Cache::getInstance(Cache::APC));

