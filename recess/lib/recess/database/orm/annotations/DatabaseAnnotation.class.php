<?php
Library::import('recess.database.orm.annotations');
class DatabaseAnnotation extends ModelAnnotation {
	public $source;
	
	function init($array) {
		$this->source = $array[0];	
	}
	
	function massage(ModelDescriptor &$descriptor) {
		$descriptor->source = $this->source;
	}
}
?>