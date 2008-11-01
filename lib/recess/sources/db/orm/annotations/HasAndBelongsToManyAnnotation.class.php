<?php
Library::import('recess.lang.Annotation');

class HasAndBelongsToManyAnnotation extends Annotation {
	public $settings;
	
	function init($array) {
		$this->settings = $array;
	}
}

?>