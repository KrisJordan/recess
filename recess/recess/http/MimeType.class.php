<?php
abstract class MimeType {
	
	static function init() {
		// TODO: Cache the MIME Type Data Structure
		MimeType::registerMany(array(
			array('all', '*/*'),
			array('text', 'text/plain', array(), array('txt')),
			array('html', 'text/html', array('application/xhtml+xml'), array('xhtml')),
			array('js', 'text/javascript', array('application/javascript', 'application/x-javascript')),
			array('css', 'text/css'),
			array('ics', 'text/calendar'),
			array('csv', 'text/csv'),
			array('xml', 'application/xml', array('text/xml', 'application/x-xml')),
			array('rss', 'application/rss+xml'),
			array('atom', 'application/atom+xml'),
			array('yaml', 'application/x-yaml', array('text/yaml')),
			array('multipart_form', 'multipart/form-data'),
			array('url_encoded_form', 'application/x-www-form-urlencoded'),
			array('json', 'application/json', array('text/x-json','application/jsonrequest')),
		));
	}
	
	static function register($type, $extension, $synonyms = array()) {
		self::registerMany(array(array($type,$extension,$synonyms)));
	}
	
	static function registerMany($array) {
		
	}
	
}

MimeType::init();
?>