<?php
namespace recess\lang;

class ErrorException extends \ErrorException {
	public $context = array();
	
	public function __construct($errorString, $code, $severity, $filename, $linenumber, $context) {
		parent::__construct($errorString, $code, $severity, $filename, $linenumber);
		$this->context = $context;
	}
}