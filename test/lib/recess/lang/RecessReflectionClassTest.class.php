<?php
/**
 * Single Attribute
 * !AttrA AttributeVal1
 */
class JustOne {}

/**
 * This class is jibberish!
 * @author Kris Jordan
 * !AttrA sectors
 * !AttrB employeesByAge, Class: Employee, OrderBy: age
 * !AttrB employeesByName, Class: Employee, OrderBy: name
 */
class JibbityJab {}

/**
 * There are no annotations in this class.
 * @author Kris Jordan
 */
class NoAnnotations {}

/**
 * There is no annotation class corresponding to the annotation...
 * !AttrC
 */
class AnnotationDoesNotExist {}

/**
 * Invalid Annotation Value!
 * !AttrB some's, gots, )
 */
class InvalidAnnotationValue {}

class AttrAAnnotation {
	function init($args) {}
}

class AttrBAnnotation {
	function init($args) {}
}

Library::import('recess.lang.RecessReflectionClass');

class RecessReflectionClassTest extends UnitTestCase {
	
	function testJustOne() {
		$reflection = new RecessReflectionClass('JustOne');
		$annotations = $reflection->getAnnotations();
		$this->assertEqual(count($annotations),1);
		$this->assertEqual('AttrAAnnotation', get_class($annotations[0]));
	}
	
	function testReflection() {
		$reflection = new RecessReflectionClass('JibbityJab');
		$annotations = $reflection->getAnnotations();
		$this->assertEqual(count($annotations), 3);
		$this->assertEqual('AttrAAnnotation', get_class($annotations[0]));
		$this->assertEqual('AttrBAnnotation', get_class($annotations[1]));
		$this->assertEqual('AttrBAnnotation', get_class($annotations[2]));
	}
	
	function testAnnotationDoesNotExist() {
		$reflection = new RecessReflectionClass('AnnotationDoesNotExist');
		try {
			$annotations = $reflection->getAnnotations();
			$this->assertEqual(count($annotations), 0);
			$this->fail('Should throw an UnknownAnnotationException');
		} catch(UnknownAnnotationException $e) {
			$this->pass('UnknownAnnotationException correctly thrown.');
		}
	}
	
	function testReflectionOnAnnotationlessDocComment() {
		$reflection = new RecessReflectionClass('NoAnnotations');
		$annotations = $reflection->getAnnotations();
		$this->assertEqual(count($annotations), 0);
	}
	
	function testInvalidAnnotationValue() {
		$reflection = new RecessReflectionClass('InvalidAnnotationValue');
		try {
			$annotations = $reflection->getAnnotations();
			$this->fail('Should throw an InvalidAnnotationValueException.');
		} catch(InvalidAnnotationValueException $e) {
			$this->pass('InvalidAnnotationValueException correctly thrown.');
		}
	}
	
}
?>