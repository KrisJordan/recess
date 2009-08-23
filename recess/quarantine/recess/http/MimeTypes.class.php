<?php
abstract class MimeTypes {
	
	protected static $byFormat = array();
	protected static $byMime = array();
	
	static function init() {
		// TODO: Cache the MIME Type Data Structure
		MimeTypes::registerMany(
			array(
				array('html', 'text/html'),
				array('xhtml', 'application/xhtml+xml'),
				array('xml', array('application/xml', 'text/xml', 'application/x-xml')),
				array('json', array('application/json', 'text/x-json','application/jsonrequest')),
				array('js', array('text/javascript', 'application/javascript', 'application/x-javascript')),
				array('css', 'text/css'),
				array('rss', 'application/rss+xml'),
				array('yaml', array('application/x-yaml', 'text/yaml')),
				array('atom', 'application/atom+xml'),
				array('text', 'text/plain'),
				array('png', 'image/png'),
				array('jpg', 'image/jpeg', 'image/pjpeg'),
				array('gif', 'image/gif'),
				array('form', 'multipart/form-data'),
				array('url-form', 'application/x-www-form-urlencoded'),
				array('csv', 'text/csv'),
			)
		);
	}
	
	static function preferredMimeTypeFor($format) {
		if(isset(self::$byFormat[$format])) {
			return self::$byFormat[$format][0];
		} else {
			return false;
		}
	}
	
	static function formatsFor($types) {
		$types = is_array($types) ? $types : array($types);
		
		$linearizedFormats = array();
		
		foreach($types as $type) {
			$parts = explode('/', $type);
			if(count($parts) >= 1) {
				if($parts[0] == '*') {
					// Wildcard -- add all formats and return
					return self::addUnique($linearizedFormats, array_keys(self::$byFormat));
				} else {
					if(isset(self::$byMime[$parts[0]])) {
						if($parts[1] == '*') {
							foreach(self::$byMime[$parts[0]] as $formats) {
								$linearizedFormats = self::addUnique($linearizedFormats, $formats);
							}
						} else {
							if(isset(self::$byMime[$parts[0]][$parts[1]])) {
								$linearizedFormats = self::addUnique($linearizedFormats, self::$byMime[$parts[0]][$parts[1]]);
							}
						}
					}
				}
			}
		}
		
		if( ($key = array_search('html', $linearizedFormats)) !== false) {
			if($key != 0) {
				array_splice($linearizedFormats, $key, 1);
				array_unshift($linearizedFormats, 'html');
			}
		}
		
		return $linearizedFormats;
	}
	
	static private function addUnique($formats, $additionalFormats) {
		foreach($additionalFormats as $format) {
			if(!in_array($format, $formats)) $formats[] = $format;
		}
		return $formats;
	}
	
	
	static function register($type, $extension, $synonyms = array()) {
		self::registerMany(array(array($type,$extension,$synonyms)));
	}
	
	static function registerMany($types) {
		foreach($types as $type) {
			$formats = is_array($type[0]) ? $type[0] : array($type[0]);
			$mimes = is_array($type[1]) ? $type[1] : array($type[1]);
			
			foreach($mimes as $mime) { 
				$parts = explode('/', $mime);
				if(count($parts) == 2) {
					if(!isset(self::$byMime[$parts[0]])) {
						self::$byMime[$parts[0]] = array();
					}
					self::$byMime[$parts[0]][$parts[1]] = $formats;
				}
			}
			
			foreach($formats as $format) {
				if(!isset(self::$byFormat[$format])) {
					self::$byFormat[$format] = array();
				}
				self::$byFormat[$format] = array_unique(array_merge(self::$byFormat[$format], $mimes));
			}
		}
	}
}

MimeTypes::init();
?>