<?php
require_once 'PHPUnit/Framework.php';

include_once __DIR__ . '/../../../../Recess/Core/Hash.php';
use Recess\Core\Hash;

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
	
}