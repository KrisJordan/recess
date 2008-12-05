<?php
Library::import('recess.lang.codegen.CodeGenClassMember');

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