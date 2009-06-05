<?php
Library::import('recess.lang.IWrapper');
Library::import('recess.lang.reflection.ReflectionMethod');

/**
 * WrappedMethod is used as an attached method provider on a Recess Object.
 * WrappedMethod provides an additional level of indirection prior to
 * invoking a method on an Object that allows classes implementing
 * IWrapper to register callbacks before() and after(). before() callbacks
 * are able to modify the arguments being passed to a method, and
 * can short-circuit a return value. after() callbacks can modify the
 * return value. Shares similarities to the aspect oriented notion of join
 * points.
 * 
 * @author Kris Jordan <krisjordan@gmail.com>
 * @copyright 2008, 2009 Kris Jordan
 * @package Recess PHP Framework
 * @license MIT
 * @link http://www.recessframework.org/
 */
class WrappedMethod {
	
	const CALL = 'call';
	
	/**
	 * Registered wrappers whose callbacks are invoked during call()
	 * @var array(IWrapper)
	 */
	protected $wrappers = array();
	
	/**
	 * Name of the method to invoke on the object passed to call()
	 * @var string
	 */
	public $method;
	
	/**
	 * The constructor takes an optional method name.
	 * 
	 * @param String $method name of the method to invoke
	 */
	function __construct($method = '') {
		$this->method = $method;
	}
	
	/**
	 * Wrap the method with another IWrapper
	 * 
	 * @param IWrapper $wrapper
	 */
	function addWrapper(IWrapper $wrapper) {
		foreach($this->wrappers as $existingWrapper) {
			if($existingWrapper->combine($wrapper)) {
				return;
			}
		}
		$this->wrappers[] = $wrapper;
	}
	
	/**
	 * Assume takes the wrappers from another WrappedMethod
	 * and makes them their own. Assume is necessary because
	 * the actual WrappedMethod in a ClassDescriptor may not exist
	 * prior to it needing to be wrapped (by a Class-level annotation)
	 * so we create a place-holder WrappedMethod until the actual
	 * wrapped method Assumes its rightful place.
	 * 
	 * @param WrappedMethod $wrappedMethod the place-holder WrappedMethod.
	 * @return WrappedMethod
	 */
	function assume(WrappedMethod $wrappedMethod) {
		$this->wrappers = $wrappedMethod->wrappers;
		return $this;
	}
	
	/**
	 * The wrappers and wrapped method are invoked in call().
	 * First the before() methods of wrappers are invoked in the order
	 * in which the Wrappers were applied. The before methods are passed
	 * the arguments which will eventually be passed to the actual wrapped
	 * method by reference for possible manipulation. If a before method
	 * returns a value this value is short-circuits the calling process
	 * and returns that value immediately. Next the wrapped method is
	 * invoked. The result is then passed to the after() methods of wrappers
	 * in the reverse order in which they were applied. The after methods
	 * can manipulate the returned value.
	 * 
	 * @return mixed
	 */
	function call() {
		$args = func_get_args();
		
		$object = array_shift($args);
		
		foreach($this->wrappers as $wrapper) {
			$returns = $wrapper->before($object, $args);
			if($returns !== null) { 
				// Short-circuit return
				return $returns;
			}
		}
		
		if(!isset($this->reflectedMethod)) {
			$this->reflectedMethod = new ReflectionMethod($object, $this->method);
		}
		
		$returns = $this->reflectedMethod->invokeArgs($object, $args);
		
		foreach(array_reverse($this->wrappers) as $wrapper) {
			$wrapperReturn = $wrapper->after($object, $returns);
			if($wrapperReturn !== null) {
				$returns = $wrapperReturn;
			}
		}
		
		return $returns;
	}
}
?>