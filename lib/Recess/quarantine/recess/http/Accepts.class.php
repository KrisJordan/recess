<?php
Library::import('recess.http.AcceptsList');
Library::import('recess.http.MimeTypes');

class Accepts {
	
	protected $headers;
	
	protected $format = '';
	protected $formats = false;
	protected $formatsTried = array();
	protected $formatsCurrent = array();
	
	protected $languages = false;
	protected $encodings = false;
	protected $charsets = false;
	
	const FORMATS = 'ACCEPT';
	const LANGUAGES = 'ACCEPT_LANGUAGE';
	const ENCODINGS = 'ACCEPT_ENCODING';
	const CHARSETS = 'ACCEPT_CHARSETS';
	
	public function __construct($headers) {
		$this->headers = $headers;
	}
	
	public function format() {
		return $this->format;
	}
	
	protected function initFormats() {
		if(isset($this->headers[self::FORMATS])) {
			$this->formats = new AcceptsList($this->headers[self::FORMATS]);
		} else {
			$this->formats = new AcceptsList('');
		}
	}
	
	public function forceFormat($format) {
		$mimeType = MimeTypes::preferredMimeTypeFor($format);
		if($mimeType != false) {
			$this->headers[self::FORMATS] = $mimeType;
		} else {
			$this->headers[self::FORMATS] = '';
		}
	}
	
	public function nextFormat() {
		if($this->formats === false) {
			$this->initFormats();
		}
		
		while(current($this->formatsCurrent) === false) {
			$key = key($this->formatsCurrent);
			
			$nextTypes = $this->formats->next();
			
			if($nextTypes === false) { return false; } // Base case, ran out of types in ACCEPT string
			$this->formatsTried = array_merge($this->formatsTried, $this->formatsCurrent);
			
			$nextTypes = MimeTypes::formatsFor($nextTypes);
			$this->formatsCurrent = array();
			foreach($nextTypes as $type) {
				if(!in_array($type, $this->formatsTried)) {
					$this->formatsCurrent[] = $type;
				}
			}
		}
		
		$result = each($this->formatsCurrent);
		$this->format = $result[1];
		return $result[1]; // Each returns an array of (key, value)
	}
	
	public function resetFormats() {
		$this->format = '';
		
		if($this->formats !== false) 
			$this->formats->reset();
			
		$this->formatsTried = array();
		$this->formatsCurrent = array();
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