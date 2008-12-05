<?php
class CodeGenClassFile {
	protected $imports = array();
	protected $classes = array();
		
	function addClass(CodeGenClass $class) {
		$this->classes[] = $class;
	}
	
	function addImport(CodeGenImport $import) {
		$this->imports[] = $import;
	}
	
	function toCode($blockIndent = '') {
		$code = '<?php' . CodeGen::NL;
		
		if(!empty($this->imports)) {
			foreach($this->imports as $import) {
				$code .= $import->toCode() . CodeGen::NL;
			}
			$code .= CodeGen::NL;
		}
		
		foreach($this->classes as $class) {
			$code .= $class->toCode();
		}
		$code .= '?>';
		return $code;		
	}
}
?>