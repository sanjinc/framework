<?php
namespace WF\UnitTests\Tests\StdLib\StdObject;

require_once '../../../../WebinyFramework.php';

use WF\StdLib\StdObject\ArrayObject\ArrayObject;

class ArrayObjectText extends \PHPUnit_Framework_TestCase{

	/**
	 * @expectedException WF\StdLib\StdObject\StdObjectException
	 */
	public function testConstructorException(){
		$a = new ArrayObject('value');
	}

	/**
	 * @expectedException WF\StdLib\StdObject\StdObjectException
	 */
	public function testConstructorException2(){
		$a = new ArrayObject('key', 'value');
	}

	public function testConstructorKeyOnly(){
		$a = new ArrayObject(['key']);
		$this->assertSame(array('key'), $a->getValue());
	}

	public function testConstructorKeyValue(){
		$a = new ArrayObject(['key'=>'value']);
		$this->assertSame(array('key'=>'value'), $a->getValue());
	}

	public function testConstructorCombine(){
		$a = new ArrayObject(['key1', 'key2'], ['value1', 'value2']);
		$this->assertSame(array('key1'=>'value1', 'key2'=>'value2'), $a->getValue());
	}

	/**
	 * @expectedException WF\StdLib\StdObject\StdObjectException
	 */
	public function testConstructorCombine2(){
		$a = new ArrayObject(['key1', 'key2', ''], ['value1', 'value2', 'value3', 'value4', 'value5']);
		$this->assertSame(array('key1'=>'value1', 'key2'=>'value2'), $a->getValue());
	}

	public function testSum(){
		$a = new ArrayObject([1, 2, 3]);
		$sum = $a->sum();
		$this->assertSame(6, $sum);
	}

	/**
	 * @dataProvider arraySet1
	 */
	public function testKeys($array){
		$a = new ArrayObject($array);
		$keys = $a->keys();

		$this->assertSame(array_keys($array), $keys->getValue());
	}

	/**
	 * @dataProvider arraySet1
	 */
	public function testValues($array){
		$a = new ArrayObject($array);
		$values = $a->values();

		$this->assertSame(array_values($array), $values->getValue());
	}

	/**
	 * @dataProvider arraySet1
	 */
	public function testLast($array){
		$a = new ArrayObject($array);
		$last = $a->last();

		$this->assertSame(end($array), $last->getValue());
	}

	/**
	 * @dataProvider arraySet1
	 */
	public function testFirst($array){
		$a = new ArrayObject($array);
		$last = $a->first();

		$firstValue = reset($array);
		$this->assertSame($firstValue, $last->getValue());
	}

	/**
	 * @dataProvider arraySet1
	 */
	public function testCount($array){
		$a = new ArrayObject($array);
		$count = $a->count();

		$this->assertSame(count($array), $count);
	}

	/**
	 * @dataProvider arraySet2
	 */
	public function testCountValues($array){
		$a = new ArrayObject($array);
		$valueCount = $a->countValues();

		$this->assertSame(array_count_values($array), $valueCount->getValue());
	}

	/**
	 * @dataProvider arraySet1
	 */
	public function testGetValue($array){
		$a = new ArrayObject($array);

		$this->assertSame($array, $a->getValue());
	}

	/**
	 * @dataProvider arraySet1
	 */
	public function testUpdateValue($array){
		$a = new ArrayObject($array);
		$a->updateValue(['k1', 'k2'=>'v2']);

		$this->assertSame(['k1', 'k2'=>'v2'], $a->getValue());
	}

	/**
	 * @dataProvider arraySet1
	 */
	public function testAppend($array){
		$a = new ArrayObject($array);
		$a->append('k512');

		array_push($array, 'k512');
		$this->assertSame($array, $a->getValue());
	}

	/**
	 * @dataProvider arraySet1
	 */
	public function testPrepend($array){
		$a = new ArrayObject($array);
		$a->prepend('k512');

		array_unshift($array, 'k512');
		$this->assertSame($array, $a->getValue());
	}

	/**
	 * @dataProvider arraySet2
	 */
	public function testPrepend2($array){
		$a = new ArrayObject($array);
		$a->prepend('k512', 'val');

		$array = array('k512'=>'val')+$array;
		$this->assertSame($array, $a->getValue());
	}

	/**
	 * @dataProvider arraySet1
	 */
	public function testRemoveFirst($array){
		$a = new ArrayObject($array);
		$a->removeFirst();

		array_shift($array);
		$this->assertSame($array, $a->getValue());
	}

	/**
	 * @dataProvider arraySet1
	 */
	public function testRemoveLast($array){
		$a = new ArrayObject($array);
		$a->removeLast();

		array_pop($array);
		$this->assertSame($array, $a->getValue());
	}

	public function testRemoveKey(){
		$array = ['k1'=>'val', 'k2'=>null, 'k3'=>false];
		$a = new ArrayObject($array);
		$a->removeKey('k2');

		unset($array['k2']);
		$this->assertSame($array, $a->getValue());
	}

	/**
	 * @dataProvider arraySet1
	 */
	public function testImplode($array){
		$a = new ArrayObject($array);
		$string = $a->implode(' ');

		@$string2 = implode(' ', $array);
		$this->assertSame($string2, $string->getValue());
	}

	/**
	 * @dataProvider arraySet1
	 */
	public function testChunk($array){
		$a = new ArrayObject($array);
		$chunk = $a->chunk(2, true);

		$chunk2 = array_chunk($array, 2, true);
		$this->assertSame($chunk2, $chunk->getValue());
	}

	/**
	 * @dataProvider arraySet1
	 */
	public function testChangeKeyCase($array){
		$a = new ArrayObject($array);
		$a->changeKeyCase('upper');

		$array = array_change_key_case($array, CASE_UPPER);
		$this->assertSame($array, $a->getValue());
	}

	/**
	 * @expectedException WF\StdLib\StdObject\StdObjectException
	 */
	public function testChangeKeyCase2(){
		$a = new ArrayObject(['k1']);
		$a->changeKeyCase('mid-case');
	}

	/**
	 * @dataProvider arraySet1
	 */
	public function testFillKeys($array){
		$a = new ArrayObject($array);
		$a->fillKeys('value');

		@$array = array_fill_keys($array, 'value');
		$this->assertSame($array, $a->getValue());
	}



	public function arraySet1(){
		return array(
			[[]],
			[['k1']],
			[['k1'=>'']],
			[[''=>'v1']],
			[['k1'=>false]],
			[['k1'=>null, false]],
			[[''=>null]],
			[[''=>false, null]],
			[['k1', 'k2', 'k3']],
			[['k1'=>'v1', 'k2'=>'v2', 'k3'=>'v3']],
			[['k1' => ['kk1'=>'v1', 'kk2'=>'v2'], 'k2'=>'v2', 'kk3'=>['vv3'=>['kk33'=>'vvv3']]]]
		);
	}

	public function arraySet2(){
		return array(
			[[]],
			[['k1']],
			[['k1'=>'']],
			[[''=>'v1']],
			[['k1', 'k2', 'k3']],
			[['k1'=>'v1', 'k2'=>'v2', 'k3'=>'v3']]
		);
	}

}