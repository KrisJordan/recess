<?php
/**
 * @author Kris Jordan <krisjordan@gmail.com>
 * @copyright 2008, 2009 Kris Jordan
 * @package Recess PHP Framework
 * @license MIT
 * @link http://www.recessframework.org/
 */
class CodeGenDocComment {
	protected $lines = array();
	
	function __construct($firstLine = '') {
		if($firstLine != '') {
			$this->lines[] = $firstLine;
		}
	}
	
	function addLine($line) {
		$this->lines[] = $line;
	}
	
	function toCode($blockIndent = '', $singleLine = false) {
		$code = $blockIndent;
		if(!$singleLine || count($this->lines) > 1) {
			// Multi-line doccomment
			if(!empty($this->lines)) {
				$code .= '/**';
				
				$code .= CodeGen::NL;;
				
				foreach($this->lines as $line) {
					$code .= ' * ' . $line;
					$code .= CodeGen::NL;
				}
						
				$code .= ' */' . CodeGen::NL;
			}
		} else {
			// Single-line doccomment
			if(isset($this->lines[0])) {
				$code .= '/** ' . $this->lines[0] . ' */' . CodeGen::NL;
			}
		}
		return $code;	
	}
}

?>