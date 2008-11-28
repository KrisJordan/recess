<?php
class ApcCacheProvider implements ICacheProvider {
	protected $reportsTo;

	function reportsTo(ICacheProvider $cache) {
		if(!$cache instanceof ICacheProvider) {
			$cache = new NoOpCacheProvider();
		}
		
		if(isset($this->reportsTo)) {
			$temp = $this->reportsTo;
			$this->reportsTo = $cache;
			$this->reportsTo->reportsTo($temp);
		} else {
			$this->reportsTo = $cache;
		}
	}
	
	function set($key, $value, $duration = 0) {
		apc_store($key, $value, $duration);
		$this->reportsTo->set($key, $value, $duration);
	}
	
	function get($key) {
		$result = apc_fetch($key);
		if($result === false) {
			$result = $this->reportsTo->get($key);
			if($result !== false) {
				$this->set($key, $result);	
			}
		}
		return $result;
	}
	
	function delete($key) {
		apc_delete($key);
		$this->reportsTo->delete($key);
	}
	
	function clear() {
		apc_clear_cache('user');
		$this->reportsTo->clear();
	}
}
?>