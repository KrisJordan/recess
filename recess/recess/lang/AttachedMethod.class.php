<?php
namespace recess\lang;

/**
 * Data structure for an attached method. Holds a reference
 * to an instance of an object and the mapped function on
 * the object.
 * 
 * @author Kris Jordan <krisjordan@gmail.com>
 */
class AttachedMethod {
	
	public $alias;
	public $callable;
	
	function __construct($alias, $callable) {
		assert(is_callable($callable));
		$this->callable = $callable;
		$this->alias = $alias;
	}
	
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
    function getParameters() { 
    	$params = $this->getReflectionObject()->getParameters(); 
    	array_shift($params); 
    	return $params;
    }
    function getNumberOfParameters() { return $this->getReflectionObject()->getNumberOfParameters() - 1; }
    function getNumberOfRequiredParameters() { return $this->getReflectionObject()->getNumberOfRequiredParameters() - 1; }
}

?>