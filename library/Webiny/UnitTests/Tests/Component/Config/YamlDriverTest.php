<?php
namespace Webiny\UnitTests\Tests\Component\Config;

use Webiny\Component\Config\Config;
use Webiny\Component\Config\ConfigObject;
use Webiny\StdLib\StdLibTrait;
use Webiny\StdLib\StdObject\FileObject\FileObject;

require_once '../../../../../../WebinyFramework.php';

class YamlDriverTest extends \PHPUnit_Framework_TestCase
{

	public function testYamlConfigRead() {
		$config = Config::Yaml(realpath(__DIR__) . '/Configs/config.yaml');
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
		$config = Config::Yaml($testPath);
		$this->assertEquals('Chris', $config->{'bill-to'}->given);
	}
}