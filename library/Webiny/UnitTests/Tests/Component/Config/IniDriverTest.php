<?php
namespace Webiny\UnitTests\Tests\Component\Config;

use Webiny\Component\Config\Config;
use Webiny\Component\Config\ConfigObject;
use Webiny\StdLib\StdLibTrait;
use Webiny\StdLib\StdObject\FileObject\FileObject;

require_once '../../../../../../WebinyFramework.php';

class IniDriverTest extends \PHPUnit_Framework_TestCase
{

	public function testIniConfigRead() {
		$config = Config::Ini(realpath(__DIR__) . '/Configs/config.ini');
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
		$config = Config::Ini($testPath);
		$this->assertEquals(12, $config->group1->someProperty);
	}
}