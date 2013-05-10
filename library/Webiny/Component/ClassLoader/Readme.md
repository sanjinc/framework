ClassLoader Component
=====================
Class loader component loads your PHP files automatically as long as they follow some standard naming convention.
For naming standard please refer to Webiny coding standard PDF od PSR-0 naming convention.

To use the ClassLoader, get its instance by calling ClassLoader::getInstance() method.
    require_once 'Webiny/Component/ClassLoader/ClassLoader.php'

    use Webiny\Component\ClassLoader;

    ClassLoader::getInstance();

Once you have the ClassLoader instance, you can register map rules. The ClassLoader automatically detects if you are
registering a namespace or a PEAR rule. PEAR rules are identified by having a underline '_' at the end of the prefix.

    ClassLoader::getInstance()->registerMap([
    										// a namespace rule
    										'Webiny' => realpath(dirname(__FILE__)).'/library',
    										// a PEAR rule
    										'Swift_' => realpath(dirname(__FILE__)).'/library/Swift',
    										]);

As you can see the registerMap method takes an array of multiple rules. Each rule consists of a prefix and a location.

For better performance you can provide a Cache component to ClassLoader. Doing so, ClassLoader will cache the paths and
files resulting in a faster performance.

    ClassLoader::getLoader()->setCache(Cache::getInstance(Cache::APC));

