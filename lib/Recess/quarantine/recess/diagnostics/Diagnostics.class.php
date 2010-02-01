<?php

Library::import('recess.http.ResponseCodes');

/**
 * @author Kris Jordan <krisjordan@gmail.com>
 * @copyright 2008, 2009-2009 Kris Jordan
 * @package recess
 * @license MIT
 * @link http://www.recessframework.org/
 */
class Diagnostics {
	
	public static function handleException(Exception $exception) {
		if(ob_get_level() > 1) {
			ob_end_clean();
		}
		
		if($exception instanceof LibraryException) {
			// Special Case for LibraryException to shift front value from stack
			$exception = new RecessFrameworkException($exception->getMessage(), 1, $exception->getTrace());
		}
		
		if($exception instanceof RecessResponseException) {
			header('HTTP/1.1 ' . ResponseCodes::getMessageForCode($exception->responseCode));
		} else {
			if(!headers_sent()) {
				header('HTTP/1.1 ' . ResponseCodes::getMessageForCode(ResponseCodes::HTTP_INTERNAL_SERVER_ERROR));
			}
		}
		
		if(!class_exists('RecessConf', false)) {
			print $exception->getMessage() . "\n";
			print $exception->getTraceAsString();
			print $exception->getFile() . ' ' . $exception->getLine();
			exit;
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

class RecessFrameworkException extends RecessErrorException {
	public $trace;
	protected $shifts;
	public function __construct($message, $shifts = 0, $trace = array()) {
		if(empty($trace)) {
			$trace = debug_backtrace();
		}
		
		while($shifts > 0 && !empty($trace)) {
			array_shift($trace);
			--$shifts;
		}
		
		$this->trace = $trace;
		
		parent::__construct($message, 0, 0, isset($trace[0]['file']) ? $trace[0]['file'] : '', isset($trace[0]['line']) ? $trace[0]['line'] : 0, array());
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
