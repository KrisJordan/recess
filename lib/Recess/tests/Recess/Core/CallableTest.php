<?php
require_once 'PHPUnit/Framework.php';

require_once __DIR__ . '/../../../Core/ICallable.php';
require_once __DIR__ . '/../../../Core/Callable.php';

use Recess\Core\Callable;

class CallableTest extends PHPUnit_Framework_TestCase {
	
	protected $simple;
	protected $oneArg;
	protected $byRef;
	protected $instanceMethod;
	protected $staticMethod;
	
	function setup() {
		$this->simple = new Callable('callableSimple');
		$this->oneArg = new Callable('callableOneArg');
		$this->byRef = new Callable('callableByRef');
		$this->instanceMethod 
			= new Callable(array(new CallableTestDummy,'instanceMethod'));
		$this->staticMethod 
			= new Callable(array('CallableTestDummy','staticMethod'));
		$this->lambda = new Callable(function() { return 'lambda'; });
	}
	
	function testSimple() {
		$callable = $this->simple;
		$this->assertEquals('simple',$callable());
	}
	
	function testSimpleCall() {
		$callable = $this->simple;
		$this->assertEquals('simple',$callable->call());
	}
	
	function testSimpleApply() {
		$callable = $this->simple;
		$this->assertEquals('simple',$callable->apply());
	}
	
	function testOneArg() {
		$callable = $this->oneArg;
		$this->assertEquals('oneArg',$callable('oneArg'));
	}
	
	function testOneArgCall() {
		$callable = $this->oneArg;
		$this->assertEquals('oneArg',$callable->call('oneArg'));
	}
	
	function testOneArgApply() {
		$callable = $this->oneArg;
		$this->assertEquals('oneArg',$callable->apply(array('oneArg')));
	}
	
	function testByRefFailure() {
		$plusOne = $this->byRef;
		$zero = 0;
		$plusOne($zero);
		$this->assertEquals(0,$zero);
	}
	
	function testByRefCallFailure() {
		$plusOne = $this->byRef;
		$zero = 0;
		$plusOne->call($zero);
		$this->assertEquals(0,$zero);
	}
	
	function testByRefApplyFailure() {
		$plusOne = $this->byRef;
		$zero = 0;
		$plusOne->apply(array($zero));
		$this->assertEquals(0,$zero);
	}
	
	function testInstanceMethod() {
		$callable = $this->instanceMethod;
		$this->assertEquals('instance method',$callable());
	}
	
	function testInstanceMethodCall() {
		$callable = $this->instanceMethod;
		$this->assertEquals('instance method',$callable->call());
	}
	
	function testInstanceMethodApply() {
		$callable = $this->instanceMethod;
		$this->assertEquals('instance method',$callable->apply(array()));
	}
	
	function testStaticMethod() {
		$callable = $this->staticMethod;
		$this->assertEquals('static method',$callable());
	}
	
	function testStaticMethodCall() {
		$callable = $this->staticMethod;
		$this->assertEquals('static method',$callable->call());
	}
	
	function testStaticMethodApply() {
		$callable = $this->staticMethod;
		$this->assertEquals('static method',$callable->apply());
	}
	
	function testLambda() {
		$callable = $this->lambda;
		$this->assertEquals('lambda',$callable());
	}
	
	function testLambdaCall() {
		$callable = $this->lambda;
		$this->assertEquals('lambda',$callable->call());
	}
	
	function testLambdaApply() {
		$callable = $this->lambda;
		$this->assertEquals('lambda',$callable->apply());
	}
	
	function testConstructNotCallable() {
		try {
			$callable = new Callable('nonExistantFunction');
			$this->fail('Constructing a new Callable without an is_callable should throw.');
		} catch (Exception $e) {
			$this->assertTrue(true);
		}
	}
	
}

class CallableTestDummy {
	function instanceMethod() { return 'instance method'; }
	static function staticMethod() { return 'static method'; }	
}

function callableSimple() { return 'simple'; }
function callableOneArg($arg) { return $arg; }
function callableByRef(&$ref) { $ref += 1; }