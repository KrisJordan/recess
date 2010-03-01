<?php
namespace Recess\Core; /** @addtogroup Core *//** @{ */

/**
 * Turns an is_callable() PHP value into a directly invocable object/closure with call() and apply() methods. 
 * 
 * Note: Callable does not support passing arguments by-reference.
 * 
 * @include docs/examples/Recess/Core/Callable.php
 * 
 * To run the example code from the command line: 
 * @code php lib/Recess/docs/examples/Recess/Core/Callable.php @endcode
 * 
 * @author Kris Jordan <http://www.krisjordan.com>
 * @author Copyright &copy; RecessFramework.org 2008-2010 (MIT License)
 * @since Recess PHP Framework 5.3
 */
class Callable implements ICallable {
/** @} */
	
	/** The is_callable value being wrapped. */
	protected $callable;

	/**
	 * Constructor's argument requires an is_callable() value. 
	 * 
	 * Values accepted:
	 * 
	 * @code
	 * 'string'                ;// User or PHP function
	 * array($object,'method') ;// Instance method
	 * array('Class','method') ;// Static method
	 * 'Class::method'         ;// Static method
	 * function(){}            ;// Closure
	 * $object                 ;// Object with an __invoke method
	 * @endcode
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
	 * Magic method that invokes the is_callable() value the instance was constructed with.
	 * 
	 * @code
	 * $printf = new Callable('printf');
	 * $printf("Hello world"); // Magic for: $printf->__invoke("Hello world"); 
	 * @endcode
	 * 
	 * @param ... Arguments expected by the is_callable the Callable was constructed with.
	 * @return mixed
	 */
	function __invoke() {
		$callable = $this->callable;
		return call_user_func_array($callable,func_get_args());
	}

	/**
	 * An alias of __invoke() called with an array of arguments.
	 * 
	 * @code $add->apply(array(1,2)); @endcode
	 * 
	 * @param array $arguments
	 * @return mixed
	 */
	function apply($arguments = array()) {
		return call_user_func_array(array($this,'__invoke'), $arguments);
	}
	
	/**
	 * An alias of __invoke().
	 * 
	 * @code $add->call(1,2); @endcode
	 * 
	 * @param ... Arguments expected by the is_callable the Callable was constructed with.
	 * @return mixed
	 */
	function call() {
		return call_user_func_array(array($this,'__invoke'), func_get_args());
	}
	
}