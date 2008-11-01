<?php
Library::import('recess.lang.Annotation');

class BelongsToAnnotation extends Annotation {

	public $settings;
	
	function init($array) {
		$this->settings = $array;
	}

}
?>