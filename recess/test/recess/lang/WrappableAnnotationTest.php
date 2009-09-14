<?php
use recess\lang\Object;
use recess\lang\WrappableAnnotation;

class WrappableAnnotationTest extends PHPUnit_Framework_TestCase {
	
	function setUp() {
		WrappableAnnotation::load();
		WrappableTestObject::clearClassDescriptor();
	}
	
	function testBasic() {
		$object = new WrappableTestObject();
		$this->assertTrue($object->wrappedTrue());
		$this->assertTrue($object->true());
		$this->assertFalse($object->wrappedTrue(false));
		$this->assertFalse($object->true(false));
	}
	
	function testWrap() {
		$object = new WrappableTestObject();
		$counter = 0;
		WrappableTestObject::attached('true')->wrap(function($candy,$true=true)use(&$counter) { $counter++;});
		
		$this->assertTrue($object->true());
		$this->assertFalse($object->true(false));
		$this->assertEquals(2,$counter);
	}
	
	function testUsage() {
		$annotation = new WrappableAnnotation();
		$this->assertEquals('!Wrappable wrappedMethodName', $annotation->usage());
	}
	
}

class WrappableTestObject extends Object {
	
	/** !Wrappable true */
	function wrappedTrue($true = true) {
		return $true;
	}
	
}