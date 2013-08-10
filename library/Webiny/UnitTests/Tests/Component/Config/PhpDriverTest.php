<?php
namespace Webiny\UnitTests\Tests\Component\Config;

use Webiny\Component\Config\Config;
use Webiny\Component\Config\ConfigObject;
use Webiny\Component\StdLib\StdLibTrait;
use Webiny\Component\StdLib\StdObject\FileObject\FileObject;

require_once '../../../../../../WebinyFramework.php';

class PhpDriverTest extends \PHPUnit_Framework_TestCase
{

	public function testPhpConfigRead() {
		$config = Config::getInstance()->php(realpath(__DIR__) . '/Configs/config.php');
		$this->assertInstanceOf('Webiny\Component\Config\ConfigObject', $config);

		return $config;
	}

	/**
	 * @depends testPhpConfigRead
	 *
	 * @return string
	 */
	public function testPhpConfigWrite($config) {
		$testPath = realpath(__DIR__) . '/Configs/testConfig.php';
		$file = new FileObject($testPath);
		$file->delete();

		$config->saveAsPhp($testPath);

		return $testPath;
	}

	/**
	 * @depends testPhpConfigWrite
	 */
	public function testConfigObjectValue($testPath) {
		$config = Config::getInstance()->php($testPath);
		$this->assertEquals('London', $config->default->name->address);
	}
}