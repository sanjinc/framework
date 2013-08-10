<?php
namespace Webiny\UnitTests\Tests\Component\Config;

use Webiny\Component\Config\Config;
use Webiny\Component\Config\ConfigObject;
use Webiny\Component\StdLib\StdLibTrait;
use Webiny\Component\StdLib\StdObject\FileObject\FileObject;

require_once '../../../../../../WebinyFramework.php';

class IniDriverTest extends \PHPUnit_Framework_TestCase
{

	public function testIniConfigRead() {
		$config = Config::getInstance()->ini(realpath(__DIR__) . '/Configs/config.ini');
		$this->assertInstanceOf('Webiny\Component\Config\ConfigObject', $config);

		return $config;
	}

	/**
	 * @depends testIniConfigRead
	 *
	 * @return string
	 */
	public function testIniConfigWrite($config) {
		$testPath = realpath(__DIR__) . '/Configs/testConfig.ini';
		$file = new FileObject($testPath);
		$file->delete();

		$config->saveAsIni($testPath);

		return $testPath;
	}

	/**
	 * @depends testIniConfigWrite
	 */
	public function testConfigObjectValue($testPath) {
		$config = Config::getInstance()->ini($testPath);
		$this->assertEquals(12, $config->group1->someProperty);
	}
}