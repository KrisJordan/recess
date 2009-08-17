<?php
require_once 'PHPUnit/Framework.php';

include_once __DIR__ . '/../../../recess/core/Event.class.php';
use recess\core\Event;

class EventTest extends PHPUnit_Framework_TestCase {
	
	function testCall() {
		$onImportantEvent = new Event;
		$this->assertEquals(array(), $onImportantEvent->callbacks());

		$listener = function() { echo "I'm a listener!"; };
		$onImportantEvent->call($listener);
		$this->assertEquals(array($listener), $onImportantEvent->callbacks());

		$listener2 = function() { echo "I'm another listener!"; };
		$onImportantEvent->call($listener2);
		$this->assertEquals(array($listener, $listener2), $onImportantEvent->callbacks());
	}
	
	function testEmptyInvoke() {
		$onUnimportantEvent = new Event;
		$onUnimportantEvent();
		$this->assertTrue(true);
	}
	
	function testSingleInvoke() {
		$onSomewhatImportantEvent = new Event;
		$callCount = 0;
		$onSomewhatImportantEvent->call(function() use (&$callCount) { $callCount++; });
		$onSomewhatImportantEvent();
		$this->assertEquals(1,$callCount);
		$onSomewhatImportantEvent();
		$this->assertEquals(2,$callCount);
	}
	
	function testManyInvoke() {
		$onImportantEvent = new Event;
		$callCount = 0;
		$callCount2 = 0;
		$onImportantEvent
			->call(function() use (&$callCount) { $callCount++; })
			->call(function() use (&$callCount, &$callCount2) { $callCount++; $callCount2++; });
		$onImportantEvent();
		$this->assertEquals(2,$callCount);
		$this->assertEquals(1,$callCount2);
		$onImportantEvent();
		$this->assertEquals(4,$callCount);
		$this->assertEquals(2,$callCount2);
	}
	
	function testInvokeArgs() {
		$onPrint = new Event;
		$theMessage = '';
		$onPrint->call(function($message) use (&$theMessage) { $theMessage = $message; });
		$onPrint('Hello World');
		$this->assertEquals('Hello World', $theMessage);
	}
	
	function testInvokeManyArgs() {
		$onSum = new Event;
		$theSum = 0;
		$onSum->call(function() use (&$theSum) { $args = func_get_args(); $theSum = array_sum($args); });
		$onSum(1,1,1,1,1,1,1,1,1,1);
		$this->assertEquals(10, $theSum);
	}
	
	function testVaryingArgNumbers() {
		$onSum = new Event;
		$theSum = 0;
		$onSum->call(function() use (&$theSum) { $theSum = array_sum(func_get_args()); });
		$onSum(1);
		$this->assertEquals(1,$theSum);
		$theSum = 0;
		$onSum(1,1);
		$this->assertEquals(2,$theSum);
		$theSum = 0;
		$onSum(1,1,1);
		$this->assertEquals(3,$theSum);
		$theSum = 0;
		$onSum(1,1,1,1);
		$this->assertEquals(4,$theSum);
		$theSum = 0;
		$onSum(1,1,1,1,1);
		$this->assertEquals(5,$theSum);
		$theSum = 0;
		$onSum(1,1,1,1,1,1);
		$this->assertEquals(6,$theSum);
		$theSum = 0;
	}
}