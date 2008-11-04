<?php
/**
 * Data structure for an attached method. Holds a reference
 * to an instance of an object and the mapped function on
 * the object.
 * 
 * @author Kris Jordan
 */
class RecessAttachedMethod {
	public $object;
	public $method;
	
	function __construct($object, $method) { 
		$this->object = $object;
		$this->method = $method;
	}
}
?>