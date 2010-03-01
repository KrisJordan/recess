<?php
require_once 'PHPUnit/Framework.php';

include_once __DIR__ . '/../../../../Recess/Core/Hash.php';
use Recess\Core\Hash;

/**
 * @group Recess\Core
 */
class HashTest extends PHPUnit_Framework_TestCase {
	
	function testEmptyConstructor() {
		$hash = new Hash();
		$this->assertEquals(0,count($hash));
	}
	
	function testArrayConstructor() {
		$hash = new Hash(array(1,2,3,4));
		$this->assertEquals(4,count($hash));
	}
	
	function testListConstructor() {
		$hash = new Hash(1,2,3,4);
		$this->assertEquals(4,count($hash));
	}
	
	function testSingleArgumentListConstructor() {
		$hash = new Hash(1);
		$this->assertEquals(1,count($hash));
	}
	
	function testMap() {
		$hash = new Hash(1,2,3,4);
		$mapped = $hash->map(function($value) {
			return $value * $value;			
		});
		$this->assertEquals(array(1,4,9,16),$mapped->toArray());
	}
	
	function testReduce() {
		$hash = new Hash(1,2,3,4);
		$sum = $hash->reduce(function($a,$b) { return $a + $b; }, 0);
		$this->assertEquals(10,$sum);
	}
	
	function testReduceIdentity() {
		$hash = new Hash();
		$sum = $hash->reduce(function($a,$b){},0);
		$this->assertEquals(0,$sum);
	}
	
	function testEach() {
		$hash = new Hash(1,2,3,4);
		$count = 0;
		$hash->each(function($item)use(&$count){$count += 1;});
		$this->assertEquals(4,$count);
	}
	
	function testFilter() {
		$hash = new Hash(1,2,3,4);
		$even = $hash->filter(function($value) { return $value % 2 === 0; });
		$this->assertEquals(array(1=>2,3=>4),$even->toArray());
	}
	
	function testForeach() {
		$hash = new Hash(1,2,3,4);
		$count = 0;
		foreach($hash as $key => $value) {
			$count += 1;
			$this->assertEquals($key + 1, $value);
		}
		$this->assertEquals(4,$count);
	}
	
	function testForeachIterator() {
		$hash = new Hash(1,2,3,4);
		$count = 0;
		foreach($hash->getIterator() as $key => $value) {
			$count += 1;
			$this->assertEquals($key + 1, $value);
		}
		$this->assertEquals(4,$count);
	}
	
	function testIsSet() {
		$hash = new Hash(1,2,3,4);
		$this->assertTrue(isset($hash[0]));
		$this->assertFalse(isset($hash[4]));		
	}
	
	function testOffsetGet() {
		$hash = new Hash(1,2,3,4);
		$this->assertEquals(1,$hash[0]);
		$this->assertEquals(2,$hash[1]);
		$this->assertEquals(3,$hash[2]);
		$this->assertEquals(4,$hash[3]);
	}
	
	function testOffsetSet() {
		$hash = new Hash();
		$this->assertEquals(0,count($hash));
		$hash[0] = 1;
		$this->assertEquals(1,count($hash));
		$this->assertEquals(1,$hash[0]);
	}
	
	function testOffsetUnset() {
		$hash = new Hash(1);
		$this->assertEquals(1,count($hash));
		$this->assertEquals(1,$hash[0]);
		unset($hash[0]);
		$this->assertEquals(0,count($hash));
		$this->assertFalse(isset($hash[0]));
	}
	
}