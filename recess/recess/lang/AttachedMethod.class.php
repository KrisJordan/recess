<?php

/**
 * Data structure for an attached method. Holds a reference
 * to an instance of an object and the mapped function on
 * the object.
 * 
 * @author Kris Jordan <krisjordan@gmail.com>
 */
class AttachedMethod {
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