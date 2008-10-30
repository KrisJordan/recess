<?php
/**
 * Memcache Cache
 * @author Kris Jordan
 * @todo Actually implement and test.
 */
class MemcacheCacheProvider implements ICacheProvider {
	function set($key, $value, $duration = 0) {
		$this->memcache->set($key, $value, $duration);
		if(isset($this->resortsTo))
			$this->resortsTo->set($key, $value, $duration);
	}
	function get($key) {
		if(($result = $this->memcache->get($key)) != null) {
			return $result;
		} else {
			if(isset($this->resortsTo)) {
				$result = $this->resortsTo->get($key);
				if($result != null)
					$this->memcache->set($key, $result);
				return $result;
			} else {
				return false;
			}
		}
	}
	function clear($key) {
		
	}
	function flush($key) {
		
	}
}
?>