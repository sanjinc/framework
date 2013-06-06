<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

use Webiny\Component\Crypt\Crypt;

require_once '../../../../../autoloader.php';

class CryptTest extends \PHPUnit_Framework_TestCase
{
	function testConstructor(){
		$crypt = new Crypt();

		$this->assertInstanceOf('\Webiny\Component\Crypt\Crypt', $crypt);
	}

	function testGenerateRandomInt(){
		$crypt = new Crypt();
		$randomInt = $crypt->generateRandomInt(10, 20);

		$this->assertGreaterThanOrEqual(10, $randomInt);
	}

	function testGenerateRandomInt2(){
		$crypt = new Crypt();
		$randomInt = $crypt->generateRandomInt(10, 20);

		$this->assertLessThanOrEqual(20, $randomInt);
	}

	function testGenerateRandomInt3(){
		$crypt = new Crypt();
		$randomInt = $crypt->generateRandomInt(10, 10);

		$this->assertSame(10, $randomInt);
	}

	function testGenerateRandomString(){
		$crypt = new Crypt();
		$randomString = $crypt->generateRandomString(9, $chars = 'abc');

		$chars = str_split($randomString);
		$chars = array_unique($chars);
		sort($chars);
		$this->assertSame(['a', 'b', 'c'], $chars);
	}

	function testGenerateUserReadableString(){
		$crypt = new Crypt();
		$randomString = $crypt->generateUserReadableString(64);

		$size = strlen($randomString);
		$this->assertSame(64, $size);
	}

	function testGenerateUserReadableString2(){
		$crypt = new Crypt();
		$randomString = $crypt->generateUserReadableString('asd');

		$size = strlen($randomString);
		$this->assertSame(0, $size);
	}

	function testGenerateHardReadableString(){
		$crypt = new Crypt();
		$randomString = $crypt->generateHardReadableString(64);

		$size = strlen($randomString);
		$this->assertSame(64, $size);
	}

	function testCreatePasswordHash(){
		$crypt = new Crypt();
		$password = $crypt->createPasswordHash('login123');

		// $2y$ is the prefix for the default 'Blowfish' password algorithm
		$this->assertStringStartsWith('$2y$', $password);
	}

	function testVerifyPasswordHash(){
		$crypt = new Crypt();
		$password = $crypt->createPasswordHash('login123');

		$this->assertTrue($crypt->verifyPasswordHash('login123', $password));
	}

	function testVerifyPasswordHash2(){
		$crypt = new Crypt();
		$password = $crypt->createPasswordHash('login123');

		$this->assertFalse($crypt->verifyPasswordHash('123login', $password));
	}

	/**
	 * @expectedException \Webiny\Component\Crypt\CryptException
	 * @expectedExceptionMessage The supplied key block is in the valid sizes
	 */
	function testEncrypt(){
		$crypt = new Crypt();
		$crypt->encrypt('some string', 'too short key');
	}

	/**
	 * @expectedException \Webiny\Component\Crypt\CryptException
	 * @expectedExceptionMessage Supplied Initialization Vector is too short
	 */
	function testEncrypt2(){
		$crypt = new Crypt();
		$crypt->encrypt('some string', 'abcdefgh12345678', 'too short');
	}

	/**
	 * @dataProvider encryptDescryptDataProvider
	 */
	function testEncryptDecrypt($stringToEncrypt, $encKey, $encInitValue, $decKey, $decInitValue, $result){
		$crypt = new Crypt();
		$encrypted = $crypt->encrypt($stringToEncrypt, $encKey, $encInitValue);

		$this->assertSame($result, $crypt->decrypt($encrypted, $decKey, $decInitValue));
	}

	function encryptDescryptDataProvider(){
		return [
			// decryption matches the original string
			['a', 'abcdefgh12345678', 'init_vector', 'abcdefgh12345678', 'init_vector', 'a'],
			['B', '12345678abcdefgh', 'init_vector', '12345678abcdefgh', 'init_vector', 'B'],
			['test string', 'abcdefgh12345678', 'init_vector', 'abcdefgh12345678', 'init_vector', 'test string'],
			['test string', 'abcdefgh/&%$#"!?', 'init_vector', 'abcdefgh/&%$#"!?', 'init_vector', 'test string'],
			['test string', '-.,_:;></&%$#"!?', 'init_vector', '-.,_:;></&%$#"!?', 'init_vector', 'test string'],
			// decryption fails because the key doesn't match
			['test string', 'abcdefgh12345678', 'init_vector', '12345678abcdefgh', 'init_vector', false],
			// decryption fails because the initialization vector doesn't match
			['test string', 'abcdefgh12345678', 'init_vector', 'abcdefgh12345678', 'foo_vector_', false],
		];
	}
}