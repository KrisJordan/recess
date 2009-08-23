<?php

class DuplicateRouteException extends RecessException {
	
	public $file;
	
	public $line;
	
	public function __construct($message, $file, $line) {
		parent::__construct($message,array());
		$this->file = $file;
		$this->line = $line;	
	}
	
}

?>