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

require_once '../../../../../autoloader.php';

class CacheTest extends \PHPUnit_Framework_TestCase
{
	const COUCHBASE_HOSTS = '127.0.0.1:8091';
	const COUCHBASE_BUCKET = 'default';
	const COUCHBASE_USER = 'webiny';
	const COUCHBASE_PASSWORD = '';

	/**
	 * @dataProvider driverSet
	 */
	function testConstructor($cache){
		$this->assertInstanceOf('Webiny\Component\Cache\Cache', $cache);
	}

	function testAPCConstructor(){
		$cache = Cache::APC('apc-test');
		$this->assertInstanceOf('Webiny\Bridge\Cache\Memory\APC', $cache->getDriver());
	}

	function testCouchbaseConstructor(){
		$cache = Cache::Couchbase('couchbase-test', self::COUCHBASE_USER, self::COUCHBASE_PASSWORD, self::COUCHBASE_BUCKET, self::COUCHBASE_HOSTS);

		$this->assertInstanceOf('Webiny\Bridge\Cache\Memory\Couchbase', $cache->getDriver());
	}

	function testMemcacheConstructor(){
		$cache = Cache::Memcache('memcache-test');
		$this->assertInstanceOf('Webiny\Bridge\Cache\Memory\Memcache', $cache->getDriver());
	}

	function testRedisConstructor(){
		$cache = Cache::Redis('redis-test');
		$this->assertInstanceOf('Webiny\Bridge\Cache\Memory\Redis', $cache->getDriver());
	}

	/**
	 * @dataProvider driverSet
	 */
	function testSave($cache){
		$cache->save('cacheKey', 'some value', 3600, ['test', 'unit', 'tag']);
	}

	/**
	 * @dataProvider driverSet
	 */
	function testRead($cache){
		$this->assertSame('some value', $cache->read('cacheKey'));
	}

	/**
	 * @dataProvider driverSet
	 */
	function testDelete($cache){

		$cache->delete('cacheKey');

		$this->assertTrue($cache->read('cacheKey')==false || $cache->read('cacheKey')==null);
	}

	/**
	 * @dataProvider driverSet
	 */
	function testDeleteByTags($cache){

		$cache->save('cacheKey', 'some value', 3600, ['test', 'unit', 'tag']);
		$cache->deleteByTags('test');

		$this->assertTrue($cache->read('cacheKey')==false || $cache->read('cacheKey')==null);
	}

	function driverSet(){
		return [
			[Cache::APC('apc')],
			[Cache::Couchbase('couchbase', self::COUCHBASE_USER, self::COUCHBASE_PASSWORD, self::COUCHBASE_BUCKET, self::COUCHBASE_HOSTS)],
			[Cache::Memcache('memcache')],
			[Cache::Redis('redis')]
		];
	}

}