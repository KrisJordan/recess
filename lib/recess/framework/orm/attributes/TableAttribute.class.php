<?php
Library::import('recess.lang.Attribute');

class TableAttribute extends Attribute {
	public $name;
	
	function init($array) {
		$this->name = $array[0];	
	}
}

?>