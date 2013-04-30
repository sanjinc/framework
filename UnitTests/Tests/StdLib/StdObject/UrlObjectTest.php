<?php

namespace WF\UnitTests\Tests\StdLib\StdObject;

require_once '../../../../WebinyFramework.php';

use WF\StdLib\StdObject\StdObjectException;
use WF\StdLib\StdObject\UrlObject\UrlObject;


class UrlObjectTest extends \PHPUnit_Framework_TestCase
{

	private $_url = 'http://www.webiny.com:666/some-page/some-subpage/?param1&param2=null
					&paramArray[]=somevalue&paramArray[]=somevalue2';

	private $_url2 = 'http://www.webiny.com/path/?query=something';


	function testConstructor() {
		$u = new UrlObject($this->_url);
	}

	/**
	 * @expectedException WF\StdLib\StdObject\StdObjectException
	 * @expectedExceptionMessage UrlObject: Unable to parse the given value
	 */
	function testConstuctor2() {
		$u = new UrlObject(false);
	}

	function testGetHost() {
		$u = new UrlObject($this->_url);

		$this->assertSame('www.webiny.com', $u->getHost());
	}

	function testGetScheme() {
		$u = new UrlObject($this->_url);

		$this->assertSame('http', $u->getScheme());
	}

	function testGetPort() {
		$u = new UrlObject($this->_url);

		$this->assertSame(666, $u->getPort());
	}

	function testGetDomain() {
		$u = new UrlObject($this->_url);

		$this->assertSame('http://www.webiny.com', $u->getDomain());
	}

	function testGetPath() {
		$u = new UrlObject($this->_url);

		$this->assertSame('/some-page/some-subpage/', $u->getPath());
	}

	function testSetters() {
		$u = new UrlObject($this->_url2);

		$u->setScheme('ftp')
			->setHost('google.com////')
			->setPath('new-path/over-me')
			->setPort(45)
			->setQuery(['name' => 'John']);

		$this->assertSame('ftp://google.com:45/new-path/over-me?name=John', $u->val());
	}
}