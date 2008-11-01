<?php
Library::import('recess.lang.Annotation');

class HasManyAnnotation extends Annotation {

	public $settings;
	
	function init($array) {
		$this->settings = $array;
	}

}
?>