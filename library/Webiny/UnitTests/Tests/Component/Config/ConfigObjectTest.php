<?php
namespace Webiny\UnitTests\Tests\Component\Config;

use Webiny\Component\Config\Config;
use Webiny\Component\Config\ConfigException;
use Webiny\Component\Config\ConfigObject;
use Webiny\Component\Config\Drivers\PhpDriver;
use Webiny\StdLib\StdObject\FileObject\FileObject;

require_once '../../../../../../WebinyFramework.php';

class ConfigObjectTest extends \PHPUnit_Framework_TestCase
{

	/**
	 * @var ConfigObject
	 */
	private $_config;

	public function setUp() {
		parent::__construct();

		$this->_config = new ConfigObject(['key' => 'value']);
	}

	public function testArrayResourceType() {
		$type = $this->_config->determineResourceType([1]);
		$this->assertSame(ConfigObject::ARRAY_RESOURCE, $type);
	}

	public function testStringResourceType() {

		$type = $this->_config->determineResourceType('anyString');
		$this->assertSame(ConfigObject::STRING_RESOURCE, $type);
	}

	public function testFileResourceType() {
		$type = $this->_config->determineResourceType(realpath(__DIR__) . '/Configs/testConfig.php');
		$this->assertSame(ConfigObject::FILE_RESOURCE, $type);
	}

	public function testFileObjectResourceType() {
		$type = $this->_config->determineResourceType(new FileObject(realpath(__DIR__) . '/Configs/testConfig.php'));
		$this->assertSame(ConfigObject::FILE_RESOURCE, $type);
	}

	/**
	 * @expectedException Webiny\Component\Config\ConfigException
	 */
	public function testConfigObjectException() {
		$this->_config->determineResourceType(false);
	}
}