<?php

interface ISqlConditions {
	
	function equal($column, $value);
	function notEqual($column, $value);
	function between ($column, $big, $small);
	function greaterThan($column, $value);
	function greaterThanOrEqualTo($column, $value);
	function lessThan($column, $value);
	function lessThanOrEqualTo($column, $value);
	function like($column, $value);
	
}

?>