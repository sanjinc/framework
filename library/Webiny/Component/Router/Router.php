<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Component\Router;

use Webiny\Component\Config\ConfigObject;
use Webiny\Component\Cache\CacheStorage;
use Webiny\Component\Router\Loader\ConfigLoader;
use Webiny\Component\Router\Matcher\UrlMatcher;
use Webiny\Component\Router\Route\RouteCollection;
use Webiny\Component\ServiceManager\ServiceManagerTrait;
use Webiny\Component\StdLib\StdLibTrait;
use Webiny\Component\StdLib\StdObject\UrlObject\UrlObject;
use Webiny\WebinyTrait;

/**
 * Router class is the main class that encapsulates all the Router components for easier usage.
 *
 * @package         Webiny\Component\Router
 */

class Router
{
	use StdLibTrait, WebinyTrait, ServiceManagerTrait;

	/**
	 * @var RouteCollection
	 */
	private $_routeCollection;

	/**
	 * @var UrlGenerator
	 */
	private $_urlGenerator;

	/**
	 * @var UrlMatcher
	 */
	private $_urlMatcher;

	/**
	 * @var ConfigLoader
	 */
	private $_loader;

	/**
	 * @var bool|CacheStorage
	 */
	private $_cache = false;


	/**
	 * Base constructor.
	 *
	 * @param ConfigObject $routes Initial routes to load.
	 */
	function __construct(ConfigObject $routes) {
		$this->setRoutes($routes);

		$cache = $this->webiny()->getConfig()->get('components.router.cache', false);
		if($cache) {
			$this->setCache($this->service('cache.' . $cache));
		}
	}

	/**
	 * Tries to match the given url against current RouteCollection.
	 *
	 * @param string|UrlObject $url Url to match.
	 *
	 * @return array|bool Array with [callback, params] is returned if url was matched. Otherwise false is returned.
	 */
	function match($url) {
		if($this->isString($url)) {
			$urlString = '/' . $this->str($url)->trimLeft('/')->trimRight('/')->val() . '/';
			$url = $this->url($urlString);
		} else {
			$url = $this->url('');
		}

		// get it from cache
		if(($result = $this->_loadFromCache('match.'.$url->val())) != false) {
			return $this->unserialize($result);
		}

		// try to match the url
		$result = $this->_urlMatcher->match($url);

		// cache it
		$cacheResult = $this->isArray($result) ? $this->serialize($result) : $result;
		$this->_saveToCache('match.'.$url->val(), $cacheResult);

		return $result;
	}

	/**
	 * Generate a url from a route.
	 *
	 * @param string $name       Name of the Route.
	 * @param array  $parameters List of parameters that need to be replaced within the Route path.
	 * @param bool   $absolute   Do you want to get the absolute url or relative. Default is absolute.
	 *
	 * @return string Generated url.
	 * @throws RouterException
	 */
	function generate($name, $parameters = [], $absolute = true) {
		$key = 'generate.'.$name.implode('|', $parameters).$absolute;
		if(($url = $this->_loadFromCache($key))){
			return $url;
		}

		$url = $this->_urlGenerator->generate($name, $parameters, $absolute);
		$this->_saveToCache($key, $url);

		return $url;
	}

	/**
	 * Sets the cache parameter.
	 * If you don't want the Router to cache stuff, pass boolean false.
	 *
	 * @param bool|CacheStorage $cache Cache object of boolean false.
	 *
	 * @throws RouterException
	 */
	function setCache($cache) {
		if($this->isBool($cache) && $cache === false) {
			$this->_cache = $cache;
		} else {
			if($this->isInstanceOf($cache, '\Webiny\Component\Cache\CacheStorage')) {
				$this->_cache = $cache;
			} else {
				throw new RouterException('$cache must either be a boolean or instance of \Webiny\Component\Cache\CacheStorage.');
			}
		}
	}

	/**
	 * Get the current cache parameter.
	 *
	 * @return bool|CacheStorage
	 */
	function getCache() {
		return $this->_cache;
	}

	/**
	 * Overwrite the current routes with these ones.
	 *
	 * @param ConfigObject $routes Routes to install.
	 */
	function setRoutes(ConfigObject $routes) {
		$this->_init($routes);
	}

	/**
	 * Overwrite the current route collection with the defined one.
	 *
	 * @param RouteCollection $routeCollection RouteCollection to install.
	 */
	function setRouteCollection(RouteCollection $routeCollection) {
		$this->_routeCollection = $routeCollection;
		$this->_urlMatcher = new UrlMatcher($this->_routeCollection);
		$this->_urlGenerator = new UrlGenerator($this->_routeCollection);
	}

	/**
	 * Initializes some objects.
	 *
	 * @param ConfigObject $routes A list of routes.
	 */
	private function _init(ConfigObject $routes) {
		$this->_loader = new ConfigLoader($routes);
		$this->_routeCollection = $this->_loader->getRouteCollection();
		$this->_urlMatcher = new UrlMatcher($this->_routeCollection);
		$this->_urlGenerator = new UrlGenerator($this->_routeCollection);
	}

	/**
	 * Save the given value into cache.
	 *
	 * @param string $path  This is the cache key.
	 * @param string $value This is the value that is going to be stored.
	 */
	private function _saveToCache($path, $value) {
		if($this->getCache()) {
			$this->getCache()
			->save('wf.component.router' . md5($path), $value, null, [
																	 '_wf',
																	 '_component',
																	 '_router'
																	 ]);
		}
	}

	/**
	 * Get a value from cache.
	 *
	 * @param string $path Cache identifier for which you wish to get the value.
	 *
	 * @return bool|string
	 */
	private function _loadFromCache($path) {
		if($this->getCache()) {
			return $this->getCache()->read('wf.component.router' . md5($path));
		}

		return false;
	}
}