<?php

class Criterion {
	public $column;
	public $value;
	public $operator;
	
	const GREATER_THAN = '>';
	const GREATER_THAN_EQUAL_TO = '>=';
	
	const LESS_THAN = '<';
	const LESS_THAN_EQUAL_TO = '<=';
	
	const EQUAL_TO = '=';
	const NOT_EQUAL_TO = '!=';
	
	const LIKE = 'LIKE';
	
	public function __construct($column, $value, $operator){
		$this->column = $column;
		$this->value = $value;
		$this->operator = $operator;
	}
	
	public function getQueryParameter() {
		return str_replace('.', '_', $this->column);
	}
}

?>