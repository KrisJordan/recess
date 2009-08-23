<?php
Library::import('recess.lang.codegen.CodeGenClassMember');
/**
 * @author Kris Jordan <krisjordan@gmail.com>
 * @copyright 2008, 2009 Kris Jordan
 * @package Recess PHP Framework
 * @license MIT
 * @link http://www.recessframework.org/
 */
class CodeGenProperty extends CodeGenClassMember {
	
	function toCode($blockIndent = '') {
		$code = $this->doccomment->toCode($blockIndent, true);
		
		$property = '';
		
		$property = $this->accessLevel . ' ';
		
		if($this->isStatic) {
			$property .= CodeGen::STAT . ' ';
		}
		
		$property .= '$' . $this->name . ';' . CodeGen::NL;
		
		$code .= $blockIndent . $property;
		
		return $code;	
	}
	
}
?>