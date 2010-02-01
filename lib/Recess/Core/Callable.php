<?php
namespace Recess\Core;

/**
 * Turns any PHP value which is_callable into a directly callable object. Note:
 * does not work with functions requiring arguments passed by reference.
 * 
 * =========
 *   Usage
 * =========
 * function foo() { echo "bar\n"; }
 * $callable = new Callable('foo');
 * $callable(); // bar
 * $callable->call(); // bar
 * $callable->apply(array()); // bar
 * 
 * function fooz($bar) { echo "$bar\n"; }
 * $callable = new Callable('fooz');
 * $callable('baz'); // baz
 * $callable->call('baz'); // baz
 * $callable->apply(array('baz')); // baz
 * 
 * @author Kris Jordan <krisjordan@gmail.com>
 * @since Recess 5.3
 * @copyright RecessFramework.org 2009, 2010
 * @license MIT
 */
class Callable {
	
	protected $callable;

	/**
	 * The $callable argument must return true to PHP's is_callable. Styles accepted:
	 * 	'string' => Stand-alone function.
	 * 	array($object,'method') => Instance method.
	 *  array('Class','method') => Static method.
	 *  function(){} => Closure / lambda.
	 *  $object => An object that implements an __invoke function.
	 *  
	 * @param is_callable $callable
	 */
	function __construct($callable) {	
		if(!is_callable($callable)) {
			throw new \Exception("Callable's constructor requires an is_callable.");
		}	
		$this->callable = $callable;
	}
	
	/**
	 * Magic method which invokes the callable using all arguments passed in.
	 * 
	 * @param variable
	 * @return any
	 */
	function __invoke() {
		$callable = $this->callable;
		return call_user_func_array($callable,func_get_args());
	}
	
	/**
	 * Helper method alias for __invoke() that can be chained.
	 * 
	 * @see Callable::__invoke
	 * @return any
	 */
	function call() {
		return call_user_func_array(array($this,'__invoke'), func_get_args());
	}
	
	/**
	 * Call with an array of arguments rather than an argument list.
	 * 
	 * @param array $arguments
	 * @return any
	 */
	function apply($arguments = array()) {
		return call_user_func_array(array($this,'__invoke'), $arguments);
	}
	
}