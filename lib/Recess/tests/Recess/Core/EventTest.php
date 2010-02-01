<?php
require_once 'PHPUnit/Framework.php';

include_once __DIR__ . '/../../../../Recess/Core/Event.php';
use Recess\Core\Event;

class EventTest extends PHPUnit_Framework_TestCase {
	
	function testCallback() {
		$onImportantEvent = new Event;
		$this->assertEquals(array(), $onImportantEvent->callbacks());

		$listener = function() { echo "I'm a listener!"; };
		$onImportantEvent->callback($listener);
		$this->assertEquals(array($listener), $onImportantEvent->callbacks());

		$listener2 = function() { echo "I'm another listener!"; };
		$onImportantEvent->callback($listener2);
		$this->assertEquals(array($listener, $listener2), $onImportantEvent->callbacks());
	}
	
	function testEmptyInvoke() {
		$onUnimportantEvent = new Event;
		$onUnimportantEvent();
		$this->assertTrue(true);
	}
	
	function testSingleInvoke() {
		$onSomewhatImportantEvent = new Event;
		$callbackCount = 0;
		$onSomewhatImportantEvent->callback(function() use (&$callbackCount) { $callbackCount++; });
		$onSomewhatImportantEvent();
		$this->assertEquals(1,$callbackCount);
		$onSomewhatImportantEvent();
		$this->assertEquals(2,$callbackCount);
	}
	
	function testManyInvoke() {
		$onImportantEvent = new Event;
		$callbackCount = 0;
		$callbackCount2 = 0;
		$onImportantEvent
			->callback(function() use (&$callbackCount) { $callbackCount++; })
			->callback(function() use (&$callbackCount, &$callbackCount2) { $callbackCount++; $callbackCount2++; });
		$onImportantEvent();
		$this->assertEquals(2,$callbackCount);
		$this->assertEquals(1,$callbackCount2);
		$onImportantEvent();
		$this->assertEquals(4,$callbackCount);
		$this->assertEquals(2,$callbackCount2);
	}
	
	function testInvokeArgs() {
		$onPrint = new Event;
		$theMessage = '';
		$onPrint->callback(function($message) use (&$theMessage) { $theMessage = $message; });
		$onPrint('Hello World');
		$this->assertEquals('Hello World', $theMessage);
	}
	
	function testInvokeManyArgs() {
		$onSum = new Event;
		$theSum = 0;
		$onSum->callback(function() use (&$theSum) { $args = func_get_args(); $theSum = array_sum($args); });
		$onSum(1,1,1,1,1,1,1,1,1,1);
		$this->assertEquals(10, $theSum);
	}
	
	function testVaryingArgNumbers() {
		$onSum = new Event;
		$theSum = 0;
		$onSum->callback(function() use (&$theSum) { $theSum = array_sum(func_get_args()); });
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
	
	function testCall() {
		$event = new Event();
		$eventIsCalled = false;
		$event->callback(function($isCalled) use (&$eventIsCalled) { $eventIsCalled = $isCalled; });
		$event->call(true);
		$this->assertTrue($eventIsCalled);
	}
	
	function testApply() {
		$event = new Event();
		$eventIsCalled = false;
		$event->callback(function($isCalled) use (&$eventIsCalled) { $eventIsCalled = $isCalled; });
		$event->apply(array(true));
		$this->assertTrue($eventIsCalled);
	}
	
	function testRegisterNonCallbackable() {
		try {
			$event = new Event();
			$event->callback('notARealFunction');
			$this->fail('Registering a non is_callable callback with Event should throw.');
		} catch(Exception $e) {
			$this->assertTrue(true);
		}
	}
}