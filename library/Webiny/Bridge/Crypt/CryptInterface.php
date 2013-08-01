<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Bridge\Crypt;

/**
 * Description
 *
 * @package         Webiny\Bridge\Crypt
 */

interface CryptInterface
{

	function __construct($passwordAlgo, $cipherMode, $cipherBlock, $cipherInitVector);

	// randoms
	/**
	 * Generates a random integer between the given $min and $max values.
	 *
	 * @param int $min   Lower limit.
	 * @param int $max   Upper limit
	 *
	 * @return int Random number between $min and $max.
	 */
	function generateRandomInt($min, $max);

	/**
	 * Generates a random string using the defined character set.
	 * If $chars param is empty, the string will be generated using numbers, letters and special characters.
	 *
	 * @param int    $length Length of the generated string.
	 * @param string $chars  A string containing a list of chars that will be uses for generating the random string.
	 *
	 * @return string Random string with the given $length containing only the provided set of $chars.
	 */
	function generateRandomString($length, $chars = '');

	/**
	 * Generates a random string, but without using special characters that are hard to read.
	 * This method is ok to use for generating random user passwords. (which, of course, should be changed after first login).
	 *
	 * @param int $length Length of the random string.
	 *
	 * @return string Random string with the given $length.
	 */
	function generateUserReadableString($length);

	/**
	 * Generates a random string with a lot of 'noise' (special characters).
	 * Use this method to generate API keys, salts and similar.
	 *
	 * @param int $length Length of the random string.
	 *
	 * @return string Random string with the given $length.
	 */
	function generateHardReadableString($length);

	// password hashing and verification
	/**
	 * Creates a hash from the given $password string.
	 * The hashing algorithm used depends on your config.
	 *
	 * @param string $password String you wish to hash.
	 *
	 * @return string Hash of the given string.
	 */
	function createPasswordHash($password);

	/**
	 * Verify if the given $hash matches the given $password.
	 *
	 * @param string $password Original, un-hashed, password.
	 * @param string $hash     Hash string to which the check should be made
	 *
	 * @return bool True if $password matches the $hash, otherwise false is returned.
	 */
	function verifyPasswordHash($password, $hash);

	// encryption and decryption
	/**
	 * Encrypt the given $string using a cypher and the secret $key
	 *
	 * @param string      $string               The string you want to encrypt.
	 * @param string      $key                  The secret key that will be used to encrypt the string.
	 * @param string|null $initializationVector Initialization vector for the encryption.
	 *
	 * @return string Encrypted string.
	 */
	function encrypt($string, $key, $initializationVector = null);

	/**
	 * Decrypt a string that has been encrypted with the 'encrypt' method.
	 * In order to decrypt the string correctly, you must provide the same secret key that was used for the encryption
	 * process.
	 *
	 * @param string      $string               The string you want to decrypt.
	 * @param string      $key                  The secret key that was used to encrypt the $string.
	 * @param string|null $initializationVector Initialization vector for the encryption.
	 *
	 * @return string Decrypted string.
	 */
	function decrypt($string, $key, $initializationVector = null);
}