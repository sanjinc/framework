<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Component\Security\Encoder;

/**
 * Description
 *
 * @package		 Webiny\Component\Security\Encoder
 */
 
interface EncoderDriverInterface{

	/**
	 * Create a hash for the given password.
	 *
	 * @param string $password
	 *
	 * @return string Password hash.
	 */
	function createPasswordHash($password);

	/**
	 * Verify if the $password matches the $hash.
	 *
	 * @param string $password
	 * @param string $hash
	 *
	 * @return bool True if $password matches $hash. Otherwise false is returned.
	 */
	function verifyPasswordHash($password, $hash);
}