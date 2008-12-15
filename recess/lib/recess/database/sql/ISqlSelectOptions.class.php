<?php
/**
 * Interface used which maps to SELECT SQL statements
 * 
 * @author Kris Jordan
 * @copyright 2008 Kris Jordan
 * @package Recess! Framework
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link http://www.recessframework.org/
 */
interface ISqlSelectOptions {

	function limit($size);
	function offset($offset);
	function range($start, $finish);
	function orderBy($clause);
	function leftOuterJoin($table, $tablePrimaryKey, $fromTableForeignKey);
	function innerJoin($table, $tablePrimaryKey, $fromTableForeignKey);
	function distinct();
		
}
?>