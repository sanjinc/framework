<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Component\ClassLoader;

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
		spl_autoload_register(array(
								   self::$_instance,
								   'getClass'
							  ), true, true);
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
		foreach($maps as $prefix=>$locations){
			$endChar = substr($prefix, -1);
			if($endChar=='_'){
				// PEAR standard
				$this->_prefixes[$prefix] = (array) $locations;
			}else{
				// namespace
				if($prefix[0]=='\\'){
					$this->_namespaces[substr($prefix, 1)] = (array) $locations;
				}else{
					$this->_namespaces[$prefix] = (array) $locations;
				}
			}
		}
	}

	/**
	 * Tries to find the class file based on currently registered rules.
	 *
	 * @param string $class
	 *
	 * @return bool
	 */
	public function getClass($class) {
		if($file = $this->findClass($class)) {
			require $file;

			return true;
		}
	}

	/**
	 * This function is taken from Symfony.
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
					$file = $dir . DIRECTORY_SEPARATOR . $normalizedClass;
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