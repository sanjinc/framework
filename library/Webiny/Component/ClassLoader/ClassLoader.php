<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Component\ClassLoader;

use Webiny\Component\Cache\CacheStorage;


/**
 * Class loader implements a more standardized way of autoloading files.
 *
 * It can load any library that is written using a one of standardized naming conventions, like:
 * - The technical interoperability standards for PHP 5.3 namespaces and
 *   class names (https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md);
 * - The PEAR naming convention for classes (http://pear.php.net/).
 *
 * Example:
 * ClassLoader::getInstance()->registerMap([
 * 											// a namespace rule
 * 											'Webiny' => realpath(dirname(__FILE__)).'/library',
 * 											// a PEAR rule (ends with an underline '_')
 * 											'Swift_' => realpath(dirname(__FILE__)).'/library/Swift'
 * 										  ]);
 *
 * @package         Webiny\Component\ClassLoader
 */
class ClassLoader
{
	static private $_instance = null;

	/**
	 * @var array
	 */
	private $_namespaces = [];
	/**
	 * @var array
	 */
	private $_prefixes = [];
	/**
	 * @var bool
	 */
	private $_includePathLookup = false;
	/**
	 * @var bool|CacheInterface
	 */
	private $_cache = false;

	private $_rules = [];

	private function __construct(){
		// omit the constructor
	}

	/**
	 * Get an instance of ClassLoader.
	 *
	 * @return $this
	 */
	static function getInstance(){
		if(self::$_instance!=null){
			return self::$_instance;
		}

		self::$_instance = new static;
		self::_registerAutoloader();

		return self::$_instance;
	}

	/**
	 * Registers SPL autoload function.
	 */
	static private function _registerAutoloader(){
		spl_autoload_register([self::$_instance, 'getClass'], true, true);
	}

	/**
	 * Sets a cache layer in front of the autoloader.
	 * Unregister the old ClassLoader::getClass autoload method.
	 *
	 * @param CacheStorage $cache Instance of the \Webiny\Component\Cache\Cache class.
	 *
	 * @throws \Exception
	 */
	public function registerCacheDriver(CacheStorage $cache){
		// set cache
		$this->_cache = $cache;

		// unregister the old autoloader
		//spl_autoload_unregister([self::$_instance, 'getClass']);

		// prepend the new cache autoloader
		//spl_autoload_register([self::$_instance, 'getClassFromCache'], true, true);
	}

	/**
	 * Do you want to search inside include path.
	 *
	 * @param bool $includePathLookup Turn on or off the option to search inside the include path.
	 */
	public function includePathLookup($includePathLookup) {
		$this->_includePathLookup = (bool) $includePathLookup;
	}

	/**
	 * Use it to check if autoloader uses the include path or not.
	 *
	 * @return bool
	 */
	public function getIncludePathLookup() {
		return $this->_includePathLookup;
	}

	/**
	 * Get a list of registered namespace maps.
	 *
	 * @return array
	 */
	public function getNamespaces() {
		return $this->_namespaces;
	}

	/**
	 * Get a list of registered PEAR maps.
	 *
	 * @return array
	 */
	public function getPrefixes() {
		return $this->_prefixes;
	}

	/**
	 * Get a list of registered maps.
	 *
	 * @return array
	 */
	public function getMaps(){
		return array_merge($this->_namespaces, $this->_prefixes);
	}

	/**
	 * Register a namespace or PEAR map rule.
	 * NOTE: PEAR rules must end with an underline '_'.
	 *
	 * @param array $maps - Array of maps rules. An example rule is ['Webiny' => '/var/WebinyFramework/library']
	 */
	public function registerMap(array $maps){
		$frameworkAbs = dirname(__FILE__).'/../../';

		foreach($maps as $prefix=>$library){
			// first check the structure of location
			if(is_array($library)){
				$path = $library['path'];
				$this->_rules[$prefix] = $library;
			}else{
				$path = $library;
			}

			// append absolute path
			if(strpos($path, '/')!==0 && strpos($path, ':')!==1){ // linux and windows absolute path
				$path = $frameworkAbs . $path;
			}

			// check if it's a PEAR standard or a namespace
			$endChar = substr($prefix, -1);
			if($endChar=='_'){
				// PEAR standard
				$this->_prefixes[$prefix] = (array) $path;
			}else{
				// namespace
				if($prefix[0]=='\\'){
					$this->_namespaces[substr($prefix, 1)] = (array) $path;
				}else{
					$this->_namespaces[$prefix] = (array) $path;
				}
			}
		}

		$this->_namespaces;
	}

	/**
	 * Tries to find the class file based on currently registered rules.
	 *
	 * @param string $class Name of the class you are trying to find.
	 *
	 * @return bool True is returned if the class if found and loaded into memory.
	 */
	public function getClass($class) {
		if($file = $this->findClass($class)) {
			require $file;

			return true;
		}
	}

	/**
	 * First tries to find the class in the cache. If the class is not found in the cache, then it tries to find it
	 * by using the registered maps.
	 *
	 * @param string $class Name of the class you are trying to find.
	 *
	 * @return bool True is retuned if the class if found and loaded into memory.
	 */
	public function getClassFromCache($class){
		// from cache
		if(($file = $this->_cache->read($class))){
			require $file;

			return true;
		}

		// from disk
		if($file = $this->findClass($class)) {
			$this->_cache->save($class, $file, 600, ['_webiny', '_kernel']);
			require $file;

			return true;
		}
	}

	/**
	 * This function is taken from Symfony (but it has been changed a bit.).
	 * (c) Fabien Potencier <fabien@symfony.com>
	 *
	 * @param string $class The name of the class
	 *
	 * @return string|null The path, if found
	 */
	public function findClass($class) {
		if(($pos = strrpos($class, '\\')) !== false) {
			$namespace = substr($class, 0, $pos);
			$className = substr($class, $pos + 1);

			$normalizedClass = str_replace('\\', DIRECTORY_SEPARATOR,
										   $namespace) . DIRECTORY_SEPARATOR . str_replace('_', DIRECTORY_SEPARATOR,
																						   $className) . '.php';

			foreach ($this->_namespaces as $ns => $dirs) {
				if(0 !== stripos($namespace, $ns)) {
					continue;
				}

				foreach ($dirs as $dir) {
					// create the path to the namespace
					$nsPath = str_replace('\\', DIRECTORY_SEPARATOR, $ns);
					$pos = strpos($normalizedClass, $nsPath);
					if ($pos !== false) {
						$normalizedClass = substr_replace($normalizedClass, '', $pos, strlen($nsPath));
					}

					// build the full path
					$file = $dir . DIRECTORY_SEPARATOR . ltrim($normalizedClass, '/');

					// no check if a file exists or not
					return $file;
				}
			}
		} else {
			// PEAR-like class name
			$normalizedClass = str_replace('_', DIRECTORY_SEPARATOR, $class) . '.php';
			foreach ($this->_prefixes as $prefix => $dirs) {
				if(0 !== strpos($class, $prefix)) {
					continue;
				}

				if(isset($this->_rules[$prefix])){
					if(isset($this->_rules[$prefix]['normalize'])){
						$normalizedClass = $class.'.php';
					}

					if(isset($this->_rules[$prefix]['case'])){
						if($this->_rules[$prefix]['case']=='lower'){
							$normalizedClass = strtolower($normalizedClass);
						}
					}
				}

				foreach ($dirs as $dir) {
					$file = $dir . DIRECTORY_SEPARATOR . $normalizedClass;
					// no check if a file exists or not
					return $file;
				}
			}
		}

		if($this->_includePathLookup && $file = stream_resolve_include_path($normalizedClass)) {
			return $file;
		}
	}
}