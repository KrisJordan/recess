<?php
use recess\lang\ReflectionMethod;
use recess\lang\Annotation;

require_once __DIR__ . '/DummyAnnotation.class.php';
use made\up\space\DummyAnnotation;

class ReflectionMethodTest extends PHPUnit_Framework_TestCase {

	function testBasic() {
		DummyAnnotation::load();
		$reflectionMethod = new ReflectionMethod('AClass','aMethod');
		$this->assertFalse($reflectionMethod->isAttached());
		$annotations = $reflectionMethod->getAnnotations();
		
		$dummyA = new DummyAnnotation();
		$dummyA->parameters = array('a');
		$dummyB = new DummyAnnotation();
		$dummyB->parameters = array('b');
		
		$this->assertEquals(array($dummyA,$dummyB),$annotations);
	}
}

class AClass {
	/**
	 * !Dummy a
	 * !Dummy b
	 */
	function aMethod() {
		
	}
}