<?php
require_once 'PHPUnit/Framework.php';

include_once __DIR__ . '/../../../../Recess/Core/Wrappable.php';
use recess\core\Wrappable;

class WrappableTest extends PHPUnit_Framework_TestCase {

	function testConstructor() {
		$wrappable = new Wrappable(function() { return 'Hello world!'; });
		$this->assertEquals('Hello world!',$wrappable());
	}
	
	function testWrap() {
		$wrappable = new Wrappable(function() { return 'link'; });
		$wrappable->wrap(function($wrappable) { return 'wrapper' . $wrappable(); });
		$this->assertEquals('wrapperlink', $wrappable());
	}
	
	function testWrapWrappable() {
		$wrappable = new Wrappable(function() { return 'middle'; });
		$wrap = function($wrappable) {return 'front' . $wrappable() . 'end';};
		$wrappable->wrap($wrap)->wrap($wrap);
		$this->assertEquals('frontfrontmiddleendend', $wrappable());
	}
	
	function testArgs() {
		$wrappable = new Wrappable(function($num) { return $num + 1; });
		$wrappable->wrap(function($wrappable, $num) { return $wrappable() + 1; });
		$this->assertEquals(3, $wrappable(1));
	}
	
	function testManyArgs() {
		$wrappable = new Wrappable(function() { return array_sum(func_get_args()); });
		$wrappable->wrap(function($wrappable) { return $wrappable() + 1; });
		$this->assertEquals(9, $wrappable(1,1,1,1,1,1,1,1));
	}
	
	function testArgManipulation() {
		$wrappable = new Wrappable(function($num) { return $num; });
		$wrappable->wrap(function($wrappable, $num) { $num += 1; return $wrappable(); });
		$this->assertEquals(1, $wrappable(1));
		
		$wrappable = new Wrappable(function($num) { return $num; });
		$wrappable->wrap(function($wrappable, $num) { $num += 1; return $wrappable($num); });
		$this->assertEquals(2, $wrappable(1));
	}
	
	function testShortCircuit() {
		$wrappable = new Wrappable(function($num) { return $num; });
		$wrappable->wrap(function($wrappable, $num) { return 2; });
		$this->assertEquals(2, $wrappable(1));
	}
	
	function testLIFO() {
		$wrappable = new Wrappable(function($num) { return $num + 1; });
		$wrappable->wrap(function($wrappable, $num) { return $wrappable($num * 2); })
			  ->wrap(function($wrappable, $num) { return $wrappable($num - 1); });
		$this->assertEquals(5, $wrappable(3));
	}
	
	function testLazyLinks() {
		$wrappable = new Wrappable(function($num) { return $num + 1; });
		$wrappable->wrap(function($wrappable, $num) { return $wrappable($num+1); })
			  ->wrap(function($wrappable, $num) { /* echo or log something */ });
		$this->assertEquals(3, $wrappable(1));
	}

	function testNonCallableException() {
		try {
			$wrappable = new Wrappable('unknownFunc');
			$this->assertTrue(false);
		} catch(Exception $e) {
			$this->assertTrue(true);
			return;
		}
		$this->assertTrue(false);
	}
	
	function testPlainOldFunction() {
		$wrappable = new Wrappable('callMe');
		$this->assertEquals('pass',$wrappable());
		$wrappable->wrap(function ($wrappable) { return $wrappable() . 'me'; });
		$this->assertEquals('passme',$wrappable());
	}
	
	function testStaticMethod() {
		$wrappable = new Wrappable(array('SomeStaticClass','callMe'));
		$this->assertEquals('pass',$wrappable());
		$wrappable->wrap(function ($wrappable) { return $wrappable() . 'me'; });
		$this->assertEquals('passme',$wrappable());
	}
	
	function testMethod() {
		$wrappable = new Wrappable(array(new SomeStaticClass,'callMe'));
		$this->assertEquals('pass',$wrappable());
		$wrappable->wrap(function ($wrappable) { return $wrappable() . 'me'; });
		$this->assertEquals('passme',$wrappable());
	}
	
	function testManyArgsUnwrapped() {
		$wrappable = new Wrappable(function() { return array_sum(func_get_args()); });
		$this->assertEquals(0, $wrappable());
		$this->assertEquals(1, $wrappable(1));
		$this->assertEquals(2, $wrappable(1,1));
		$this->assertEquals(3, $wrappable(1,1,1));
		$this->assertEquals(4, $wrappable(1,1,1,1));
		$this->assertEquals(5, $wrappable(1,1,1,1,1));
		$this->assertEquals(6, $wrappable(1,1,1,1,1,1));
		$this->assertEquals(7, $wrappable(1,1,1,1,1,1,1));
	}
	
	function testMultipleCalls() {
		$print = new Wrappable(function($text) { return $text; });
		$print->wrap(
			function($print, $text) 
				{ $print("It's $text"); $print(); $print('Gum!'); }
		);
		try {
			$print('Doublemint! ');
			$this->fail('Calling a wrappable multiple times in a single wrapper should throw.');
		} catch(Exception $e) {
			$this->assertTrue(true);
		}
	}
	
	function testShortCircuitInsanity() {
		$wrappable = new Wrappable(function() { return "wrappable"; });
		$wrappable->wrap(
			function($wrappable) { return "first wrapper"; }
		)->wrap(
			function($wrappable) { return "second wrapper"; }
		)->wrap(
			function($wrappable) { }
		);
		$this->assertEquals("second wrapper",$wrappable());
	}
	
	function testShortCircuitInsanity2() {
		$wrappable = new Wrappable(function() { return "wrappable"; });
		$wrappable->wrap(
			function($wrappable) { return "first wrapper"; }
		)->wrap(
			function($wrappable) { return "second wrapper"; }
		)->wrap(
			function($wrappable) { $wrappable(); }
		);
		$this->assertEquals("second wrapper",$wrappable());
	}
}


class SomeStaticClass { 
	static function callMe() { return 'pass'; }
	function callMeToo() { return 'pass'; }
}
function callMe() { return 'pass'; }