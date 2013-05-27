<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Component\Crypt;

use Webiny\Bridge\Crypt\CryptInterface;
use Webiny\StdLib\StdLibTrait;

/**
 * This is a class for simple cryptographic functions in PHP.
 *
 * @package         Webiny\Component\Crypt
 */

class Crypt
{
	use StdLibTrait;

	/**
	 * @var null|CryptInterface
	 */
	static private $_driverInstance = null;


	/**
	 * Create a Crypt instance.
	 */
	public function __construct() {
		if($this->isNull(self::$_driverInstance)) {
			try {
				self::$_driverInstance = \Webiny\Bridge\Crypt\Crypt::getInstance();

				if(!$this->isInstanceOf(self::$_driverInstance, '\Webiny\Bridge\Crypt\CryptInterface')) {
					throw new CryptException('The provided bridge does not implement the required
												interface "\Webiny\Bridge\Crypt\CryptInterface"');
				}
			} catch (\Exception $e) {
				throw new CryptException('Unable to get the instance from \Webiny\Bridge\Crypt\Crypt::getInstance()');
			}
		}
	}

	/**
	 * Generates a random integer between the given $min and $max values.
	 *
	 * @param int $min   Lower limit.
	 * @param int $max   Upper limit
	 *
	 * @throws CryptException
	 * @return int Random number between $min and $max.
	 */
	function generateRandomInt($min, $max) {
		try {
			return self::$_driverInstance->generateRandomInt($min, $max);
		} catch (\Exception $e) {
			throw new CryptException($e->getMessage());
		}
	}

	/**
	 * Generates a random string using the defined character set.
	 * If $chars param is empty, the string will be generated using numbers, letters and special characters.
	 *
	 * @param int    $length Length of the generated string.
	 * @param string $chars  A string containing a list of chars that will be uses for generating the random string.
	 *
	 * @throws CryptException
	 * @return string Random string with the given $length containing only the provided set of $chars.
	 */
	function generateRandomString($length, $chars = ''){
		try {
			return self::$_driverInstance->generateRandomString($length, $chars);
		} catch (\Exception $e) {
			throw new CryptException($e->getMessage());
		}
	}

	/**
	 * Generates a random string, but without using special characters that are hard to read.
	 * This method is ok to use for generating random user passwords. (which, of course, should be changed after first login).
	 *
	 * @param int $length  Length of the random string.
	 *
	 * @throws CryptException
	 * @return string Random string with the given $length.
	 */
	function generateUserReadableString($length) {
		try {
			return self::$_driverInstance->generateUserReadableString($length);
		} catch (\Exception $e) {
			throw new CryptException($e->getMessage());
		}
	}

	/**
	 * Generates a random string with a lot of 'noise' (special characters).
	 * Use this method to generate API keys, salts and similar.
	 *
	 * @param int $length  Length of the random string.
	 *
	 * @throws CryptException
	 * @return string Random string with the given $length.
	 */
	function generateHardReadableString($length) {
		try {
			return self::$_driverInstance->generateHardReadableString($length);
		} catch (\Exception $e) {
			throw new CryptException($e->getMessage());
		}
	}

	// password hashing and verification
	/**
	 * Creates a hash from the given $password string.
	 * The hashing algorithm used depends on your config.
	 *
	 * @param string $password String you wish to hash.
	 *
	 * @throws CryptException
	 * @return string Hash of the given string.
	 */
	function createPasswordHash($password) {
		try {
			return self::$_driverInstance->createPasswordHash($password);
		} catch (\Exception $e) {
			throw new CryptException($e->getMessage());
		}
	}

	/**
	 * Verify if the given $hash matches the given $password.
	 *
	 * @param string $password Original, un-hashed, password.
	 * @param string $hash     Hash string to which the check should be made
	 *
	 * @throws CryptException
	 * @return bool True if $password matches the $hash, otherwise false is returned.
	 */
	function verifyPasswordHash($password, $hash) {
		try {
			return self::$_driverInstance->verifyPasswordHash($password, $hash);
		} catch (\Exception $e) {
			throw new CryptException($e->getMessage());
		}
	}

	// encryption and decryption
	/**
	 * Encrypt the given $string using a cypher and the secret $key
	 *
	 * @param string      $string               The string you want to encrypt.
	 * @param string      $key                  The secret key that will be used to encrypt the string.
	 * @param null|string $initializationVector Initialization vector for the encryption. More about
	 *                                          initialization vector (iv) @link http://en.wikipedia.org/wiki/Initialization_vector
	 *
	 * @throws CryptException
	 *
	 * @return string Encrypted string.
	 */
	function encrypt($string, $key, $initializationVector = null) {
		try {
			return self::$_driverInstance->encrypt($string, $key, $initializationVector);
		} catch (\Exception $e) {
			throw new CryptException($e->getMessage());
		}
	}

	/**
	 * Decrypt a string that has been encrypted with the 'encrypt' method.
	 * In order to decrypt the string correctly, you must provide the same secret key that was used for the encryption
	 * process.
	 *
	 * @param string $string                    The string you want to decrypt.
	 * @param string $key                       The secret key that was used to encrypt the $string.
	 * @param        $initializationVector      Initialization vector for the encryption. More about
	 *                                          initialization vector (iv) @link http://en.wikipedia.org/wiki/Initialization_vector
	 *
	 * @throws CryptException
	 * @return string Decrypted string.
	 */
	function decrypt($string, $key, $initializationVector = null) {
		try {
			return self::$_driverInstance->decrypt($string, $key, $initializationVector);
		} catch (\Exception $e) {
			throw new CryptException($e->getMessage());
		}
	}

}