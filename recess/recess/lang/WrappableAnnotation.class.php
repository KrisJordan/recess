<?php
namespace recess\lang;
use recess\lang\Candy;

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
 * @author Kris Jordan <krisjordan@gmail.com>
 * @copyright 2008, 2009 Kris Jordan
 * @package Recess PHP Framework
 * @license MIT
 * @link http://www.recessframework.org/
 */
class WrappableAnnotation extends Annotation {
	
	public $alias;
	public $wrapped;
	
	public function usage() {
		return '!Wrappable wrappedMethodName';
	}
	
	public function validate($class) {
		$this->exactParameterCount(1);
	}
	
	public function isFor() {
		return Annotation::FOR_METHOD;
	}
	
	protected function expand($class, $reflection, $descriptor) {
		$this->alias = $this->values[0];
		$this->wrapped = $reflection->name;
		$descriptor->attach(/* Method Name */ $this->alias, 
							/* Callable */    new Candy($this));
	}
	
	public function __invoke() {
		$args = func_get_args();
		$object = array_shift($args);
		return call_user_func_array(array($object,$this->wrapped),$args);
	}
}