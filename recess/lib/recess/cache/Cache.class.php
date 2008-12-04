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

class SqliteCacheProvider implements ICacheProvider {
	protected $reportsTo;
	protected $pdo;
	protected $setStatement;
	protected $getStatement;
	protected $getManyStatement;
	protected $deleteStatement;
	protected $time;
	
	protected $entries = array();

	const VALUE = 0;
	const EXPIRE = 1;
	const KEY = 2;
	
	function __construct() {
		$this->pdo = new Pdo('sqlite:' . $_ENV['dir.temp'] . 'sqlite-cache.db');
		$this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		try {
			$this->setStatement = $this->pdo->prepare('INSERT OR REPLACE INTO cache (key,value,expire) values (:key,:value,:expire)');
			$this->getStatement = $this->pdo->prepare('SELECT value,expire FROM cache WHERE key = :key');
			$this->getManyStatement = $this->pdo->prepare('SELECT value,expire,key FROM cache WHERE key LIKE :key');
		} catch(PDOException $e) {
			$this->pdo->exec('CREATE TABLE "cache" ("key" TEXT PRIMARY KEY  NOT NULL , "value" TEXT NOT NULL , "expire" INTEGER NOT NULL)');
			$this->pdo->exec('CREATE INDEX "expiration" ON "cache" ("expire" ASC)');
			$this->setStatement = $this->pdo->prepare('INSERT OR REPLACE INTO cache (key,value,expire) values (:key,:value,:expire)');
			$this->getStatement = $this->pdo->prepare('SELECT value,expire FROM cache WHERE key = :key');
			$this->getManyStatement = $this->pdo->prepare('SELECT value,expire,key FROM cache WHERE key LIKE :key');
		}
		$this->time = time();
	}
	
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
		$this->setStatement->execute(array(':key' => $key, ':value' => serialize($value), ':expire' => $duration == 0 ? 0 : time() + $duration));
		$this->reportsTo->set($key, $value, $duration);
		$this->entries[$key] = $value;
	}
	
	function clearStaleEntries() {
		$this->pdo->exec('DELETE FROM cache WHERE expire != 0 AND expire < ' . $this->time);
	}
	
	function get($key) {
		if(isset($this->entries[$key])) {
			return $this->entries[$key];
		}

		if(($starPos = strpos($key,'*')) === false) {
			// Fetch Single
			$this->getStatement->execute(array(':key' => $key));
			$entries = $this->getStatement->fetchAll(PDO::FETCH_NUM);
		} else {
			// Prefetch With Wildcard
			$this->getManyStatement->execute(array(':key' => substr($key,0,$starPos+1) . '%'));
			$entries = $this->getManyStatement->fetchAll(PDO::FETCH_NUM);
		}
		
		$clearStaleEntries = false;
		foreach($entries as $entry) {
			if($entry[self::EXPIRE] == 0 || $entry[self::EXPIRE] <= $this->time) {
				if(isset($entry[self::KEY])) {
					$this->entries[$entry[self::KEY]] = unserialize($entry[self::VALUE]);
				} else {
					$this->entries[$key] = unserialize($entry[self::VALUE]);
				}
			} else {
				$clearStaleEntries = true;
			}
		}
		
		if($clearStaleEntries) {
			$this->clearStaleEntries();
		}
		
		if(isset($this->entries[$key])) {
			return $this->entries[$key];
		} else{
			return $this->reportsTo->get($key);
		}
	}
	
	function delete($key) {
		if($this->deleteStatement == null) {
			$this->deleteStatement = $this->pdo->prepare('DELETE FROM cache WHERE key = :key OR (expire != 0 AND expire < ' . $this->time . ')');
		}
		$this->deleteStatement->execute(array(':key' => $key));
		$this->reportsTo->delete($key);
	}
	
	function clear() {
		$this->pdo->exec('DELETE FROM cache');
		$this->reportsTo->clear();
	}
}
?>