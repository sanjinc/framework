<?php
namespace Webiny\UnitTests\Tests\Component\Config;

use Webiny\Component\Config\Config;
use Webiny\Component\Config\ConfigObject;
use Webiny\Component\StdLib\StdLibTrait;
use Webiny\Component\StdLib\StdObject\FileObject\FileObject;

require_once '../../../../../autoloader.php';

class YamlDriverTest extends \PHPUnit_Framework_TestCase
{

	public function testYamlConfigRead() {
		$config = Config::getInstance()->yaml(realpath(__DIR__) . '/Configs/config.yaml');
		$this->assertInstanceOf('Webiny\Component\Config\ConfigObject', $config);

		return $config;
	}

	/**
	 * @depends testYamlConfigRead
	 */
	public function testYamlConfigWrite($config) {
		$testPath = realpath(__DIR__) . '/Configs/testConfig.yaml';
		$file = new FileObject($testPath);
		$file->delete();

		$config->saveAsYaml($testPath);

		return $testPath;
	}

	/**
	 * @depends testYamlConfigWrite
	 */
	public function testConfigObjectValue($testPath) {
		$config = Config::getInstance()->yaml($testPath);
		$this->assertEquals('Chris', $config->{'bill-to'}->given);
	}
}