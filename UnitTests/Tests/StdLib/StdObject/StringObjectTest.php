<?php

namespace WF\UnitTests\Tests\StdLib\StdObject;

require_once '../../../../WebinyFramework.php';

use WF\StdLib\StdObject\StdObjectException;
use WF\StdLib\StdObject\StringObject\StringObject;

class StringObjectTest extends \PHPUnit_Framework_TestCase
{

	public function testConstructor(){
		$s = new StringObject('some string');
	}

	public function testConstructor2(){
		$s = new StringObject(12);
	}

	public function testConstructor3(){
		$s = new StringObject(12.5);
	}

	/**
	 * @expectedException WF\StdLib\StdObject\StdObjectException
	 */
	public function testConstructor4(){
		$s = new StringObject([]);
	}

	/**
	 * @dataProvider stringSet
	 */
	public function testLength($str){
		$s = new StringObject($str);
		$length = $s->length();

		$lengthCheck = strlen($str);
		$this->assertSame($lengthCheck, $length);
	}

	/**
	 * @dataProvider stringSet
	 */
	public function testWordCount($str){
		$s = new StringObject($str);
		$words = $s->wordCount();

		$wordCountCheck = str_word_count($str);
		$this->assertSame($wordCountCheck, $words);
	}

	/**
	 * @dataProvider stringSet
	 */
	public function testWordCount2($str){
		$s = new StringObject($str);
		$words = $s->wordCount(1);

		$wordCountCheck = str_word_count($str, 1);
		$this->assertSame($wordCountCheck, $words->getValue());
	}

	/**
	 * @dataProvider stringSet
	 */
	public function testWordCount3($str){
		$s = new StringObject($str);
		$words = $s->wordCount(2);

		$wordCountCheck = str_word_count($str, 2);
		$this->assertSame($wordCountCheck, $words->getValue());
	}

	public function testTrim(){
		$s = new StringObject(' a ');
		$s->trim();

		$this->assertSame('a', $s->getValue());
	}

	public function testTrim2(){
		$s = new StringObject(' a b');
		$s->trim('b');

		$this->assertSame(' a ', $s->getValue());
	}

	public function testTrim3(){
		$s = new StringObject('\ a b');
		$s->trim('\\');

		$this->assertSame(' a b', $s->getValue());
	}

	public function testCaseLower(){
		$s = new StringObject('ASDŽĆČĐŠ');
		$s->caseLower();

		$this->assertSame('asdžćčđš', $s->getValue());
	}

	public function testCaseUpper(){
		$s = new StringObject('asdžćčđš');
		$s->caseUpper();

		$this->assertSame('ASDŽĆČĐŠ', $s->getValue());
	}

	public function testCaseFirstUpper(){
		$s = new StringObject('šAH Mat');
		$s->caseFirstUpper();

		$this->assertSame('Šah mat', $s->getValue());
	}

	public function testCaseWordUpper(){
		$s = new StringObject('šah mat');
		$s->caseWordUpper();

		$this->assertSame('Šah Mat', $s->getValue());
	}

	public function testNl2br(){
		$s = new StringObject("new \n line");
		$s->nl2br();

		$this->assertSame('new <br />
 line', $s->getValue());
	}

	public function testStripTrailingSlash(){
		$s = new StringObject("http://www.webiny.com/");
		$s->stripTrailingSlash();

		$this->assertSame('http://www.webiny.com', $s->getValue());
	}

	public function testStripTrailingSlash2(){
		$s = new StringObject("/http://www.webiny.com//");
		$s->stripTrailingSlash();

		$this->assertSame('/http://www.webiny.com', $s->getValue());
	}

	public function testStripStartingSlash(){
		$s = new StringObject("/http://www.webiny.com//");
		$s->stripStartingSlash();

		$this->assertSame("http://www.webiny.com//", $s->getValue());
	}

	public function testStripStartingSlash2(){
		$s = new StringObject("//http://www.webiny.com//");
		$s->stripStartingSlash()->stripTrailingSlash();

		$this->assertSame("http://www.webiny.com", $s->getValue());
	}

	public function testTrimLeft(){
		$s = new StringObject('a b c');
		$s->trimLeft('a');

		$this->assertSame(' b c', $s->getValue());
	}

	public function testTrimLeft2(){
		$s = new StringObject('a b c');
		$s->trimLeft('a')->trim();

		$this->assertSame('b c', $s->getValue());
	}

	public function testTrimRight(){
		$s = new StringObject('a b c');
		$s->trimRight('c');

		$this->assertSame('a b ', $s->getValue());
	}

	public function testTrimRight2(){
		$s = new StringObject('a b c');
		$s->trimRight('c')->trim();

		$this->assertSame('a b', $s->getValue());
	}

	public function testSubString(){
		$s = new StringObject('a b c');
		$s->subString(0, 3);

		$this->assertSame('a b', $s->getValue());
	}

	public function testSubString2(){
		$s = new StringObject('a b c');
		$s->subString(2,1);

		$this->assertSame('b', $s->getValue());
	}

	/**
	 * @expectedException \WF\StdLib\StdObject\StdObjectException
	 */
	public function testSubString3(){
		$s = new StringObject('a b c');
		$s->subString('a','b');
	}

	public function testReplace(){
		$s = new StringObject('a b c');
		$s->replace(['a','b'], 'c');

		$this->assertSame('c c c', $s->getValue());
	}

	public function testReplace2(){
		$search  = array('A', 'B', 'C', 'D', 'E');
		$replace = array('B', 'C', 'D', 'E', 'F');

		$s = new StringObject('a');
		$s->replace($search, $replace);

		$this->assertSame('F', $s->getValue());
	}

	public function testExplode(){
		$s = new StringObject('a b c');
		$arr = $s->explode(' ');

		$this->assertSame(['a', 'b', 'c'], $arr->getValue());
	}

	public function testExplode2(){
		$s = new StringObject('a b c');
		$arr = $s->explode(' ', 2);

		$this->assertSame(['a', 'b c'], $arr->getValue());
	}

	public function testSplit(){
		$s = new StringObject('a b c');
		$arr = $s->split();

		$this->assertSame(['a', ' ', 'b', ' ', 'c'], $arr->getValue());
	}

	public function testSplit2(){
		$s = new StringObject('a b c');
		$arr = $s->split(2);

		$this->assertSame(['a ', 'b ', 'c'], $arr->getValue());
	}

	public function testHash(){
		$s = new StringObject('abc');
		$s->hash();

		$this->assertSame('a9993e364706816aba3e25717850c26c9cd0d89d', $s->getValue());
	}

	public function testHash2(){
		$s = new StringObject('abc');
		$s->hash('md5');

		$this->assertSame('900150983cd24fb0d6963f7d28e17f72', $s->getValue());
	}

	/**
	 * @expectedException \WF\StdLib\StdObject\StdObjectException
	 */
	public function testHash3(){
		$s = new StringObject('abc');
		$s->hash('breakMe');
	}

	public function testHtmlEntityDecode(){
		$s = new StringObject("I'll &quot;walk&quot; the &lt;b&gt;dog&lt;/b&gt; now");
		$s->htmlEntityDecode();

		$this->assertSame("I'll \"walk\" the <b>dog</b> now", $s->getValue());
	}

	public function testHtmlEntityEncode(){
		$s = new StringObject("I'll \"walk\" the <b>dog</b> now");
		$s->htmlEntityEncode();

		$this->assertSame("I'll &quot;walk&quot; the &lt;b&gt;dog&lt;/b&gt; now", $s->getValue());
	}

	public function testAddSlashes(){
		$s = new StringObject("Is your name O'reilly?");
		$s->addSlashes();

		$this->assertSame("Is your name O\'reilly?", $s->getValue());
	}

	public function testStripSlashes(){
		$s = new StringObject("Is your name O\'reilly?");
		$s->stripSlashes();

		$this->assertSame("Is your name O'reilly?", $s->getValue());
	}

	public function testMd5(){
		$s = new StringObject('abc');
		$s->md5();

		$this->assertSame('900150983cd24fb0d6963f7d28e17f72', $s->getValue());
	}

	public function testCrc32(){
		$s = new StringObject('abc');
		$s->crc32();

		$this->assertSame(crc32('abc'), $s->getValue());
	}

	public function testSha1(){
		$s = new StringObject('abc');
		$s->sha1();

		$this->assertSame(sha1('abc'), $s->getValue());
	}

	public function testParseString(){
		$s = new StringObject('first=value&arr[]=foo+bar&arr[]=baz');
		$result = $s->parseString();

		$compare = ['first'=>'value', 'arr'=>['foo bar', 'baz']];
		$this->assertSame($compare, $result->getValue());
	}

	public function testQuoteMeta(){
		$s = new StringObject("Hello world. (can you hear me?)");
		$s->quoteMeta();

		$this->assertSame("Hello world\. \(can you hear me\?\)", $s->getValue());
	}

	public function testFormat(){
		$s = new StringObject('There are %d monkeys in the %s');
		$s->format([5, 'tree']);

		$this->assertSame('There are 5 monkeys in the tree', $s->getValue());
	}

	public function testFormat2(){
		$s = new StringObject('Your price is: %01.2f');
		$s->format('120');

		$this->assertSame('Your price is: 120.00', $s->getValue());
	}

	public function testPadLeft(){
		$s = new StringObject('A');
		$s->padLeft(3,'B');

		$this->assertSame('BBA', $s->getValue());
	}

	public function testPadRight(){
		$s = new StringObject('A');
		$s->padRight(3,'B');

		$this->assertSame('ABB', $s->getValue());
	}

	public function testPadBoth(){
		$s = new StringObject('A');
		$s->padBoth(4,'B');

		$this->assertSame('BABB', $s->getValue());
	}

	public function testRepeat(){
		$s = new StringObject('ABC');
		$s->repeat(3);

		$this->assertSame('ABCABCABC', $s->getValue());
	}

	public function testShuffle(){
		$s = new StringObject('ABC');
		$s->shuffle();

		// note: this can fail sometime because the result cannot be predicted
		$this->assertNotSame('ABC', $s->getValue());
	}

	public function testStripTags(){
		$s = new StringObject('<b>Bold</b>');
		$s->stripTags();

		$this->assertSame('Bold', $s->getValue());
	}

	public function testStripTags2(){
		$s = new StringObject('<p><b>Bold</b><em>Italic</em></p>');
		$s->stripTags('<b>,<em>');

		$this->assertSame('<b>Bold</b><em>Italic</em>', $s->getValue());
	}

	public function testReverse(){
		$s = new StringObject('ABC');
		$s->reverse();

		$this->assertSame('CBA', $s->getValue());
	}

	public function testTruncate(){
		$s = new StringObject('A very long word.');
		$s->truncate(7);

		$this->assertSame('A very', $s->getValue());
	}

	public function testTruncate2(){
		$s = new StringObject('A very long word.');
		$s->truncate(10, '...');

		$this->assertSame('A very...', $s->getValue());
	}

	public function testContains(){
		$s = new StringObject('Marry had a little lamb.');
		$result = $s->contains('little');

		$this->assertTrue($result);
	}

	public function testContains2(){
		$s = new StringObject('Marry had a little lamb.');
		$result = $s->contains('big');

		$this->assertFalse($result);
	}

	public function testContains3(){
		$s = new StringObject('Marry had a little lamb.');
		$result = $s->contains('rry');

		$this->assertTrue($result);
	}

	public function testEquals(){
		$s = new StringObject('test string');
		$s2  = new StringObject('test string');

		$this->assertTrue($s->equals($s2));
	}

	public function testStringPosition(){
		$s = new StringObject('Marry had a little lamb.');
		$pos = $s->stringPosition('little');

		$this->assertSame(12, $pos);
	}

	public function testStartsWith(){
		$s = new StringObject('Marry had a little lamb.');

		$this->assertTrue($s->startsWith('Marry'));
	}

	public function testStartsWith2(){
		$s = new StringObject('Marry had a little lamb.');

		$this->assertFalse($s->startsWith('lamb'));
	}

	public function testMatch(){
		$s = new StringObject('I had 10 dollars.');
		$result = $s->match('|([0-9]{1,5})|', false);

		$this->assertSame(['10', '10'], $result->getValue());
	}

	public function testLongerThan(){
		$s = new StringObject('I had 10 dollars.');

		$this->assertTrue($s->longerThan(4));
	}

	public function testLongerThan2(){
		$s = new StringObject('I had 10 dollars.');

		$this->assertFalse($s->longerThan(30));
	}

	public function stringSet(){
		return [
			[' '],
			['a'],
			['a '],
			[' a '],
			['  a'],
			['a\'asd'],
			['some test string'],
			['string with slash/'],
			['string with /slashes /'],
			['/string with slash/'],
		];
	}
}