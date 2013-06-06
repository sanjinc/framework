<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Bridge\Crypt\CryptLib;

use Webiny\WebinyTrait;
use Webiny\Bridge\Crypt\CryptInterface;

/**
 * Bridge to PHP-CryptLib library.
 *
 * @package         Webiny\Bridge\Crypt
 */

class CryptLib implements CryptInterface
{
	use WebinyTrait;

	static $_factory = null;
	static $_lowStrengthGenerator = null;

	static $_passwordImplementationLibrary = null;
	static $_defaultPassImplLibrary = 'Blowfish';

	static $_cipherModeInstance = null;
	static $_cipherBlockInstance = null;
	static $_defaultCipherMode = 'CCM';
	static $_defaultCipherBlock = 'rijndael-128';
	static $_defaultInitVector = '_FOO_VECTOR';

	/**
	 * Generates a random integer between the given $min and $max values.
	 *
	 * @param int $min   Lower limit.
	 * @param int $max   Upper limit
	 *
	 * @return int Random number between $min and $max.
	 */
	function generateRandomInt($min, $max) {
		return $this->_getLowStrengthGenerator()->generateInt($min, $max);
	}

	/**
	 * Generates a random string using the defined character set.
	 * If $chars param is empty, the string will be generated using numbers, letters and special characters.
	 *
	 * @param int    $length Length of the generated string.
	 * @param string $chars  A string containing a list of chars that will be uses for generating the random string.
	 *
	 * @return string Random string with the given $length containing only the provided set of $chars.
	 */
	function generateRandomString($length, $chars = '') {
		if($chars==''){
			$chars = '0123456789abcdefghijklmnopqrstiuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!#$%/()=?*.@[]{},;:_-><';
		}

		return $this->_getLowStrengthGenerator()->generateString($length, $chars);
	}

	/**
	 * Generates a random string, but without using special characters that are hard to read.
	 * This method is ok to use for generating random user passwords. (which, of course, should be changed after first login).
	 *
	 * @param int $length Length of the random string.
	 *
	 * @return string Random string with the given $length.
	 */
	function generateUserReadableString($length) {
		$chars = '0123456789abcdefghijklmnopqrstiuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

		return $this->generateRandomString($length, $chars);
	}

	/**
	 * Generates a random string with a lot of 'noise' (special characters).
	 * Use this method to generate API keys, salts and similar.
	 *
	 * @param int          $length  Length of the random string.
	 * @param array|string Optional set of characters that will be used for the random string.
	 *
	 * @return string Random string with the given $length.
	 */
	function generateHardReadableString($length) {
		$chars = '0123456789abcdefghijklmnopqrstiuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!#$%/()=?*.@[]{},;:_-><';

		return $this->generateRandomString($length, $chars);
	}

	/**
	 * Creates a hash from the given $password string.
	 * The hashing algorithm used depends on your config.
	 *
	 * @param string $password String you wish to hash.
	 *
	 * @return string Hash of the given string.
	 */
	function createPasswordHash($password) {
		return $this->_getPasswordImplementationLibrary()->create($password);
	}

	/**
	 * Verify if the given $hash matches the given $password.
	 *
	 * @param string $password Original, un-hashed, password.
	 * @param string $hash     Hash string to which the check should be made
	 *
	 * @return bool True if $password matches the $hash, otherwise false is returned.
	 */
	function verifyPasswordHash($password, $hash) {
		return $this->_getPasswordImplementationLibrary()->verify($password, $hash);
	}

	/**
	 * Encrypt the given $string using a cypher and the secret $key
	 *
	 * @param string      $data                 Data you wish to encrypt.
	 * @param string      $key                  The secret key that will be used to encrypt the string.
	 * @param string|null $initializationVector Initialization vector for the encryption.
	 *
	 * @internal param string $string The string you want to encrypt.
	 *
	 * @return string Encrypted string.
	 */
	function encrypt($data, $key, $initializationVector = null) {
		$cipher = $this->_getCipherMode($key, $initializationVector);
		$cipher->encrypt($data);
		$enc = $cipher->finish();
		$cipher->reset();

		return $enc;
	}

	/**
	 * Decrypt a string that has been encrypted with the 'encrypt' method.
	 * In order to decrypt the string correctly, you must provide the same secret key that was used for the encryption
	 * process.
	 *
	 * @param string      $data                 Data you wish to decrypt.
	 * @param string      $key                  The secret key that was used to encrypt the $string.
	 * @param null|string $initializationVector Initialization vector for the decryption.
	 *
	 * @internal param string $string The string you want to decrypt.
	 *
	 * @return string Decrypted string.
	 */
	function decrypt($data, $key, $initializationVector = null) {
		$cipher = $this->_getCipherMode($key, $initializationVector);
		$cipher->decrypt($data);
		$data = $cipher->finish();
		$cipher->reset();

		return $data;
	}

	/**
	 * Returns the low noise generator for generating random strings and integers.
	 *
	 * @return \CryptLib\Random\Generator|null
	 */
	private function _getLowStrengthGenerator() {
		if(is_null(self::$_lowStrengthGenerator)) {
			self::$_lowStrengthGenerator = $this->_getFactory()->getLowStrengthGenerator();
		}

		return self::$_lowStrengthGenerator;
	}

	/**
	 * Returns the factory class for 'CryptLib\Random' package.
	 *
	 * @return \CryptLib\Random\Factory|null
	 */
	private function _getFactory() {
		if(is_null(self::$_factory)) {
			self::$_factory = new \CryptLib\Random\Factory;
		}

		return self::$_factory;
	}

	/**
	 * Returns the instance of password implementation library.
	 *
	 * @return \CryptLib\Password\Password Password implementation library
	 */
	private function _getPasswordImplementationLibrary() {
		if(!is_null(self::$_passwordImplementationLibrary)) {
			return self::$_passwordImplementationLibrary;
		}

		$library = '\CryptLib\Password\Implementation\\' . self::$_defaultPassImplLibrary;
		if(isset($this->webiny()->getConfig()->components->crypt->password_algo)) {
			$library = '\CryptLib\Password\Implementation\\' . $this->webiny()
															   ->getConfig()->components->crypt->password_algo;
		}
		self::$_passwordImplementationLibrary = new $library;

		return self::$_passwordImplementationLibrary;
	}

	/**
	 * Creates and returns a Cipher instances based on current settings.
	 *
	 * @param string      $secretKey            Secret key to encrypt or decrypt data
	 * @param string|null $initializationVector Cipher initialization vector. Set to null if you wish to use your default
	 *                                          initialization vector.
	 *
	 * @return \CryptLib\Cipher\Block\AbstractCipher Instance of \CryptLib\Cipher\Block\AbstractMode
	 */
	private function _getCipherMode($secretKey, $initializationVector = null) {
		$mode = '\CryptLib\Cipher\Block\Cipher\\' . self::$_defaultCipherMode;
		if(isset($this->webiny()->getConfig()->components->crypt->cipher_mode)) {
			$mode = '\CryptLib\Cipher\Block\Mode\\' . $this->webiny()->getConfig()->components->crypt->cipher_mode;
		}

		if(is_null($initializationVector)) {
			$initializationVector = self::$_defaultInitVector;
			if(isset($this->webiny()->getConfig()->components->crypt->cipher_initialization_vector)) {
				$initializationVector = $this->webiny()->getConfig()->components->crypt->cipher_initialization_vector;
			}
		}

		$cipherBlock = $this->_getCipherBlock();
		$cipherBlock->setKey($secretKey);
		$modeInstance = new $mode($cipherBlock, $initializationVector);

		return $modeInstance;
	}

	/**
	 * Creates an instance of Cipher\Block needed to create Cipher\Mode instance.
	 *
	 * @return \CryptLib\Cipher\Block\Cipher|null
	 */
	private function _getCipherBlock() {
		if(!is_null(self::$_cipherBlockInstance)) {
			return self::$_cipherBlockInstance;
		}

		$cipherBlock = self::$_defaultCipherBlock;
		if(isset($this->webiny()->getConfig()->components->crypt->cipher_block)) {
			$cipherBlock = $this->webiny()->getConfig()->components->crypt->cipher_block;
		}

		$factory = new \CryptLib\Cipher\Factory();
		self::$_cipherBlockInstance = $factory->getBlockCipher($cipherBlock);

		return self::$_cipherBlockInstance;
	}

}