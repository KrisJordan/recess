<?php
namespace recess\lang;

/**
 * @author Kris Jordan <krisjordan@gmail.com>
 * @copyright 2008, 2009 Kris Jordan
 * @package Recess PHP Framework
 * @license MIT
 * @link http://www.recessframework.org/
 */
class ReflectionMethod extends \ReflectionMethod {
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
	
	function isAttached() {
		return false;
	}
}
?>