<?php
use recess\lang\ReflectionClass;
use recess\lang\ReflectionProperty;
use recess\lang\Object;

require_once __DIR__ . '/DummyAnnotation.class.php';
use made\up\space\DummyAnnotation;

class ReflectionClassTest extends PHPUnit_Framework_TestCase {

	function testGetAnnotations() {
		DummyAnnotation::load();
		$klass = new ReflectionClass('ReflectionClassObject');
		$annotations = $klass->getAnnotations();
		$this->assertEquals(1, count($annotations));
		$this->assertEquals(array(new DummyAnnotation), $annotations);
	}
	
	function testGetProperties() {
		DummyAnnotation::load();
		$childClass = new ReflectionClass('ReflectionClassObject');
		$parentClass = new ReflectionClass('recess\lang\Object');
		$this->assertEquals(1, count($childClass->getProperties())-count($parentClass->getProperties()));
	}
	
	function testGetMethods() {
		DummyAnnotation::load();
		$childClass = new ReflectionClass('ReflectionClassObject');
		$parentClass = new ReflectionClass('recess\lang\Object');
		$this->assertEquals(1, count($childClass->getMethods())-count($parentClass->getMethods()));
	}
	
	function testGetAttachedMethods() {
		DummyAnnotation::load();
		ReflectionClassObject::attachMethod('aMethod',function($self){return true;});
		$childClass = new ReflectionClass('ReflectionClassObject');
		$parentClass = new ReflectionClass('recess\lang\Object');
		$this->assertEquals(2, count($childClass->getMethods())-count($parentClass->getMethods()));		
	}

}

/** !Dummy */
class ReflectionClassObject extends Object {
	/** !Dummy */
	function fooMethod() {}
	
	/** !Dummy */
	public $fooProperty;	
}