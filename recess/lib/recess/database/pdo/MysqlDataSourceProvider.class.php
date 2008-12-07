<?php
Library::import('recess.database.pdo.IPdoDataSourceProvider');

/**
 * MySql Data Source Provider
 * @author Kris Jordan
 */
class MysqlDataSourceProvider implements IPdoDataSourceProvider {
	protected static $mysqlToRecessMappings;
	protected static $recessToMysqlMappings;
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
				$result['Null'] == 'NO' ? false : true,
				$result['Key'] == 'PRI' ? true : false,
				$result['Default'] == null ? '' : $result['Default'],
				$result['Extra'] == 'auto_increment' ? array('autoincrement' => true) : array());
		}
		
		return $tableDefinition;
	}
	
	function getRecessType($mysqlType) {
		if($mysqlType == 'TINYINT(1)')
			return RecessType::BOOLEAN;
		
		if( ($parenPos = strpos($mysqlType,'(')) !== false ) {
			$mysqlType = substr($mysqlType,0,$parenPos);
		}
		if( ($spacePos = strpos($mysqlType,' '))) {
			$mysqlType = substr($mysqlType(0,$spacePos));
		}
		$mysqlType = strtolower(rtrim($mysqlType));
		
		$mysqlToRecessMappings = MysqlDataSourceProvider::getMysqlToRecessMappings();
		if(isset($mysqlToRecessMappings[$mysqlType])) {
			return $mysqlToRecessMappings[$mysqlType];
		} else {
			return RecessType::STRING;
		}
	}
	
	static function getMysqlToRecessMappings() {
		if(!isset(self::$mysqlToRecessMappings)) {
			self::$mysqlToRecessMappings = array(
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
		return self::$mysqlToRecessMappings;
	}
	
	static function getRecessToMysqlMappings() {
		if(!isset(self::$recessToMysqlMappings)) {
			self::$recessToMysqlMappings = array(
				RecessType::BLOB => 'BLOB',
				RecessType::BOOLEAN => 'TINYINT(1)',
				RecessType::DATE => 'DATE',
				RecessType::DATETIME => 'DATETIME',
				RecessType::FLOAT => 'FLOAT',
				RecessType::INTEGER => 'INTEGER',
				RecessType::STRING => 'VARCHAR(255)',
				RecessType::TEXT => 'TEXT',
				RecessType::TIME => 'TIME',
				RecessType::TIMESTAMP => 'TIMESTAMP',
			);
		}
		return self::$recessToMysqlMappings;
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
		$sql = 'CREATE TABLE ' . $definition->name;
		
		$mappings = MysqlDataSourceProvider::getRecessToMysqlMappings();
		
		$columnSql = null;
		foreach($definition->getColumns() as $column) {
			if(isset($columnSql)) { $columnSql .= ', '; }
			$columnSql .= "\n\t" . $column->name . ' ' . $mappings[$column->type];
			if($column->isPrimaryKey) {
				$columnSql .= ' NOT NULL';
			
				if(isset($column->options['autoincrement'])) {
					$columnSql .= ' AUTO_INCREMENT';
				}
				
				$columnSql .= ' PRIMARY KEY';
			}
		}
		$columnSql .= "\n";
		
		return $sql . ' (' . $columnSql . ')';
	}
}
?>