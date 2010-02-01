<?php
namespace recess\lang;

/**
 * The data structure behind dynamically attached methods in Recess.
 * Since Recess 5.3 any callable can be attached at runtime. This class
 * implements the same interface as a recess\lang\ReflectionMethod.
 * 
 * @author Kris Jordan <krisjordan@gmail.com>
 * @copyright 2008, 2009 Kris Jordan
 * @package Recess PHP Framework
 * @license MIT
 * @link http://www.recessframework.org/
 */
class AttachedMethod {
	/**
	 * The name that this method is attached as.
	 * @var string
	 */
	public $alias;
	
	/**
	 * The callable that implements the functionality.
	 * @var callable
	 */
	public $callable;
	
	/**
	 * Construct an AttachedMethod. Must be stored on a ClassDescriptor
	 * to have meaning/binding to a class.
	 * 
	 * @param string $alias
	 * @param callable $callable
	 * @return AttachedMethod
	 */
	function __construct($alias, $callable) {
		assert(is_callable($callable));
		$this->callable = $callable;
		$this->alias = $alias;
	}
	
	/**
	 * Returns a Reflection object for the attached callable.
	 * 
	 * @return ReflectionMethod|ReflectionFunction
	 */
	private function getReflectionObject() {
		if(is_string($this->callable)) {
			return new \ReflectionFunction($this->callable);
		} else if(is_array($this->callable)) {
			return new \ReflectionMethod($this->callable[0], $this->callable[1]);
		} else {
			if($this->callable instanceof \Closure) {
				return new \ReflectionFunction($this->callable);
			} else {
				return new \ReflectionMethod($this->callable, '__invoke');
			}
		}
	}
	
	/* Implementation of ReflectionMethod Members */
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
    
    function getFileName() { return $this->getReflectionObject()->getFileName(); }
    function getStartLine() { return $this->getReflectionObject()->getStartLine(); }
    function getEndLine() { return $this->getReflectionObject()->getEndLine(); }
    
    /* Shifts the first parameter off because it is $self */
    function getParameters() { 
    	$params = $this->getReflectionObject()->getParameters(); 
    	array_shift($params); 
    	return $params;
    }
    
    /* Returns one less than actually required of the implementor, because first is always $self */
    function getNumberOfParameters() { return $this->getReflectionObject()->getNumberOfParameters() - 1; }
    
    /* Returns one less than actually required of the implementor, because first is always $self */
   	function getNumberOfRequiredParameters() { return $this->getReflectionObject()->getNumberOfRequiredParameters() - 1; }
}