<?php
/**
 * @author Kris Jordan <krisjordan@gmail.com>
 * @copyright 2008 Kris Jordan
 * @package Recess! Framework
 * @license MIT
 * @link http://www.recessframework.org/
 */
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