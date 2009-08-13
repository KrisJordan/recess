<?php
require_once 'PHPUnit/Framework.php';

include_once __DIR__ . '/../../../recess/core/Chain.class.php';
use recess\core\Chain;

class ChainTest extends PHPUnit_Framework_TestCase {
	
	function testConstructor() {
		$chain = new Chain(function() { return 'Hello world!'; });
		$this->assertEquals('Hello world!',$chain());
	}
	
	function testAdd() {
		$chain = new Chain(function() { return 'link'; });
		$chain->add(function($next) { return 'chain' . $next(); });
		$this->assertEquals('chainlink', $chain());
	}
	
	function testWrapChain() {
		$chain = new Chain(function() { return 'middle'; });
		$wrap = function($next) {return 'front' . $next() . 'end';};
		$chain->add($wrap)->add($wrap);
		$this->assertEquals('frontfrontmiddleendend', $chain());
	}
	
	function testArgs() {
		$chain = new Chain(function($num) { return $num + 1; });
		$chain->add(function($next, $num) { return $next() + 1; });
		$this->assertEquals(3, $chain(1));
	}
	
	function testArgManipulation() {
		$chain = new Chain(function($num) { return $num; });
		$chain->add(function($next, $num) { $num += 1; return $next(); });
		$this->assertEquals(1, $chain(1));
		
		$chain = new Chain(function($num) { return $num; });
		$chain->add(function($next, &$num) { $num += 1; return $next(); });
		$this->assertEquals(2, $chain(1));
		
		$chain = new Chain(function($num) { return $num; });
		$chain->add(function($next, $num) { $num += 1; return $next($num); });
		$this->assertEquals(2, $chain(1));
	}
	
	function testShortCircuit() {
		$chain = new Chain(function($num) { return $num; });
		$chain->add(function($next, $num) { return 2; });
		$this->assertEquals(2, $chain(1));
	}
	
	function testLIFO() {
		$chain = new Chain(function($num) { return $num + 1; });
		$chain->add(function($next, $num) { return $next($num * 2); })
			  ->add(function($next, $num) { return $next($num - 1); });
		$this->assertEquals(5, $chain(3));
	}
	
	function testLazyLinks() {
		$chain = new Chain(function($num) { return $num + 1; });
		$chain->add(function($next, &$num) { $num += 1; })
			  ->add(function($next, $num) { /* echo or log something */ });
		$this->assertEquals(3, $chain(1));
	}
	
} 