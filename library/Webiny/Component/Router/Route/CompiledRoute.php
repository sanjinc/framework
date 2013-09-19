<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Component\Router\Route;

/**
 * CompiledRoute contains the extracted patterns and compiled regexes for matching url.
 *
 * @package         Webiny\Component\Router\Route
 */

class CompiledRoute
{
	/**
	 * @var string
	 */
	private $_staticPrefix;

	/**
	 * @var string
	 */
	private $_regex;

	/**
	 * @var array
	 */
	private $_variables;

	/**
	 * @var array
	 */
	private $_extractedRegexes = [];

	/**
	 * @var array
	 */
	private $_defaults = [];

	/**
	 * Base constructor.
	 *
	 * @param string $staticPrefix     Pattern prefix that doesn't contain regular expression.
	 * @param string $regex            Regular expression that will be matched against the given url.
	 * @param array  $variables        List of available variables extracted from the route path.
	 * @param array  $extractedRegexes List of extracted regexes from the route.
	 * @param array  $defaults         List of default values for route parameters.
	 */
	function __construct($staticPrefix, $regex, array $variables, array $extractedRegexes, array $defaults) {
		$this->_staticPrefix = $staticPrefix;
		$this->_regex = $regex;
		$this->_variables = $variables;
		$this->_extractedRegexes = $extractedRegexes;
		$this->_defaults = $defaults;
	}

	/**
	 * Get the static prefix.
	 *
	 * @return string
	 */
	function getStaticPrefix() {
		return $this->_staticPrefix;
	}

	/**
	 * Get the regular expression to match the url.
	 *
	 * @return string
	 */
	function getRegex() {
		return $this->_regex;
	}

	/**
	 * Get the extracted variables from the path.
	 *
	 * @return array
	 */
	function getVariables() {
		return $this->_variables;
	}

	/**
	 * Returns a list of extracted regexes.
	 *
	 * @return array
	 */
	function getExtractedRegexes() {
		return $this->_extractedRegexes;
	}

	/**
	 * Returns a list of default values for route parameters.
	 *
	 * @return array
	 */
	function getDefaults(){
		return $this->_defaults;
	}
}