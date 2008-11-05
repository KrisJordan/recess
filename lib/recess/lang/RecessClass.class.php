<?php
/**
 * Recess! Framework base class for anonymous classes
 * with attachable methods.
 * 
 * @author Kris Jordan
 */
abstract class RecessClass extends stdClass {
	
	protected static $descriptors = array();
	
	/**
	 * Attach a method to a RecessClass. The result of this static method is the ability to
	 * call, on any instance of $attachOnClassName, a method named $attachedMethodAlias
	 * which delegates that method call to $providerInstance's $providerMethodName.
	 *
	 * @param string $attachOnClassName
	 * @param string $attachedMethodAlias
	 * @param object $providerInstance
	 * @param string $providerMethodName
	 */
	static function attachMethod($attachOnClassName, $attachedMethodAlias, $providerInstance, $providerMethodName) {
		$attachedMethod = new RecessClassAttachedMethod($providerInstance, $providerMethodName);
		self::getClassDescriptor($attachOnClassName)->addAttachedMethod($attachedMethodAlias, $attachedMethod);
	}
	
	/**
	 * Dynamic dispatch of function calls to attached methods.
	 *
	 * @param string $name
	 * @param array $arguments
	 * @return variant
	 */
	final function __call($name, $arguments) {
		$classDescriptor = self::getClassDescriptor($this);
		
		$attachedMethod = $classDescriptor->getAttachedMethod($name);
		
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
	 * Return the RecessClassInfo for provided RecessClass instance.
	 *
	 * @param variant $classNameOrInstance - String Class Name or Instance of Recess Class
	 * @return RecessClassDescriptor
	 */
	final static protected function getClassDescriptor($classNameOrInstance) {
		if($classNameOrInstance instanceof RecessClass) {
			$class = get_class($classNameOrInstance);
		} else {
			$class = $classNameOrInstance;
		}
		
		if(isset(self::$descriptors[$class])) {
			return self::$descriptors[$class];
		} else {
			if(is_subclass_of($class, __CLASS__)) {
				return self::$descriptors[$class] = call_user_func(array($class, 'buildClassDescriptor'), $class);
			} else {
				throw new RecessException('RecessClassRegistry only retains information on classes derived from RecessClass. Class of type "' . $class . '" given.', get_defined_vars());
			}
		}
	}
	
	/**
	 * Returns instance of RecessClassDescriptor which describes
	 * this class. Should be overridden in subclasses.
	 * 
	 * @return RecessClassDescriptor
	 */
	static protected function buildClassDescriptor($class) {
		return new RecessClassDescriptor();
	}

	/**
	 * Clear the descriptors cache.
	 */
	final static function clearDescriptors() {
		self::$descriptors = array();
	}
}

/**
 * Recess! Framework class info object that stores additional
 * state about a RecessClass. This additional state includes
 * attached methods or named public properties.
 * 
 * @author Kris Jordan
 */
class RecessClassDescriptor extends stdClass {
	protected $attachedMethods = array();
	
	/**
	 * Return a RecessAttachedMethod for given name, or return false.
	 *
	 * @param string $methodName Method name.
	 * @return RecessAttachedMethod on success, false on failure.
	 */
	function getAttachedMethod($methodName) {
		if(isset($this->attachedMethods[$methodName]))
			return $this->attachedMethods[$methodName];
		else
			return false;
	}
	
	/**
	 * Add an attached method with given methodName alias.
	 *
	 * @param string $methodName
	 * @param RecessClassAttachedMethod $attachedMethod
	 */
	function addAttachedMethod($methodName, RecessClassAttachedMethod $attachedMethod) {
		$this->attachedMethods[$methodName] = $attachedMethod;
	}
}

/**
 * Data structure for an attached method. Holds a reference
 * to an instance of an object and the mapped function on
 * the object.
 * 
 * @author Kris Jordan
 */
class RecessClassAttachedMethod {
	public $object;
	public $method;
	
	function __construct($object, $method) { 
		$this->object = $object;
		$this->method = $method;
	}
}

?>