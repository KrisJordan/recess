<?php
Library::import('recess.lang.codegen.CodeGenClassMember');
/**
 * @author Kris Jordan <krisjordan@gmail.com>
 * @copyright 2008, 2009 Kris Jordan
 * @package Recess PHP Framework
 * @license MIT
 * @link http://www.recessframework.org/
 */
class CodeGenMethod extends CodeGenClassMember {
	
	function toCode($blockIndent = '') {
		$code = $this->doccomment->toCode();
		
		$method;
		if($this->accessLevel != CodeGen::PUB) {
			$method = $this->accessLevel;
		}
		
		if($method != '')	$method .= ' ';
		
		if($this->isStatic) {
			$method .= CodeGen::STAT;
		}
		
		if($method != '')	$method .= ' ';
		
		$method .= 'function ' . $this->name . ' {};';
		
		$code .= $method;
		
		return $code;	
	}
	
}
?>