<?php

class ColumnAnnotation extends ModelPropertyAnnotation {
	public $type;
	public $primaryKey = false;
	public $autoIncrement = false;
	
	function init($array) {
		foreach($array as $item) {
			$lowerItem = strtolower($item);
			if($lowerItem == 'primarykey') {
				$this->primaryKey = true;
			} else if ($lowerItem == 'autoincrement') {
				$this->autoIncrement = true;
			} else {
				$this->type = $item;
			}
		}
	}
	
	function massage(ModelProperty $property) {
		$property->type = $this->type;
		$property->isPrimaryKey = true;
		$property->autoincrement = $this->autoIncrement;
		
	}
}

?>