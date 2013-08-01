<?php
namespace Webiny\UnitTests\Tests\Component\Config;

use Webiny\Component\Config\Config;
use Webiny\Component\Config\ConfigException;
use Webiny\Component\Config\ConfigObject;
use Webiny\StdLib\StdObject\FileObject\FileObject;

require_once '../../../../../../WebinyFramework.php';

class ConfigCacheTest extends \PHPUnit_Framework_TestCase
{

	public function testConfigCache() {
		$config = Config::getInstance()->php(realpath(__DIR__) . '/Configs/config.php');
		$config->testKey = 'value';
		$config2 = Config::getInstance()->php(realpath(__DIR__) . '/Configs/config.php');

		$this->assertEquals($config->testKey, $config2->testKey);

	}

	public function testConfigCacheFlush() {
		$config = Config::getInstance()->php(realpath(__DIR__) . '/Configs/config.php');
		$config->testKey = 'value';
		$config2 = Config::getInstance()->php(realpath(__DIR__) . '/Configs/config.php', true);

		$this->assertNull($config2->testKey);

	}
}