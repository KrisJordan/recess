<?php

abstract class RecessType {
	const STRING = 'String';
	const TEXT = 'Text';
	const INTEGER = 'Integer';
	const BOOLEAN = 'Boolean';
	const FLOAT = 'Float';
	const TIME = 'Time';
	const TIMESTAMP = 'Timestamp';
	const DATE = 'Date';
	const DATETIME = 'DateTime';
	const BLOB = 'Blob';
	
	private static $all;
	
	static function all() {
		if(!isset(self::$all)) {
			self::$all = array(
							self::STRING, 
							self::TEXT, 
							self::INTEGER, 
							self::BOOLEAN, 
							self::FLOAT, 
							self::TIME, 
							self::TIMESTAMP, 
							self::DATETIME, 
							self::BLOB);
		}
		return self::$all;
	}
}

?>