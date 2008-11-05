<?php

class PrimaryKeyAnnotation extends ModelAnnotation {
	public $pk;
	
	function init($array) {
		$this->pk = $array[0];	
	}
	
	function massage(ModelDescriptor &$descriptor) {
		$descriptor->primaryKey = $this->pk;
	}
}

?>