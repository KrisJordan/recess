<?php

class Criterion {
	public $lhs;
	public $rhs;
	public $operator;
	
	const GREATER_THAN = '>';
	const GREATER_THAN_EQUAL_TO = '>=';
	
	const LESS_THAN = '<';
	const LESS_THAN_EQUAL_TO = '<=';
	
	const EQUAL_TO = '=';
	const NOT_EQUAL_TO = '!=';
	
	const LIKE = 'LIKE';
	
	public function __construct($lhs, $rhs, $operator){
		$this->lhs = $lhs;
		$this->rhs = $rhs;
		$this->operator = $operator;
	}
}

?>