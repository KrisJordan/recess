<?php
/**
 * Interface used which maps to conditional SQL statements
 * 
 * @author Kris Jordan
 * @copyright 2008 Kris Jordan
 * @package Recess! Framework
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link http://www.recessframework.org/
 */
interface ISqlConditions {
	
	function equal($column, $value);
	function notEqual($column, $value);
	function between ($column, $big, $small);
	function greaterThan($column, $value);
	function greaterThanOrEqualTo($column, $value);
	function lessThan($column, $value);
	function lessThanOrEqualTo($column, $value);
	function like($column, $value);
	function notLike($column, $value);
	
}
?>