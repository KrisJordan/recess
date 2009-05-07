<?php
abstract class MimeType {
	
	protected static $byFormat = array();
	protected static $byMime = array();
	
	static function init() {
		// TODO: Cache the MIME Type Data Structure
		MimeType::registerMany(array(
			array('html', 'text/html', array('application/xhtml+xml'), array('xhtml')),
			array('xml', 'application/xml', array('text/xml', 'application/x-xml')),
			array('json', 'application/json', array('text/x-json','application/jsonrequest')),
			array('js', 'text/javascript', array('application/javascript', 'application/x-javascript')),
			array('css', 'text/css'),
			array('rss', 'application/rss+xml'),
			array('atom', 'application/atom+xml'),
			array('yaml', 'application/x-yaml', array('text/yaml')),
			array('text', 'text/plain', array()),
			array('png', 'image/png', array()),
			array('jpg', 'image/jpeg', array('image/pjpeg')),
			array('gif', 'image/gif', array()),
			array('form', 'multipart/form-data'),
			array('url-form', 'application/x-www-form-urlencoded'),
			array('csv', 'text/csv'),
			array('*', '*/*'),
		));
	}
	
	static function formatsFor($array) {
		return array();
	}
	
	static function register($type, $extension, $synonyms = array()) {
		self::registerMany(array(array($type,$extension,$synonyms)));
	}
	
	static function registerMany($array) {
		
	}
	
}

MimeType::init();
?>