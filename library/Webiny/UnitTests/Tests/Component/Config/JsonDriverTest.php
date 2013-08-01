<?php
namespace Webiny\UnitTests\Tests\Component\Config;

use Webiny\Component\Config\Config;
use Webiny\Component\Config\ConfigObject;
use Webiny\StdLib\StdLibTrait;
use Webiny\StdLib\StdObject\FileObject\FileObject;

require_once '../../../../../../WebinyFramework.php';

class JsonDriverTest extends \PHPUnit_Framework_TestCase
{

	public function testJsonConfigRead() {
		$config = Config::getInstance()->json(realpath(__DIR__) . '/Configs/config.json');
		$this->assertInstanceOf('Webiny\Component\Config\ConfigObject', $config);

		return $config;
	}

	/**
	 * @depends testJsonConfigRead
	 *
	 * @return string
	 */
	public function testJsonConfigWrite($config) {
		$testPath = realpath(__DIR__) . '/Configs/testConfig.json';
		$file = new FileObject($testPath);
		$file->delete();

		$config->saveAsJson($testPath);

		return $testPath;
	}

	/**
	 * @depends testJsonConfigWrite
	 */
	public function testConfigObjectValue($testPath) {
		$config = Config::getInstance()->json($testPath);
		$this->assertEquals('www.webiny.com', $config->website->url);
	}
}