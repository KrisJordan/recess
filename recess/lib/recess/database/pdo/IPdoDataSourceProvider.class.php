<?php
Library::import('recess.database.pdo.RecessTableDescriptor');
Library::import('recess.database.pdo.RecessColumnDescriptor');
Library::import('recess.database.pdo.RecessType');

/**
 * Interface for vendor specific operations needed by PdoDataSource.
 * 
 * @author Kris Jordan <krisjordan@gmail.com>
 * @copyright 2008, 2009 Kris Jordan
 * @package Recess PHP Framework
 * @license MIT
 * @link http://www.recessframework.org/
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
	 * Retrieve the a table's RecessTableDescriptor.
	 *
	 * @param string $table Name of table.
	 * @return RecessTableDescriptor
	 */
	function getTableDescriptor($table);
	
	/**
	 * Sanity check and semantic sugar from higher level
	 * representation of table pushed down to the RDBMS
	 * representation of the table.
	 *
	 * @param string $table
	 * @param RecessTableDescriptor $descriptor
	 */
	function cascadeTableDescriptor($table, RecessTableDescriptor $descriptor);
	
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
	
	/**
	 * Given a Table Definition, return the CREATE TABLE SQL statement
	 * in the provider's desired syntax.
	 *
	 * @param RecessTableDescriptor $tableDescriptor
	 */
	function createTableSql(RecessTableDescriptor $tableDescriptor);
}

?>