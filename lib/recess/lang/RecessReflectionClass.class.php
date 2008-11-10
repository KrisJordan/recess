<?php
Library::import('recess.lang.Annotation');
Library::import('recess.lang.RecessClass');
Library::import('recess.lang.RecessReflectionMethod');
Library::import('recess.lang.RecessReflectionProperty');

/**
 * Recess! Framework reflection for class which introduces annotations.
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
 * @todo Remove colon after annotation name.
 * @todo Cache annotations on a per-class basis.
 * 
 * @author Kris Jordan
 */
class RecessReflectionClass extends ReflectionClass {
	function getProperties() {
		$rawProperties = parent::getProperties();
		$properties = array();
		foreach($rawProperties as $rawProperty) {
			$properties[] = new RecessReflectionProperty($this->name, $rawProperty->name);
		}
		return $properties;
	}
	function getMethods($getAttachedMethods = true){
		$rawMethods = parent::getMethods();
		$methods = array();
		foreach($rawMethods as $rawMethod) {
			$method = new RecessReflectionMethod($this->name, $rawMethod->name);
			$methods[] = $method;
		}
		if($getAttachedMethods && $this->isSubclassOf('RecessClass')) {
			// $attachedMethods = RecessClass::getAttachedMethods($this->name);
			$methods = array_merge($methods, RecessClass::getAttachedMethods($this->name));
		}
		return $methods;
	}
	function getAnnotations() {
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