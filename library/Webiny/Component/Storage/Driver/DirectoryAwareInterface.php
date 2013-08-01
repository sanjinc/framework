<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Component\Storage\Driver;

/**
 * @package   Webiny\Component\Storage
 */

interface DirectoryAwareInterface
{
	/**
	 * Check if key is directory
	 *
	 * @param string $key
	 *
	 * @return boolean
	 */
	public function isDirectory($key);
}