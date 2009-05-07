<?php
Library::import('recess.http.AcceptsString');
Library::import('recess.http.MimeType');

class Accepts {
	
	protected $headers;
	
	protected $types = false;
	protected $typesArray = array();
	
	protected $languages = false;
	protected $encodings = false;
	protected $charsets = false;
	
	const TYPES = 'ACCEPT';
	const LANGUAGES = 'ACCEPT_LANGUAGE';
	const ENCODINGS = 'ACCEPT_ENCODING';
	const CHARSETS = 'ACCEPT_CHARSETS';
	
	public function __construct($headers, $overrides = array()) {
		$this->headers = $headers;
		foreach($overrides as $key => $value) {
			$this->headers[$key] = $value;
		}
	}
	
	public function nextFormat() {
		if($this->types === false) { $this->initFormats(); }
		
		while(current($this->typesArray) === false) {
			$nextTypes = $this->types->next();
			if($nextTypes === false) { return '*'; } // Base case, ran out of types in ACCEPT string
			$this->typesArray = MimeType::formatsFor($nextTypes);			
		}
		
		$result = each($this->typesArray);
		return $result[1];
	}
	
	protected function initFormats() {
		$this->types = new AcceptsString($this->headers[self::TYPES]);
	}
	
	public function resetFormats() {
		if($this->types !== false) 
			$this->types->reset();
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