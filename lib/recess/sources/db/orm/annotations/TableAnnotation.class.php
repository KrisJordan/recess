<?php
Library::import('recess.lang.Annotation');

class TableAnnotation extends Annotation {
	public $name;
	
	function init($array) {
		$this->name = $array[0];	
	}
}

?>