<?php
Library::import('recess.lang.Annotation');
Library::import('recess.lang.WrappedMethod');

/**
 * The WrappableAnnotation can be applied to methods in classes deriving
 * from Object. The wrappable annotation expands to create a WrappedMethod
 * called by the first (and only) parameter passed to the Wrappable annotation.
 * 
 * class Foo extends Object {
 * 	/** !Wrappable test * /
 * 	function wrappedTest($arg) { echo $arg; return 'fooz'; }
 * 
 *  /** !Before test * /
 *  function echoArgs(&$args) { echo 'Before test("' . $args[0] . '")'; }
 *  
 *  /** !After test * /
 *  function echoArgs($retVal) { echo 'After test() returns: ' . $retVal; return 'baz'; }
 * }
 * $foo = new Foo();
 * $result = $foo->test('bar');
 * // > Before test("bar")
 * // > bar
 * // > After test() returns: fooz
 * echo $result
 * // > baz
 * 
 * Key methods in the framework are made Wrappable so that functionality can
 * easily be plugged into Recess.
 * 
 * 
 * @author Kris Jordan <krisjordan@gmail.com>
 * @copyright 2008, 2009 Kris Jordan
 * @package Recess PHP Framework
 * @license MIT
 * @link http://www.recessframework.org/
 */
class WrappableAnnotation extends Annotation {
	
	public function usage() {
		return '!Wrappable wrappedMethodName';
	}
	
	protected function validate($class) {
		$this->exactParameterCount(1);
	}
	
	public function isFor() {
		return Annotation::FOR_METHOD;
	}
	
	protected function expand($class, $reflection, $descriptor) {
		$methodName = $this->values[0];
		
		$wrappedMethod = new WrappedMethod($reflection->name);
		
		$descriptor->attachMethod($class, $methodName, $wrappedMethod, WrappedMethod::CALL);
				
		$descriptor->addWrappedMethod($methodName, $wrappedMethod);
	}
	
}
?>