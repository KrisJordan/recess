<?php
Library::import('recess.http.AcceptsList');
Library::import('recess.http.MimeTypes');

class Accepts {
	
	protected $headers;
	
	protected $types = false;
	protected $typeOverride = null;
	protected $typesTried = array();
	protected $typesCurrent = array();
	
	protected $languages = false;
	protected $encodings = false;
	protected $charsets = false;
	
	const TYPES = 'ACCEPT';
	const LANGUAGES = 'ACCEPT_LANGUAGE';
	const ENCODINGS = 'ACCEPT_ENCODING';
	const CHARSETS = 'ACCEPT_CHARSETS';
	
	public function __construct($headers) {
		$this->headers = $headers;
	}
	
	protected function initFormats() {
		$this->types = new AcceptsList($this->headers[self::TYPES]);
	}
	
	public function overrideFormat($format) {
		$this->typeOverride = $format;
		$this->headers[self::TYPES] = array();
	}
	
	public function nextFormat() {
		if($this->types === false) { 
			if($this->typeOverride !== null) {
				$format = $this->typeOverride;
				$this->typeOverride = null;
				return $format;
			}
			$this->initFormats();
		}
		
		while(current($this->typesCurrent) === false) {
			$key = key($this->typesCurrent);
			
			$nextTypes = $this->types->next();
			
			if($nextTypes === false) { return false; } // Base case, ran out of types in ACCEPT string
			$this->typesTried = array_merge($this->typesTried, $this->typesCurrent);
			
			$nextTypes = MimeTypes::formatsFor($nextTypes);
			$this->typesCurrent = array();
			foreach($nextTypes as $type) {
				if(!in_array($type, $this->typesTried)) {
					$this->typesCurrent[] = $type;
				}
			}
		}
		
		$result = each($this->typesCurrent);
		return $result[1]; // Each returns an array of (key, value)
	}
	
	public function resetFormats() {
		if($this->types !== false) 
			$this->types->reset();
			
		$this->typesTried = array();
		$this->typesCurrent = array();
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