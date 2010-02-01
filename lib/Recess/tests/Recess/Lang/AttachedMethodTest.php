<?php
use recess\lang\AttachedMethod;

class AttachedMethodTest extends PHPUnit_Framework_TestCase {
	
	function testBasicProperties() {
		$attachedMethod = new AttachedMethod('aMethod',function($self){return $self;});
		$this->assertTrue($attachedMethod->isFinal());
		$this->assertFalse($attachedMethod->isAbstract());
		$this->assertTrue($attachedMethod->isPublic());
		$this->assertFalse($attachedMethod->isPrivate());
		$this->assertFalse($attachedMethod->isProtected());
		$this->assertFalse($attachedMethod->isStatic());
		$this->assertFalse($attachedMethod->isConstructor());
		$this->assertFalse($attachedMethod->isDestructor());
		$this->assertTrue($attachedMethod->isAttached());
		$this->assertEquals('aMethod',$attachedMethod->getName());
		$this->assertFalse($attachedMethod->isInternal());
		$this->assertTrue($attachedMethod->isUserDefined());
		$this->assertEquals(__FILE__, $attachedMethod->getFileName());
		$this->assertEquals(7, $attachedMethod->getStartLine());
		$this->assertEquals(7, $attachedMethod->getEndLine());
		$this->assertEquals(array(), $attachedMethod->getParameters());
		$this->assertEquals(0, $attachedMethod->getNumberOfParameters());
		$this->assertEquals(0, $attachedMethod->getNumberOfRequiredParameters());
	}
	
	function testOnPlainFunction() {
		$attachedMethod = new AttachedMethod('aMethod','aPlainFunction');
		$this->assertEquals(1, $attachedMethod->getNumberOfParameters());
		$this->assertEquals(1, $attachedMethod->getNumberOfRequiredParameters());
	}
	
	function testOnMethod() {
		$foo = new ACallableClass;
		$attachedMethod = new AttachedMethod('aMethod',array($foo,'method'));
		$this->assertEquals(0, $attachedMethod->getNumberOfParameters());
		$this->assertEquals(0, $attachedMethod->getNumberOfRequiredParameters());
	}
	
	function testOnStaticMethod() {
		$attachedMethod = new AttachedMethod('aMethod',array('ACallableClass','staticMethod'));
		$this->assertEquals(3, $attachedMethod->getNumberOfParameters());
		$this->assertEquals(2, $attachedMethod->getNumberOfRequiredParameters());
	}
	
	function testOnCallable() {
		$attachedMethod = new AttachedMethod('aMethod',new ACallableClass);
		$this->assertEquals(2, $attachedMethod->getNumberOfParameters());
		$this->assertEquals(2, $attachedMethod->getNumberOfRequiredParameters());
	}
	
	function testCreatedFunction() {
		$attachedMethod = new AttachedMethod('aMethod', create_function('$self, $two','return $self;'));
		$this->assertEquals(1, $attachedMethod->getNumberOfParameters());
		$this->assertEquals(1, $attachedMethod->getNumberOfRequiredParameters());
	}
	
}

class ACallableClass {
	function __invoke($self, $foo, $bar) {
		return $self;
	}
	function method($self) {
		return $self;
	}
	static function staticMethod($self,$static,$three,$params=2) {
		return $self;
	}
}

function aPlainFunction($self, $string) {
	return $string;
}