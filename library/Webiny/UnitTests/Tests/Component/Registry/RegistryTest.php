<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

require_once '../../../../../autoloader.php';

class RegistryTest extends \PHPUnit_Framework_TestCase
{
	function testGetInstance(){
		$registry = \Webiny\Component\Registry\Registry::getInstance();

		$this->assertInstanceOf('\Webiny\Component\Registry\Registry', $registry);
	}

	function testSaveToRegistry(){
		$registry = \Webiny\Component\Registry\Registry::getInstance();

		$registry->someKey = 'value';
		$value = $registry->someKey;

		$this->assertSame('value', $value);
	}

	function testSaveArrayToRegistry(){
		$registry = \Webiny\Component\Registry\Registry::getInstance();

		$registry->someArray = ['key1'=>'value1', 'key2'=>'value2'];

		$this->assertSame(['key1'=>'value1', 'key2'=>'value2'], $registry->someArray);
	}

	function testInDepthKeys(){
		$registry = \Webiny\Component\Registry\Registry::getInstance();

		$registry->depth1->depth2->depth3 = 'value';
		$this->assertSame('value', $registry->depth1->depth2->depth3);
	}

	function testInDepthKeys2(){
		$registry = \Webiny\Component\Registry\Registry::getInstance();

		$registry->depth1->depth2->depth3_2 = ['key1' => 'some value'];
		$this->assertSame('some value', $registry->depth1->depth2->depth3_2['key1']);
	}

	function testOverwrite(){
		$registry = \Webiny\Component\Registry\Registry::getInstance();

		$registry->someKey = 'test';
		$registry->someKey = 'test new';

		$this->assertSame('test new', $registry->someKey);
	}

	function testExists(){
		$registry = \Webiny\Component\Registry\Registry::getInstance();

		$this->assertFalse($registry->exists('someNonExistingKey'));
	}

	function testExists2(){
		$registry = \Webiny\Component\Registry\Registry::getInstance();

		$this->assertFalse($registry->exists(['someNonExistingKey', 'depth2']));
	}

	function testExists3(){
		$registry = \Webiny\Component\Registry\Registry::getInstance();

		$registry->depth1 = 'some value';

		$this->assertTrue($registry->exists('depth1'));
	}

	function testExists4(){
		$registry = \Webiny\Component\Registry\Registry::getInstance();

		$registry->depthTest2_1->depth2 = 'some value';

		$this->assertFalse($registry->exists('depth2'));
	}

	function testExists5(){
		$registry = \Webiny\Component\Registry\Registry::getInstance();

		$registry->depthTest2_2->depth2 = 'some value';

		$this->assertTrue($registry->exists(['depthTest2_2', 'depth2']));
	}
}