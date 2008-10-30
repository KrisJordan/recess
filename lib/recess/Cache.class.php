<?php
/**
 * Cache is a low level service offering volatile key-value pair
 * storage. 
 * 
 * @author Kris Jordan
 * @todo Actually write tests for this this (and implement subclasses.)
 *  */
class Cache {
	protected static $reportsTo;

	static function reportsTo(Cache $cache) {
		if(isset($this->reportsTo)) {
			$temp = self::$reportsTo;
			self::$reportsTo = $cache;
			self::$reportsTo->reportsTo($temp);
		} else {
			self::$reportsTo = $cache;
		}
	}
	
	static function set($key, $value, $timeout = 0) {
		return self::$reportsTo->set($key, $value);
	}
	
	static function get($key) {
		return self::$reportsTo->get($key);
	}
	
	static function clear($key) {
		return self::$reportsTo->clear($key);
	}
	
	static function flush() {
		return self::$reportsTo->flush();
	}
}
Cache::reportsTo(new NoOpCache());
?>