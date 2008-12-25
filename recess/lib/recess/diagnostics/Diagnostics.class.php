<?php

Library::import('recess.http.ResponseCodes');

class Diagnostics {
	
	public static function handleException(Exception $exception) {
		if(ob_get_level() > 1) {
			ob_end_clean();
		}
		
		if($exception instanceof LibraryException) {
			// Special Case for LibraryException to shift front value from stack
			$trace = $exception->getTrace();
			if(count($trace) > 0) 
				array_shift($trace);
			$exception = new RecessTraceException($exception->getMessage(), $trace);
		}
		
		if($exception instanceof RecessResponseException) {
			header('HTTP/1.1 ' . ResponseCodes::getMessageForCode($exception->responseCode));
		} else {
			if(!headers_sent()) {
				header('HTTP/1.1 ' . ResponseCodes::getMessageForCode(ResponseCodes::HTTP_INTERNAL_SERVER_ERROR));
			}
		}
		
		if(RecessConf::$mode == RecessConf::DEVELOPMENT) {
			include('output/exception_report.php');
		} else {
			if($exception instanceof RecessResponseException) {
				echo ResponseCodes::getMessageForCode($exception->responseCode);
			} else {
				echo ResponseCodes::getMessageForCode(ResponseCodes::HTTP_INTERNAL_SERVER_ERROR);
			}
			echo '<br />', $exception->getMessage();
		}
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

class RecessResponseException extends RecessException {
	public $responseCode = 500;
	
	public function __construct($message, $responseCode, $context) {
		parent::__construct($message, $context);
		$this->responseCode = $responseCode;
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

?>