<?php

class TypeAnnotation extends Annotation {
	public $type;
	
	function init($array) {
		$this->type = $array[0];	
	}
}

?>