<?php

class TypeAnnotation extends ModelPropertyAnnotation {
	public $type;
	
	function init($array) {
		$this->type = $array[0];	
	}
	
	function massage(ModelProperty $property) {
		$property->type = $this->type;
	}
}

?>