<?php

class Join {
	const NATURAL = 'NATURAL';
	
	const LEFT = 'LEFT';
	const RIGHT = 'RIGHT';
	const FULL = 'FULL';
	
	const INNER = 'INNER';
	const OUTER = 'OUTER';
	const CROSS = 'CROSS';
	
	public $natural = '';
	public $leftRightOrFull = '';
	public $innerOuterOrCross = 'OUTER';
	
	public $table;
	public $tablePrimaryKey;
	public $fromTableForeignKey;
	
	public function __construct($leftRightOrFull, $innerOuterOrCross, $table, $tablePrimaryKey, $fromTableForeignKey, $natural = ''){
		$this->natural = $natural;
		$this->leftRightOrFull = $leftRightOrFull;
		$this->innerOuterOrCross = $innerOuterOrCross;
		$this->table = $table;
		$this->tablePrimaryKey = $tablePrimaryKey;
		$this->fromTableForeignKey = $fromTableForeignKey;
	}
}

?>