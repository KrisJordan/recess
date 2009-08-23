<?php
/**
 * @author Kris Jordan <krisjordan@gmail.com>
 * @copyright 2008, 2009 Kris Jordan
 * @package Recess PHP Framework
 * @license MIT
 * @link http://www.recessframework.org/
 */
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