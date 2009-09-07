<?php
namespace recess\lang;

/**
 * Extends PHP's built-in ReflectionMethod to introduce two new
 * methods: getAnnotations and isAttached.
 * 
 * @author Kris Jordan <krisjordan@gmail.com>
 * @copyright 2008, 2009 Kris Jordan
 * @package Recess PHP Framework
 * @license MIT
 * @link http://www.recessframework.org/
 */
class ReflectionMethod extends \ReflectionMethod {
	
	/**
	 * Returns an array of annotations, throws an ErrorException if annotation
	 * cannot be parsed or has not been loaded.
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
				throw new ErrorException('In class "' . $this->getDeclaringClass()->name . '" on method "'. $this->name .'".' . $e->getMessage(),0,0,$this->getFileName(),$this->getStartLine(),array());
			}
		}
		return $returns;
	}
	
	/**
	 * Is this method a dynamically attached method?
	 * 
	 * @return bool
	 */
	function isAttached() {
		return false;
	}
}