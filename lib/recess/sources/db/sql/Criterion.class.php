<?php

class Criterion {
	public $column;
	public $value;
	public $operator;
	
	const GREATER_THAN = '>';
	const GREATER_THAN_EQUAL_TO = '>=';
	
	const LESS_THAN = '<';
	const LESS_THAN_EQUAL_TO = '<=';
	
	const EQUAL_TO = '==';
	const NOT_EQUAL_TO = '!=';
	
	const LIKE = 'LIKE';
	
	const COLON = ':';
	
	const ASSIGNMENT = '=';
	const ASSIGNMENT_PREFIX = 'assgn_';
	
	const UNDERSCORE = '_';
	
	public function __construct($column, $value, $operator){
		$this->column = $column;
		$this->value = $value;
		$this->operator = $operator;
	}
	
	public function getQueryParameter() {
		if($this->operator == self::ASSIGNMENT) { 
			return self::COLON . str_replace(Library::dotSeparator, self::UNDERSCORE, self::ASSIGNMENT_PREFIX . $this->column);
		} else {
			return self::COLON . str_replace(Library::dotSeparator, self::UNDERSCORE, $this->column);
		}
	}
}

?>