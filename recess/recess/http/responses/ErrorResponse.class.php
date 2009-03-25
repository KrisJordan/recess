<?php

Library::import('recess.http.Response');

abstract class ErrorResponse extends Response {
	public $trace;
	
	public function __construct(Request $request, $code, $data = '') {
		parent::__construct($request, $code, $data);
		
		$trace = debug_backtrace();
		array_shift($trace); // Pop this frame off.
		$this->trace = $trace;
	}
}

?>