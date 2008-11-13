<?php
/**
 * Cache is a low level service offering volatile key-value pair
 * storage. 
 * 
 * @author Kris Jordan
 * @todo Actually write tests for this this (and implement subclasses.)
 *  */
abstract class Cache {
	protected static $reportsTo;

	static function reportsTo(ICacheProvider $cache) {
		if(!$cache instanceof ICacheProvider) {
			$cache = new NoOpCacheProvider();
		}
		
		if(isset(self::$reportsTo)) {
			$temp = self::$reportsTo;
			self::$reportsTo = $cache;
			self::$reportsTo->reportsTo($temp);
		} else {
			self::$reportsTo = $cache;
		}
	}
	
	static function set($key, $value, $duration = 0) {
		return self::$reportsTo->set($key, $value, $duration);
	}
	
	static function get($key) {
		return self::$reportsTo->get($key);
	}
	
	static function delete($key) {
		return self::$reportsTo->delete($key);
	}
	
	static function clear() {
		return self::$reportsTo->clear();
	}
}

/**
 * Common interface for caching subsystems.
 * @author Kris Jordan
 */
interface ICacheProvider {
	/**
	 * Enter description here...
	 *
	 * @param string $key
	 * @param mixed $value
	 * @param unknown_type $duration
	 */
	function set($key, $value, $duration = 0);
	function get($key);
	function delete($key);
	function clear();
}

class NoOpCacheProvider implements ICacheProvider {
	function set($key, $value, $duration = 0) {}
	function get($key) { return false; }
	function delete($key) {}
	function clear() {}
}

Cache::reportsTo(new NoOpCacheProvider());
?>