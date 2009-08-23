<?php
namespace recess\lang;

class Exception extends \Exception {
	public $context = array();
	
	public function __construct($message, $context) {
		parent::__construct($message);
		$this->context = $context;
	}
	
	public function getRecessTrace() {
		return $this->getTrace();
	}
}