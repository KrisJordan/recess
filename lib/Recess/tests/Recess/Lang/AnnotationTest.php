<?php
use recess\lang\Annotation;

require_once __DIR__ . '/DummyAnnotation.class.php';
use made\up\space\DummyAnnotation;
require_once __DIR__ . '/ValidateAnnotation.class.php';
use made\up\space\ValidateAnnotation;

class AnnotationTest extends PHPUnit_Framework_TestCase {
	
	function setUp() {
		DummyAnnotation::load();
		ValidateAnnotation::load();
	}
	
	function testSimpleParse() {
		$docstring = "/** !Dummy */";
		$annotations = Annotation::parse($docstring);
		$this->assertEquals(1,count($annotations));
		$this->assertEquals(array(new DummyAnnotation), $annotations);
	}
	
	function testParamsParse() {
		$docstring = "/** !Dummy a, b, c */";
		$annotations = Annotation::parse($docstring);
		$this->assertEquals(1, count($annotations));
		$expected = new DummyAnnotation;
		$expected->parameters = array('a','b','c');
		$this->assertEquals(array($expected), $annotations);
	}
	
	function testKeyValParamsParse() {
		$docstring = "/** !Dummy a: b, c: d */";
		$annotations = Annotation::parse($docstring);
		$this->assertEquals(1, count($annotations));
		$expected = new DummyAnnotation;
		$expected->parameters = array('a'=>'b','c'=>'d');
		$this->assertEquals(array($expected), $annotations);
	}
	
	function testSubArrayParse() {
		$docstring = "/** !Dummy a: (b: c, d, e, f: g), h */";
		$annotations = Annotation::parse($docstring);
		$this->assertEquals(1, count($annotations));
		$expected = new DummyAnnotation;
		$expected->parameters = array('a'=>array('b'=>'c','d','e','f'=>'g'),'h');
		$this->assertEquals(array($expected), $annotations);
	}

	function testMultiParse() {
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
		$docstring = "/** !Dummy 'bar */";
		try {
			$annotations = Annotation::parse($docstring);
			$this->assertTrue(false);
		} catch (Exception $e) {
			$this->assertTrue(true);
		}
	}
	
	function testAcceptedKeys() {
		$docstring = '/** !Validate AValue, KeyA: foo, KeyB: bar, KeyD: foobar */';
		$annotations = Annotation::parse($docstring);
		$this->assertEquals(1,count($annotations));
		$annotations[0]->validate('Basic');
		$this->assertEquals(0,count($annotations[0]->getErrors()));
	}
	
	function testAcceptedKeysFail() {
		$docstring = '/** !Validate AValue, KeyA: foo, KeyC: foo */';
		$annotations = Annotation::parse($docstring);
		$this->assertEquals(1,count($annotations));
		$annotations[0]->validate('Basic');
		$this->assertEquals(1,count($annotations[0]->getErrors()));
	}
	
	function testRequiredKeys() {
		$docstring = '/** !Validate AValue, KeyA: foo */';
		$annotations = Annotation::parse($docstring);
		$this->assertEquals(1,count($annotations));
		$annotations[0]->validate('Basic');
		$this->assertEquals(0,count($annotations[0]->getErrors()));
	}
	
	function testRequiredKeysFail() {
		$docstring = '/** !Validate AValue, KeyB: bar */';
		$annotations = Annotation::parse($docstring);
		$this->assertEquals(1,count($annotations));
		$annotations[0]->validate('Basic');
		$this->assertEquals(1,count($annotations[0]->getErrors()));
	}
	
	function testAcceptedValues() {
		$docstring = '/** !Validate AValue, BValue, KeyA: foo*/';
		$annotations = Annotation::parse($docstring);
		$this->assertEquals(1,count($annotations));
		$annotations[0]->validate('Basic');
		$this->assertEquals(0,count($annotations[0]->getErrors()));
	}
	
	function testAcceptedValuesFail() {
		$docstring = '/** !Validate AValue, CValue, KeyA: foo */';
		$annotations = Annotation::parse($docstring);
		$this->assertEquals(1,count($annotations));
		$annotations[0]->validate('Basic');
		$this->assertEquals(1,count($annotations[0]->getErrors()));
	}
	
	function testAcceptedIndexedValues() {
		$docstring = '/** !Validate AValue, BValue, KeyA: foo*/';
		$annotations = Annotation::parse($docstring);
		$this->assertEquals(1,count($annotations));
		$annotations[0]->validate('Basic');
		$this->assertEquals(0,count($annotations[0]->getErrors()));
	}
	
	function testAcceptedIndexedValuesFail() {
		$docstring = '/** !Validate KeyA: foo, BValue, AValue */';
		$annotations = Annotation::parse($docstring);
		$this->assertEquals(1,count($annotations));
		$annotations[0]->validate('Basic');
		$this->assertEquals(1,count($annotations[0]->getErrors()));
	}
	
	function testAcceptedValuesForKeyFail() {
		$docstring = '/** !Validate AValue, KeyA: foo, KeyB: foo */';
		$annotations = Annotation::parse($docstring);
		$this->assertEquals(1,count($annotations));
		$annotations[0]->validate('Basic');
		$this->assertEquals(1,count($annotations[0]->getErrors()));
		
		$docstring = '/** !Validate AValue, KeyA: foo, KeyB: BAR */';
		$annotations = Annotation::parse($docstring);
		$this->assertEquals(1,count($annotations));
		$annotations[0]->validate('Basic');
		$this->assertEquals(1,count($annotations[0]->getErrors()));
	}
	
	function testAcceptedValuesForKeyChangeCase() {
		$docstring = '/** !Validate AValue, KeyA: FOO, KeyD: foobar */';
		$annotations = Annotation::parse($docstring);
		$this->assertEquals(1,count($annotations));
		$annotations[0]->validate('Basic');
		$this->assertEquals(0,count($annotations[0]->getErrors()));
	}
	
	function testAcceptsNoKeylessValues() {
		$docstring = '/** !Validate KeyA: Foo */';
		$annotations = Annotation::parse($docstring);
		$this->assertEquals(1,count($annotations));
		$annotations[0]->validate('AcceptsNoKeylessValues');
		$this->assertEquals(0,count($annotations[0]->getErrors()));
	}
	
	function testAcceptsNoKeylessValuesFail() {
		$docstring = '/** !Validate AValue */';
		$annotations = Annotation::parse($docstring);
		$this->assertEquals(1,count($annotations));
		$annotations[0]->validate('AcceptsNoKeylessValues');
		$this->assertEquals(1,count($annotations[0]->getErrors()));
	}
	
	function testAcceptsNoKeyedValues() {
		$docstring = '/** !Validate AValue */';
		$annotations = Annotation::parse($docstring);
		$this->assertEquals(1,count($annotations));
		$annotations[0]->validate('AcceptsNoKeyedValues');
		$this->assertEquals(0,count($annotations[0]->getErrors()));
	}
	
	function testAcceptsNoKeyedValuesFail() {
		$docstring = '/** !Validate AValue, KeyA: Foo */';
		$annotations = Annotation::parse($docstring);
		$this->assertEquals(1,count($annotations));
		$annotations[0]->validate('AcceptsNoKeyedValues');
		$this->assertEquals(1,count($annotations[0]->getErrors()));
	}
	
	function testValidOnSubclassesOf() {
		$docstring = '/** !Validate */';
		$annotations = Annotation::parse($docstring);
		$this->assertEquals(1,count($annotations));
		$annotations[0]->validate('made\up\space\ValidateAnnotation');
		$this->assertEquals(0,count($annotations[0]->getErrors()));
	}
	
	function testValidOnSubclassesOfFail() {
		$docstring = '/** !Validate */';
		$annotations = Annotation::parse($docstring);
		$this->assertEquals(1,count($annotations));
		$annotations[0]->validate('recess\lang\Object');
		$this->assertEquals(1,count($annotations[0]->getErrors()));
	}
	
	function testMinimumParameterCount() {
		$docstring = '/** !Validate foo */';
		$annotations = Annotation::parse($docstring);
		$this->assertEquals(1,count($annotations));
		$annotations[0]->validate('MinMax');
		$this->assertEquals(0,count($annotations[0]->getErrors()));
	}
	
	function testMinimumParameterCountFail() {
		$docstring = '/** !Validate */';
		$annotations = Annotation::parse($docstring);
		$this->assertEquals(1,count($annotations));
		$annotations[0]->validate('MinMax');
		$this->assertEquals(1,count($annotations[0]->getErrors()));
	}
	
	function testMaximumParameterCount() {		
		$docstring = '/** !Validate foo, bar */';
		$annotations = Annotation::parse($docstring);
		$this->assertEquals(1,count($annotations));
		$annotations[0]->validate('MinMax');
		$this->assertEquals(0,count($annotations[0]->getErrors()));
	}
	
	function testMaximumParameterCountFail() {
		$docstring = '/** !Validate foo, bar, baz*/';
		$annotations = Annotation::parse($docstring);
		$this->assertEquals(1,count($annotations));
		$annotations[0]->validate('MinMax');
		$this->assertEquals(1,count($annotations[0]->getErrors()));
	}
	
	function testExactParameterCount() {		
		$docstring = '/** !Validate foo */';
		$annotations = Annotation::parse($docstring);
		$this->assertEquals(1,count($annotations));
		$annotations[0]->validate('Exact');
		$this->assertEquals(0,count($annotations[0]->getErrors()));
		
		$docstring = '/** !Validate KeyA: foo */';
		$annotations = Annotation::parse($docstring);
		$this->assertEquals(1,count($annotations));
		$annotations[0]->validate('Exact');
		$this->assertEquals(0,count($annotations[0]->getErrors()));
	}
	
	function testExactParameterCountFail() {
		$docstring = '/** !Validate foo, bar*/';
		$annotations = Annotation::parse($docstring);
		$this->assertEquals(1,count($annotations));
		$annotations[0]->validate('Exact');
		$this->assertEquals(1,count($annotations[0]->getErrors()));
		
		$docstring = '/** !Validate */';
		$annotations = Annotation::parse($docstring);
		$this->assertEquals(1,count($annotations));
		$annotations[0]->validate('Exact');
		$this->assertEquals(1,count($annotations[0]->getErrors()));
	}
	
	function testIsAValue() {
		$docstring = '/** !Validate foo, bar*/';
		$annotations = Annotation::parse($docstring);
		$this->assertEquals(1,count($annotations));
		$this->assertTrue($annotations[0]->isAValue('foo'));
		$this->assertFalse($annotations[0]->isAValue('foobar'));
	}
	
	function testValueNotIn() {
		$docstring = '/** !Validate foo, bar*/';
		$annotations = Annotation::parse($docstring);
		$this->assertEquals(1,count($annotations));
		$this->assertEquals('foo',$annotations[0]->valueNotIn(array('bar','baz')));
		$this->assertFalse($annotations[0]->valueNotIn(array('bar','foo')));
	}
	
	function testExpandAnnotationOnWrongConstruct() {
		$docstring = '/** !Validate */';
		$annotations = Annotation::parse($docstring);
		$this->assertEquals(1,count($annotations));
		$reflectionClass = new recess\lang\ReflectionClass('AnnotationTest');
		$classDescriptor = new recess\lang\ClassDescriptor();
		try {
			$annotations[0]->expandAnnotation('AnnotationTest',$reflectionClass,$classDescriptor);
			$this->fail('Should throw error when annotation is on wrong construct.');
		} catch(\Exception $e) {
			$this->assertTrue(true);
		}
	}
	
	public $dummyProperty;
	function testExpandAnnotationOnWrongConstructProperty() {
		$docstring = '/** !Validate KeyA: foo */';
		$annotations = Annotation::parse($docstring);
		$this->assertEquals(1,count($annotations));
		$reflectionClass = new recess\lang\ReflectionProperty('AnnotationTest','dummyProperty');
		$classDescriptor = new recess\lang\ClassDescriptor();
		try {
			$annotations[0]->expandAnnotation('AnnotationTest',$reflectionClass,$classDescriptor);
			$this->fail('Should throw error when annotation is on wrong construct.');
		} catch(\Exception $e) {
			$this->assertTrue(true);
		}
	}
	
	function testExpandAnnotationWithValidationError() {
		$docstring = '/** !Validate foo */';
		$annotations = Annotation::parse($docstring);
		$this->assertEquals(1,count($annotations));
		$reflectionClass = new recess\lang\ReflectionMethod('AnnotationTest','testExpandAnnotationWithValidationError');
		$classDescriptor = new recess\lang\ClassDescriptor();
		try {
			$annotations[0]->expandAnnotation('AnnotationTest',$reflectionClass,$classDescriptor);
			$this->fail('Should throw error when validate does not pass.');
		} catch(\Exception $e) {
			$this->assertTrue(true);
		}
	}
	
	function testExpandAnnotation() {
		$docstring = '/** !Validate KeyA: foo */';
		$annotations = Annotation::parse($docstring);
		$this->assertEquals(1,count($annotations));
		$reflectionClass = new recess\lang\ReflectionMethod('AnnotationTest','testExpandAnnotationWithValidationError');
		$classDescriptor = new recess\lang\ClassDescriptor();
		$annotations[0]->expandAnnotation('AnnotationTest',$reflectionClass,$classDescriptor);
	}
}