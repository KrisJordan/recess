<?php
Library::import('recess.http.Request');
Library::import('recess.http.Cookie');

/**
 * @author Kris Jordan <krisjordan@gmail.com>
 * @copyright 2008, 2009 Kris Jordan
 * @package Recess PHP Framework
 * @license MIT
 * @link http://www.recessframework.org/
 */
class Response {
	public $code;
	public $data;
	public $request;
	public $headers = array();
	public $meta = array();
	
	protected $cookies = array();
	
	public function __construct(Request $request, $code, $data = '') {
		$this->request = $request;
		$this->code = $code;
		$this->data = $data;
		$this->meta = $request->meta;
	}
	
	public function addCookie(Cookie $cookie) {
		$this->cookies[] = $cookie;
	}
	
	public function getCookies() {
		return $this->cookies;
	}
	
	protected function addHeader($header) {
		$this->headers[] = $header;
	}
	
	public function clearCookies() {
		if(is_array($this->request->cookies))
		foreach(array_keys($this->request->cookies) as $cookieKey) {
			$this->addCookie(new Cookie($cookieKey,''));
		}
	}
}
?>