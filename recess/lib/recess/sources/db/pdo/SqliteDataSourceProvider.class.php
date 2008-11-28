<?php
Library::import('recess.sources.db.pdo.IPdoDataSourceProvider');

/**
 * Sqlite 3 Data Source Provider
 * @author Kris Jordan
 */
class SqliteDataSourceProvider implements IPdoDataSourceProvider {
	const SQLITE_TABLE_PREFIX = 'sqlite_';
	
	protected $pdo = null;
	
	/**
	 * Initialize with a reference back to the PDO object.
	 *
	 * @param PDO $pdo
	 */
	function init(PDO $pdo) {
		$this->pdo = $pdo;
	}
	
	/**
	 * List the tables in a data source.
	 * @return array(string) The tables in the data source ordered alphabetically.
	 */
	function getTables() {
		$results = $this->pdo->query('SELECT tbl_name FROM sqlite_master WHERE type="table"');
		
		$tables = array();
		
		foreach($results as $result) {
			if(substr($result[0],0,strlen(self::SQLITE_TABLE_PREFIX)) != self::SQLITE_TABLE_PREFIX)
				$tables[] = $result[0];
		}
		
		sort($tables);
		
		return $tables;
	}
	
	/**
	 * List the column names of a table alphabetically.
	 * @param string $table Table whose columns to list.
	 * @return array(string) Column names sorted alphabetically.
	 */
	function getColumns($table) {
		$results = $this->pdo->query('PRAGMA table_info("' . $table . '");');
		
		$columns = array();
		
		foreach($results as $result) {
			$columns[] = $result['name'];
		}
		
		sort($columns);
		
		return $columns;
	}
}
?>