<?php

namespace Webiny\UnitTests\Tests\StdLib\StdObject;

require_once '../../../../WebinyFramework.php';

use Webiny\StdLib\StdLibTrait;
use Webiny\StdLib\StdObject\StdObjectException;
use Webiny\StdLib\StdObject\FileObject\FileObject;

class FileObjectTest extends \PHPUnit_Framework_TestCase
{

	public $filePath = '/var/www/newwebiny/framework/UnitTests/Tests/StdLib/StdObject/file-test.txt';


	/**
	 * @var FileObject
	 */
	public $f;

	public function setUp() {
		if(file_exists($this->filePath)) {
			unlink($this->filePath);
		}
		$this->f = new FileObject($this->filePath);
		$this->f->write('some text');
	}

	public function testConstructor() {
		$f = new FileObject($this->filePath);
		$f->write('test');

		$this->assertTrue(file_exists($this->filePath));
	}

	public function testGetSize() {
		$this->assertSame(9, $this->f->getSize());
	}

	public function testGetBasename() {
		$this->assertSame('file-test.txt', $this->f->getBasename());
	}

	public function testGetExtension() {
		$this->assertSame('txt', $this->f->getExtension());
	}

	public function testGetMTime() {
		$this->assertSame(time(), $this->f->getMTime());
	}

	public function testGetPath() {
		$this->assertSame('/var/www/newwebiny/framework/UnitTests/Tests/StdLib/StdObject', $this->f->getPath());
	}

	public function testGetMimeType() {
		$this->assertSame('text/plain', $this->f->getMimeType());
	}

	public function testGetValue() {
		$this->assertSame($this->filePath, $this->f->val());
	}

	public function testGetFileContent() {
		$this->assertSame('some text', $this->f->getFileContent());
	}

	public function testWrite() {
		$this->f->write(' added');
		$this->assertSame('some text added', $this->f->getFileContent());
	}

	/**
	 * @expectedException Webiny\StdLib\StdObject\StdObjectException
	 * @expectedExceptionMessage FileObject: The chmod $mode param must be exactly 4 chars.
	 */
	public function testChmod() {
		$this->f->chmod(04444);
	}

	/**
	 * @expectedException Webiny\StdLib\StdObject\StdObjectException
	 * @expectedExceptionMessage FileObject: The chmod $mode param must be exactly 4 chars.
	 */
	public function testChmod2() {
		$this->f->chmod(044);
	}

	/**
	 * @expectedException Webiny\StdLib\StdObject\StdObjectException
	 * @expectedExceptionMessage FileObject: $mode must be an integer (octal).
	 */
	public function testChmod3() {
		$this->f->chmod('444');
	}

	public function testChmod4() {
		$this->f->chmod(0777);

		$this->assertTrue($this->f->isReadable());
		$this->assertTrue($this->f->isWritable());
	}

	public function testChmod5() {
		$this->f->chmod(0444);

		$this->assertTrue($this->f->isReadable());
		$this->assertTrue(!$this->f->isWritable());
	}

	public function testIsFile() {
		$this->assertTrue($this->f->isFile());
	}

	public function testIsLink() {
		$this->assertFalse($this->f->isLink());
	}

	public function testIsImage() {
		$this->assertFalse($this->f->isImage());
	}

	public function testDelete() {
		$this->f->delete();
		$this->assertFileNotExists($this->filePath);
	}


}