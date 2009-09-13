<?php
use recess\lang\Object;

require_once __DIR__ . '/DummyAnnotation.class.php';
use made\up\space\DummyAnnotation;
DummyAnnotation::load();

class ObjectTest extends PHPUnit_Framework_TestCase {
	
	function setUp() {
		AnObject::clearClassDescriptor();
	}
	
	function testConstruct() {
		$anObject = new AnObject();
		$this->assertType('recess\lang\Object',$anObject);
	}
	
	function testAttach() {
		$provider = new IsTrueProvider();
		AnObject::attach('returnTrue',array($provider,'returnTrue'));
		$anObject = new AnObject();
		$this->assertTrue($anObject->returnTrue());
	}
	
	function testAttachPlainFunction() {
		AnObject::attach('returnTrue','returnTruePlain');
		$anObject = new AnObject();
		$this->assertTrue($anObject->returnTrue());
	}
	
	function testRenamePlainFunction() {
		AnObject::attach('trueOrNotTrue','returnTruePlain');
		$anObject = new AnObject();
		$this->assertTrue($anObject->trueOrNotTrue());
		try {
			$anObject->returnTruePlain();
			$this->fail('Should have thrown an exception, attached method does not exist.');
		} catch(Exception $e) {
			$this->assertTrue(true);
		}
	}
	
	function testAttachLambda() {
		AnObject::attach('returnTrue', function ($self) { return $self instanceof AnObject; });
		$anObject = new AnObject();
		$this->assertTrue($anObject->returnTrue());
	}
	
	function testGetAttachedMethods() {
		$attachedMethods = AnObject::getAttachedMethods();
		$this->assertEquals(0,count($attachedMethods));
		
		AnObject::attach('returnTrue', function ($self) { return $self instanceof AnObject; });
		$anObject = new AnObject();
		$attachedMethods = AnObject::getAttachedMethods();
		$this->assertEquals(1, count($attachedMethods));
		$this->assertType('recess\lang\AttachedMethod',$attachedMethods['returnTrue']);
	}
	
	function testClearIndividualDescriptor() {
		$originalDescriptor = clone AnObject::getClassDescriptor();
		
		AnObject::attach('returnTrue','returnTruePlain');
		$this->assertNotEquals($originalDescriptor,AnObject::getClassDescriptor());
		
		AnObject::clearClassDescriptor();
		$this->assertEquals($originalDescriptor,AnObject::getClassDescriptor());
	}
	
	function testGetDescriptorAbstract() {
		$attachedMethods = AbstractObject::getAttachedMethods();
		$this->assertEquals(0,count($attachedMethods));	
	}
	
	function testGetDescriptorNonObject() {
		try {
			$descriptor = Object::getClassDescriptor('NotAChildObject');
			$this->fail('Should throw error when getting class descriptor of non Object');
		} catch(Exception $e) {
			$this->assertTrue(true);
		}
	}
}

class NotAChildObject {
	
}

// Helpers

abstract class AbstractObject extends Object {
	
}

/** !Dummy */
class AnObject extends Object {
	
	/** !Dummy */
	public $foo; 
	
	function __invoke($self, $foo, $bar) {
		return $self;
	}
	
	/** !Dummy */
	function method($self) {
		return $self;
	}
	static function staticMethod($self,$static,$three,$params=2) {
		return $self;
	}
}

class IsTrueProvider {
	function returnTrue($self) {
		if($self instanceof AnObject) {
			return true;
		} else {
			return false;
		}
	}
}

function returnTruePlain($self) {
	if($self instanceof AnObject) {
		return true;
	} else {
		return false;
	}
}