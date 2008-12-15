<?php
/**
 * Recess has a fixed set of native 'recess' types that are mapped to vendor specific
 * column types by individual DataSourceProviders.
 * 
 * @author Kris Jordan
 * @copyright 2008 Kris Jordan
 * @package Recess! Framework
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link http://www.recessframework.org/
 */
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