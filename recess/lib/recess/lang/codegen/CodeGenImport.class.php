<?php
class CodeGenImport {
	protected $import;
	
	function __construct($import) {
		$this->import = $import;
	}
	
	function setImport($import) {
		$this->import = $import;
	}
	
	function toCode($blockIndent = '') {
		return $blockIndent . 'Library::import(\'' . $this->import . '\');';
	}
}
?>