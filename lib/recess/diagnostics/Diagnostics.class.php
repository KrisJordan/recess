<?php

Library::import('recess.http.ResponseCodes');

class Diagnostics {
	
	public static function handleException(Exception $exception) {
		if($exception instanceof LibraryException) {
			// Special Case for LibraryException to shift front value from stack
			$trace = $exception->getTrace();
			array_shift($trace);			
			$exception = new RecessTraceException($exception->getMessage(), $trace);
		}
		
		header('HTTP/1.1 ' . ResponseCodes::getMessageForCode(ResponseCodes::HTTP_INTERNAL_SERVER_ERROR));
		include('output/exception_report.php');
	}
	
	public static function handleError($errorNumber, $errorString, $errorFile, $errorLine, $errorContext) {
		if(ini_get('error_reporting') == 0) return true;
	
		throw new RecessErrorException($errorString, 0, $errorNumber, $errorFile, $errorLine, $errorContext);
	}
}

class RecessException extends Exception {
	public $context = array();
	
	public function __construct($message, $context) {
		parent::__construct($message);
		$this->context = $context;
	}
	
	public function getRecessTrace() {
		return $this->getTrace();
	}
}

class RecessErrorException extends ErrorException {
	public $context = array();
	
	public function __construct($errorString, $code, $severity, $filename, $linenumber, $context) {
		parent::__construct($errorString, $code, $severity, $filename, $linenumber);
		$this->context = $context;
	}
	
	public function getRecessTrace() {
		return $this->getTrace();
	}
}

class RecessTraceException extends RecessErrorException {
	public $trace;
	public function __construct($message, $trace = array()) {
		parent::__construct($message, 0, 0, isset($trace[0]['file']) ? $trace[0]['file'] : '', isset($trace[0]['line']) ? $trace[0]['line'] : 0, array());
		$this->trace = $trace;
	}
	
	public function getRecessTrace() {
		if(!empty($this->trace)) {
			return $this->trace;
		} else {
			return parent::getRecessTrace();
		}
	}
}

function recho($message) { echo $message; }
function rexit() { exit; }

?>