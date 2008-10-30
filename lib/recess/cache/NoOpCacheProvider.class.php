<?php
require_once('ICacheProvider.class.php');
class NoOpCacheProvider implements ICacheProvider {
	function set($key, $value, $duration = 0) {}
	function get($key) {}
	function clear($key) {}
	function flush($key) {}
}
?>