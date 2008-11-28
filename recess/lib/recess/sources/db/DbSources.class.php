<?php

class DbSources {
	
	static $sources = array();
	static $default = null;
	
	static function getSource($name) {
		return self::$sources[$name];
	}
	
	static function addSource($name, $source) {
		self::$sources[$name] = $source;
	}
	
	static function setDefaultSource($source) {
		self::$default = $source;
	}
	
	static function getDefaultSource() {
		return self::$default;
	}
	
}

?>