<?php
Library::import('recess.lang.IWrapper');

/**
 * A Wrapper that will call methods on a class before or after calling the
 * wrapped method. Used by Before and After annotations.
 * 
 * @author Kris Jordan
 * @since 0.20
 */
class MethodCallWrapper implements IWrapper {
	protected $callBefore = array();
	protected $callAfter = array();
	
	/**
	 * Add methods to be called before the wrapped method.
	 * Methods must have same argument signature as the wrapped method.
	 * If a non-null value is returned from any method called
	 * before the wrapped method its return value will short circuit
	 * the call and return immediately without calling the method.
	 */
	public function addCallBefore() {
		$args = func_get_args();
		foreach($args as $method) {
			if(is_string($method)){
				$this->callBefore[] = $method;
			}
		}
	}
	
	/**
	 * Add methods to be called after the wrapped method has returned.
	 * Methods called after must take a single argument that is the
	 * return value of the called method. Methods called after are expected
	 * to return the value returned by the wrapped method unless it 
	 * needs to be massaged in some way.
	 */
	public function addCallAfter() {
		$args = func_get_args();
		foreach($args as $method) {
			if(is_string($method)){
				$this->callAfter[] = $method;
			}
		}
	}
	
	/**
	 * Call all other methods on this class registered to be called before
	 * the wrapped method.
	 * 
	 * @param $object
	 * @param $args
	 * @return mixed If a value is returned during a call to before that value short-circuits the method call.
	 */
	function before($object, &$args) {
		$reflectedClass = new RecessReflectionClass($object);
		foreach($this->callBefore as $method) {
			$reflectedMethod = $reflectedClass->getMethod($method);
			$result = $reflectedMethod->invokeArgs($object, $args);
			if($result !== null) {
				return $result;
			}
		}
		// Return null so that method call proceeds as expected
		return null;
	}
	
	/**
	 * Call all other methods on this class registered to be called after
	 * the wrapped method has returned.
	 * 
	 * @param $object
	 * @param $returnValue
	 * @return mixed Should be of the same type as the wrapped method returns.
	 */
	function after($object, $returnValue) {
		$reflectedClass = new RecessReflectionClass($object);
		foreach($this->callAfter as $method) {
			$reflectedMethod = $reflectedClass->getMethod($method);
			$result = $reflectedMethod->invoke($object, $returnValue);
			if($result !== null) {
				$returnValue = $result;
			}
		}
		return $returnValue;
	}
	
	/**
	 * Attempt to combine a wrapper with another. Example usage:
	 * Required wrappers declared on multiple fields of a model 
	 * can be combined into a single wrapper.
	 * 
	 * @param IWrapper $wrapper
	 * @return true on success, false on failure
	 */
	function combine(IWrapper $that) {
		if($that instanceof MethodCallWrapper) {
			$this->callBefore = array_merge($this->callBefore, $that->callBefore);
			$this->callAfter = array_merge($this->callAfter, $that->callAfter);
			return true;
		} else {
			return false;
		}
	}
}
?>