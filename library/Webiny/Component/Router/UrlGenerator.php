<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Component\Router;

use Webiny\Component\Http\HttpTrait;
use Webiny\Component\Router\Route\RouteCollection;
use Webiny\Component\StdLib\StdLibTrait;
use Webiny\Component\StdLib\StdObject\UrlObject\UrlObject;

/**
 * UrlGenerator can generate urls from the Routes.
 *
 * @package         Webiny\Component\Router
 */

class UrlGenerator
{

	use StdLibTrait, HttpTrait;

	/**
	 * @var Route\RouteCollection A RouteCollection.
	 */
	private $_routeCollection;

	/**
	 * Base constructor.
	 * It takes a RouteCollection from which it can extract Routes to generate urls.
	 *
	 * @param Route\RouteCollection A RouteCollection.
	 */
	function __construct(RouteCollection $routeCollection) {
		$this->_routeCollection = $routeCollection;
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
		$route = $this->_routeCollection->get($name);
		if($this->isNull($route)) {
			throw new RouterException('Unknown route "%s".', [$name]);
		}

		$count = 0;
		$unknownParams = [];
		$path = $route->getPath();
		foreach ($parameters as $pk => $pv) {
			$path = str_replace('{' . $pk . '}', $pv, $path, $count);
			if($count < 1) {
				$unknownParams[$pk] = $pv;
			}
		}

		if(strpos($path, '{') !== false) {
			throw new RouterException('Unable to generate a url for "%s" route. Some parameters are missing: "%s"',
									  [
									  $name,
									  $path
									  ]);
		}

		/**
		 * @var $url UrlObject
		 */
		$url = $this->request()
			   ->getCurrentUrl(true)
			   ->setPath($path)
			   ->setQuery($unknownParams);

		$path = '/' . $this->str($url->getPath())->trimLeft('/')->trimRight('/')->val() . '/';

		if(!$absolute) {
			$query = $url->getQuery();
			if(count($query)>0){
				$query = '?'.http_build_query($query);
			}else{
				$query = '';
			}

			return $path.$query;
		}

		return $url->setPath($path)->val();
	}

}