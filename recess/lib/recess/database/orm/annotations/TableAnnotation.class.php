<?php
Library::import('recess.database.orm.annotations');
class TableAnnotation extends ModelAnnotation {
	public $table;
	
	function init($array) {
		$this->table = $array[0];	
	}
	
	function massage(ModelDescriptor &$descriptor) {
		$descriptor->setTable($this->table);
	}
}
?>