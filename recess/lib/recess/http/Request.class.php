<?php
Library::import('recess.lang.RecessObject');

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
	
	public $meta; // Key/value store used by Policy to mark-up request
	
	public $username = '';
	public $password = '';
	
	public function __construct() {
		$this->meta = new Meta;
	}
	
	public function setResource($resource) {
		$this->resource = $resource;
		$this->resourceParts = self::splitResourceString($resource);
	}
	
	public static function splitResourceString($resourceString) {
		$parts = array_filter(split(Library::pathSeparator, $resourceString));
		if(!empty($parts)) { 
			return array_combine(range(0, count($parts)-1), $parts);
		} else {
			return $parts;	
		}
	}
}

class Meta extends RecessObject {}

?>