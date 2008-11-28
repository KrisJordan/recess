<?php
/**
 * Interface for vendor specific operations needed by PdoDataSource.
 * 
 * @author Kris Jordan
 */
interface IPdoDataSourceProvider {
	
	/**
	 * Initialize with a reference back to the PDO object.
	 *
	 * @param PDO $pdo
	 */
	function init(PDO $pdo);
	
	/**
	 * List the tables in a data source alphabetically.
	 * @return array(string) The tables in the data source
	 */
	function getTables();
	
	/**
	 * List the column names of a table alphabetically.
	 * @param string $table Table whose columns to list.
	 * @return array(string) Column names sorted alphabetically.
	 */
	function getColumns($table);
	
}

?>