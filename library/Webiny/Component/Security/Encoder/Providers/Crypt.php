<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Component\Security\Encoder\Providers;

use Webiny\Component\Security\Encoder\EncoderProviderInterface;

/**
 * This is the Crypt implementation of EncoderProviderInterface.
 *
 * @package		 Webiny\Component\Security\Encoder\Providers
 */
 
class Crypt implements EncoderProviderInterface{
	/**
	 * @var null|\Webiny\Component\Crypt\Crypt
	 */
	static private $_instance = null;

	/**
	 * Constructor
	 */
	public function __construct(){
		if(is_null(self::$_instance)){
			self::$_instance = new \Webiny\Component\Crypt\Crypt();
		}
	}

	/**
	 * Create a hash for the given password.
	 *
	 * @param string $password
	 *
	 * @return string Password hash.
	 */
	function createPasswordHash($password) {
		return self::$_instance->createPasswordHash($password);
	}

	/**
	 * Verify if the $password matches the $hash.
	 *
	 * @param string $password
	 * @param string $hash
	 *
	 * @return bool True if $password matches $hash. Otherwise false is returned.
	 */
	function verifyPasswordHash($password, $hash) {
		return self::$_instance->verifyPasswordHash($password, $hash);
	}
}