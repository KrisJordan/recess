<?php
Library::import('recess.http.Request');
Library::import('recess.http.Cookie');

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
}
?>