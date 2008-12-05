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
	
	
	/**
	 * Retrieve the a table's RecessTableDefinition.
	 *
	 * @param string $table Name of table.
	 * @return RecessTableDefinition
	 */
	function getTableDefinition($table);
	
	
	/**
	 * Drop a table from the data source.
	 *
	 * @param string $table Table to drop.
	 */
	function dropTable($table);
	
	/**
	 * Empty a table in the data source.
	 *
	 * @param string $table Table to drop.
	 */
	function emptyTable($table);
	
}

?>