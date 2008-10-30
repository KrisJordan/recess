<?php
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
	function clear($key);
	function flush($key);
}

?>