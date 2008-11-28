<?php
/**
 * Recess! Framework base class for anonymous classes
 * with attachable methods.
 * 
 * @author Kris Jordan
 */
abstract class RecessClass {
	
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
		$attachedMethod = new RecessClassAttachedMethod($providerInstance, $providerMethodName, $attachedMethodAlias);
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
			$reflectedMethod = new ReflectionMethod($object, $method);
			return $reflectedMethod->invokeArgs($object, $arguments);
			// return call_user_func_array($method, $object, $arguments);
		} else {
			throw new RecessException('"' . get_class($this) . '" class does not contain a method or an attached method named "' . $name . '".', get_defined_vars());
		}
	}
	
	const RECESS_CLASS_KEY_PREFIX = 'RecessClass::desc::';
	/**
	 * Return the RecessClassInfo for provided RecessClass instance.
	 *
	 * @param variant $classNameOrInstance - String Class Name or Instance of Recess Class
	 * @return RecessClassDescriptor
	 */
	final static protected function getClassDescriptor($classNameOrInstance) {
		if($classNameOrInstance instanceof RecessClass) {
			$class = get_class($classNameOrInstance);
			$instance = $classNameOrInstance;
		} else {
			$class = $classNameOrInstance;
			$instance = new $class;
		}
		
		if(!isset(self::$descriptors[$class])) {		
			$cache_key = self::RECESS_CLASS_KEY_PREFIX . $class;
			$descriptor = Cache::get($cache_key);
			
			if($descriptor === false) {				
				if($instance instanceof RecessClass) {
					$descriptor = call_user_func(array($class, 'buildClassDescriptor'), $class);
					Cache::set($cache_key, $descriptor);
					self::$descriptors[$class] = $descriptor;
				} else {
					throw new RecessException('RecessClassRegistry only retains information on classes derived from RecessClass. Class of type "' . $class . '" given.', get_defined_vars());
				}
			} else {
				self::$descriptors[$class] = $descriptor;
			}
		}
		
		return self::$descriptors[$class];
	}
	
	/**
	 * Retrieve an array of the attached methods for a particular class.
	 *
	 * @param variant $classNameOrInstance - String class name or instance of a Recess Class
	 * @return array
	 */
	final static function getAttachedMethods($classNameOrInstance) {
		$descriptor = self::getClassDescriptor($classNameOrInstance);
		return $descriptor->getAttachedMethods();
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
	 * Return all attached methods.
	 *
	 * @return array(AttachedMethod)
	 */
	function getAttachedMethods() {
		return $this->attachedMethods;
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
	public $name;
	
	function __construct($object, $method, $name) { 
		$this->object = $object;
		$this->method = $method;
		$this->name = $name;
	}
	
	function isFinal() { return true; }
    function isAbstract() { return false; }
    function isPublic() { return true; }
    function isPrivate() { return false; }
    function isProtected() { return false; }
    function isStatic() { return false; }
    function isConstructor() { return false; }
    function isDestructor() { return false; }
    function isAttached() { return true; }

    function getName() { return $this->alias; }
    function isInternal() { return false; }
    function isUserDefined() { return true; }
    
    function getFileName() { $reflection = new ReflectionClass($this->object); return $reflection->getMethod($this->method)->getFileName(); }
    function getStartLine() { $reflection = new ReflectionClass($this->object); return $reflection->getMethod($this->method)->getStartLine(); }
    function getEndLine() { $reflection = new ReflectionClass($this->object); return $reflection->getMethod($this->method)->getEndLine(); }
    function getParameters() { 
    	$reflection = new ReflectionClass($this->object); 
    	$params = $reflection->getMethod($this->method)->getParameters(); 
    	array_shift($params); 
    	return $params;
    }
    function getNumberOfParameters() { $reflection = new ReflectionClass($this->object); return $reflection->getMethod($this->method)->getNumberOfParameters() - 1; }
    function getNumberOfRequiredParameters() { $reflection = new ReflectionClass($this->object); return $reflection->getMethod($this->method)->getNumberOfRequiredParameters() - 1; }
}

?>