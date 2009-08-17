<?php
require_once 'PHPUnit/Framework.php';

include_once __DIR__ . '/../../../recess/core/Candy.class.php';
use recess\core\Candy;

class CandyTest extends PHPUnit_Framework_TestCase {
	
	function testConstructor() {
		$candy = new Candy(function() { return 'Hello world!'; });
		$this->assertEquals('Hello world!',$candy());
	}
	
	function testWrap() {
		$candy = new Candy(function() { return 'link'; });
		$candy->wrap(function($candy) { return 'wrapper' . $candy(); });
		$this->assertEquals('wrapperlink', $candy());
	}
	
	function testWrapCandy() {
		$candy = new Candy(function() { return 'middle'; });
		$wrap = function($candy) {return 'front' . $candy() . 'end';};
		$candy->wrap($wrap)->wrap($wrap);
		$this->assertEquals('frontfrontmiddleendend', $candy());
	}
	
	function testArgs() {
		$candy = new Candy(function($num) { return $num + 1; });
		$candy->wrap(function($candy, $num) { return $candy() + 1; });
		$this->assertEquals(3, $candy(1));
	}
	
	function testManyArgs() {
		$candy = new Candy(function() { return array_sum(func_get_args()); });
		$candy->wrap(function($candy) { return $candy() + 1; });
		$this->assertEquals(9, $candy(1,1,1,1,1,1,1,1));
	}
	
	function testArgManipulation() {
		$candy = new Candy(function($num) { return $num; });
		$candy->wrap(function($candy, $num) { $num += 1; return $candy(); });
		$this->assertEquals(1, $candy(1));
		
		$candy = new Candy(function($num) { return $num; });
		$candy->wrap(function($candy, &$num) { $num += 1; return $candy(); });
		$this->assertEquals(2, $candy(1));
		
		$candy = new Candy(function($num) { return $num; });
		$candy->wrap(function($candy, $num) { $num += 1; return $candy($num); });
		$this->assertEquals(2, $candy(1));
	}
	
	function testShortCircuit() {
		$candy = new Candy(function($num) { return $num; });
		$candy->wrap(function($candy, $num) { return 2; });
		$this->assertEquals(2, $candy(1));
	}
	
	function testLIFO() {
		$candy = new Candy(function($num) { return $num + 1; });
		$candy->wrap(function($candy, $num) { return $candy($num * 2); })
			  ->wrap(function($candy, $num) { return $candy($num - 1); });
		$this->assertEquals(5, $candy(3));
	}
	
	function testLazyLinks() {
		$candy = new Candy(function($num) { return $num + 1; });
		$candy->wrap(function($candy, &$num) { $num += 1; })
			  ->wrap(function($candy, $num) { /* echo or log something */ });
		$this->assertEquals(3, $candy(1));
	}
	
	function testNonCallableException() {
		try {
			$candy = new Candy('unknownFunc');
			$this->assertTrue(false);
		} catch(Exception $e) {
			$this->assertTrue(true);
			return;
		}
		$this->assertTrue(false);
	}
	
	function testPlainOldFunction() {
		$candy = new Candy('callMe');
		$this->assertEquals('pass',$candy());
		$candy->wrap(function ($candy) { return $candy() . 'me'; });
		$this->assertEquals('passme',$candy());
	}
	
	function testStaticMethod() {
		$candy = new Candy(array('SomeStaticClass','callMe'));
		$this->assertEquals('pass',$candy());
		$candy->wrap(function ($candy) { return $candy() . 'me'; });
		$this->assertEquals('passme',$candy());
	}
	
	function testMethod() {
		$candy = new Candy(array(new SomeStaticClass,'callMe'));
		$this->assertEquals('pass',$candy());
		$candy->wrap(function ($candy) { return $candy() . 'me'; });
		$this->assertEquals('passme',$candy());
	}
	
	function testManyArgsUnwrapped() {
		$candy = new Candy(function() { return array_sum(func_get_args()); });
		$this->assertEquals(0, $candy());
		$this->assertEquals(1, $candy(1));
		$this->assertEquals(2, $candy(1,1));
		$this->assertEquals(3, $candy(1,1,1));
		$this->assertEquals(4, $candy(1,1,1,1));
		$this->assertEquals(5, $candy(1,1,1,1,1));
		$this->assertEquals(6, $candy(1,1,1,1,1,1));
		$this->assertEquals(7, $candy(1,1,1,1,1,1,1));
	}
}


class SomeStaticClass { 
	static function callMe() { return 'pass'; }
	function callMeToo() { return 'pass'; }
}
function callMe() { return 'pass'; }