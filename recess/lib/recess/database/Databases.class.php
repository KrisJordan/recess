<?php

class Databases {
	
	const DEFAULT_SOURCE = 'Default';
	
	static $sources = array();
	static $default = null;
	
	/**
	 * Get a named database source
	 *
	 * @param string $name
	 * @return PdoDataSource
	 */
	static function getSource($name) {
		if(isset(self::$sources[$name]))
			return self::$sources[$name];
		else
			return null;
	}
	
	static function addSource($name, $source) {
		self::$sources[$name] = $source;
	}
	
	
	static function getSources() {
		return self::$sources;
	}
	
	static function setDefaultSource($source) {
		self::$sources[self::DEFAULT_SOURCE] = $source;
	}
	
	static function getDefaultSource() {
		return self::$sources[self::DEFAULT_SOURCE];
	}
	
}

?>