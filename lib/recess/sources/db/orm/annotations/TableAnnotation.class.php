<?php
Library::import('recess.lang.Annotation');

class TableAnnotation extends Annotation {
	public $table;
	
	function init($array) {
		$this->table = $array[0];	
	}
}

?>