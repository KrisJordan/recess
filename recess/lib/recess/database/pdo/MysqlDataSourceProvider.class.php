<?php
Library::import('recess.database.pdo.IPdoDataSourceProvider');

/**
 * MySql Data Source Provider
 * @author Kris Jordan
 */
class MysqlDataSourceProvider implements IPdoDataSourceProvider {
	protected static $mappings;
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
		
		Library::import('recess.database.pdo.RecessTableDefinition');
		$tableDefinition = new RecessTableDefinition();
		
		foreach($results as $result) {
			$tableDefinition->addColumn(
				$result['Field'],
				$this->getRecessType($result['Type']),
				$result['Null'] == 'No' ? false : true,
				$result['Key'] == 'PRI' ? true : false,
				$result['Default'] == null ? '' : $result['Default']);
				array($result['Extra']);
		}
		
		return $tableDefinition;
	}
	
	function getRecessType($mysqlType) {
		if( ($parenPos = strpos($mysqlType,'(')) !== false ) {
			$mysqlType = substr($mysqlType,0,$parenPos);
		}
		if( ($spacePos = strpos($mysqlType,' '))) {
			$mysqlType = substr($mysqlType(0,$spacePos));
		}
		$mysqlType = strtolower(rtrim($mysqlType));
		
		$mappings = MysqlDataSourceProvider::getMysqlToRecessMappings();
		if(isset($mappings[$mysqlType])) {
			return $mappings[$mysqlType];
		} else {
			return RecessType::STRING;
		}
	}
	
	static function getMysqlToRecessMappings() {
		if(!isset(self::$mappings)) {
			self::$mappings = array(
				'enum' => RecessType::STRING,
				'binary' => RecessType::STRING,
				'varbinary' => RecessType::STRING,
				'varchar' => RecessType::STRING,
				'char' => RecessType::STRING,
				'national' => RecessType::STRING,
			
				'text' => RecessType::TEXT,
				'tinytext' => RecessType::TEXT,
				'mediumtext' => RecessType::TEXT,
				'longtext' => RecessType::TEXT,
				'set' => RecessType::TEXT,
			
				'blob' => RecessType::BLOB,
				'tinyblob' => RecessType::BLOB,
				'mediumblob' => RecessType::BLOB,
				'longblob' => RecessType::BLOB,
			
				'int' => RecessType::INTEGER,
				'integer' => RecessType::INTEGER,
				'tinyint' => RecessType::INTEGER,
				'smallint' => RecessType::INTEGER,
				'mediumint' => RecessType::INTEGER,
				'bigint' => RecessType::INTEGER,
				'bit' => RecessType::INTEGER,
			
				'bool' => RecessType::BOOLEAN,
				'boolean' => RecessType::BOOLEAN,
			
				'float' => RecessType::FLOAT,
				'double' => RecessType::FLOAT,
				'decimal' => RecessType::STRING,
				'dec' => RecessType::STRING,
			
				'year' => RecessType::INTEGER,
				'date' => RecessType::DATE,
				'datetime' => RecessType::DATETIME,
				'timestamp' => RecessType::TIMESTAMP,
				'time' => RecessType::TIME,
			); 
		}
		return self::$mappings;
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
	
	/**
	 * Given a Table Definition, return the CREATE TABLE SQL statement
	 * in the MySQL's syntax.
	 *
	 * @param RecessTableDefinition $tableDefinition
	 */
	function createTableSql(RecessTableDefinition $definition) {
		throw new RecessException("Not implemented.", get_defined_vars());
	}
}
?>