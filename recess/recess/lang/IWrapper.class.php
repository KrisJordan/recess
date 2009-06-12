<?php
/**
 * Implement this interface for classes that need to wrap functionality around a Wrappable method.
 * 
 * @author Kris Jordan <krisjordan@gmail.com>
 * @copyright 2008, 2009 Kris Jordan
 * @package Recess PHP Framework
 * @license MIT
 * @link http://www.recessframework.org/
 */
interface IWrapper {
	
	/**
	 * Called before the wrapped method is called. Before is given a chance to
	 * massage the arguments being passed to the wrapped method, throw an exception,
	 * or short-circuit a return value without the wrapped method being called.
	 * 
	 * @param $object
	 * @param $args
	 * @return mixed If a value is returned during a call to before that value short-circuits the method call.
	 */
	function before($object, &$args);
	
	/**
	 * Called after the wrapped method is called. After is given a chance to
	 * post-process the return value of the wrapped method. If a return value from
	 * after is non-null then after's return value will override the return value
	 * of the wrapped method.
	 * 
	 * @param $object
	 * @param $returnValue
	 * @return mixed Should be of the same type as the wrapped method returns.
	 */
	function after($object, $returnValue);
	
	/**
	 * Attempt to combine a wrapper with another. Example usage:
	 * Required wrappers declared on multiple fields of a model 
	 * can be combined into a single wrapper.
	 * 
	 * @param IWrapper $wrapper
	 * @return true on success, false on failure
	 */
	function combine(IWrapper $wrapper);
	
}
?>