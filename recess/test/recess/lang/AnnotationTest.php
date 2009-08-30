<?php
use made\up\space;
use recess\lang\Annotation;

require_once 'DummyAnnotation.class.php';
use made\up\space\DummyAnnotation;

class AnnotationTest extends PHPUnit_Framework_TestCase {
	
	function testSimpleParse() {
		DummyAnnotation::load();
		$docstring = "/** !Dummy */";
		$annotations = Annotation::parse($docstring);
		$this->assertEquals(1,count($annotations));
		$this->assertEquals(array(new DummyAnnotation), $annotations);
	}
	
	function testParamsParse() {
		DummyAnnotation::load();
		$docstring = "/** !Dummy a, b, c */";
		$annotations = Annotation::parse($docstring);
		$this->assertEquals(1, count($annotations));
		$expected = new DummyAnnotation;
		$expected->parameters = array('a','b','c');
		$this->assertEquals(array($expected), $annotations);
	}
	
	function testKeyValParamsParse() {
		DummyAnnotation::load();
		$docstring = "/** !Dummy a: b, c: d */";
		$annotations = Annotation::parse($docstring);
		$this->assertEquals(1, count($annotations));
		$expected = new DummyAnnotation;
		$expected->parameters = array('a'=>'b','c'=>'d');
		$this->assertEquals(array($expected), $annotations);
	}
	
	function testSubArrayParse() {
		DummyAnnotation::load();
		$docstring = "/** !Dummy a: (b: c, d, e, f: g), h */";
		$annotations = Annotation::parse($docstring);
		$this->assertEquals(1, count($annotations));
		$expected = new DummyAnnotation;
		$expected->parameters = array('a'=>array('b'=>'c','d','e','f'=>'g'),'h');
		$this->assertEquals(array($expected), $annotations);
	}

	function testMultiParse() {
		DummyAnnotation::load();
		$docstring = "/** 
					   * !Dummy a, b, c
					   * !Dummy d, e, f
					   */";
		$annotations = Annotation::parse($docstring);
		$this->assertEquals(2, count($annotations));
		$expectedA = new DummyAnnotation;
		$expectedA->parameters = array('a','b','c');
		$expectedB = new DummyAnnotation;
		$expectedB->parameters = array('d','e','f');
		$this->assertEquals(array($expectedA,$expectedB), $annotations);
	}
	
	function testUnknownAnnotation() {
		$docstring = "/** !Foo */";
		try {
			$annotations = Annotation::parse($docstring);
			$this->assertTrue(false);
		} catch (Exception $e) {
			$this->assertTrue(true);
		}
	}
	
	function testInvalidAnnotation() {
		$docstring = "/** !Foo 'bar */";
		try {
			$annotations = Annotation::parse($docstring);
			$this->assertTrue(false);
		} catch (Exception $e) {
			$this->assertTrue(true);
		}
	}
	
}