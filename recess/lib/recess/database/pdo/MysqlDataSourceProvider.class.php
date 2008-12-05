<?php
Library::import('recess.database.pdo.IPdoDataSourceProvider');

/**
 * MySql Data Source Provider
 * @author Kris Jordan
 */
class MysqlDataSourceProvider implements IPdoDataSourceProvider {
	
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
		$results = $this->pdo->query('SHOW TABLES');
		
		$tables = array();
		
		foreach($results as $result) {
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
		try {
			$results = $this->pdo->query('SHOW COLUMNS FROM ' . $table . ';');
		} catch(Exception $e) {
			return array();
		}
		
		$columns = array();
		
		foreach($results as $result) {
			$columns[] = $result['Field'];
		}
		
		sort($columns);
		
		return $columns;
	}
	
	/**
	 * Retrieve the a table's RecessTableDefinition.
	 *
	 * @param string $table Name of table.
	 * @return RecessTableDefinition
	 */
	function getTableDefinition($table) {
		$results = $this->pdo->query('SHOW COLUMNS FROM ' . $table . ';');
		
		$columns = array();
		
		$tableDefinition = new RecessTableDefinition();
		
		foreach($results as $result) {
			$tableDefinition->addColumn(
				$result['Field'],
				$result['Type'],
				$result['Null'] == 'No' ? false : true,
				$result['Key'] == 'PRI' ? true : false,
				$result['Default'] == null ? '' : $result['Default']);
				array($result['Extra']);
		}
		
		return $tableDefinition;
	}
	
	/**
	 * Drop a table from MySql database.
	 *
	 * @param string $table Name of table.
	 */
	function dropTable($table) {
		return $this->pdo->exec('DROP TABLE ' . $table);
	}
	
	/**
	 * Empty a table from MySql database.
	 *
	 * @param string $table Name of table.
	 */
	function emptyTable($table) {
		return $this->pdo->exec('DELETE FROM ' . $table);
	}
}
?>