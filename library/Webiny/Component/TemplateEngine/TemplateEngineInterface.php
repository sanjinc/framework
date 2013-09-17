<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Component\TemplateEngine;

/**
 * This is the interface that every template engine driver must implement.
 *
 * @package         Webiny\Component\TemplateEngine
 */

interface TemplateEngineInterface
{
	/**
	 * Fetch the template from the given location, parse it and return the output.
	 *
	 * @param string $template Path to the template.
	 * @param array  $parameters A list of parameters to pass to the template.
	 *
	 * @return string Parsed template.
	 */
	function fetch($template, $parameters = []);

	/**
	 * Fetch the template from the given location, parse it and output the result to the browser.
	 *
	 * @param string $template   Path to the template.
	 * @param array  $parameters A list of parameters to pass to the template.
	 *
	 * @return void
	 */
	function render($template, $parameters = []);

	/**
	 * Assign a variable and its value into the template engine.
	 *
	 * @param string $var   Variable name.
	 * @param mixed  $value Variable value.
	 *
	 * @return void
	 */
	function assign($var, $value);

	/**
	 * Root path where the templates are stored.
	 *
	 * @param string $path Absolute path to the directory that holds the templates.
	 *
	 * @return void
	 */
	function setTemplatePath($path);

	/**
	 * Register a plugin for the template engine.
	 *
	 * @param Plugin $plugin
	 *
	 * @return void
	 */
	function registerPlugin(Plugin $plugin);
}