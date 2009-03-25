<?php
Library::import('recess.lang.Object');

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
 * @todo Harden the regular expressions.
 * 
 * @author Kris Jordan <krisjordan@gmail.com>
 * @copyright 2008, 2009 Kris Jordan
 * @package Recess PHP Framework
 * @license MIT
 * @link http://www.recessframework.org/
 */
class RecessReflectionClass extends ReflectionClass {
	function getProperties($filter = null) {
		Library::import('recess.lang.reflection.RecessReflectionProperty');
		$rawProperties = parent::getProperties();
		$properties = array();
		foreach($rawProperties as $rawProperty) {
			$properties[] = new RecessReflectionProperty($this->name, $rawProperty->name);
		}
		return $properties;
	}
	function getMethods($getAttachedMethods = true){
		Library::import('recess.lang.reflection.RecessReflectionMethod');
		$rawMethods = parent::getMethods();
		$methods = array();
		foreach($rawMethods as $rawMethod) {
			$method = new RecessReflectionMethod($this->name, $rawMethod->name);
			$methods[] = $method;
		}
		
		if($getAttachedMethods && is_subclass_of($this->name, 'Object')) {
			$methods = array_merge($methods, Object::getAttachedMethods($this->name));
		}
		
		return $methods;
	}
	function getAnnotations() {
		Library::import('recess.lang.Annotation');
		$docstring = $this->getDocComment();
		if($docstring == '') return array();
		else {
			$returns = array();
			try {
				$returns = Annotation::parse($docstring);
			} catch(InvalidAnnotationValueException $e) {			
				throw new InvalidAnnotationValueException('In class "' . $this->name . '".' . $e->getMessage(),0,0,$this->getFileName(),$this->getStartLine(),array());
			} catch(UnknownAnnotationException $e) {
				throw new UnknownAnnotationException('In class "' . $this->name . '".' . $e->getMessage(),0,0,$this->getFileName(),$this->getStartLine(),array());
			}
		}
		return $returns;
	}	
}

?>