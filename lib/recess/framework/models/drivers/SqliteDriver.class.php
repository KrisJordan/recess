<?php

Library::import('recess.data.drivers.Driver');

class SqliteDriver extends Driver {
	
	public function connect($dsn) {
		try {
			$this->pdo = new PDO($dsn);
			$this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		} catch (PDOException $e) {
			throw new ScaffoldException('Database connection could not be established.');
		}
	}
	
	public function disconnect() {
	}
	
	public function tables() {
		$tables = $this->pdo->query('SELECT tbl_name FROM sqlite_master WHERE type="table"');
		
		$return = array();
		
		foreach($tables as $table) {
			$return[] = $table[0];
		}
		
		return $return;
	}
	
	public function columns($table) {
		$results = $this->pdo->query('PRAGMA table_info("' . $table . '");');
		
		$return = array();
		
		foreach($results as $result) {
			$return[] = $result['name'];
		}
		
		return $return;
	}
	
}

?>