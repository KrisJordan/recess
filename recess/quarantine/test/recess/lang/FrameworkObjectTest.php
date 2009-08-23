<?php
require_once 'PHPUnit/Framework.php';

Library::import('recess.lang.Object');

class MyObject extends Object {
	
	/** !Wrappable argumentFree */
	public function wrappedArgumentFree() {
		return 'argumentFree';
	}
	
	/** !Wrappable singleArgument */
	public function wrappedSingleArgument($argument) {
		return "Arg:$argument";
	}
	
	/** !Wrappable testWrapper */
	public function wrappedTestWrapper() {
		return 0;
	}
	
	/** !Wrappable testWrappers */
	public function wrappedTestWrappers() {
		return 0;
	}
	
}

class Add1ToAnnotation extends Annotation implements IWrapper {
	protected $method; 
	
	function init($array) { $this->method = $array[0]; }
	
	function shapeDescriptor($class, $reflection, $descriptor) {
		$descriptor->addWrapper($this->method, $this);
	}
	
	function combine(IWrapper $requiredWrapper) {}
	
	function before($object, &$args) {}
	
	function after($object, $result) { return $result + 1; }
}

class ConcatAnnotation extends Annotation implements IWrapper {
	protected $prepend;
	protected $append; 
	protected $method; 
	
	function init($array) {
		$this->prepend = $array[0];
		$this->append = $array[1];
		$this->method = $array[2];
	}
	
	function shapeDescriptor($class, $reflection, $descriptor) {
		$descriptor->addWrapper($this->method, $this);
	}
	
	function combine(IWrapper $requiredWrapper) {}
	
	function before($object, &$args) { $args[1] = $this->prepend . $args[1]; }
	
	function after($object, $result) { return $result . $this->append; }
}

/** 
 *  !Add1To testWrapper 
 * 	!Add1To testWrappers
 * 	!Add1To testWrappers
 *  !Concat "Hello ", "!!!", stringConcat
 *  !Concat "{", "}", twoConcats
 *  !Concat "[", "]", twoConcats
 *  !Concat "(", ")", twoConcats
 **/
class MyExtendedObject extends MyObject {
	
	/** !Wrappable singleArgument */
	public function wrappedSingleArgument($argument) {
		return parent::wrappedSingleArgument($argument);
	}
	
	/** !Wrappable stringConcat */
	public function wrappedStringConcat($string) {
		return $string . ' World';
	}
	
	/** !Wrappable twoConcats */
	public function theConventionDoesntMatter($string) {
		return $string . ' World';
	}
	
	
}

class ObjectTest extends PHPUnit_Framework_TestCase {

	function testSimpleWrappedMethod() {
		$obj = new MyObject();
		$this->assertEquals('argumentFree', $obj->argumentFree());
		$this->assertEquals('argumentFree', $obj->wrappedArgumentFree());
	}
	
	function testSingleArgumentWrappedMethod() {
		$obj = new MyObject();
		$this->assertEquals('Arg:Hello World', $obj->singleArgument('Hello World'));
	}
	
	function testShadowedSingleArgumentWrappedMethod() {
		$obj = new MyExtendedObject();
		$this->assertEquals('Arg:Hello World2', $obj->singleArgument('Hello World2'));
	}
	
	function testParentWrapped() {
		$obj = new MyExtendedObject();
		$this->assertEquals('argumentFree', $obj->argumentFree());
	}
	
	function testWrapperAfter() {
		$obj = new MyExtendedObject();
		$this->assertEquals(1, $obj->testWrapper());
	}
	
	function testWrappersAfter() {
		$obj = new MyExtendedObject();
		$this->assertEquals(2, $obj->testWrappers());
	}
	
	function testStringConcat() {
		$obj = new MyExtendedObject();
		$this->assertEquals('Hello Wrappable World!!!', $obj->stringConcat('Wrappable'));
	}
	
	function testMultipleStringConcats() {
		$obj = new MyExtendedObject();
		$this->assertEquals('([{Wrappable World}])', $obj->twoConcats('Wrappable'));
	}

}
?>