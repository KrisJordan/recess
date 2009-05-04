<?php
class ContentNegotiation {
	
	protected $headers;
	
	public function __construct($httpHeaders) {
		$this->headers = $httpHeaders;
	}
		
	public function nextFormat() {
		return 'html';	
	}
	
	public function resetFormats() {
		
	}
	
	public function nextLanguage() {
		return 'en';
	}

	public function resetLanguages() {
		
	}
		
	public function nextEncoding() {
		return 'gzip';
	}
	
	public function resetEncodings() {
		
	}	
	
	public function nextCharset() {
		return 'utf-8';
	}
	
	public function resetCharset() {
		
	}
	
}
?>