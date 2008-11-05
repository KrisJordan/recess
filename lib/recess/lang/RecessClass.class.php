<?php
Library::import('recess.lang.RecessClassInfo');

/**
 * Recess! Framework base class for anonymous classes
 * with attachable methods.
 * 
 * @author Kris Jordan
 */
abstract class RecessClass extends stdClass {
	/**
	 * Dynamic dispatch of function calls to attached methods.
	 *
	 * @param string $name
	 * @param array $arguments
	 * @return variant
	 */
	function __call($name, $arguments) {
		$thisClassInfo = RecessClassRegistry::infoForObject($this);
		
		$attachedMethod = $thisClassInfo->getAttachedMethod($name);
		
		if($attachedMethod !== false) {
			$object = $attachedMethod->object;
			$method = $attachedMethod->method;
			array_unshift($arguments, $this);
			return call_user_method_array($method, $object, $arguments);
		} else {
			throw new RecessException(get_class($this) . ' does not contain a method or an attached method named "' . $name . '".', get_defined_vars());
		}
	}
	
	/**
	 * Returns instance of RecessClassInfo which describes
	 * this class.
	 * 
	 * @param string $class Classname
	 * @return RecessClassInfo
	 */
	static function getRecessClassInfo($class) {
		return new RecessClassInfo();
	}
}

?>