<?php
Library::import('recess.lang.Annotation');

/**
 * @author Kris Jordan <krisjordan@gmail.com>
 * @copyright 2008, 2009 Kris Jordan
 * @package Recess PHP Framework
 * @license MIT
 * @link http://www.recessframework.org/
 * @todo Add custom getFileName() and getStartLine() methods
 */
class RecessReflectionProperty extends ReflectionProperty {
	function getAnnotations() {
		$docstring = $this->getDocComment();
		if($docstring == '') return array();
		else {
			$returns = array();
			try {
				$returns = Annotation::parse($docstring);
			} catch(InvalidAnnotationValueException $e) {			
				throw new InvalidAnnotationValueException('In class "' . $this->getDeclaringClass()->name . '" on property "'. $this->name .'".' . $e->getMessage(),0,0,$this->getDeclaringClass()->getFileName(),$this->getDeclaringClass()->getStartLine(),array());
			} catch(UnknownAnnotationException $e) {
				throw new UnknownAnnotationException('In class "' . $this->getDeclaringClass()->name . '" on property "'. $this->name .'".' . $e->getMessage(),0,0,$this->getDeclaringClass()->getFileName(),$this->getDeclaringClass()->getStartLine(),array());
			}
		}
		return $returns;
	}
}

?>