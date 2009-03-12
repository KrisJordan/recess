<?php
/**
 * Interface used which maps to SELECT SQL statements
 * 
 * @author Kris Jordan <krisjordan@gmail.com>
 * @copyright 2008, 2009 Kris Jordan
 * @package Recess PHP Framework
 * @license MIT
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