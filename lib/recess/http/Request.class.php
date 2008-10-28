<?php

class Request {	
	
	public $format;
	public $headers;
	public $resource;
	public $resourceParts = array();
	public $method;

	public $get = array();
	public $post = array();
	public $put = array();

	public $cookie;
	
	public $meta = array(); // Key/value store used by Policy to mark-up request
	
	public $username = '';
	public $password = '';
	
	public function setResource($resource) {
		$this->resource = $resource;
		$this->resourceParts = self::splitResourceString($resource);
	}
	
	public static function splitResourceString($resourceString) {
		$parts = split(Library::pathSeparator, $resourceString);
		
		// "Trim" empty strings
		if(!empty($parts)) {
			array_shift($parts);
		}
		
		if(!empty($parts)) {
			$last = array_pop($parts);
			if($last !== '') {
				$parts[] = $last;
			}
		}
		
		return $parts;
	}
}

?>