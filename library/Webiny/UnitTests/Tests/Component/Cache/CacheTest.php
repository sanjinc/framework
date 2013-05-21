<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\UnitTests\Tests\Component\Config;

use Webiny\Component\Cache\Cache;

require_once '../../../../../../WebinyFramework.php';

class CacheTest extends \PHPUnit_Framework_TestCase
{
	const COUCHBASE_HOSTS = '127.0.0.1:8091';
	const COUCHBASE_BUCKET = 'default';
	const COUCHBASE_USER = 'webiny';
	const COUCHBASE_PASSWORD = '';

	/**
	 * @dataProvider driverSet
	 */
	function testConstructor($driver){
		$cache = new Cache($driver);

		$this->assertInstanceOf('Webiny\Component\Cache\Cache', $cache);
	}

	function testAPCConstructor(){
		$cache = Cache::APC('some-key');
		$this->assertInstanceOf('Webiny\Bridge\Cache\Memory\APC', $cache->getDriver());
	}

	function testCouchbaseConstructor(){
		$cache = Cache::Couchbase('some-key', new \Couchbase(self::COUCHBASE_HOSTS, self::COUCHBASE_USER, self::COUCHBASE_PASSWORD, self::COUCHBASE_BUCKET));

		$this->assertInstanceOf('Webiny\Bridge\Cache\Memory\Couchbase', $cache->getDriver());
	}

	function testMemcacheConstructor(){
		$cache = Cache::Memcache('some-key');
		$this->assertInstanceOf('Webiny\Bridge\Cache\Memory\Memcache', $cache->getDriver());
	}

	function testRedisConstructor(){
		$cache = Cache::Redis('some-key');
		$this->assertInstanceOf('Webiny\Bridge\Cache\Memory\Redis', $cache->getDriver());
	}

	/**
	 * @dataProvider driverSet
	 */
	function testSave($driver){
		$cache = new Cache($driver);
		$cache->save('cacheKey', 'some value', 3600, ['test', 'unit', 'tag']);
	}

	/**
	 * @dataProvider driverSet
	 */
	function testRead($driver){
		$cache = new Cache($driver);

		$this->assertSame('some value', $cache->read('cacheKey'));
	}

	/**
	 * @dataProvider driverSet
	 */
	function testDelete($driver){
		$cache = new Cache($driver);
		$cache->delete('cacheKey');

		$this->assertTrue($cache->read('cacheKey')==false || $cache->read('cacheKey')==null);
	}

	/**
	 * @dataProvider driverSet
	 */
	function testDeleteByTags($driver){
		$cache = new Cache($driver);
		$cache->save('cacheKey', 'some value', 3600, ['test', 'unit', 'tag']);
		$cache->deleteByTags('test');

		$this->assertTrue($cache->read('cacheKey')==false || $cache->read('cacheKey')==null);
	}

	function driverSet(){
		return [
			[Cache::APC('some-key')->getDriver()],
			[Cache::Couchbase('some-key', new \Couchbase(self::COUCHBASE_HOSTS, self::COUCHBASE_USER, self::COUCHBASE_PASSWORD, self::COUCHBASE_BUCKET))->getDriver()],
			[Cache::Memcache('some-key')->getDriver()],
			[Cache::Redis('some-key')->getDriver()]
		];
	}

}