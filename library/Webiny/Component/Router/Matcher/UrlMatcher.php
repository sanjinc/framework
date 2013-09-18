<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Component\Router\Matcher;

use Webiny\Component\Http\HttpTrait;
use Webiny\Component\Router\Route\CompiledRoute;
use Webiny\Component\Router\Route\Route;
use Webiny\Component\Router\Route\RouteCollection;
use Webiny\Component\Router\Route\RouteOption;
use Webiny\Component\StdLib\StdLibTrait;
use Webiny\Component\StdLib\StdObject\UrlObject\UrlObject;

/**
 * UrlMatcher tries to match a Route from given RouteCollection to defined url.
 *
 * @package         Webiny\Component\Router\Matcher
 */

class UrlMatcher
{

	use HttpTrait, StdLibTrait;

	/**
	 * @var \Webiny\Component\Router\Route\RouteCollection
	 */
	private $_routeCollection;


	/**
	 * Base constructor.
	 *
	 * @param RouteCollection $routeCollection Set the RouteCollection on which the class will do the matching.
	 */
	function __construct(RouteCollection $routeCollection) {
		$this->_routeCollection = $routeCollection;
	}

	/**
	 * This method tries to match the given url against the route collection.
	 * If match is successful, an array with callback and params is returned.
	 * If match is not successful, false is returned.
	 *
	 * @param UrlObject $url
	 *
	 * @return array|bool
	 */
	function match(UrlObject $url) {

		$pathWithHost = $this->str($url->getHost() . $url->getPath())->trimRight('/')->val();
		$pathWithoutHost = $this->str($url->getPath())->trimRight('/')->val();

		/**
		 * @var Route $route
		 */
		foreach ($this->_routeCollection->all() as $name => $route) {
			$compiledRoute = $route->compile();

			// first check the static prefix on path becuse we don't want to use heavy preg_matching if the prefix
			// doesn't match
			if($compiledRoute->getStaticPrefix() != '' && strpos($url->getPath(),
																 $compiledRoute->getStaticPrefix()) !== 0
			) {
				continue;
			}

			// let's check the host
			if($route->getHost() != '' && $route->getHost() != $url->getHost()) {
				continue;
			}

			// let's check schemes
			if(count($route->getSchemes()) > 0 && !in_array($url->getScheme(), $route->getSchemes())) {
				continue;
			}

			// let's check them methods
			if(count($route->getMethods()) > 0 && !in_array($this->request()->server()->requestMethod(),
															$route->getMethods())
			) {
				continue;
			}

			// check if we need to match the host also
			if($route->getHost() != '') {
				$fullPath = $pathWithHost;
			} else {
				$fullPath = $pathWithoutHost;
			}

			// finally let's try to match the full url
			preg_match_all($compiledRoute->getRegex(), $fullPath, $matches);

			if(count($matches[0]) < 1) {
				continue;

				// @TODO
				// if we haven't matched the url, lets see if we have all the default values for every pattern,
				// because if we do, and since the static prefix has been matched, we can still consider the url to be
				// matched
			}

			// if we matched the route, we need to extract the parameters
			$params = $this->_extractParameters($matches, $compiledRoute);

			return [
				'callback' => $route->getCallback(),
				'params'   => $params
			];
		}

		return false;
	}

	/**
	 * Extracts and matches the parameters with their defined names.
	 *
	 * @param               $matches
	 * @param CompiledRoute $compiledRoute
	 *
	 * @return array
	 */
	private function _extractParameters($matches, CompiledRoute $compiledRoute) {
		$params = [];
		foreach ($compiledRoute->getVariables() as $index => $var) {
			$params[$var['name']] = $matches[($index + 1)][0];
		}

		return $params;
	}
}