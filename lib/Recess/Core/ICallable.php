<?php
namespace Recess\Core; /** @addtogroup Core *//** @{ */

/**
 * The interface for lambda/closure/callable objects in Recess.
 * 
 * @author Kris Jordan <http://www.krisjordan.com>
 * @author Copyright &copy; RecessFramework.org 2008-2010 (MIT License)
 * @since Recess PHP Framework 5.3
 */
interface ICallable {
/** @} */
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
	function __invoke();
	
	/**
	 * An alias of __invoke() called with an array of arguments.
	 * 
	 * @code $add->apply(array(1,2)); @endcode
	 * 
	 * @param array $arguments
	 * @return mixed
	 */
	function apply($arguments = array());
		
	/**
	 * An alias of __invoke(). 
	 * 
	 * @code $add->call(1,2); @endcode
	 * 
	 * @param ... Arguments expected by the is_callable the Callable was constructed with.
	 * @return mixed
	 */
	function call();

}