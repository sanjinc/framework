<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Component\Router\Loader;

use Webiny\Component\Config\ConfigObject;
use Webiny\Component\Router\Route\Route;
use Webiny\Component\Router\Route\RouteCollection;

/**
 * ConfigLoader parses the given config file, extracts the routes and builds a RouteCollection object.
 *
 * @package         Webiny\Component\Router\Loader
 */

class ConfigLoader
{

	/**
	 * @var \Webiny\Component\Config\ConfigObject
	 */
	private $_config;

	/**
	 * @var \Webiny\Component\Router\Route\RouteCollection
	 */
	private $_routeCollection;

	/**
	 * Base constructor.
	 *
	 * @param ConfigObject $config Instance of config object containing route definitions.
	 */
	function __construct(ConfigObject $config) {
		$this->_config = $config;
		$this->_routeCollection = new RouteCollection();
	}

	/**
	 * Builds and returns RouteCollection instance.
	 *
	 * @return RouteCollection
	 */
	function getRouteCollection() {
		foreach ($this->_config as $name => $routeConfig) {
			$this->_routeCollection->add($name, $this->_processRoute($routeConfig));
		}

		return $this->_routeCollection;
	}

	/**
	 * Builds a Route instance based on the given route config.
	 *
	 * @param ConfigObject $routeConfig A config object containing route parameters.
	 *
	 * @return Route
	 */
	private function _processRoute(ConfigObject $routeConfig) {
		// base route
		$route = new Route($routeConfig->path, $routeConfig->callback);

		// route options
		if(($options = $routeConfig->get('options', false))!=false){
			$route->setOptions($options->toArray());
		}

		// host
		if(($host = $routeConfig->get('host', false))!=false){
			$route->setHost($host);
		}

		// schemes
		if(($schemes = $routeConfig->get('schemes', false))!=false){
			$route->setSchemes($schemes);
		}

		// methods
		if(($methods = $routeConfig->get('methods', false))!=false){
			$route->setMethods($methods);
		}

		return $route;
	}
}