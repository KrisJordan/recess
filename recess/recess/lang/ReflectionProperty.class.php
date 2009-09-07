<?php
namespace recess\lang;

/**
 * Extends PHP's built-in ReflectionProperty to introduce a new
 * method for extracting the annotations from a property.
 * 
 * @author Kris Jordan <krisjordan@gmail.com>
 * @copyright 2008, 2009 Kris Jordan
 * @package Recess PHP Framework
 * @license MIT
 * @link http://www.recessframework.org/
 */
class ReflectionProperty extends \ReflectionProperty {
	
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
				throw new ErrorException('In class "' . $this->getDeclaringClass()->name . '" on property "'. $this->name .'".' . $e->getMessage(),0,0,$this->getFileName(),$this->getStartLine(),array());
			}
		}
		return $returns;
	}
}