<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link         http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright    Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license      http://www.webiny.com/framework/license
 * @package      WebinyFramework
 */
namespace Webiny\Bridge\Yaml;

use Webiny\Component\Config\Config;
use Webiny\Component\Config\ConfigObject;
use Webiny\StdLib\StdObject\FileObject\FileObject;
use Webiny\StdLib\StdObject\StringObject\StringObject;

/**
 * Yaml bridge interface
 *
 * @package      Webiny\Bridge\Yaml
 */
interface YamlInterface
{

	/**
	 * Write current Yaml data to file
	 *
	 * @param string|StringObject|FileObject $destination
	 *
	 * @return bool
	 */
	function writeToFile($destination);

	/**
	 * Get current Yaml value as string
	 *
	 * @param int  $indent
	 * @param bool $wordWrap
	 *
	 * @return string
	 */
	function getString($indent = 2, $wordWrap = false);

	/**
	 * Get Yaml value as array
	 *
	 * @return array
	 */
	function getArray();


	/**
	 * Set driver resource to work on
	 * @param mixed $resource
	 *
	 * @return $this
	 */
	function setResource($resource);

}