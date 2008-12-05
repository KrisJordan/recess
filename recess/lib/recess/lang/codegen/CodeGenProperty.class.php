<?php
Library::import('recess.lang.codegen.CodeGenClassMember');

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