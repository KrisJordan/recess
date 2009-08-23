<?php

/**
 * @author Kris Jordan <krisjordan@gmail.com>
 * @copyright 2008, 2009 Kris Jordan
 * @package Recess PHP Framework
 * @license MIT
 * @link http://www.recessframework.org/
 */
class Cookie {
	
	public $name = '';
	
	public $value = null;
	
	public $expire = null;
	
	public $path = null;
	
	public $domain = null;
	
	public $secure = null; // https only
	
	public $httponly = null; // used to protect against javascript cross-site scripting
	
	public function __construct($name, $value = null, $expire = null, $path = null, $domain = null, $secure = null, $httponly = null) {
		$this->name = $name;
		$this->value = $value;
		$this->expire = $expire;
		$this->path = $path;
		$this->domain = $domain;
		$this->secure = $secure;
		$this->httponly = $httponly;
	}
	
}
?>