<?php
namespace recess\lang;

use recess\lang\Object;
use recess\lang\ReflectionMethod;
use recess\lang\ReflectionProperty;

/**
 * Recess PHP Framework reflection for class which introduces annotations.
 * Annotations follow the following syntax:
 * 
 * !AnnotationName value, key: value, value, (sub array value, key: value, (sub sub array value))
 * 
 * When parsed, AnnotationName is concatenated with 'Annotation' to derive a classname,
 * ex: !HasMany => HasManyAnnotation
 * 
 * This class is instantiated if it exists (else throws UnknownAnnotationException) and its init 
 * method is passed the value array following the annotation's name.
 * 
 * 
 * @author Kris Jordan <krisjordan@gmail.com>
 * @copyright 2008, 2009 Kris Jordan
 * @package Recess PHP Framework
 * @license MIT
 * @link http://www.recessframework.org/
 */
class ReflectionClass extends \ReflectionClass {
	
	/**
	 * Returns an array of ReflectionProperties. Optional parameter
	 * $getAttachedParams (defaults to true) specifies whether or not
	 * to include dynamically attached methods in the return value.
	 * 
	 * @param $filter
	 * @return array of ReflectionProperty's
	 */
	function getProperties($filter = null) {
		$rawProperties = parent::getProperties();
		$properties = array();
		foreach($rawProperties as $rawProperty) {
			$properties[] = new ReflectionProperty($this->name, $rawProperty->name);
		}
		return $properties;
	}
	
	/**
	 * Returns an array of ReflectionMethods. Optional parameter
	 * $getAttachedParams (defaults to true) specifies whether or not
	 * to include dynamically attached methods in the return value.
	 * 
	 * @param bool $getAttachedMethods Return dynamically attached methods?
	 * @return array of ReflectionMethod's
	 */
	function getMethods($getAttachedMethods = true){
		$rawMethods = parent::getMethods();
		$methods = array();
		foreach($rawMethods as $rawMethod) {
			$method = new ReflectionMethod($this->name, $rawMethod->name);
			$methods[] = $method;
		}
		
		if($getAttachedMethods && is_subclass_of($this->name, 'recess\lang\Object')) {
			$methods = array_merge($methods, Object::getAttachedMethods($this->name));
		}
		
		return $methods;
	}
	
	/**
	 * Returns an array of parsed annotations. Will throw an ErrorException
	 * if annotations cannot be parsed or have not been loaded.
	 * 
	 * @return array of Annotations
	 */
	function getAnnotations() {
		$docstring = $this->getDocComment();
		if($docstring == '') return array();
		else {
			$returns = array();
			try {
				$returns = Annotation::parse($docstring);
			} catch(\Exception $e) {			
				throw new \ErrorException('In class "' . $this->name . '".' . $e->getMessage(),0,0,$this->getFileName(),$this->getStartLine());
			}
		}
		return $returns;
	}	
}