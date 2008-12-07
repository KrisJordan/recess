<?php
Library::import('recess.database.orm.annotations.ModelPropertyAnnotation');

class ColumnAnnotation extends ModelPropertyAnnotation {
	public $type;
	public $isPrimaryKey = false;
	public $autoIncrement = false;
	
	function init($array) {
		foreach($array as $item) {
			$lowerItem = strtolower($item);
			if($lowerItem == 'primarykey') {
				$this->isPrimaryKey = true;
			} else if ($lowerItem == 'autoincrement') {
				$this->autoIncrement = true;
			} else {
				$this->type = $item;
			}
		}
	}
	
	function massage(ModelProperty $property) {
		$property->type = $this->type;
		$property->isPrimaryKey = $this->isPrimaryKey;
		$property->isAutoIncrement = $this->autoIncrement;
	}
}
?>