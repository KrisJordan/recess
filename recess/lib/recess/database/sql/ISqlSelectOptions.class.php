<?php

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